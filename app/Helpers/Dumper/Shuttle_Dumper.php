<?php

namespace App\Helpers\Dumper;

use App\Helpers\Dumper\Shuttle_DBConn;
use App\Helpers\Dumper\Shuttle_Dump_File;
use App\Helpers\Dumper\Shuttle_Dumper_Native;
use App\Helpers\Dumper\Shuttle_Dumper_ShellCommand;

/**
 * Main facade
 */
abstract class Shuttle_Dumper
{
    /**
     * Maximum length of single insert statement
     */
    const INSERT_THRESHOLD = 838860;

    /**
     * @var Shuttle_DBConn
     */
    public $db;
    /**
     * @var Shuttle_Dump_File
     */
    public $dump_file;
    /**
     * End of line style used in the dump
     */
    public $eol = "\r\n";
    /**
     * Specificed tables to include
     */
    public $include_tables;
    /**
     * Specified tables to exclude
     */
    public $exclude_tables = array();

    /**
     * Factory method for dumper on current hosts's configuration.
     */
    public static function create($db_options)
    {
        $db = Shuttle_DBConn::create($db_options);
        $db->connect();
        if (self::has_shell_access()
            && self::is_shell_command_available('mysqldump')
            && self::is_shell_command_available('gzip')
        ) {
            $dumper = new Shuttle_Dumper_ShellCommand($db);
        } else {
            $dumper = new Shuttle_Dumper_Native($db);
        }
        if (isset($db_options['include_tables'])) {
            $dumper->include_tables = $db_options['include_tables'];
        }
        if (isset($db_options['exclude_tables'])) {
            $dumper->exclude_tables = $db_options['exclude_tables'];
        }
        return $dumper;
    }

    public function __construct(Shuttle_DBConn $db)
    {
        $this->db = $db;
    }

    public static function has_shell_access()
    {
        if (!is_callable('shell_exec')) {
            return false;
        }
        $disabled_functions = ini_get('disable_functions');
        return stripos($disabled_functions, 'shell_exec') === false;
    }

    public static function is_shell_command_available($command)
    {
        if (preg_match('~win~i', PHP_OS)) {
            /*
                            On Windows, the `where` command checks for availabilty in PATH. According
                            to the manual(`where /?`), there is quiet mode:
                            ....
                                /Q       Returns only the exit code, without displaying the list
                                         of matched files. (Quiet mode)
                            ....
            */
            $output = array();
            exec('where /Q ' . $command, $output, $return_val);
            return intval($return_val) !== 1;
        }

        $last_line = exec('which ' . $command);
        $last_line = trim($last_line);
        // Whenever there is at least one line in the output,
        // it should be the path to the executable
        if (empty($last_line)) {
            return false;
        }

        return true;
    }

    /**
     * Create an export file from the tables with that prefix.
     * @param string $export_file_location the file to put the dump to.
     *      Note that whenever the file has .gz extension the dump will be comporessed with gzip
     * @param string $table_prefix Allow to export only tables with particular prefix
     * @return void
     */
    abstract public function dump($export_file_location, $table_prefix = '');

    protected function get_tables($table_prefix)
    {
        if (!empty($this->include_tables)) {
            return $this->include_tables;
        }
        $tables = $this->db->fetch_numeric('
            SHOW TABLES LIKE "' . $this->db->escape_like($table_prefix) . '%"
        ');
        $tables_list = array();
        foreach ($tables as $table_row) {
            $table_name = $table_row[0];
            if (!in_array($table_name, $this->exclude_tables)) {
                $tables_list[] = $table_name;
            }
        }
        return $tables_list;
    }
}

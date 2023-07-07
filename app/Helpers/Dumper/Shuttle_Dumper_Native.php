<?php

namespace App\Helpers\Dumper;

use App\Helpers\Dumper;
use App\Helpers\Dumper\Shuttle_Dump_File;
use App\Helpers\Dumper\Shuttle_Insert_Statement;

class Shuttle_Dumper_Native extends Dumper\Shuttle_Dumper
{
    public function dump($export_file_location, $table_prefix = '')
    {
        $eol = $this->eol;
        $this->dump_file = Shuttle_Dump_File::create($export_file_location);
        $this->dump_file->write("-- Generation time: " . date('r') . $eol);
        $this->dump_file->write("-- Host: " . $this->db->host . $eol);
        $this->dump_file->write("-- DB name: " . $this->db->name . $eol);
        $this->dump_file->write("/*!40030 SET NAMES UTF8 */;$eol$eol");
        $tables = $this->get_tables($table_prefix);
        foreach ($tables as $table) {
            $this->dump_table($table);
        }
        unset($this->dump_file);
    }

    protected function dump_table($table)
    {
        $eol = $this->eol;
        $this->dump_file->write("DROP TABLE IF EXISTS `$table`;$eol");
        $create_table_sql = $this->get_create_table_sql($table);
        $this->dump_file->write($create_table_sql . $eol . $eol);
        $data = $this->db->query("SELECT * FROM `$table`");
        $insert = new Shuttle_Insert_Statement($table);
        while ($row = $this->db->fetch_row($data)) {
            $row_values = array();
            foreach ($row as $value) {
                $row_values[] = $this->db->escape($value);
            }
            $insert->add_row($row_values);
            if ($insert->get_length() > self::INSERT_THRESHOLD) {
                // The insert got too big: write the SQL and create
                // new insert statement
                $this->dump_file->write($insert->get_sql() . $eol);
                $insert->reset();
            }
        }
        $sql = $insert->get_sql();
        if ($sql) {
            $this->dump_file->write($insert->get_sql() . $eol);
        }
        $this->dump_file->write($eol . $eol);
    }

    public function get_create_table_sql($table)
    {
        $create_table_sql = $this->db->fetch('SHOW CREATE TABLE `' . $table . '`');
        return $create_table_sql[0]['Create Table'] . ';';
    }
}

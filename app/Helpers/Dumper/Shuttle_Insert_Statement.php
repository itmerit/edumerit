<?php

namespace App\Helpers\Dumper;

/**
 * MySQL insert statement builder.
 */
class Shuttle_Insert_Statement
{
    private $rows = array();
    private $length = 0;
    private $table;

    public function __construct($table)
    {
        $this->table = $table;
    }

    public function reset()
    {
        $this->rows = array();
        $this->length = 0;
    }

    public function add_row($row)
    {
        $row = '(' . implode(",", $row) . ')';
        $this->rows[] = $row;
        $this->length += strlen($row);
    }

    public function get_sql()
    {
        if (empty($this->rows)) {
            return false;
        }
        return 'INSERT INTO `' . $this->table . '` VALUES ' .
            implode(",\n", $this->rows) . '; ';
    }

    public function get_length()
    {
        return $this->length;
    }
}

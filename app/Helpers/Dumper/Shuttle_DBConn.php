<?php

namespace App\Helpers\Dumper;

class Shuttle_DBConn
{
    public $host;
    public $username;
    public $password;
    public $name;
    protected $connection;

    public function __construct($options)
    {
        $this->host = $options['host'];
        if (empty($this->host)) {
            $this->host = '127.0.0.1';
        }
        $this->username = $options['username'];
        $this->password = $options['password'];
        $this->name = $options['db_name'];
    }

    public static function create($options): Shuttle_DBConn_Mysqli
    {

        $class_name = \App\Helpers\Dumper\Shuttle_DBConn_Mysqli::class;

        return new $class_name($options);
    }
}

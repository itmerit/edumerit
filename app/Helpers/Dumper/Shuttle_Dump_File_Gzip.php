<?php

namespace App\Helpers\Dumper;

use App\Helpers\Dumper\Shuttle_Dump_File;

/**
 * Gzip implementation. Uses gz* functions.
 */
class Shuttle_Dump_File_Gzip extends Shuttle_Dump_File
{
    public function open()
    {
        return gzopen($this->file_location, 'wb9');
    }

    public function write($string)
    {
        return gzwrite($this->fh, $string);
    }

    public function end()
    {
        return gzclose($this->fh);
    }
}

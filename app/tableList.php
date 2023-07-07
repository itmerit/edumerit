<?php

namespace App;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class tableList extends Model
{
	public static function getTableList($colunm_name_id, $id)
	{
		
		try{
			$db_name = env('DB_DATABASE', null);
			$table_list = DB::select("SELECT TABLE_NAME 
			FROM INFORMATION_SCHEMA.COLUMNS
			WHERE COLUMN_NAME ='$colunm_name_id'
				AND TABLE_SCHEMA='$db_name'");
			$tables = '';

			foreach ($table_list as $row) {
				$data_test = DB::table($row->TABLE_NAME)->select('*')->where($colunm_name_id, $id)->when(Schema::hasColumn($row->TABLE_NAME, 'school_id'), function ($q){
                    $q->where('school_id', Auth::user()->school_id);
                })->first();
				if($data_test != ""){

					$name = str_replace('sm_', '', $row->TABLE_NAME);
					$name = str_replace('_', ' ', $name);
					$name = ucfirst($name);
					$tables .= $name . ', ';
				}
			}
			return $tables;
		}catch(\Exception $e){
            return null;
		}
	}


	public static function ONLY_TABLE_LIST($id)
	{
		try{
			$db_name = env('DB_DATABASE', null);
			$table_list = DB::select("SELECT TABLE_NAME 
			FROM INFORMATION_SCHEMA.COLUMNS
			WHERE COLUMN_NAME ='$id'
				AND TABLE_SCHEMA='$db_name'");
			$tables = [];
			foreach ($table_list as $row) {
				$tables[] = $row->TABLE_NAME;
			}
			return $tables;

		}catch(\Exception $e){
            return [];
		}

	}
	
	public static function allTableList($column)
	{

		//this function not working 
		try {
            return env('DB_DATABASE', null);
		} catch (\Exception $e) {
			return [];
		}

		$db_name = env('DB_DATABASE', null);
		$table_list = DB::select("SELECT TABLE_NAME 
		FROM INFORMATION_SCHEMA.COLUMNS
		WHERE COLUMN_NAME ='$column'
			AND TABLE_SCHEMA='$db_name'");
		$tables = [];
		foreach ($table_list as $row) {
			$tables[] = $row->TABLE_NAME;
		}
		return $tables;
	}
}
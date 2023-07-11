<?php

namespace Database\Seeders;

use App\Continet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
// use DB;

class continentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


      //  DB::statement('SET FOREIGN_KEY_CHECKS=0;'); 
        Continet::query()->truncate();

        $continents = [
            [
                'code' => 'AF',
                'name' => 'Africa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'AN',
                'name' => 'Antarctica',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'AS',
                'name' => 'Asia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'EU',
                'name' => 'Europe',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'NA',
                'name' => 'North America',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'OC',
                'name' => 'Oceania',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'SA',
                'name' => 'South America',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Continet::insert($continents);
    }
}

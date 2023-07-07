<?php

namespace Database\Seeders;

use App\Smcountry;
use Illuminate\Database\Seeder;

class countriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


       // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Smcountry::query()->truncate();
    }
}

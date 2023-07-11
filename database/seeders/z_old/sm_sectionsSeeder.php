<?php

namespace Database\Seeders;

use App\SmSection;
use Illuminate\Database\Seeder;


class sm_sectionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

       
        SmSection::factory()->times(5)->create();
     
    }
}

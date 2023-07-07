<?php

namespace Database\Seeders\FrontSettings;

use App\SmBackgroundSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class SmBackgroundSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($school_id, $count=3)
    {
        //
        
        DB::table('sm_background_settings')->insert([
            [
               
                'title'         => 'Dashboard Background',
                'type'          => 'image',
                'image'         => 'public/backEnd/img/body-bg.jpg',
                'color'         => '',
                'is_default'    => 1,
                'school_id'     => $school_id,

            ],

            [
               
                'title'         => 'Login Background',
                'type'          => 'image',
                'image'         => 'public/backEnd/img/login-bg.jpg',
                'color'         => '',
                'is_default'    => 0,
                'school_id'     => $school_id,


            ],

        ]);
    }
}

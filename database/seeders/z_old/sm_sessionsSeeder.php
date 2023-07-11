<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class sm_sessionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sm_sessions')->insert([
            [
                'session' => '2019',
                'created_at' => date('Y-m-d h:i:s')
            ],
            [
                'session' => '2020',
                'created_at' => date('Y-m-d h:i:s')
            ],
            [
                'session' => '2021',
                'created_at' => date('Y-m-d h:i:s')
            ],
        ]);
    }
}

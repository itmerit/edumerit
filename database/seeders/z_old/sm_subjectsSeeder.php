<?php

namespace Database\Seeders;

use App\SmSubject;
use Illuminate\Database\Seeder;

class sm_subjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SmSubject::factory()->times(10)->create();
    }
}

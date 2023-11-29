<?php

namespace Database\Seeders;

use App\SmExpenseHead;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class sm_expense_headsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SmExpenseHead::factory()->times(10)->create();        
    }
}

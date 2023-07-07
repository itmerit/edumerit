<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\SmBankAccount;
use Faker\Factory as Faker;

class sm_bank_accountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SmBankAccount::factory()->times(10)->create();
    }
}

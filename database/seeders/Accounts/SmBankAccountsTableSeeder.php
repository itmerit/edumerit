<?php

namespace Database\Seeders\Accounts;

use App\SmBankAccount;
use Illuminate\Database\Seeder;

class SmBankAccountsTableSeeder extends Seeder
{
    public function run($school_id = 1, $count = 10){
        SmBankAccount::factory()->times($count)->create([
            'school_id' => $school_id
        ]);
    }
}
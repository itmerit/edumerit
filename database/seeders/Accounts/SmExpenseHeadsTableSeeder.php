<?php

namespace Database\Seeders\Accounts;

use App\SmAddExpense;
use App\SmExpenseHead;
use Illuminate\Database\Seeder;

class SmExpenseHeadsTableSeeder extends Seeder
{

    public function run($school_id = 1, $count = 10){
        SmExpenseHead::factory()->times($count)->create([
            'school_id' => $school_id
        ])->each(function ($expense_head){
            SmAddExpense::factory()->times(10)->create([
                'school_id' => $expense_head->school_id,
                'expense_head_id' => $expense_head->id,
            ]);
        });
    }

}
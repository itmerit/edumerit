<?php

namespace Database\Seeders\Fees;

use App\SmFeesDiscount;
use Illuminate\Database\Seeder;

class SmFeesDiscountTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($school_id, $academic_id, $count=5)
    {
        //
        SmFeesDiscount::factory()->times($count)->create([
            'school_id'=>$school_id,
            'academic_id'=>$academic_id
        ]);
    }
}

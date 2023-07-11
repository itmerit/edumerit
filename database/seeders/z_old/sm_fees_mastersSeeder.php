<?php

namespace Database\Seeders;

use App\SmFeesType;
use App\SmFeesMaster;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class sm_fees_mastersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $data = SmFeesType::all();
        foreach ($data as $row) {
            $store= new SmFeesMaster();
            $store->fees_group_id=$row->fees_group_id;
            $store->fees_type_id=$row->id;
            $store->date=date('Y-m-d');
            $store->amount=500+rand()%500;
            $store->save(); 
        } 
    }
}

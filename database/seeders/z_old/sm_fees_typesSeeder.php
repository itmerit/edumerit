<?php

namespace Database\Seeders;

use App\SmFeesType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class sm_fees_typesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
 
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        $array = ['Library','Sports','Environment','E-learning','Fine','Extra-curricular activities','Laptop','Software','Uniforms','Transportation','Lunch','School Products'];
        for($j=1; $j<=6; $j++) {
            $store                  = new SmFeesType();
            $store->name            = $array[1+rand()%10];
            $store->fees_group_id   = $j;
            $store->description     = 'Sample Data genarated';  
            $store->save(); 
        }
      //  end loop  
    }



}

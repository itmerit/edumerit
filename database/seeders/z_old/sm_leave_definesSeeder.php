<?php

namespace Database\Seeders;

use App\Role;
use App\User;
use App\SmLeaveDefine;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Modules\RolePermission\Entities\InfixRole;

class sm_leave_definesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $roles = InfixRole::where('active_status', '=', '1')->where('id', '!=', 1)/* ->where('id', '!=', 2) */->where('id', '!=', 3)->where('id', '!=', 10)->get();
        foreach ($roles as $key => $value) {
            $users=User::where('role_id',$value->id)->get();
            foreach($users as $user){
                $store= new SmLeaveDefine();
                $store->role_id= $value->id;
                $store->user_id= $user->id;
                $store->type_id=$faker->numberBetween(1,5);
                $store->days=$faker->numberBetween(1,10);
                $store->created_at = date('Y-m-d h:i:s');
                $store->save();
            }
        }
        /* for($i=1; $i<=5; $i++){
            $store= new SmLeaveDefine();
            $store->role_id=4;
            $store->type_id=$faker->numberBetween(1,5);
            $store->days=$faker->numberBetween(1,10);
            $store->save();
        } */
    }
}

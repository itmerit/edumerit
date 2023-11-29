<?php

namespace Database\Seeders\Transport;

use App\SmStaff;
use App\SmAssignVehicle;
use Illuminate\Database\Seeder;

class SmAssignVehiclesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($school_id, $academic_id, $count = 10)
    {
        $i = 1;
        $drivers = SmStaff::whereRole(9)->where('school_id',$school_id)->where('active_status', 1)->get();
        foreach ($drivers as $driver) {
            $store = new SmAssignVehicle();
            $store->route_id = $i;
            $store->vehicle_id = $i;
            $store->created_at = date('Y-m-d h:i:s');
            $store->school_id = $school_id;
            $store->academic_id = $academic_id;
            $store->save();
            $i++;
        }
    }
}

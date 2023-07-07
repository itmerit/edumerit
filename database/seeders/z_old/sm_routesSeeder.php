<?php

namespace Database\Seeders;

use App\SmRoute;
use App\SmStaff;
use Illuminate\Database\Seeder;

class sm_routesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $drivers = SmStaff::whereRole(9)->where('active_status', 1)->get();
        foreach ($drivers as $driver) {
            $store = new SmRoute();
            $store->title = 'Transport Route ' . $driver->id;
            $store->far = rand(100, 500);
            $store->created_at = date('Y-m-d h:i:s');
            $store->save();
        }
    }
}

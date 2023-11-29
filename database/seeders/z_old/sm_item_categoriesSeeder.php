<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\SmItemCategory;

class sm_item_categoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $store = new SmItemCategory();
        $store->category_name = 'Raw Materials Inventory';
        $store->created_at = date('Y-m-d h:i:s');
        $store->save();

        $store = new SmItemCategory();
        $store->category_name = 'Transit Inventory';
        $store->created_at = date('Y-m-d h:i:s');
        $store->save();

        $store = new SmItemCategory();
        $store->category_name = 'Buffer Inventory';
        $store->created_at = date('Y-m-d h:i:s');
        $store->save();

        $store = new SmItemCategory();
        $store->category_name = 'Application Inventory';
        $store->created_at = date('Y-m-d h:i:s');
        $store->save();

        $store = new SmItemCategory();
        $store->category_name = 'Enterprice Inventory';
        $store->created_at = date('Y-m-d h:i:s');
        $store->save();

        $store = new SmItemCategory();
        $store->category_name = 'Others Inventory';
        $store->created_at = date('Y-m-d h:i:s');
        $store->save();
    }
}

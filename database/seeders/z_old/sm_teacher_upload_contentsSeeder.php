<?php

namespace Database\Seeders;

use App\SmTeacherUploadContent;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class sm_teacher_upload_contentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $i = 1;

        $contents = ['as','st','ot'];
        foreach ($contents as $content) {
            $store = new SmTeacherUploadContent();    

            $store->content_title = $faker->text(100);
            $store->content_type = $content;
            $store->available_for_admin=1;
            $store->available_for_all_classes=1;
            $store->upload_date=$faker->dateTime()->format('Y-m-d');
            $store->description=$faker->text(500);
            $store->source_url='google.com';           
            $store->upload_file = 'public/uploads/upload_contents/sample.pdf';
            $store->created_by = 1;
            $store->created_at = date('Y-m-d h:i:s');
            $store->save();
        }
    }
}

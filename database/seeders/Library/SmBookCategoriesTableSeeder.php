<?php

namespace Database\Seeders\Library;

use App\SmBook;
use App\SmBookCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SmBookCategoriesTableSeeder extends Seeder
{
    public function run($school_id = 1, $count = 16){

        SmBookCategory::factory()->times($count)->create([
            'school_id' => $school_id,
        ])->each(function ($book_category){
            SmBook::factory()->times(11)->create([
               'school_id' => $book_category->school_id,
            ]);
        });
    }
}
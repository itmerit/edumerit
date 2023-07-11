<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\SmBook;

class CreateSmBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_books', function (Blueprint $table) {
            $table->increments('id');
            $table->string('book_title', 200)->nullable();
            $table->string('book_number', 200)->nullable();
            $table->string('isbn_no', 200)->nullable();
            $table->string('publisher_name', 200)->nullable();
            $table->string('author_name', 200)->nullable();
            // $table->string('subject',200)->nullable();
            $table->string('rack_number', 50)->nullable();
            $table->integer('quantity')->nullable()->default(0);
            $table->integer('book_price')->nullable();

            $table->date('post_date')->nullable();
            $table->string('details', 500)->nullable();
            $table->tinyInteger('active_status')->default(1);
            $table->timestamps();

            $table->integer('book_subject_id')->nullable()->unsigned();

            $table->integer('book_category_id')->nullable()->unsigned();
            $table->foreign('book_category_id')->references('id')->on('sm_book_categories')->onDelete('cascade');

            $table->integer('created_by')->nullable()->default(1)->unsigned();

            $table->integer('updated_by')->nullable()->default(1)->unsigned();

            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
            
            $table->integer('academic_id')->nullable()->default(1)->unsigned();
            $table->foreign('academic_id')->references('id')->on('sm_academic_years')->onDelete('cascade');
        });
        // $books = [
        //         'Algorithms & Data Structures',
        //         'Cellular Automata',
        //         'Cloud Computing',
        //         'Competitive Programming',
        //         'Compiler Design',
        //         'Database',
        //         'Datamining',
        //         'Information Retrieval',
        //         'Licensing',
        //         'Machine Learning', 
        //         'Mathematics',  
        //     ];
        //     $i=1;
        //     foreach ($books as $book) { 
        //     $store = new SmBook();
        //     $store->book_category_id = $i;
        //     $store->book_title = $book;
        //     $store->book_number = 'B-'.$i;
        //     $store->isbn_no = 'ISBN-0'.$i; 
        //     $store->publisher_name = 'Infix';
        //     $store->author_name = 'Author Infix'; 
        //     $store->subject = 1+ $i%5;
        //     $store->rack_number = $i;
        //     $store->quantity =200+ $i;
        //     $store->book_price =300+ 20* $i; 
        //     $store->save();
        //     $i++;
        // }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sm_books');
    }
}

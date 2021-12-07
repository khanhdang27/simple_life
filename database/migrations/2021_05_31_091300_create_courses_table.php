<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('courses', function(Blueprint $table){
            $table->id();
            $table->string('name');
            $table->double('price');
            $table->integer('intend_time')->default(30);
            $table->text('description')->nullable();
            $table->integer('status')->default(1);
            $table->unsignedInteger('category_id');
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::dropIfExists('courses');
    }
}

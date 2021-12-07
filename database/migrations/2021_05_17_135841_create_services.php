<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServices extends Migration{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('services', function(Blueprint $table){
            $table->id();
            $table->string('name');
            $table->double('price');
            $table->integer('intend_time')->default(30);
            $table->double('additional_price')->nullable();
            $table->text('additional_service')->nullable();
            $table->text('description')->nullable();
            $table->integer('status')->default(1);
            $table->unsignedInteger('type_id');
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
        Schema::dropIfExists('services');
    }
}

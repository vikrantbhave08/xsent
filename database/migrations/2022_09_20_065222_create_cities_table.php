<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
           
                $table->bigIncrements('city_id');         
                $table->string('city_name',250)->nullable();
                $table->string('city_code',250)->nullable();
                $table->integer('province_id')->default(0);          
                $table->tinyInteger('is_active')->default(0); 
                $table->timestamps();
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cities');
    }
}

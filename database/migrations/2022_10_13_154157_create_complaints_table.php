<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->bigIncrements('complaint_id');         
            $table->integer('reason_id')->default(0);   
            $table->integer('by_user')->default(0);  
            $table->integer('by_role')->default(0);  
            $table->string('complaint_details',250);   
            $table->string('complaint_img',60);   
            $table->tinyInteger('is_active')->default(1); 
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
        Schema::dropIfExists('complaints');
    }
}

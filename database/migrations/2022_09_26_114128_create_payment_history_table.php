<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_history', function (Blueprint $table) {
            $table->bigIncrements('payment_id');         
            $table->string('pay_txn_id',50);   
            $table->integer('from_user')->default(0);   
            $table->integer('to_user')->default(0);  
            $table->integer('amt_request_id')->nullable();  
            $table->string('bank_detail_id',50)->nullable(); 
            $table->string('amount',50); 
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
        Schema::dropIfExists('payment_history');
    }
}

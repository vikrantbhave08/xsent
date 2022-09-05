<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_transaction', function (Blueprint $table) {
            $table->bigIncrements('wallet_trans_id');   
            $table->integer('from_user')->default('0');       
            $table->integer('user_id')->default('0');       
            $table->integer('wallet_id')->nullable(false);       
            $table->string('credit',50)->default('0');   
            $table->string('debit',50)->default('0');   
            $table->string('payment_gate_id',20);   
            $table->string('status_msg',50);   
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
        Schema::dropIfExists('wallet_transaction');
    }
}

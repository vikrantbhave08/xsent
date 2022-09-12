<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_transactions', function (Blueprint $table) {
            $table->bigIncrements('shop_trans_id');
            $table->integer('by_user')->nullable();
            $table->integer('shop_id')->default('1');
            $table->string('amount')->nullable();
            $table->tinyInteger('is_active')->default('1');      
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
        Schema::dropIfExists('shop_transactions');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToWalletTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wallet_transaction', function (Blueprint $table) {
            $table->integer('from_role')->default(0)->after('from_user'); 
            $table->integer('to_role')->default(0)->after('user_id'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wallet_transaction', function (Blueprint $table) {
            //
        });
    }
}

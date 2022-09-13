<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRevertRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amount_requests', function (Blueprint $table) {
            $table->bigIncrements('revert_id');
            $table->integer('by_user');
            $table->integer('to_user')->default(0);
            $table->string('request_amount',20);
            $table->string('reason',50)->nullable();
            $table->string('admin_remark',50)->nullable();
            $table->tinyInteger('status')->default(0);  // 0 pending , 1 paid , 2 declined
            $table->date('date_of_expenditure')->format('Y-m-d H:i:s')->nullable();
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
        Schema::dropIfExists('amount_requests');
    }
}

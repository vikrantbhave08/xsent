<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->integer('user_role')->default('1')->after('user_id');
            $table->string('first_name',50)->nullable()->after('user_role');
            $table->string('last_name',50)->nullable()->after('first_name');
            $table->string('email',50)->nullable()->after('last_name');
            $table->string('username',50)->nullable()->after('email');
            $table->integer('contact_no')->nullable()->after('username');
            $table->string('address',250)->nullable()->after('contact_no');
            $table->string('profile_pic',250)->nullable()->after('address');
            $table->string('password',100)->nullable()->after('profile_pic');
            $table->string('token',100)->nullable()->after('password');
            $table->tinyInteger('f_pwd_flag')->nullable()->after('token');
            $table->integer('created_by')->default('1')->after('f_pwd_flag');
            $table->tinyInteger('is_active')->default('1')->after('created_by');           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}

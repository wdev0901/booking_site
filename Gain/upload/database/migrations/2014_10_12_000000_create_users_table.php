<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable()->unique();
            $table->string('phone_object')->nullable();
            $table->string('password', 60);
            $table->string('gender')->nullable();           
            $table->date('date_of_birth')->nullable();
            $table->string('avatar')->default('default.jpg');
            $table->boolean('verified')->default(0);
            $table->boolean('is_admin')->default(0);
            $table->integer('role_id')->default(0);
            $table->tinyInteger('disabled')->default(0);
            $table->string('token', 254)->nullable();   // confirm_token', 100
            $table->rememberToken();
            $table->string('verification_code');
            $table->dateTime('notification_check')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}

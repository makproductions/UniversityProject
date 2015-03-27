<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /*
    |--------------------------------------------------------------------------
    | Create Users Table
    |--------------------------------------------------------------------------
        Here, we've created an user, with
        - name
        - email ( Unique )
        - password ( max-length : 60 )
        - rememberToken

        This User Model is used by the Auth Controller / Middleware
    |
    */


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->rememberToken();
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
        Schema::drop('users');
    }

}

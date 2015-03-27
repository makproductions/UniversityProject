<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePasswordResetsTable extends Migration
{

    /*
    |--------------------------------------------------------------------------
    | Create Password Reset Table
    |--------------------------------------------------------------------------
        Here, we've created a table, which saves the user's email and a token to be used on password reset.

        This Table is used by the Auth Controller / Middleware
    |
    */
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token')->index();
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('password_resets');
    }

}

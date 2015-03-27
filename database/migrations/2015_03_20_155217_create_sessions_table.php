<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSessionsTable extends Migration
{
    /*
        |--------------------------------------------------------------------------
        | Create Sessions Table
        |--------------------------------------------------------------------------
            Here, we've created a Session Table, with
            - time ( Length of the Session )
            - finished ( an integer, to check if the session has ended )
            - user_id ( This session's owner )
        |
        */
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('time');
            $table->integer('finished');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

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
        Schema::drop('sessions');
    }

}

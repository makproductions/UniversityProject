<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubmissionsTable extends Migration
{
    /*
        |--------------------------------------------------------------------------
        | Create Submissions Table
        |--------------------------------------------------------------------------
            Here, we've created a Submission Table, with
            - process_time
            - process_number
            - quantum_time
            - arrival_time
            - method ( Which button was clicked when asking for the simulation )
            - session_id ( During which Session this experiment was submitted )
        |
        */
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('process_time');
            $table->integer('process_number');
            $table->integer('quantum_time');
            $table->integer('arrival_time');

            $table->string('method');

            $table->integer('session_id')->unsigned();
            $table->foreign('session_id')
                ->references('id')
                ->on('sessions')
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
        Schema::drop('submissions');
    }

}

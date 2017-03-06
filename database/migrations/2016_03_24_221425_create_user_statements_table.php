<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserStatementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_statements', function (Blueprint $table) {
            $table->increments('id');
            $table->char('currency',3);
            $table->float('balance');
            $table->float('bonus_balance');
            $table->tinyInteger('bonus_status');
            $table->integer('deposits');
            $table->integer('withdrawals');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_statements');
    }
}

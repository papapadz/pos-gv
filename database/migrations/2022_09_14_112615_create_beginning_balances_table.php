<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBeginningBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beginning_balances', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->double('balance', 11,2)->default(0);
            $table->double('cash_count', 11,2)->default(0);
            $table->boolean('is_active')->default(true);
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
        Schema::drop('beginning_balances');
    }
}

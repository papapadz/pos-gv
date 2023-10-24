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
        Schema::create('tbl_beginning_balances', function (Blueprint $table) {
            $table->bigIncrements('beginning_balance_id');
            $table->bigInteger('user_id');
                $table->foreign('user_id')->references('id')->on('tbl_users')->onDelete('cascade');
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
        Schema::drop('tbl_beginning_balances');
    }
}

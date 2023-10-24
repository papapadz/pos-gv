<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExtraChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_extra_charges', function (Blueprint $table) {
            $table->bigIncrements('extra_charge_id');
            $table->bigInteger('transaction_id');
                $table->foreign('transaction_id')->references('transaction_id')->on('tbl_transactions')->onDelete('cascade');
            $table->string('charge_name',100);
            $table->double('charge_amount',11,2)->default(0);
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
        Schema::drop('tbl_extra_charges');
    }
}

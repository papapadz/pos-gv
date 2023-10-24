<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_sales', function (Blueprint $table) {
            $table->bigIncrements('sales_id');
            $table->bigInteger('product_id');
                $table->foreign('product_id')->references('product_id')->on('tbl_products')->onDelete('cascade');
            $table->integer('qty')->default(1);
            $table->bigInteger('price_id');
                $table->foreign('price_id')->references('price_id')->on('tbl_product_prices')->onDelete('cascade');
            $table->double('discount_amount',11,2)->default(0);
            $table->bigInteger('user_id');
                $table->foreign('user_id')->references('id')->on('tbl_users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_sales');
    }
}

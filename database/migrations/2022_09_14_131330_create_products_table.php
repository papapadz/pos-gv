<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_products', function (Blueprint $table) {
            $table->bigIncrements('product_id');
            $table->string('product_name',255);
            $table->bigInteger('product_category');
                $table->foreign('product_category')->references('product_category_id')->on('tbl_product_categories')->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->string('description',255);
            $table->string('img_file',255);
            $table->bigInteger('company_id');
                $table->foreign('company_id')->references('id')->on('shops')->onDelete('cascade');
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
        Schema::drop('tbl_products');
    }
}

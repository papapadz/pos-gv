<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAffiliationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affiliations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
                $table->foreign('user_id')->references('id')->on('tbl_users')->onDelete('cascade');
            $table->bigInteger('shop_id');
                $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
            $table->bigInteger('role_id');
                $table->foreign('role_id')->references('id')->on('user_roles')->onDelete('cascade');
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
        Schema::drop('affiliations');
    }
}

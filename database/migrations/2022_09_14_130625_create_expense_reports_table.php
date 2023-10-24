<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpenseReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_expense_reports', function (Blueprint $table) {
            $table->increments('expense_report_id');
            $table->string('expense_name',255);
            $table->double('expense_amount',11,2)->default(0);
            $table->enum('payment_type',[0,1,2])->default(0);
            $table->date('report_date');
            $table->integer('user_id');
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
        Schema::drop('tbl_expense_reports');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpenseReportsModel extends Model
{	
	public $timestamps = false;
    protected $connection = 'mysql';
    protected $primaryKey = 'expense_report_id';
    protected $table = 'tbl_expense_reports';

}
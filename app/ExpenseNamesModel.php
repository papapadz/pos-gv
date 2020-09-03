<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpenseNamesModel extends Model
{	
	public $timestamps = false;
    protected $connection = 'mysql';
    protected $primaryKey = 'expense_id';
    protected $table = 'tbl_expenses';

}
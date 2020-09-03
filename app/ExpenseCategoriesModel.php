<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpenseCategoriesModel extends Model
{	
	public $timestamps = false;
    protected $connection = 'mysql';
    protected $primaryKey = 'expense_category_id';
    protected $table = 'tbl_expense_categories';

}
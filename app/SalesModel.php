<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesModel extends Model
{
	public $timestamps = false;
    protected $connection = 'mysql';
    protected $primaryKey = 'sales_id';
    protected $table = 'tbl_sales';

}
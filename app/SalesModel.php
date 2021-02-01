<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesModel extends Model
{
	public $timestamps = false;
    protected $connection = 'mysql';
    protected $primaryKey = 'sales_id';
    protected $table = 'tbl_sales';

    public function transaction() {
        return $this->belongsTo(TransactionsModel::class,'transaction_id','transaction_id');
    }

    public function products() {
        return $this->hasOne(ProductsModel::class,'product_id','product_id')->with('price');
    }
}
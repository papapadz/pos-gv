<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductPricesModel extends Model
{
    protected $connection = 'mysql';
    protected $primaryKey = 'price_id';
    protected $table = 'tbl_product_prices';

    public function sales() {
        return $this->belongsTo(SalesModel::class,'price_id','price_id');
    }
}
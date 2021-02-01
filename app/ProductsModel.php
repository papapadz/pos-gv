<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductsModel extends Model
{
    protected $connection = 'mysql';
    protected $primaryKey = 'product_id';
    protected $table = 'tbl_products';

    public function sales() {
        return $this->belongsTo(SalesModel::class,'product_id');
    }

    public function price() {
        return $this->hasOne(ProductPricesModel::class,'price_id');
    }
}
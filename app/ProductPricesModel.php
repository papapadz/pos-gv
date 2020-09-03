<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductPricesModel extends Model
{
    protected $connection = 'mysql';
    protected $primaryKey = 'price_id';
    protected $table = 'tbl_product_prices';

}
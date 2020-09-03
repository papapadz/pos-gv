<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductsModel extends Model
{
    protected $connection = 'mysql';
    protected $primaryKey = 'product_id';
    protected $table = 'tbl_products';

}
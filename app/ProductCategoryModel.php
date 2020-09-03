<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductCategoryModel extends Model
{
    protected $connection = 'mysql';
    protected $primaryKey = 'category_id';
    protected $table = 'tbl_product_categories';

}
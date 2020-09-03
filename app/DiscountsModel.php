<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiscountsModel extends Model
{	
    protected $connection = 'mysql';
    protected $primaryKey = 'promo_id';
    protected $table = 'tbl_promos';

}
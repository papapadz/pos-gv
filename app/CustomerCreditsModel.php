<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerCreditsModel extends Model
{
    protected $connection = 'mysql';
    protected $primaryKey = 'credit_id';
    protected $table = 'tbl_credits';

}
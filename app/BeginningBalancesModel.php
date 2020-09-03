<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BeginningBalancesModel extends Model
{	
    protected $connection = 'mysql';
    protected $primaryKey = 'beginning_balance_id';
    protected $table = 'tbl_beginning_balances';

}
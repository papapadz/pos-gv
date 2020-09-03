<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionsModel extends Model
{
    protected $connection = 'mysql';
    protected $primaryKey = 'transaction_id';
    protected $table = 'tbl_transactions';

}
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionsModel extends Model
{
    protected $connection = 'mysql';
    protected $primaryKey = 'transaction_id';
    protected $table = 'tbl_transactions';

    public function sales() {
        return $this->hasMany(SalesModel::class,'transaction_id')->with('products');
    }

    public function price() {
        return $this->hasOne(ProductPricesModel::class,'price_id');
    }

    public function member() {
        return $this->hasOne(GreenPerksModel::class,'perk_id');
    }

    public function user() {
        return $this->hasOne(User::class,'id','user_id');
    }
}
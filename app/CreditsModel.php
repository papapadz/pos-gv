<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CreditsModel extends Model
{	
	public $timestamps = false;
    protected $connection = 'mysql';
    protected $primaryKey = 'credit_id';
    protected $table = 'tbl_credits';

}
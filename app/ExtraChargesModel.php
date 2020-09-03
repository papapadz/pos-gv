<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExtraChargesModel extends Model
{	
	public $timestamps = false;
    protected $connection = 'mysql';
    protected $primaryKey = 'extra_charge_id';
    protected $table = 'tbl_extra_charges';

}
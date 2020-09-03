<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GreenPerksModel extends Model
{
    protected $connection = 'mysql';
    protected $primaryKey = 'perk_id';
    protected $table = 'tbl_green_perks';

}
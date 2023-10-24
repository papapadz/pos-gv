<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $fillable = ['lastname','firstname','middlename','birthdate','address','citymun_id','zipcode'];
}

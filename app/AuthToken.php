<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuthToken extends Model
{
    protected $fillable = ['token', 'date', 'last_date'];
    public $timestamps = false;
}

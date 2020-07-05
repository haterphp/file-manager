<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChildrenDir extends Model
{
    public $timestamps = false;
    protected $fillable = ['type', 'parent', 'child_dir', 'child_file', 'user_id'];
}

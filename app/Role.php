<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'tb_role';

    protected $fillable = [
        'role'
    ];

    protected $guarded = [];

    public $timestamps = true;
}

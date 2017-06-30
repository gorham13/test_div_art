<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    protected $fillable = [
        'category', 'price', 'name', 'photo', 'characteristic'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    const STATUS_YES = 1;
    const STATUS_NO = 2;
    const STATUS_DEL = 3;

    protected $table = 'pro_seckill';
    // public $timestamps = false;

    protected $fillable = [
        'name', 'price', 'quantity', 'start_at', 'end_at'
    ];


    protected $hidden = [
        'password', 'remember_token',
    ];

}

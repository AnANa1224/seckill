<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const IsPay_YES = 1;
    const IsPay_NO = 2;
    const IsCancel_YES = 1;
    const IsCancel_NO = 2;

    protected $table = 'pro_order';
    // public $timestamps = false;

    protected $fillable = [
        'user_id', 'product_id', 'order_num'
    ];


    protected $hidden = [
        'password', 'remember_token',
    ];
}

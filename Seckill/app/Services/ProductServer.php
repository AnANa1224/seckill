<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class ProductServer
{
    /**
     * 講數據存入緩存之中
     * @param bool $bool
     * @return bool
     */
    public function createCache(bool $bool)
    {
        // 查询商品
        $products = Product::where('status', '!=', Product::STATUS_DEL)->get();
        // 存入库存
        DB::beginTransaction();
        foreach ($products as $product) {
            Redis::hset($product['id'], 'name', $product['name']);
            Redis::hset($product['id'], 'price', $product['price']);
            Redis::hset($product['id'], 'quantity', $product['quantity']);
        }

        if (!$products) {
            return false;
        }

        return true;
    }

}

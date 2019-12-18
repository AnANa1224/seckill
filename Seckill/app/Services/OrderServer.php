<?php
/**------- 订单存放在db3中--------*
 */

namespace App\Services;


use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class OrderServer
{

    /**
     * 获取订单号
     * @param array $data
     * @return mixed
     */
    public function find(array $data)
    {
        // 查询数据
        $res = Redis::lindex($data['product_id'], $data['id'] - 1);
        $res = unserialize($res);
        $res['id'] = Redis::llen($data['product_id']);
        return $res;
    }

    /**
     * 添加缓存订单
     * @param array $data
     * @return bool|int|mixed
     */
    public function create(array $data)
    {
        // 减库存
        $row = Redis::hincrby($data['product_id'], 'quantity', -1);
        // 售罄
        if ($row < 0) {
            Redis::hincrby($data['product_id'], 'quantity', 1);
            return -1;
        }
        // 完善订单数据
        $res['product_id'] = $data['product_id'];
        $res['user_id'] = $data['user_id'];
        $res['order_num'] = uniqid();
        // 添加订单
        $row = Redis::lpush('list_' . $data['product_id'], serialize($res));
        if (!$row) {
            Redis::hincrby($data['product_id'], 'quantity', 1);
            return false;
        }
        return $res;
    }

    /**
     * 执行添加mysql数据库
     * @param int $key
     * @return bool
     */
    public function add(int $key)
    {
        while (1) {
            // 弹出数据,如果不为空则添加数据库
            $res = Redis::rpop('list_' . $key);
            if ($res) {
                //数据转换
                $data = unserialize($res);
                //数据库操作
                // 减库存
                $res = Product::find($data['product_id']);
                Product::where('id', $data['product_id'])
                    ->update(['quantity' => $res['quantity'] - 1]);
                // 添加订单
                $Order = new Order();
                $Order->user_id = $data['user_id'];
                $Order->product_id = $data['product_id'];
                $Order->order_num = $data['order_num'];
                if (!$Order->save()) {
                    Redis::rpush($data['product_id'], $res);
                }
            } else {
                //如果队列中没有数据的话 可以休息一下
                sleep(5);
            }
        }
    }

}

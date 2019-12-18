<?php


namespace App\Http\Controllers;


use App\Services\OrderServer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * 查询订单
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    protected function find(Request $request)
    {
        $result = (new OrderServer())->find($request->input());
        if (!$result) {
            return response()->json([
                'code' => -1,
                'msg' => '获取失败',
                'data' => null,
            ]);
        }

        // 成功
        return response()->json([
            'code' => 0,
            'msg' => '获取成功',
            'data' => $result,
        ]);
    }

    /**
     * 创建订单
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        // 数据效验
        $validator = Validator::make($request->all(),
            [
                'product_id' => 'required|integer',
                'user_id' => 'required|integer',
            ], [
                'required' => ':attribute为必填项',
                'integer' => ':attribute类型为整数',
            ], [
                'product_id' => '商品id',
                'user_id' => '商品id',
            ]);
        if ($validator->fails()) {
            /* dd($validator->errors()->first());
            return $validator->errors()->first();*/
            return response()->json([
                'code' => -1,
                'msg' => $validator->errors()->first(),
                'data' => null,
            ]);
        }
        $result = (new OrderServer())->create($request->input());
        if ($result === -1) {
            return response()->json([
                'code' => -1,
                'msg' => '已售空',
                'data' => null,
            ]);
        } else if (!$result) {
            return response()->json([
                'code' => -1,
                'msg' => '下单失败',
                'data' => null,
            ]);
        }
        // 成功
        return response()->json([
            'code' => 0,
            'msg' => '下单成功',
            'data' => $result,
        ]);
    }

    /**
     * 持久化数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add()
    {
        $product_id = 1;
        // 执行添加数据库操作
        (new OrderServer())->add($product_id);

    }


}

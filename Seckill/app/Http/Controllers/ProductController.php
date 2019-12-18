<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ProductServer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * 验证管理员
     * @param Request $request
     * @return mixed
     */
    public function createCache(Request $request)
    {
        if ($request->input()['user'] !== 'admin' && $request->input()['pwd'] !== '123456') {
            return response()->json([
                'code' => -1,
                'msg' => '拒絕訪問',
                'data' => null,
            ]);
        }
        //
        $result = (new ProductServer())->createCache(true);
        if (!$result) {
            return response()->json([
                'code' => -1,
                'msg' => '添加失败',
                'data' => null,
            ]);
        }

        // 成功
        return response()->json([
            'code' => 0,
            'msg' => '添加成功',
            'data' => $result,
        ]);
    }

}

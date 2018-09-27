<?php
/**
 * Created by PhpStorm.
 * User: zf424zf
 * Date: 2018/9/16
 * Time: 23:43
 */

namespace App\Http\Controllers\api;


use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductionController extends Controller
{
    public function index(Request $request, Product $product)
    {
        $pagesize = empty($request->pagesize) ? 10 : $request->pagesize;
        $type = $request->has('type') ? $request->type : 0;
        $product = $product->where('status', 1)->orderBy('is_recommend','desc');
        switch ($type) {
            case 0:
                $product = $product->orderBy('id', 'desc');
                break;
            case 1:
                $product = $product->orderBy('apply_num', 'desc');
                break;
            case 2:
                $product = $product->orderBy('order', 'asc')->orderBy('id', 'desc');
                break;
        }
        if ($request->has('s')) {
            $search = $request->get('s');
            $product = $product->where('name', 'like', "%$search%");
        }
        return $product->paginate($pagesize);
    }

    public function show($id)
    {
        $product = Product::find($id);
        $product->keywords = array_filter(explode(',',$product->keywords));
        if (!isset($product)) {
            return ['status' => 404, 'data' => null];
        }
        return ['status' => 200, 'data' => $product];
    }
}
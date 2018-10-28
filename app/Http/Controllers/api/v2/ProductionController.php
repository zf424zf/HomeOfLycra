<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/9 0009
 * Time: 上午 11:03
 */

namespace App\Http\Controllers\api\v2;


use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductHot;
use App\Models\ProductRecommend;
use Illuminate\Http\Request;

class ProductionController extends Controller
{
    public function index(Request $request, Product $product)
    {
        // 每页显示条数
        $pagesize = empty($request->pagesize) ? 10 : $request->pagesize;
        // 查询方式 0最新 1最热 2推荐
        $type = $request->has('type') ? $request->type : 0;
        //只查询上架的 并且按照推荐排列
        // 按照排序进行排序
        $product = $product->where('status', 1)->orderBy('is_recommend','desc')->orderBy('order');
        // 根据type创建查询构造器
        switch ($type) {
            case 0:
                $product = $product->orderBy('id', 'desc');
                break;
            case 1:
                $product = app()->make(ProductHot::class)->with('product');
                break;
            case 2:
                $product = app()->make(ProductRecommend::class)->with('product');
                break;
        }
        //type=0时搜索product 否则进行关联搜索
        if ($request->has('s')) {
            $search = $request->get('s');
            if($type == 0){
                $product = $product->where('name', 'like', "%$search%");
            }else{
                $product = $product->whereHas('product',function ($query) use($search){
                    $query->where('name', 'like', "%$search%");
                });
            }
        }
        // 分页
         $products =  $product->paginate($pagesize);

        // 格式化数据 将type不为0的数据格式统一成type为0的格式
        if($type != 0){
            foreach ($products as $key => &$item){
               unset($item->pid,$item->product->order,$item->product->is_recommend,$item->product->status);
               $itemProduct = $item->product;
               unset($item->product);
               $products[$key] =  collect($item)->merge($itemProduct);
            }
        }
        return $products;
    }

}
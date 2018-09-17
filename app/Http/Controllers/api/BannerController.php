<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/17 0017
 * Time: 下午 7:56
 */

namespace App\Http\Controllers\api;


use App\Http\Controllers\Controller;
use App\Models\Banner;

class BannerController extends Controller
{
    public function index(Banner $banner)
    {
        $banner = $banner->where('status', 1)->orderBy('created_at', 'desc')->take(3)->get();
        return [
            'status' => 200,
            'data' => $banner
        ];
    }
}
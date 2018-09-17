<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', function(){
        return redirect()->route('products.index');
    });
//    $router->resource('users', UserController::class);
    $router->resource('products',ProductController::class);
    $router->resource('banners',BannerController::class);
    $router->resource('tags',TagController::class);
});

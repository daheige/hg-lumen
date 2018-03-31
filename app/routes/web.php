<?php
//路由设置 lumen5.5和lumen5.3不同
$app->router->group([
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    $router->get('/', function () use ($router) {
        return $router->app->version();
    });

    $router->get('/test', 'ExampleController@test');
    $router->get('/info', 'ExampleController@info');
});

// //api路由设置
$app->router->group(['namespace' => 'App\Http\Controllers\Api', 'prefix' => 'api'], function ($router) {
    //检验基本参数
    //http://hglumen.com/api/foo?app_version=1.0.1&app_utm=1
    $router->get('/foo', ['middleware' => 'checkParams', 'uses' => 'TestController@foo']);

    $router->get('/info', 'TestController@info');
});

<?php
//路由设置
$app->router->group([
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    $router->get('/', function () use ($router) {
        return $router->app->version();
    });
    $router->get('/test', 'ExampleController@test');
    $router->get('/info', 'ExampleController@info');
    $router->get('/get-user', 'ExampleController@getUser');
});

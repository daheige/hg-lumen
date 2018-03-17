<?php
//加载env配置文件
try {
    (new Dotenv\Dotenv(ROOT_PATH))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    exit(json_encode(['code' => 500, 'message' => 'error setting!', 'time' => time()]));
}

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
 */
//应用basepath采用绝对路径
$app = new Laravel\Lumen\Application(
    ROOT_PATH
);

//开启门面模式
$app->withFacades();

//开启ORM模式
$app->withEloquent();

//monolog配置
$app->configureMonologUsing(function (Monolog\Logger $monoLog) use ($app) {
    return $monoLog->pushHandler(
        new \Monolog\Handler\RotatingFileHandler(LOG_PATH . '/hg_lumen.log')
    );
});

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
 */

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
 */

//中间件服务
$app->middleware([
    App\Http\Middleware\RequestLog::class, //记录请求参数日志
]);

// $app->routeMiddleware([
//     'auth' => App\Http\Middleware\Authenticate::class,
// ]);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
 */

// $app->register(App\Providers\AppServiceProvider::class);
// $app->register(App\Providers\AuthServiceProvider::class);
// $app->register(App\Providers\EventServiceProvider::class);

//路由加载放在AppBoot中的runApp()中
return $app;

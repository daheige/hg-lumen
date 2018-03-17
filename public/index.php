<?php
// 定义应用目录
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');

//加载composer工具类
require_once ROOT_PATH . '/vendor/autoload.php';

//启动应用
App\AppBoot::runApp();

<?php
namespace App;

/**
 * app启动类
 */
class AppBoot
{
    public static $_instance = null;
    public static function initConf()
    {
        // 默认时区定义
        date_default_timezone_set('Asia/Shanghai');
        // 设置错误报告模式
        error_reporting(E_ALL);
        // 设置默认区域
        setlocale(LC_ALL, 'zh_CN.utf-8');
        // 设置内部字符默认编码为 UTF-8
        mb_internal_encoding('UTF-8');
        // 打开/关闭错误显示
        ini_set('display_errors', !PRODUCTION);
        // 避免 cli 或 curl 模式下 xdebug 输出 html 调试信息
        ini_set('html_errors', !(IS_CLI || IS_CURL));
        // 使得在 api|ajax 模式下，输出 json 格式的错误信息
        if (API_MODE || IS_AJAX) {
            $_SERVER['HTTP_ACCEPT'] = 'application/json';
        }
    }

    public static function loadConstants()
    {
        //加载系统常量
        defined('ROOT_PATH') or define('ROOT_PATH', dirname(__DIR__));
        defined('APP_PATH') or define('APP_PATH', ROOT_PATH . '/app');
        require_once APP_PATH . '/bootstrap/constants.php';
    }

    //处理错误
    public static function handlError()
    {
        error_reporting(E_ALL & ~E_NOTICE);
        //获取 常见错误和fatal error
        register_shutdown_function("App\Libs\Slog::fatalHandler");
        set_error_handler("App\Libs\Slog::errorHandler", E_ALL | E_STRICT);
    }

    //运行app
    public static function runApp()
    {
        $app = self::getAppInstance();
        $app->run();
    }

    //获取$app的一个实例
    public static function getAppInstance()
    {
        if (self::$_instance == null) {
            self::initSystem();
            //加入lumen框架app设置
            $app             = require_once APP_PATH . '/bootstrap/app.php';
            self::$_instance = $app;
        }

        return self::$_instance;
    }

    public function loadFunctions()
    {
        include_once FUNC_PATH . '/common.php';      //加载公共函数库
        load_functions(['array', 'alias', 'logic']); //加载函数，可以加载多个文件
    }

    //初始化设置
    public static function initSystem()
    {
        //加载常量
        self::loadConstants();

        //加载公共函数库
        self::loadFunctions();

        //注册错误抓取事件
        self::handlError();

        //加载初始化配置
        self::initConf();
    }

}

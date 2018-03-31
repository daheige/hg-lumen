<?php
//公共函数操作
//写日志函数
function write_log($data = [], $filename = 'common', $method = 'info')
{
    $class = '\\App\\Libs\\Slog';
    if (class_exists($class)) {
        if (method_exists($class, $method)) {
            call_user_func_array([$class, $method], [$data, $filename]); //透明调用Slog::info,error等方法
            return true;
        }

        $class::info($data, $filename);
        return true;
    }

    return false;
}

/**
 * [redis 公共函数]
 * @param  string $config                  [redis.php中的redis配置名称]
 * @return [type] [redis的一个实例]
 */
function redis($config = 'default')
{
    $config = strtolower($config);
    $config = get_config('redis.' . $config);
    if (!$config) {
        return false;
    }

    return \App\Libs\RedisHandler::getInstance($config);
}

/**
 * 将当前环境转换为字符串
 */
function env_str()
{
    switch (true) {
        case PRODUCTION:
            return 'production';
        case STAGING:
            return 'staging';
        case TESTING:
            return 'testing';
        default:
            return 'local';
    }
}

/**
 * 加载配置文件数据
 *     get_config('database')
 *     get_config('database.default.adapter')
 *
 * @param  string  $name
 * @return mixed
 */
function get_config($name, $value = null)
{
    static $info = [];
    $name_hash   = md5($name);
    if (array_key_exists($name_hash, $info)) {
        return $info[$name_hash];
    }
    if (strpos($name, '.') !== false) {
        $arr = explode('.', $name);
        //优先从环境目录读取,最后从Conf目录下读取
        $filename = CONF_PATH . env_str() . '/' . $arr[0] . '.php';
        if (!is_file($filename)) {
            $filename = CONF_PATH . '/' . $arr[0] . '.php';
            if (!is_file($filename)) {
                $info[$name_hash] = $value;
                return $info[$name_hash];
            }
        }
        //缓存文件内容，防止反复导入
        $filename_hash = md5($filename);
        if (!isset($info[$filename_hash])) {
            $info[$filename_hash] = include $filename;
        }

        $config = $info[$filename_hash];
        if (count($arr) == 2) {
            $info[$name_hash] = array_key_exists($arr[1], $config) ? $config[$arr[1]] : $value;
            return $info[$name_hash];
        }
        if (count($arr) == 3) {
            $secondArr        = array_key_exists($arr[1], $config) ? $config[$arr[1]] : [];
            $info[$name_hash] = array_key_exists($arr[2], $secondArr) ? $secondArr[$arr[2]] : $value;
            return $info[$name_hash];
        }
        $info[$name_hash] = null;
        return $info[$name_hash];
    }

    //读取整个文件内容
    //优先从环境目录读取,最后从Conf目录下读取
    $filename = CONF_PATH . env_str() . '/' . $name . '.php';
    if (!is_file($filename)) {
        $filename = CONF_PATH . '/' . $name . '.php';
        if (!is_file($filename)) {
            $info[$name_hash] = $value;
            return $info[$name_hash];
        }
    }
    //缓存文件内容，防止反复导入
    $filename_hash = md5($filename);
    if (!isset($info[$filename_hash])) {
        $info[$filename_hash] = include $filename;
    }

    $info[$name_hash] = $info[$filename_hash];
    return $info[$name_hash];
}

if (!function_exists('last_sql')) {
    /**
     * @desc 获取最新一条sql语句 例子: dd(last_sql(3));
     * @param  int     $count
     * @param  int     $dataType 1字符串 2数组
     * @return array
     */
    function last_sql($count = 1, $dataType = 1)
    {
        if (strtolower(get_config('app.env')) == 'testing') {
            \DB::enableQueryLog();
        }

        $arrSql = \DB::getQueryLog();
        $total  = count($arrSql);
        $pos    = $total - $count;
        $pos    = $pos > 0 ? $pos : 0;

        $arrSql = array_slice($arrSql, $pos);
        if ($dataType == 1) {
            foreach ($arrSql as $key => $arr) {
                $sql          = $arr['query'];
                $sql          = str_replace('?', '%s', $sql);
                $arrSql[$key] = vsprintf($sql, $arr['bindings']);
            }
        }

        return $arrSql;
    }
}

/**
 * 加载函数库
 *     load_functions('tag', ...)
 *     load_functions(array('tag', ...))
 * @author heige <zhuwei313@hotmail.com>
 *
 * @param string|array $names
 */
function load_functions($names)
{
    static $cached = ['common' => 1];
    if (func_num_args() > 1) {
        $names = func_get_args();
    } elseif (!is_array($names)) {
        $names = [$names];
    }

    // $names = array_map('strtolower', $names);
    foreach ($names as $name) {
        if (empty($name)) {
            continue;
        }

        if (isset($cached[$name])) {
            continue;
        }

        $file = FUNC_PATH . '/' . $name . '.php';
        if (is_file($file)) {
            require_once $file;
        }
        $cached[$name] = 1;
    }
}

/**
 * CURL POST 请求
 *
 * @param  string   $url
 * @param  array    $postdata
 * @param  array    $curl_opts
 * @return string
 */
function post($url, array $postdata = null, array $curl_opts = null)
{
    $ch = curl_init();

    if (null !== $postdata) {
        $postdata = http_build_query($postdata);
    }

    curl_setopt_array($ch, [
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_URL            => $url,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_POST           => 1,
        CURLOPT_POSTFIELDS     => $postdata,
        CURLOPT_RETURNTRANSFER => 1,
    ]);

    if (null !== $curl_opts) {
        curl_setopt_array($ch, $curl_opts);
    }
    $result = curl_exec($ch);
    // 获取http状态码
    $intReturnCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if (200 != $intReturnCode) {
        return [];
    }

    return $result;
}

/**
 * CURL GET 请求
 *
 * @param  string   $url
 * @param  array    $curl_opts
 * @return string
 */
function get($url, array $curl_opts = null)
{
    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_URL            => $url,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_RETURNTRANSFER => 1,
    ]);

    if (null !== $curl_opts) {
        curl_setopt_array($ch, $curl_opts);
    }

    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}

//curl
function curl($url, $method = 'GET', $data = [], $opts = [])
{
    $method = strtoupper($method);
    $ch     = curl_init();

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    //curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: multipart/form-data']);
    foreach ($opts as $k => $v) {
        curl_setopt($ch, $k, $v);
    }
    switch ($method) {
        case 'GET':
            //拼接get参数
            $url = $data == [] ? $url : $url . '?' . urldecode(http_build_query($data));
            break;
        case 'POST':
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, is_array($data) ? http_build_query($data) : $data);
            break;
        case 'PUT':
        case 'DELETE':
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_POSTFIELDS, is_array($data) ? http_build_query($data) : $data);
        default:
            break;
    }
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        \App\Libs\Slog::error('curl_error: ' . curl_error($ch), __FUNCTION__);
    }

    return $response;
}

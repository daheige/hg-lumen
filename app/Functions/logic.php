<?php
/**
 * 创建logic
 * @author heige
 *
 * @param  string    $name logic名称
 * @return B\Logic
 */
function logic($name, $group = 'Common')
{
    $name  = parse_name($name, 1);
    $class = '\\App\Logics\\' . parse_name($group, 1) . '\\' . $name . 'Logic';
    if (class_exists($class)) {
        $logic = $class::getInstance();
    } else {
        $class = '\\App\Logics\\' . $name . 'Logic';
        $logic = class_exists($class) ? $class::getInstance() : \App\Logics\BaseLogic::getInstance();
    }
    return $logic;
}

/**
 * 创建model
 * @author heige
 *
 * @param  string              $name service名称
 * @return Model\BaseService
 */
function model($name, $group = 'Common')
{
    $name  = parse_name($name, 1);
    $class = '\\App\Models\\' . parse_name($group, 1) . '\\' . $name . 'Model';
    if (class_exists($class)) {
        $model = $class::getInstance();
    } else {
        $class = '\\App\Models\\' . $name . 'Model';
        $model = class_exists($class) ? $class::getInstance() : \App\Models\BaseModel::getInstance();
    }
    return $model;
}

/**
 * 字符串命名风格转换
 * type 0 将Java风格转换为C的风格 1 将C风格转换为Java的风格
 * @param  string   $name 字符串
 * @param  integer  $type 转换类型
 * @return string
 */
function parse_name($name, $type = 0)
{
    if ($type) {
        return ucfirst(preg_replace_callback('/_([a-zA-Z])/', function ($match) {return strtoupper($match[1]);}, $name));
    } else {
        return strtolower(trim(preg_replace("/[A-Z]/", "_\\0", $name), "_"));
    }
}

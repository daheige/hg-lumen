<?php
/**
 * 数组相关函数
 */
/**
 * 从数组中获取值，如果未设定时，返回默认值
 *
 * @param  array   $array
 * @param  string  $name
 * @param  mixed   $default
 * @return mixed
 */
function array_get_value($array, $name, $default = null)
{
    return array_get($array, $name, $default);
}

/**
 * 递归地合并一个或多个数组(不同于 array_merge_recursive)
 *
 * @return array
 */
function array_merge_deep()
{
    $a = func_get_args();
    for ($i = 1; $i < count($a); $i++) {
        foreach ($a[$i] as $k => $v) {
            if (isset($a[0][$k])) {
                if (is_array($v)) {
                    if (is_array($a[0][$k])) {
                        $a[0][$k] = array_merge_deep($a[0][$k], $v);
                    } else {
                        $v[]      = $a[0][$k];
                        $a[0][$k] = $v;
                    }
                } else {
                    $a[0][$k] = is_array($a[0][$k]) ? array_merge($a[0][$k], [$v]) : $v;
                }
            } else {
                $a[0][$k] = $v;
            }
        }
    }

    return $a[0];
}

/**
 * 移除数组中的 null 值
 *
 * @param  array   $array
 * @return array
 */
function array_remove_null(array $array)
{
    return array_filter($array, function ($val) {
        return !is_null($val);
    });
}

/**
 * 移除数组中的 '' 值
 *
 * @param  array   $array
 * @return array
 */
function array_remove_nothing(array $array)
{
    return array_filter($array, function ($val) {
        return '' !== $val;
    });
}

/**
 * 用回调函数，根据数组键&值，过滤数组中的单元
 *
 * @param  array   $array
 * @param  mixed   $callback
 * @return array
 */
function array_filter_full(array $array, $callback)
{
    if (!is_callable($callback)) {
        trigger_error(__FUNCTION__ . '() expects parameter 2 to be a valid callback', E_USER_ERROR);
    }

    return $array = array_filter($array, function ($val) use (&$array, $callback) {
        $key = key($array);
        next($array);

        return (bool) $callback($key, $val);
    });
}

/**
 * 将数据转换为字符，并在同一行输出
 *
 * @param  array    $array
 * @param  string   $separator
 * @param  string   $prefx
 * @return string
 */
function array_join_inline(array $array, $separator = ', ', $prefx = '')
{
    $tmp = [];
    foreach ($array as $key => $val) {
        $tmp[] = "$prefx$key: " . (is_array($val) ? array_join_inline($val, $separator) : $val);
    }

    return implode($separator, $tmp);
}

/**
 * 使用一个二维数组中的某一个key作键值，返回一个新的数组
 *
 *     $a = array(
 *         'a' => array('id' => 1, 'name' => 'x'),
 *         'b' => array('id' => 2, 'name' => 'y'),
 *         'c' => array('id' => 3, 'name' => 'z'),
 *     );
 *     array_using_key($a, 'name') // array('x' => ..., 'y' => ..., ...)
 *
 * @param  array    $array
 * @param  string   key
 * @return array
 */
function array_using_key($array, $key)
{
    $result = [];
    if (empty($array)) {
        return $result;
    }
    foreach ($array as $r) {
        if (is_object($r) && isset($r->$key)) {
            $result[$r->$key] = $r;
        } elseif (is_array($r) && isset($r[$key])) {
            $result[$r[$key]] = $r;
        }
    }

    return $result;
}

/**
 * 用回调函数，根据数组键值，过滤数组中的单元
 *
 * @param  array    $array
 * @param  callable $callback
 * @return array
 */
function array_filter_by_key(array &$array, $callback)
{
    if (!is_callable($callback)) {
        trigger_error(__FUNCTION__ . '() expects parameter 2 to be a valid callback', E_USER_ERROR);
    }

    return $array = array_filter($array, function ($val) use (&$array, $callback) {
        $key = key($array);
        next($array);

        return (bool) $callback($key);
    });
}

/**
 * 从一个二维数组中选取指定字段并返回
 *
 *     $a = array(
 *         'a' => array('id' => 1, 'name' => 'x', 'value' => 'a'),
 *         'b' => array('id' => 2, 'name' => 'y', 'value' => 'b'),
 *         'c' => array('id' => 3, 'name' => 'z', 'value' => 'c'),
 *     );
 *
 *     array_pick($a, 'name') // array('a' => 'x', 'b' => 'y', ...)
 *     array_pick($a, array('id', 'value')) // array('a' => array('id' => 1, 'value' => 'a'), ...)
 *
 * @param  array        $array 要选取的数组
 * @param  array|string $field 要选取的字段
 * @return array
 */
function array_pick($array, $field)
{
    $result = [];
    foreach ($array as $item) {
        if (is_array($field)) {
            $tmp = [];
            foreach ($field as $f) {
                $tmp[$f] = A($item, $f);
            }

            $result[] = $tmp;
        } else {
            $result[] = A($item, $field);
        }
    }

    return $result;
}

/**
 * 使用一个二维数组的某一个列进行分组
 *
 *      从一个二维数组中 选取指定字段并返回 如：
 *      $a = array(
 *          'a'=>   array('msg_id' => 111, 'content' => 'xxx'),
 *          'b'=>   array('msg_id' => 222, 'content' => 'yyy'),
 *          'c'=>   array('msg_id' => 333, 'content' => 'xxx'),
 *      );
 *
 *      array_group($a, 'content') 返回:
 *      Array (
 *          [xxx] => array(
 *              array('msg_id' => 111, 'content' => 'xxx'),
 *              array('msg_id' => 333, 'content' => 'xxx')
 *          )
 *          [yyy] =>  array(
 *              array('msg_id' => 222, 'content' => 'yyy')
 *          )
 *      )
 *
 * @param  array    $array
 * @param  string   $key
 * @return array
 */
function array_group(array $array, $key)
{
    $return = [];
    foreach ($array as $v) {
        if (isset($v[$key])) {
            $return[$v[$key]][] = $v;
        }
    }

    return $return;
}

/**
 * 数据转 stdClass
 *
 * @param  array       $array
 * @return \stdClass
 */
function array_to_std(array $array)
{
    $std = new \stdClass;
    foreach ($array as $key => $val) {
        if (!$key) {
            continue;
        }

        $std->$key = is_array($val) ? array_to_std($val) : $val;
    }

    return $std;
}

/**
 * 三维数组按字段求和
 *
 * @param array  $array
 * @param string $filed     字段
 * @param array  $condition 条件 类似 array('cop_way' => 1);
 */
function array_sum_field(array $array = [], $filed = '', array $conditions = [])
{
    if (empty($array) || empty($filed)) {
        return 0;
    }
    $sum = 0;
    foreach ($array as $item) {
        if (!isset($item[$filed])) {
            continue;
        }
        $bool = true;
        foreach ($conditions as $key => $val) {
            if (!(isset($item[$key]) && $item[$key] == $val)) {
                $bool = false;
            }
        }
        if ($bool) {
            $sum += $item[$filed];
        }
    }

    return $sum;
}
/**
 * 统计三维数组中符合条件的记录数
 *
 * @param array $array
 * @param array $condition 条件 类似 array('cop_way' => 1);
 */
function array_count(array $array = [], array $conditions = [])
{
    return count(array_filter($array, function ($item) use ($conditions) {
        $bool = true;
        foreach ($conditions as $key => $val) {
            if (isset($item[$key]) && $item[$key] == $val) {
                $bool = false;
            }
        }

        return $bool;
    }));
}

// 把一个对象结构递归变成一数组结构
function o2a($d)
{
    if (is_object($d)) {
        if (method_exists($d, 'getArrayCopy')) {
            $d = $d->getArrayCopy();
        } elseif (method_exists($d, 'getArrayIterator')) {
            $d = $d->getArrayIterator()->getArrayCopy();
        } elseif (method_exists($d, 'toArray')) {
            $d = $d->toArray();
        } else {
            $d = get_object_vars($d);
        }
    }

    if (is_array($d)) {
        return array_map(__FUNCTION__, $d);
    }

    return $d;
}

// 把数组中的空数组转成null
function empty2null($arr)
{
    if (empty($arr) && is_array($arr)) {
        return null;
    }

    if (is_array($arr)) {
        return array_map(__FUNCTION__, $arr);
    }

    return $arr;
}

//数组处理,去掉在arr1中arr2值,不存在则忽略
function filter_array($arr1, $arr2)
{
    if (empty($arr2)) {
        return array_unique($arr1);
    }

    foreach ($arr1 as $k => $val) {
        if (in_array($val, $arr2)) {
            unset($arr1[$k]);
        }
    }

    return array_unique($arr1);
}

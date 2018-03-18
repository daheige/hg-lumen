<?php

/**
 * 别名函数
 */
/**
 * 从数组中获取值，如果未设定时，返回默认值
 *
 * @see array_get
 */
function A($array, $name, $default = null)
{
    return array_get($array, $name, $default);
}

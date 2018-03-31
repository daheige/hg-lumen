<?php
namespace App\Logics;

class CommonLogic extends BaseLogic
{
    //检测手机号码
    public function checkMobile($mobile)
    {
        return preg_match("/1[345789]{1}\d{9}$/", $mobile);
    }

    public function checkEmail($email)
    {
        return preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/", $email);
    }

    //参数基本校验
    public function checkPublicParams($params = [], $isCheck = true)
    {
        if (!$isCheck) {
            return true;
        }

        if (!isset($params['app_version']) || empty($params['app_version'])) {
            $this->setErrorInfo(10001, 'app版本号不能为空');
            return false;
        }

        if (!isset($params['app_utm']) || empty($params['app_utm'])) {
            $this->setErrorInfo(10001, 'app_utm不能为空');
            return false;
        }

        return true;
    }

    /**
     * [getSubTableNum 获取分表的表后缀]
     * @param  [type] $name                                                      [phone或unionid,email等]
     * @return [type] [手机号支持uid,hash分表，其他类型采用hash]
     */

    public function getSubTableNum($name, $isSubtable = true)
    {
        if (!$isSubtable) {
            return '';
        }

        $conf = get_config('subtable.member');
        //手机号
        if (preg_match('/^1(3|4|5|7|8|9)\d{11}$/', $name)) {
            $num = $conf['type'] == 'uid' ? intval(substr($name, -2)) : sprintf("%u", crc32($name));
            return $num % $conf['num'];
        } else if (is_numeric($name)) {
            $num = $conf['type'] == 'uid' ? intval(substr($name, -2)) : sprintf("%u", crc32($name));
            return $num % $conf['num'];
        }

        $crc = sprintf("%u", crc32($name));
        return $crc % $conf['num'];
    }

}

<?php

namespace App\Models;

use DB;

//测试模型
class TestModel extends BaseModel
{
    protected $connection    = 'hglumen';
    protected $_table_prefix = 'user_';
    protected $table         = 'user';

    public static function getUserById($id = 0)
    {
        if (empty($id)) {
            return false;
        }

        //采用门面的方式调用
        $res = DB::connection('hglumen')
            ->table('user')
            ->where('id', $id)
            ->first();
        return o2a($res); //转换为数组
    }

    //采用自身的self::where方式调用
    public function getUser($id)
    {
        $this->setTable($this->_table_prefix, 1); //设置操作的表
        $res = self::where('id', $id)->first();
        return $res ? o2a($res) : null;
    }

}

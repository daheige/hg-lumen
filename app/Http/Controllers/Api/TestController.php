<?php

namespace App\Http\Controllers\Api;

class TestController extends BaseController
{
    public function info()
    {
        return $this->success(['id' => 1, 'user' => 'daheige']);
        die;
        write_log('daheige', __FUNCTION__, 'info'); //写日志方式
    }

    public function test()
    {
        return $this->success(['id' => 1, 'user' => 'daheige']);
        $redis = redis('default');
        $redis->set('hg_name', '1234');
        $res = $redis->get('hg_name');
        var_dump($res);die;
    }

    public function foo()
    {
        $res = service('Test')->getUser();
        echo $res;die;
    }
}

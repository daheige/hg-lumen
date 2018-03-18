<?php

namespace App\Http\Controllers;

class ExampleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function test()
    {
        return view('index', ['name' => 'heige']);
    }

    public function info()
    {
        $redis = redis();
        $redis->set('hg_name', 'daheige');
        return "redis set success!";
    }

    public function getUser()
    {
        echo logic('Test')->getUser();
        die;
    }
}

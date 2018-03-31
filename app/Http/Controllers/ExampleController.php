<?php

namespace App\Http\Controllers;

class ExampleController extends ControllerBase
{
    public function test()
    {
        return view('index', ['name' => 'heige']);
    }

    public function info()
    {
        echo 333;
    }
}

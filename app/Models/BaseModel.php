<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Illuminate\Database\Query\Builder
 */
class BaseModel extends Model
{
    protected static $instances = [];
    /**
     * 获取单例实例
     *
     * @return App\Models\BaseModel
     */
    public static function getInstance()
    {
        $class = get_called_class();

        if (!isset(self::$instances[$class])) {
            self::$instances[$class] = new $class;
        }

        return self::$instances[$class];
    }

    public function __construct()
    {
        parent::__construct();
    }
}

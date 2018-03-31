<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Illuminate\Database\Query\Builder
 */
class BaseModel extends Model
{
    protected static $instances = [];
    protected $connection       = ''; //db连接实例
    protected $table            = ''; //表名称
    protected $_table_prefix    = ''; //分表前缀
    public $timestamps          = false;

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

    //设置表方法，映射到相关的表上
    public function setTable($num = 0)
    {
        $this->table = !empty($this->_table_prefix) ? $this->_table_prefix . $num : $this->table;
        return $this;
    }
}

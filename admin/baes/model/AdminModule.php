<?php
namespace admin\baes\model;

use think\Model;

class AdminModule extends Model{

    //设置连接库
    protected $connection = 'db_config_cards';

    //启用字段过滤
    protected $field = true;

    // 定义时间戳字段名
    protected $autoWriteTimestamp = true;
    protected $createTime = 'add_time';
    protected $updateTime = 'update_time';

    //修改返回格式
    protected $resultSetType = 'collection';

    public function initialize()
    {
        //继承父级构造函数
        parent::initialize();

    }

    //查询单挑数据
    public function findAdminModule($field = '*',$condition = ''){
        $data = $this-> field($field)
            -> where($condition)
            -> find();
        return $data -> toArray();
    }

    //查询多条数据
    public function getAdminModule($field = '*',$condition = '',$order = 'sort desc'){
        $data = $this-> field($field)
            -> where($condition)
            -> order($order)
            -> select();
        return $data -> toArray();
    }

}


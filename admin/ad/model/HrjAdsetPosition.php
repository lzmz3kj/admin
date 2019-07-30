<?php
namespace app\ad\model;

use think\Model;

class HrjAdsetPosition extends Model{

    //设置连接库
    protected $connection = 'db_config_cards';
    
    //启用字段过滤
    protected $field = true;
    
    //修改返回格式
    protected $resultSetType = 'collection';

    public function initialize()
    {
        //继承父级构造函数
        parent::initialize();

    }

   
    //查询单挑数据
    public function findHrjAdsetPosition($field = '*',$condition = ''){
        $data = $this-> field($field)
            -> where($condition)
            -> find();
        return $data -> toArray();
    }

    //查询多条数据
    public function getHrjAdsetPosition($field = '*',$condition = '',$order = 'positionid ASC'){
        $data = $this-> field($field)
            -> where($condition)
            -> order($order)
            -> select();
        return $data -> toArray();
    }

}


<?php
namespace app\ad\model;

use think\Model;

class Category extends Model{

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

    //连表查询数据
    public function joinCategory($field = '*',$condition = '',$order = 'a.catorder'){
        $data = $this -> alias('a')
            -> field($field)
            -> join('hrj_catcity b','a.catid=b.catid','LEFT')
            -> where($condition)
            -> order($order)
            -> select();
        return $data -> toArray();
    }

    //查询单挑数据
    public function findCategory($field = '*',$condition = ''){
        $data = $this-> field($field)
            -> where($condition)
            -> find();
        return !empty($data)?$data -> toArray():$data;
    }

    //查询多条数据
    public function getCategory($field = '*',$condition = '',$order = ''){
        $data = $this-> field($field)
            -> where($condition)
            -> order($order)
            -> select();
        return $data -> toArray();
    }

}


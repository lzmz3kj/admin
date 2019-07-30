<?php
namespace admin\myweb\model;

use think\Model;

class Nav extends Model{

    //设置连接库
    protected $connection = 'database_myweb';

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
    public function findNav($field = '*',$condition = ''){
        $data = $this-> field($field)
            -> where($condition)
            -> find();
        return $data -> toArray();
    }

    //查询多条数据
    public function getNav($field = '*',$condition = '',$order = 'id desc'){
        $data = $this-> field($field)
            -> where('nav_name','like',"%{$condition['search']}%")
            -> order($order)
            -> select();
        return $data -> toArray();
    }

}


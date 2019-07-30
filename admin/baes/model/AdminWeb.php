<?php
namespace admin\baes\model;

use think\Model;

class AdminWeb extends Model{

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

    //获取器
    public function getDatabaseAttr($value)
    {
        return json_decode($value,true);
    }

    //查询单挑数据
    public function findAdminWeb($field = '*',$condition = ''){
        $data = $this-> field($field)
            -> where($condition)
            -> find();
        return $data -> toArray();
    }

    //查询多条数据
    public function getAdminWeb($field = '*',$condition = '',$order = 'id desc'){
        $data = $this-> field($field)
            -> where('web_name','like',"%{$condition['search']}%")
            -> order($order)
            -> select();
        return $data -> toArray();
    }

}


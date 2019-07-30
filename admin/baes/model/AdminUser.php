<?php
namespace admin\baes\model;

use think\Model;

class AdminUser extends Model{

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
    public function findAdminUser($field = '*',$condition = ''){
        $data = $this-> field($field)
            -> where($condition)
            -> find();
        return !empty($data)?$data -> toArray():false;
    }

    //查询多条数据
    public function getAdminUser($field = '*',$condition = '',$order = 'id desc'){
        $data = $this-> field($field)
            -> where($condition)
            -> order($order)
            -> select();
        return $data -> ToArray();
    }

    //修改数据
    public function updateAdminUser($condition = '',$data = []){
        return $this  -> allowField(true)->save($data,$condition);
    }


}


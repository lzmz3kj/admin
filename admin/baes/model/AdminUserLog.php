<?php
namespace admin\baes\model;

use think\Model;

class AdminUserLog extends Model{

    //启用字段过滤
    protected $field = true;

    // 定义时间戳字段名
    protected $autoWriteTimestamp = true;
    protected $createTime = 'add_time';

    //修改返回格式
    protected $resultSetType = 'collection';

    public function initialize()
    {
        //继承父级构造函数
        parent::initialize();

    }

    //转换器
    public function getTypeAttr($value)
    {
        $status = [1=>'<span class="text-success">添加</span>',2=>'<span class="text-info">修改</span>',3=>'<span class="text-danger">删除</span>',4=>'<span class="text-warning">异常</span>',5=>'<span class="text-info">其他</span>',0=>'<span class="text-warning">未知</span>'];
        return $status[$value];
    }
    //添加数据
    public function addUserLog($data){
        return $this -> insert($data);
    }

    //连表查询数据
    public function getLog($field = '*',$condition = '',$search,$limit = 15){
        return $this-> alias('a')
            -> field($field)
            -> where('b.admin_name','like',"%{$search['search']}%")
            -> where($search['type'])
            -> join('admin_user b','a.uid = b.id','LEFT')
            -> order('a.add_time DESC')
            -> paginate($limit,false,$condition);
    }

}


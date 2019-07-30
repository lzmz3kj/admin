<?php
namespace app\ad\model;

use think\Model;

class HrjAdset extends Model{

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
    //获取器
    public function getStatusAttr($value)
    {
        $status = [ 0 =>'<span class="text-warning">关闭</span>' , 1 => '<span class="text-success">开启</span>'];
        return ['raw' => $value,'new' => $status[$value]];
    }

    public function getFidsAttr($value)
    {
        $status = explode (',',$value);
        return $status;
    }

    public function getBegintimeAttr($value)
    {
        $status = date ('Y-m-d H:i:s',$value);
        return $status;
    }

    public function getEndtimeAttr($value)
    {
        $status = date ('Y-m-d H:i:s',$value);
        return $status;
    }

    public function getMarginAttr($value)
    {
        $status = explode ('-',$value);
        return ['top' => $status[0] ,'right' => $status[1] ,'bottom' => $status[2] ,'left' => $status[2]];
    }

    //连表查询数据
    public function gapeHrjAdset($field = '*',$condition = '',$order = 'displayorder ASC',$limit = 10){
        return $this -> alias('a')
            -> field($field)
            -> join('hrj_adset_position b','a.positionid=b.positionid','INNER')
            -> where('a.name','like',"%{$condition['name']}%")
            -> where('a.fids','like',"%{$condition['fids']}%")
            -> where($condition['where'])
            -> order($order)
            -> paginate($limit,false,$condition['page']);
    }

    //查询单挑数据
    public function findHrjAdset($field = '*',$condition = ''){
        $data = $this-> field($field)
            -> where($condition)
            -> find();
        return $data -> toArray();
    }

    //查询多条数据
    public function getHrjAdset($field = '*',$condition = '',$order = ''){
        $data = $this-> field($field)
            -> where($condition)
            -> order($order)
            -> select();
        return $data -> toArray();
    }

}


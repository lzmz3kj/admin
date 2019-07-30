<?php
namespace app\ad\model;

use think\Model;

class HrjAppadset extends Model{

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

    public function getEndtimeAttr($value)
    {
        $status = date('Y-m-d H:i:s',$value);
        return $status;
    }

    public function getBegintimeAttr($value)
    {
        $status = date('Y-m-d H:i:s',$value);
        return $status;
    }

    public function getTypeAttr($value)
    {
        $status = [0 => '其他', 1=> '内部帖子跳转',2 => '内部餐馆跳转' ,3 => '浏览器跳转',4=> '纯图片广告',5=>'google广告'];
        return $status[$value];
    }


    public function getPicAttr($value)
    {
        $img_url= config('img_url').$value;
        return  ['new' => $img_url,'raw' => $value];
    }


    //连表查询数据
    public function gapeHrjAppadset($field = '*',$condition = '',$order = 'sort DESC',$limit = 10){
        return $this-> field($field)
            -> where('title','like',"%{$condition['title']}%")
            -> where($condition['where'])
            -> order($order)
            -> order('dateline DESC')
            -> paginate($limit,false,$condition['page']);
    }

    //查询单挑数据
    public function findHrjAppadset($field = '*',$condition = ''){
        $data = $this-> field($field)
            -> where($condition)
            -> find();
        return $data -> toArray();
    }

    //查询多条数据
    public function getHrjAppadset($field = '*',$condition = '',$order = ''){
        $data = $this-> field($field)
            -> where($condition)
            -> order($order)
            -> select();
        return $data -> toArray();
    }

}


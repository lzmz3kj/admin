<?php
namespace app\ad\model;

use think\Model;

class AppConfig extends Model{

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
    public function getEndtimeAttr($value)
    {
        $value = date('Y-m-d H:i:s',$value);
        return $value;
    }
    public function getPicAttr($value)
    {
        $img_url= config('img_url').$value;
        return  ['url' => $img_url,'raw' => $value];
    }

    //连表查询数据
    public function gapeAppConfig($field = '*',$condition = '',$order = 'endtime DESC',$limit = 10){
        return $this-> field($field)
            -> where('title','like',"%{$condition['title']}%")
            -> where($condition['where'])
            -> order($order)
            -> paginate($limit,false,$condition['page']);
    }

    //查询单挑数据
    public function findAppConfig($field = '*',$condition = ''){
        $data = $this-> field($field)
            -> where($condition)
            -> find();
        return !empty($data)?$data -> toArray():$data;
    }

    //查询多条数据
    public function getAppConfig($field = '*',$condition = '',$order = ''){
        $data = $this-> field($field)
            -> where($condition)
            -> order($order)
            -> select();
        return $data -> toArray();
    }

}


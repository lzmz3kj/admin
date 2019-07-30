<?php
namespace app\ad\model;

use think\Model;

class ForumCffiles extends Model{

    //启用字段过滤
    protected $field = true;

    //修改返回格式
    protected $resultSetType = 'collection';

    public function initialize()
    {
        //继承父级构造函数
        parent::initialize();

    }


    public function getFilepathAttr($value)
    {

        $status= config('img_url').$value;
        return  ['new' => $status,'raw' => $value];
    }

    //连表查询数据
    public function gapeForumCffiles($field = '*',$condition = '',$order = 'dateline DESC',$limit = 8){
        return $this-> field($field)
            -> where('filedesc','like',"%{$condition['name']}%")
            -> order($order)
            -> paginate($limit,false,$condition['page']);
    }

    //查询单挑数据
    public function findForumCffiles($field = '*',$condition = ''){
        $data = $this-> field($field)
            -> where($condition)
            -> find();
        return !empty($data)?$data -> toArray():$data;
    }

    //查询多条数据
    public function getForumCffiles($field = '*',$condition = '',$order = ''){
        $data = $this-> field($field)
            -> where($condition)
            -> order($order)
            -> select();
        return $data -> toArray();
    }

}


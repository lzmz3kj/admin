<?php
namespace app\ad\model;

use think\Model;

class ForumForum extends Model{

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
    public function gapeForumForum($field = '*',$condition = '',$order = ''){
        return $this-> alias('a')
            -> field($field)
            -> join('forum_forumfield b','a.fid=b.fid','inner')
            -> where($condition)
            -> whereOr(['b.mycountry' => ''])
            -> order($order)
            -> select();
    }

    //查询单挑数据
    public function findForumForum($field = '*',$condition = ''){
        $data = $this-> field($field)
            -> where($condition)
            -> find();
        return $data -> toArray();
    }

    //查询多条数据
    public function getForumForum($field = '*',$condition = '',$order = ''){
        $data = $this-> field($field)
            -> where($condition)
            -> order($order)
            -> select();
        return $data -> toArray();
    }

}


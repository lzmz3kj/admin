<?php
namespace app\ad\model;

use think\Model;

class ForumCfadset extends Model{

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
        return $status[$value];
    }
    public function getCtypeAttr($value)
    {
        $status = [
            0=>'文字置顶',1=>'图片置顶',2=>'中介用户',3=>'左侧浮动1',4=>'左侧浮动2',5=>'右侧浮动1',6=>'右侧浮动2',7=>'主导航下广告1',8=>'主导航下广告2',9=>'主导航下广告3',10=>'主导航下广告4',
            11=>'主导航下广告5', 12=>'主导航下广告6', 13=>'主导航下广告7', 14=>'主导航下广告8', 15=>'主导航下广告9', 16=>'主导航下广告10',
            ];

        return ['val' => $value, 'text' => $status[$value]];
    }
    public function getFidsAttr($value)
    {
        $status = explode (',',$value);
        return $status;
    }

    //连表查询数据
    public function gapeForumCfadset($field = '*',$condition = '',$order = 'serialno ASC',$limit = 10){
        return $this-> field($field)
            -> where('name','like',"%{$condition['name']}%")
            -> where('fids','like',"%{$condition['fids']}%")
            -> where($condition['where'])
            -> order($order)
            -> paginate($limit,false,$condition['page']);
    }

    //查询单挑数据
    public function findForumCfadset($field = '*',$condition = ''){
        $data = $this-> field($field)
            -> where($condition)
            -> find();
        return !empty($data)?$data -> toArray():$data;
    }

    //查询多条数据
    public function getForumCfadset($field = '*',$condition = '',$order = ''){
        $data = $this-> field($field)
            -> where($condition)
            -> order($order)
            -> select();
        return $data -> toArray();
    }

}


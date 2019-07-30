<?php
//后台日志控制器
namespace admin\baes\controller;

class AdminLog extends Baes
{

    public function __construct()
    {
        //继承父级构造函数
        parent::__construct();

        $this -> assign('viewname' , '日志');//视图层标记
        //调用权限方法
        $this -> allow();
    }

    //列表页
    public function lists()
    {
        $where['query']['search'] = $search['search'] = input('search');
        if(input('type')){
            $where['query']['type'] = input('type');
            $search['type'] = ['type' => input('type')];
        }else{
            $search['type'] = '';
        }

        $list = model('AdminUserLog') ->getLog(['a.id','a.content','a.add_time','a.add_ip','a.type','b.admin_name'],$where,$search);
        $this -> assign('list' , $list);
        //加载本页面数据
        return $this -> fetch('adminLog/list');
    }
}

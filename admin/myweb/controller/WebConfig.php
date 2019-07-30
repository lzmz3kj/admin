<?php
//后台日志控制器
namespace admin\myweb\controller;

use think\Hook;

class WebConfig extends Baes
{

    public function __construct()
    {
        //继承父级构造函数
        parent::__construct();

        //调用权限方法
        $this -> allow();
    }

    //点点信息
    public function info()
    {
        if(request() -> isAjax()){
            $data = input('post.');
            $Validate = validate('WebConfig');
            //验证字段
            if(!$Validate -> check($data)){
                $return = [
                    'status'    => false,
                    'msg'       => $Validate -> getError(),
                    'token' => request()->token()
                ];
                return $return;
            }
            if(!empty($data['id'])){
                //添加操作
                $src = model('Website') -> isUpdate()->save($data);
                //返回结果
                if(empty($src)){
                    $return = [
                        'status'    => false,
                        'msg'       => '操作失败！网络连接错误！',
                        'token'     => request()->token()
                    ];
                }else{
                    $return = [
                        'status'       => true,
                        'msg'          =>'操作成功！',
                        'Additional'   => url('info')
                    ];
                    //调用行为监听
                    $logData = [
                        'type' => 2,
                        'content' => '修改个人主站信息',
                    ];
                    Hook::listen('admin_log',$logData);
                }
            }else{
                $return = [
                    'status'       => false,
                    'msg'          =>'操作失败！未收集到站点ID',
                    'token'     => request()->token()
                ];
            }
            return $return;
        }
        $info = model('Website') ->findWebsite();
        $this -> assign('info' , $info);
        //加载本页面数据
        $this -> assign('viewname' , '个人主站');//视图层标记
        return $this -> fetch('webConfig/info');
    }

    //站点导航
    public function navList(){
        $list = model('Nav') ->getNav('*',['search' => input('search')]);
        $list = makeTree($list);
        $this -> assign('list' , $list);
        //加载本页面数据
        $this -> assign('viewname' , '站点导航');//视图层标记
        return $this -> fetch('webConfig/navlist');
    }

    //站点导航
    public function navInfo(){
        //详情
        if($id = input('id')){
            $info = model('Nav') -> findNav('*',['id' => $id]);
            $this -> assign('info' , $info);
        }
        //加载本页面数据
        $list = model('Nav') -> getNav('*',['search' => '']);
        $list = makeTree($list);
        $this -> assign('list' , $list);
        $this -> assign('viewname' , '站点导航');//视图层标记
        return $this -> fetch('webConfig/navinfo');
    }

    //导航啦提交操作
    public function navSubmit(){
        if(request() -> isAjax()){
            $data = input('post.');
            $Validate = validate('Nav');
            //验证字段
            if(!$Validate -> check($data)){
                $return = [
                    'status'    => false,
                    'msg'       => $Validate -> getError(),
                    'token' => request()->token()
                ];
                return $return;
            }
            //判断是修改还是添加
            if(empty($data['id'])){
                //添加
                $src = model('Nav') -> save($data);
            }else{
                //修改
                $src = model('Nav') -> isUpdate()->save($data);
            }

            //返回结果
            if(empty($src)){
                $return = [
                    'status'    => false,
                    'msg'       => '操作失败！网络连接错误！',
                    'token' => request()->token()
                ];
            }else{
                $return = [
                    'status'    => true,
                    'msg'       =>'操作成功！',
                    'Additional'     => url('navList')
                ];
                //调用行为监听
                $logData = [
                    'type' => (!empty($data['id'])?2:1),
                    'content' => (!empty($data['id'])?'修改':'添加').'站点导航栏：'.$data['nav_name'],
                ];
                Hook::listen('admin_log',$logData);
            }
            return $return;
        }else{
            $return = [
                'status'    => false,
                'msg'       => '非法提交！',
                'token' => request()->token()
            ];
            return $return;
        }
    }

    //动态修改导航栏
    public function navShut(){
        if(request() -> isAjax()){
            $data = input('post.');
            $Validate = validate('Nav');
            //验证字段
            if(!$Validate -> scene('shut')-> check($data)){
                $return = [
                    'status'    => false,
                    'msg'       => $Validate -> getError(),
                    'token' => request()->token()
                ];
                return $return;
            }
            //先查看是否有子级
            $child = model('Nav')->field(['id','nav_name']) ->where(['pid' => $data['id']]) -> where('nav_status','neq',$data['nav_status']) -> find();
            //如果有子级就先修改子级
            $srcChild = true;
            if(!empty($child)){
                $srcChild  = model('Nav') -> where(['pid' => $data['id']])-> update(['nav_status' => $data['nav_status']]);
            }
            if(!empty($srcChild)){
                $src = model('Nav') -> isUpdate()->save($data);
            }
            //返回结果
            if(empty($src)){
                $return = [
                    'status'    => false,
                    'msg'       => '操作失败！网络连接错误！',
                    'token' => request()->token()
                ];
            }else{
                $return = [
                    'status'    => true,
                    'msg'       =>'操作成功！',
                    'Additional'     => url('lists')
                ];
                //调用行为监听
                $logData = [
                    'type' => 2,
                    'content' =>'关闭模块：'.$child['nav_name'],
                ];
                Hook::listen('admin_log',$logData);
            }
            return $return;
        }else{
            $return = [
                'status'    => false,
                'msg'       => '非法提交！',
                'token' => request()->token()
            ];
            return $return;
        }
    }

}

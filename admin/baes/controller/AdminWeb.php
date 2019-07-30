<?php
//子站设置控制器
namespace admin\baes\controller;

use think\Hook;

class AdminWeb extends Baes
{

    public function __construct()
    {
        //继承父级构造函数
        parent::__construct();

        $this -> assign('viewname' , '子站');//视图层标记
        //调用权限方法
        $this -> allow();
    }

    //列表
    public function lists()
    {
        $list = model('AdminWeb') -> getAdminWeb('*',['search' => input('search')]);
        $this -> assign('list' , $list);
        return $this -> fetch('web/list');
    }

    //详情
    public function info(){
        //详情
        if($id = input('id')){
            $info = model('AdminWeb') -> findAdminWeb('*',['id' => $id]);
            $this -> assign('info' , $info);
        }
        //加载本页面数据
        return $this -> fetch('web/info');
    }

    //提交操作
    public function submit(){
        if(request() -> isAjax()){
            $data = input('post.');
            $Validate = validate('AdminWeb');
            //验证字段
            if(!$Validate -> check($data)){
                $return = [
                    'status'    => false,
                    'msg'       => $Validate -> getError(),
                    'token' => request()->token()
                ];
                return $return;
            }
            //组合参数
            $data['database'] = json_encode([
                'hostname' => $data['hostname'],
                'type'     => $data['type'],
                'database' => $data['database'],
                'username' => $data['username'],
                'password' => $data['password'],
                'hostport' => $data['hostport'],
                'prefix'   => $data['prefix'],
            ]);
            //判断是修改还是添加
            if(empty($data['id'])){
                //添加
                $src = model('AdminWeb') -> save($data);
            }else{
                //修改
                $src = model('AdminWeb') -> isUpdate()->save($data);
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
                    'type' => (!empty($data['id'])?2:1),
                    'content' => (!empty($data['id'])?'修改':'添加').'子站：'.$data['web_name'],
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

    //关闭模块
    public function shut(){
        if(request() -> isAjax()){
            $data = input('post.');
            $Validate = validate('AdminWeb');
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
            $child = model('AdminWeb')->field(['id','name']) ->where(['pid' => $data['id']]) -> where('status','neq',$data['status']) -> find();
            //如果有子级就先修改子级
            $srcChild = true;
            if(!empty($child)){
                $srcChild  = model('AdminWeb') -> where(['pid' => $data['id']])-> update(['status' => $data['status']]);
            }
            if(!empty($srcChild)){
                $src = model('AdminWeb') -> isUpdate()->save($data);
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
                //清除模块缓存
                session(config('AdminConfig.Module'),null);
                //调用行为监听
                $logData = [
                    'type' => 2,
                    'content' => '关闭模块：'.$child['name'],
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

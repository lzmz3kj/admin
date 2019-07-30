<?php
namespace admin\baes\controller;

use think\Request;
use think\Controller;
use think\Hook;

class Login extends Controller
{
    public function __construct()
    {
        //继承父级构造函数
        parent::__construct();

    }

    //登录
    public function adminLogin(){
        if(request() -> isAjax()){
            $data = input('post.');
            $Validate = validate('AdminLogin');
            //验证字段
            if(!$Validate -> scene('login') -> check($data)){
                $return = [
                    'status'    => false,
                    'msg'       => $Validate -> getError(),
                    'token' => request()->token(),
                ];
                return $return;
            }
            $where = [
                'admin_name' => $data['admin_name'],
                'admin_pass' => md5X($data['admin_pass'],$data['admin_name']),
                'status'     => 1
            ];
            $userInfo = model('AdminUser') -> findAdminUser('*',$where);
            //判断用户是否存在
            if(!empty($userInfo)){
                $return = [
                    'status'      => true,
                    'Additional'  => url('Baes/index'),
                ];
                //调用行为监听
                Hook::listen('admin_login',$userInfo);
                Hook::listen('admin_log',$userInfo);
                //将用户信息保存至SESSION
                session('last_time',time()+1800);
                session(config('AdminConfig.User'),$userInfo);
            }else{
                $return = [
                    'status' => false,
                    'msg'    => '账号或密码错误！',
                    'token'  => request()->token(),
                ];
            }
            return $return;
        }
        //加载本界资源
        return $this -> fetch('adminLogin/login');
    }

    //退出登录
    public function adminQuit()
    {
        if(request() -> isAjax()){
            $data = input('post.');
            $Validate = validate('AdminLogin');
            //验证字段
            if(!$Validate -> scene('ouit') -> check($data)){
                $return = [
                    'status'    => false,
                    'msg'       => $Validate -> getError(),
                    'token' => request()->token()
                ];
                return $return;
            }
            //记录日志
            $userInfo = session(config('AdminConfig.User'));
            $userLog = [
                'uid' => $userInfo['id'],
                'type' => 4,
                'content' => '用户于 '.date('Y-m-d H:i:s',time()).' 退出登录！'
            ];
            Hook::listen('admin_log',$userLog);
            //清除缓存
            session(null);
            //返回结果
            $return = [
                'status'    => true,
                'msg'       =>'操作成功！',
            ];
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

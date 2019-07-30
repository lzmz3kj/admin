<?php
namespace admin\baes\controller;

use think\Hook;
use think\Request;
use think\Controller;
use admin\baes\model\AdminModule;


class Baes extends Controller{

    protected $AdminUser;//后台用户基本信息

    public function __construct()
    {
        //继承父级构造函数
        parent::__construct();


        //预加载方法和参数
        $this -> is_login();
        $this -> ModuleList();
    }

    //后台首页
    public function index(){
        return $this -> fetch();
    }


    //查询用户登录情况
    public function is_login(){
        if ((session('last_time') - time()) <= 0) {
            //清除掉缓存数据
            session(null);
            return $this -> redirect('baes/Login/adminLogin');
        }
        $this -> AdminUser = session(config('AdminConfig.User'));
    }

    //调用模块列表
    public function ModuleList(){
        if($list = session(config('AdminConfig.Module'))){
            $list = json_decode($list,true);
        }else{
            $AdminModule = new AdminModule();
            //判断用户是否为管理员
            if($this -> AdminUser['limit'] == 1){
                $list = $AdminModule -> getAdminModule('*',['status' => 1]);
            }else{
                //组合成符合用户权限访问的导航栏
                $Fatherlist = $AdminModule -> getAdminModule('*',['status' => 1,'pid' => 0]);
                $list = $AdminModule -> where('id' , 'in',$this -> AdminUser['level']) -> whereOr('pid','in',$this -> AdminUser['level']) -> select()-> toArray();
                $idArr = 0;
                foreach ($Fatherlist as $sk => $sv){
                    foreach ($list as $k => $v){
                        if($v['pid'] == $sv['id'] && $v['pid'] != $idArr){
                            array_push($list,$sv);
                            $idArr = $sv['id'];
                        }
                    }
                }
            }
            $list = makeTree($list);
            session(config('AdminConfig.Module'),json_encode($list));
        }
        $this -> assign('ModuleList' , $list);
    }

    //访问许可
    public function allow(){
        //判断用户是否为管理员
        if($this -> AdminUser['limit'] != 1){
            //获取当前模块路由
            $controller = request()->controller();//当前控制器
            $list = json_decode(session(config('AdminConfig.Module')),true);
            foreach ($list as $sk => $sv){
                foreach ($sv['has_son'] as $k => $v){
                    if($v['mark'] == $controller){
                        $rsc = true;
                    }
                }
            }
        }else{
            $rsc = true;
        }
        //判断是否非法访问
        if(empty($rsc)){
            //同步记录非法请求记录
            $text = '非法请求地址：'.request()->module().'--'.request()->controller().'--'.request()->action();
            $data = [
                'type' => 4,//异常
                'content' => $text,
            ];
            Hook::listen('admin_log',$data);
            return $this -> redirect('baes/Baes/index');
        }
    }

    //文件上传
    public function uploadFile(){
        $file = request()->file('file');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $data = input('post.');
        if(!empty($data['path'])){
            $path = 'public/'.$data['path'];
        }else{
            $path = 'public/upload/file';
        }

        $info = $file->move('./'.$path);
        if(!empty($info)){
            $fileName = $info->getSaveName();
            $imgInfo = getimagesize('./'.$path.'/'.$fileName);
            $return = [
                'path'  => $path,
                'width' => $imgInfo[0],
                'height' => $imgInfo[1],
                'filename'  => $fileName,
                'fileUrl'   => $path.'/'.$fileName,
                'token'     => request()->token()
            ];
            return json_encode($return);
        }
    }

}
<?php
//后台管理员控制器
namespace admin\baes\controller;

use think\Hook;

class AdminUser extends Baes
{

    public function __construct()
    {
        //继承父级构造函数
        parent::__construct();

        $this -> assign('viewname' , '管理员');//视图层标记
        //调用权限方法
        $this -> allow();
    }

    //列表页
    public function lists()
    {

        if($search = input('search')){
            $list = model('AdminUser') -> where('admin_name','like','%'.$search.'%') -> select()-> toArray();
        }else{
            $list = model('AdminUser') -> getAdminUser('*');
        }
        foreach ($list as $sk => $sv){
            $list[$sk]['level'] = explode(',',$sv['level']);
        }
        $this -> assign('list' , $list);
        //加载本页面数据
        $moduleList = model('AdminModule') -> getAdminModule();
        $moduleList = makeTree($moduleList);
        $this -> assign('moduleList' , $moduleList);
        return $this -> fetch('adminUser/list');
    }

    //详情页
    public function info(){
        //详情
        if($id = input('id')){
            $info = model('AdminUser') -> findAdminUser('*',['id' => $id]);
            $info['level'] = explode(',',$info['level']);
            $this -> assign('info' , $info);
        }
        //加载本页面数据
        $list = model('AdminModule') -> getAdminModule();
        $list = makeTree($list);
        $this -> assign('list' , $list);
        return $this -> fetch('adminUser/info');
    }

    //提交操作
    public function submit(){
        if(request() -> isAjax()){
            $data = input('post.');
            $Validate = validate('AdminUser');
            //验证字段
            if(!$Validate -> check($data)){
                $return = [
                    'status'    => false,
                    'msg'       => $Validate -> getError(),
                    'token' => request()->token()
                ];
                return $return;
            }
            $level = '';
            foreach ($data['module_id'] as $sv){
                if(!empty($sv)){
                    $level .= implode(',',$sv).',';
                }
            }
            $data['level'] = substr($level, 0, -1);
            $data['admin_pass'] = md5X($data['admin_pass'],$data['admin_name']);
            //判断是修改还是添加
            if(empty($data['id'])){
                //添加操作
                $name = model('AdminUser') -> findAdminUser('*',['admin_name' => $data['admin_name']]);
                if(empty($name)){
                    $data['status'] = true;
                    $src = model('AdminUser') -> save($data);
                }else{
                    $return = [
                        'status'    => false,
                        'msg'       => '用户已存在！',
                        'token' => request()->token()
                    ];
                    return $return;
                }

            }else{
                //修改操作
                $src = model('AdminUser') -> isUpdate()->save($data);
                //判断是否修改的在线用户
                if($this -> AdminUser['id'] == $data['id']){
                    //替换缓存
                    $info = model('AdminUser') -> findAdminUser('*',['id' => $data['id']]);
                    session(config('AdminConfig.User'),$info);
                }
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
                    'type' => (!empty($data['id'])?2:1),
                    'uid' => $this -> AdminUser['id'],
                    'content' => '用户'.$this -> AdminUser['admin_name'].(!empty($data['id'])?'修改':'添加').'管理员：'.$data['admin_name'],
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

    //开启、关闭操作
    public function shut(){
        if(request() -> isAjax()){
            $data = input('post.');
            $Validate = validate('AdminUser');
            //验证字段
            if(!$Validate -> scene('shut')-> check($data)){
                $return = [
                    'status'    => false,
                    'msg'       => $Validate -> getError(),
                    'token' => request()->token()
                ];
                return $return;
            }
            if(is_array($data['id'])){
                $allData = [];
                foreach ($data['id'] as $sv){
                    $allData[] = [
                        'id' =>   $sv,
                        'status' =>   $data['status'],
                    ];
                }
                $src = model('AdminUser') -> isUpdate()->saveAll($allData);
            }else{
                $src = model('AdminUser') -> isUpdate()->save($data);
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
            }
            //调用行为监听
            $logData = [
                'type' => 2,
                'uid' => $this -> AdminUser['id'],
                'content' => '用户'.$this -> AdminUser['admin_name'].(($data['status'] == 1)?'开启':'关闭').'管理员：'.$data['admin_name'],
            ];
            Hook::listen('admin_log',$logData);
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

<?php
//广告控制器
namespace admin\myweb\controller;

use think\Hook;

class Ad extends Baes
{

    public function __construct()
    {
        //继承父级构造函数
        parent::__construct();
        $this -> assign('viewname' , '广告');//视图层标记
        //调用权限方法
        $this -> allow();
    }

    //轮播图列表
    public function bannerList()
    {
        $list = model('Banner') ->getBanner('*',['search' => input('search')]);
        $this -> assign('list' , $list);
        //加载本页面数据
        $this -> assign('viewname' , '轮播图');//视图层标记
        return $this -> fetch('ad/bannerlist');
    }


    //轮播图详情
    public function bannerInfo(){
        //详情
        if($id = input('id')){
            $info = model('Banner') -> findBanner('*',['id' => $id]);
            $this -> assign('info' , $info);
        }
        //加载本页面数据
        $this -> assign('viewname' , '轮播图');//视图层标记
        return $this -> fetch('ad/bannerinfo');
    }

    //轮播图提交操作
    public function bannerSubmit(){
        if(request() -> isAjax()){
            $data = input('post.');
            $Validate = validate('Ad');
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
                $src = model('Banner') -> save($data);
            }else{
                //修改
                $src = model('Banner') -> isUpdate()->save($data);
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
                    'Additional'     => url('bannerList')
                ];
                //调用行为监听
                $logData = [
                    'type' => (!empty($data['id'])?2:1),
                    'content' => (!empty($data['id'])?'修改':'添加').'轮播图：'.$data['title'],
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

    //删除轮播
    public function bannerDel(){
        if(request() -> isAjax()){
            $data = input('post.');
            $Validate = validate('Ad');
            //验证字段
            if(!$Validate -> scene('del')-> check($data)){
                $return = [
                    'status'    => false,
                    'msg'       => $Validate -> getError(),
                    'token' => request()->token()
                ];
                return $return;
            }
            //先查看是否有子级
            $child = model('Banner')-> where(['id' => $data['id']]) -> find();
            //先删除图片
            $headpic = ROOT_PATH.config('upload_path').$child['img_path'];
            if(!is_dir($headpic)) {
                @unlink($headpic);
            }
            $src  = model('Banner') -> where(['id' => $data['id']])-> delete();
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
                    'Additional'     => url('bannerList')
                ];
                //调用行为监听
                $logData = [
                    'type' => 2,
                    'content' =>'关闭模块：'.$child['title'],
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

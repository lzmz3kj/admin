<?php
//博客控制器
namespace admin\myweb\controller;

use think\Hook;

class Blogs extends Baes
{

    public function __construct()
    {
        //继承父级构造函数
        parent::__construct();
        $this -> assign('viewname' , '博客');//视图层标记
        //调用权限方法
        $this -> allow();
    }

    //博客列表
    public function blogsList()
    {
        $list = model('Blogs') ->getBlogs('*',['search' => input('search')]);
        $this -> assign('list' , $list);
        //加载本页面数据
        $this -> assign('viewname' , '轮播图');//视图层标记
        return $this -> fetch('blogs/list');
    }


    //博客详情
    public function blogsInfo(){
        //详情
        if($id = input('id')){
            $info = model('Blogs') -> findBlogs('*',['id' => $id]);
            $this -> assign('info' , $info);
        }
        //加载本页面数据
        $this -> assign('viewname' , '轮播图');//视图层标记
        return $this -> fetch('blogs/info');
    }

    //博客分类
    public function blogsCategory(){
        $list = model('BlogsCategory') -> getBlogsCategory('*',['search' => input('search')]);
        $list = makeTree($list);
        $this -> assign('list' , $list);
        return $this -> fetch('blogs/category_list');
    }

    //博客分类详情
    public function categoryInfo(){
        //详情
        if($id = input('id')){
            $info = model('BlogsCategory') -> findBlogsCategory('*',['id' => $id]);
            $this -> assign('info' , $info);
        }
        return $this -> fetch('blogs/category_info');
    }

    //导航啦提交操作
    public function BlogsSubmit(){
        if(request() -> isAjax()){
            $data = input('post.');
            $Validate = validate('Blogs');
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
                $src = model('Blogs') -> save($data);
            }else{
                //修改
                $src = model('Blogs') -> isUpdate()->save($data);
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
                    'Blogsditional'     => url('BlogsList')
                ];
                //调用行为监听
                $logData = [
                    'type' => (!empty($data['id'])?2:1),
                    'content' => (!empty($data['id'])?'修改':'添加').'轮播图：'.$data['title'],
                ];
                Hook::listen('Blogsmin_log',$logData);
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
    public function BlogsDel(){
        if(request() -> isAjax()){
            $data = input('post.');
            $Validate = validate('Blogs');
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
            $child = model('Blogs')-> where(['id' => $data['id']]) -> find();
            //先删除图片
            $heBlogspic = ROOT_PATH.config('uploBlogs_path').$child['img_path'];
            if(!is_dir($heBlogspic)) {
                @unlink($heBlogspic);
            }
            $src  = model('Blogs') -> where(['id' => $data['id']])-> delete();
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
                    'Blogsditional'     => url('BlogsList')
                ];
                //调用行为监听
                $logData = [
                    'type' => 2,
                    'content' =>'关闭模块：'.$child['title'],
                ];
                Hook::listen('Blogsmin_log',$logData);
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

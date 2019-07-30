<?php
//广告资源控制器
namespace app\ad\controller;

use think\Hook;

class AdStuff extends Baes
{
    protected $base;

    public function __construct()
    {
        //继承父级构造函数
        parent::__construct();

        //加载本控制器基本参数
        $this -> base = input('base')?input('base'):'Discuzx';
        $this -> assign('country' ,$this -> country );
        $this -> assign('countryid' ,$this -> countryid );
        $this -> assign('base' , $this -> base);
        $this -> assign('viewname' , config('baseMag')[$this -> base].'文件');//视图层标记
    }

    //列表
    public function stuffList()
    {
        //论坛数据
        if($this -> base == 'Discuzx'){
            $condition['page']['query']['search'] = $condition['name'] = input('search');
            $list = model('ForumCffiles') -> gapeForumCffiles('*',$condition);
            $this -> assign('list' , $list);
        }

        //分类广告
        if($this -> base == 'ClassAd'){
            $condition['page']['query']['search'] = $condition['name'] = input('search');
            $list = model('HrjAdsetimg') -> gapeHrjAdsetimg('*',$condition);
            $this -> assign('list' , $list);
        }

        return $this -> fetch('adStuff/list');
    }

    //详情
    public function info(){
        //加载论坛基本参数
        if($this -> base == 'Discuzx'){
            //判断是否是编辑
            if($id = input('id')){
                $info = model('ForumCffiles') -> findForumCffiles('*',['id' => $id]);
                $this -> assign('info' , $info);
            }
        }

        //加载分类广告基本参数
        if($this -> base == 'ClassAd'){
            //判断是否是编辑
            if($id = input('id')){
                $info = model('HrjAdsetimg') -> findHrjAdsetimg('*',['id' => $id]);
                $this -> assign('info' , $info);
            }
        }

        return $this -> fetch('adStuff/info');
    }

    //提交操作
    public function submit(){
        if(request() -> isAjax()){
            $data = input('post.');
            $Validate = validate('AdStuff');
            //验证字段
            if(!$Validate ->scene('submit') -> check($data)){
                $return = [
                    'status'    => false,
                    'msg'       => $Validate -> getError(),
                    'token' => request()->token()
                ];
                return $return;
            }
            //论坛
            if($data['base'] == 'Discuzx'){
                //组合数据
                $isImg = isImage($data['filepath']);
                $data['filetype'] = !empty($isImg)?0:1;
                $data['country'] = config('cityid')[$data['country']];
                $data['dateline'] = time();
                //判断是修改还是添加
                if(empty($data['id'])){
                    //添加
                    $src = model('ForumCffiles') -> save($data);
                }else{
                    //修改
                    $src = model('ForumCffiles') -> isUpdate()->save($data);
                }
            }

            //分类广告
            if($data['base'] == 'ClassAd'){
                //组合数据
                $isImg = isImage($data['img_url']);
                $data['type'] = !empty($isImg)?0:1;
                $data['remark'] = $data['filedesc'];
                //判断是修改还是添加
                if(empty($data['id'])){
                    //添加
                    $src = model('HrjAdsetimg') -> save($data);
                }else{
                    //修改
                    $src = model('HrjAdsetimg') -> isUpdate()->save($data);
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
                    'Additional'     => url('stuffList',['base' => $data['base'] ])
                ];

                //调用行为监听
                $logData = [
                    'type' => (!empty($data['id'])?2:1),
                    'uid' => $this -> AdminUser['id'],
                    'content' => '用户'.$this -> AdminUser['admin_name'].(!empty($data['id'])?'修改':'添加').config('baseMag')[$this -> base].'图片资源：',
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

    //删除
    public function del(){
        if(request() -> isAjax()){
            $data = input('post.');
            $Validate = validate('AdStuff');
            //验证字段
            if(!$Validate -> scene('del')-> check($data)){
                $return = [
                    'status'    => false,
                    'msg'       => $Validate -> getError(),
                    'token' => request()->token()
                ];
                return $return;
            }
            //判断是否是论坛
            if($data['base'] == 'Discuzx'){
                if(is_array($data['id'])){
                    foreach ($data['id'] as $sk => $sv){
                        $path = model('ForumCffiles') -> findForumCffiles('filepath',['id' => $sv]);
                        @unlink('./'.$path['filepath']['raw']);
                    }
                }else{
                    $path = model('ForumCffiles') -> findForumCffiles('filepath',['id' => $data['id']]);
                    @unlink('./'.$path['filepath']['raw']);
                }
                $src = model('ForumCffiles') -> destroy($data['id']);
            }
            //判断是否是分类广告
            if($data['base'] == 'ClassAd'){
                if(is_array($data['id'])){
                    foreach ($data['id'] as $sk => $sv){
                        $path = model('HrjAdsetimg') -> findHrjAdsetimg('img_url',['id' => $sv]);
                        @unlink('./'.$path['img_url']['raw']);
                    }
                }else{
                    $path = model('HrjAdsetimg') -> findHrjAdsetimg('img_url',['id' => $data['id']]);
                    @unlink('./'.$path['img_url']['raw']);
                }
                $src = model('HrjAdsetimg') -> destroy($data['id']);
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
                    'Additional'     => url('stuffList',['base' => $data['base'] ])
                ];
                //调用行为监听
                $logData = [
                    'type' => 3,
                    'uid' => $this -> AdminUser['id'],
                    'content' => '用户'.$this -> AdminUser['admin_name'].'删除'.config('baseMag')[$this -> base].'广告：',
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

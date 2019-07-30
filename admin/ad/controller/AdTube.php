<?php
//广告管理控制器
namespace app\ad\controller;

use think\Hook;

class AdTube extends Baes
{
    protected $base;
    protected $source;

    public function __construct()
    {
        //继承父级构造函数
        parent::__construct();

        //加载本控制器基本参数
        $this -> base = input('base')?input('base'):'Discuzx';
        $this -> source = input('source')?input('source'):1;
        $this -> assign('country' ,$this -> country );
        $this -> assign('countryid' ,$this -> countryid );
        $this -> assign('source' , $this -> source );
        $this -> assign('base' , $this -> base);
        $this -> assign('viewname' , config('baseMag')[$this -> base]);//视图层标记
    }

    //PC列表
    public function adPcList()
    {
        //论坛数据
        if($this -> base == 'Discuzx'){
            $condition['page']['query']['search'] = $condition['name'] = input('search');
            $condition['page']['query']['source'] = input('source');
            $condition['page']['query']['fids'] =  $condition['fids'] = input('fids');
            $condition['where'] = ['source' => $this -> source , 'country' => config('cityid')[$this -> country]];
            $list = model('ForumCfadset') -> gapeForumCfadset('*',$condition);
            if($this -> source == 3){
                $ForumList = model('ForumForum') -> gapeForumForum(['a.fid','a.name'], ['b.mycountry' => $this -> country]);
                $this -> assign('forum_list' , $ForumList);
            }
            $this -> updateTime($list -> ToArray()['data']);
            $this -> assign('list' , $list);
        }
        //分类广告数据
        if($this -> base == 'ClassAd'){
            $condition['page']['query']['search'] = $condition['name'] = input('search');
            $condition['page']['query']['source'] = input('source');
            $condition['page']['query']['base'] = 'ClassAd';
            $condition['page']['query']['fids'] =  $condition['fids'] = input('fids');
            $condition['where'] = ['b.page' => $this -> source , 'a.cityid' => $this -> countryid];
            $list = model('HrjAdset') -> gapeHrjAdset('*',$condition);
            if($this -> source == 2){
                //获取类别
                $categoryList = model('Category') -> joinCategory(['a.catid','a.catname'],['b.cityid' => $this -> countryid]);
                $this -> assign('category' , $categoryList);
            }
            $this -> assign('list' , $list);
        }

        $this -> assign('sourceList' , config('sourceList.pc')[$this -> base]);
        //默认加载数据
        return $this -> fetch('adTube/pcList');
    }



    //APP广告列表
    public function adAppList()
    {
        //论坛数据
        if($this -> base == 'Discuzx'){
            $condition['page']['query']['search'] = $condition['title'] = input('search');
            $condition['page']['query']['source'] = input('source');
            $condition['where'] = ['source' => $this -> source ];
            $list = model('AppConfig') -> gapeAppConfig('*',$condition);
        }

        //分类广告数据
        if($this -> base == 'ClassAd'){
            $condition['page']['query']['search'] = $condition['title'] = input('search');
            $condition['page']['query']['source'] = input('source');
            $condition['where'] = ['source' => $this -> source ];
            $list = model('HrjAppadset') -> gapeHrjAppadset('*',$condition);
        }

        //默认加载数据
        $this -> assign('list' , $list);
        $this -> assign('sourceList' , config('sourceList.app')[$this -> base]);
        return $this -> fetch('adTube/appList');
    }

    //WAP列表
    public function adWapList()
    {
        //论坛数据
        if($this -> base == 'Discuzx'){
            $source = 11;
            $condition['page']['query']['search'] = $condition['name'] = input('search');
            $condition['page']['query']['source'] = $source;
            $condition['page']['query']['fids'] =  $condition['fids'] = input('fids');
            $condition['where'] = ['source' => $source , 'country' => config('cityid')[$this -> country]];
            $list = model('ForumCfadset') -> gapeForumCfadset('*',$condition);
            $this -> updateTime($list -> ToArray()['data']);
            $this -> assign('list' , $list);
        }

        $this -> assign('viewname' , '《WAP广告》');//视图层标记
        return $this -> fetch('adTube/wapList');
    }


    //PC详情
    public function insertPc(){

        //加载论坛基本参数
        if($this -> base == 'Discuzx'){
            if($this -> source == 3 || $this -> source == 4){
                $condition = ['b.mycountry' => $this -> country];
                $ForumList = model('ForumForum') -> gapeForumForum(['a.fid','a.name'],$condition);
                $this -> assign('forum_list' , $ForumList);
                $this -> assign('ctype' , config('ctype'));
            }
            //判断是否是编辑
            if($id = input('id')){
                $info = model('ForumCfadset') -> findForumCfadset('*',['id' => $id]);
                $this -> assign('info' , $info);
            }
            $this -> assign('viewname' , config('baseMag')[$this -> base].config('sourceList.pc')[$this -> base][$this -> source].'广告');

        }
        //加载分类广告基本参数
        if($this -> base == 'ClassAd'){
            //判断是否是编辑
            if($id = input('id')){
                $info = model('HrjAdset') -> findHrjAdset('*',['id' => $id]);
                $this -> assign('info' , $info);
            }
            //获取位置列表
            $positionList = model('HrjAdsetPosition') -> getHrjAdsetPosition(['positionid','desc'],['page' => $this -> source]);
            $this -> assign('position' , $positionList);
            //获取类别
            $categoryList = model('Category') -> joinCategory(['a.catid','a.catname'],['b.cityid' => $this -> countryid]);
            $this -> assign('category' , $categoryList);
            $this -> assign('viewname' , config('baseMag')[$this -> base].config('sourceList.pc')[$this -> base][$this -> source].'广告');

        }
        //加载本页面数据
        $this -> assign('action' , 'adpclist');
        return $this -> fetch('adTube/infoPc');
    }

    //APP详情
    public function insertApp(){

        //加载论坛基本参数
        if($this -> base == 'Discuzx'){

            //判断是否是编辑
            if($id = input('id')){
                $info = model('AppConfig') -> findAppConfig('*',['id' => $id]);
                $this -> assign('info' , $info);
            }

            $this -> assign('countryType' , config('countryType'));
            $this -> assign('viewname' , config('baseMag')[$this -> base].config('sourceList.app')[$this -> base][$this -> source].'广告');

        }


        //加载分类广告基本参数
        if($this -> base == 'ClassAd'){
            //判断是否是编辑
            if($id = input('id')){
                $info = model('HrjAppadset') -> findHrjAppadset('*',['id' => $id]);
                $this -> assign('info' , $info);
            }

            $this -> assign('viewname' , config('baseMag')[$this -> base].config('sourceMag1')[$this -> source].'广告');

        }
        //加载本页面数据
        $this -> assign('action' , 'adapplist');
        return $this -> fetch('adTube/infoApp');
    }

    //WAP详情
    public function insertWap(){
        //加载论坛基本参数
        if($this -> base == 'Discuzx'){
            //判断是否是编辑
            if($id = input('id')){
                $info = model('ForumCfadset') -> findForumCfadset('*',['id' => $id]);
                $this -> assign('info' , $info);
            }
            $this -> assign('viewname' , '《WAP广告》');
        }
        //加载本页面数据
        $this -> assign('action' , 'adwaplist');
        return $this -> fetch('adTube/infoWap');
    }

    //PC提交操作
    public function submitPc(){
        if(request() -> isAjax()){
            $data = input('post.');
            $Validate = validate('AdTube');
            //判断添加表
            if($data['base'] == 'Discuzx'){
                //验证字段
                if(!$Validate ->scene('discuzx') -> check($data)){
                    $return = [
                        'status'    => false,
                        'msg'       => $Validate -> getError(),
                        'token' => request()->token()
                    ];
                    return $return;
                }
                //组合数据
                $data['endtime'] = $data['endtime'].' 23:59:59';
                $data['serialno'] = !empty($data['serialno'])? $data['serialno']:0;
                $data['fids'] = isset($data['fids'])? implode($data['fids'],","):'';
                $data['ctype'] = isset($data['ctype'])? $data['ctype']:0;
                $data['country'] = config('cityid')[$data['country']];
                $data['dateline'] = date('Y-m-d H:i:s',time());
                //判断是修改还是添加
                if(empty($data['id'])){
                    //添加
                    $src = model('ForumCfadset') -> save($data);
                }else{
                    //修改
                    $src = model('ForumCfadset') -> isUpdate()->save($data);
                }
            }

            if($data['base'] == 'ClassAd'){
                //验证字段
                if(!$Validate ->scene('classAd') -> check($data)){
                    $return = [
                        'status'    => false,
                        'msg'       => $Validate -> getError(),
                        'token' => request()->token()
                    ];
                    return $return;
                }
                //组合数据
                $data['begintime'] = strtotime($data['begintime'].' 00:00:01');
                $data['endtime'] = strtotime($data['endtime'].' 23:59:59');
                $data['margin'] = $data['margin-top'].'-'.$data['margin-right'].'-'.$data['margin-bottom'].'-'.$data['margin-left'];
                $data['fids'] = isset($data['fids'])? implode($data['fids'],","):'';
                $data['displayorder'] = isset($data['displayorder'])? $data['displayorder']:0;
                $data['ctype'] = isset($data['ctype'])? $data['ctype']:0;
                //判断是修改还是添加
                if(empty($data['id'])){
                    //添加
                    $src = model('HrjAdset') -> save($data);
                }else{
                    //修改
                    $src = model('HrjAdset') -> isUpdate()->save($data);
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
                    'Additional'     => url('adPcList',['base' => $data['base'] ,'source' => $data['source']])
                ];

                //调用行为监听
                $logData = [
                    'type' => (!empty($data['id'])?2:1),
                    'uid' => $this -> AdminUser['id'],
                    'content' => '用户'.$this -> AdminUser['admin_name'].(!empty($data['id'])?'修改':'添加').config('baseMag')[$this -> base].'PC广告：'.$data['name'],
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

    //APP提交操作
    public function submitApp(){
        if(request() -> isAjax()){
            $data = input('post.');
            $Validate = validate('AdTube');
            //判断添加表
            if($data['base'] == 'Discuzx'){
                //验证字段
                if(!$Validate ->scene('discuzxApp') -> check($data)){
                    $return = [
                        'status'    => false,
                        'msg'       => $Validate -> getError(),
                        'token' => request()->token()
                    ];
                    return $return;
                }
                //组合数据
                $data['fids'] = isset($data['fids'])? $data['fids']:'';
                $data['tid'] = !empty($data['tid'])? $data['tid']:0;
                $data['first'] = !empty($data['first'])? $data['first']:0;
                $data['country'] = !empty($data['country'])? implode($data['country'],","):$this -> country;
                $data['endtime'] = !empty($data['endtime'])? $data['endtime'].' 23:59:59':'0';
                $data['endtime'] = strtotime($data['endtime']);
                $data['pubtime'] = strtotime(!empty($data['pubtime'])? $data['pubtime']:'0');
                $data['dateline'] = time();
                //判断是修改还是添加
                if(empty($data['id'])){
                    //添加
                    $src = model('AppConfig') -> save($data);
                }else{
                    //修改
                    $src = model('AppConfig') -> isUpdate()->save($data);
                }
            }

            if($data['base'] == 'ClassAd'){
                //验证字段
                if(!$Validate ->scene('classAdApp') -> check($data)){
                    $return = [
                        'status'    => false,
                        'msg'       => $Validate -> getError(),
                        'token' => request()->token()
                    ];
                    return $return;
                }
                //组合数据
                $data['begintime'] = strtotime($data['begintime'].' 00:00:01');
                $data['endtime'] = strtotime($data['endtime'].' 23:59:59');
                $data['fids'] = isset($data['fids'])? $data['fids']:'0';
                //判断是修改还是添加
                if(empty($data['id'])){
                    //添加
                    $src = model('HrjAppadset') -> save($data);
                }else{
                    //修改
                    $src = model('HrjAppadset') -> isUpdate()->save($data);
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
                    'Additional'     => url('adAppList',['base' => $data['base'] ,'source' => $data['source']])
                ];

                //调用行为监听
                $logData = [
                    'type' => (!empty($data['id'])?2:1),
                    'uid' => $this -> AdminUser['id'],
                    'content' => '用户'.$this -> AdminUser['admin_name'].(!empty($data['id'])?'修改':'添加').config('baseMag')[$this -> base].'APP广告：'.$data['title'],
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

    //WAP提交操作
    public function submitWap(){
        if(request() -> isAjax()){
            $data = input('post.');
            $Validate = validate('AdTube');
            //判断添加表
            if($data['base'] == 'Discuzx'){
                //验证字段
                if(!$Validate ->scene('discuzx') -> check($data)){
                    $return = [
                        'status'    => false,
                        'msg'       => $Validate -> getError(),
                        'token' => request()->token()
                    ];
                    return $return;
                }
                //组合数据
                $data['source'] = 11;
                $data['endtime'] = $data['endtime'].' 23:59:59';
                $data['serialno'] = !empty($data['serialno'])? $data['serialno']:0;
                $data['ctype'] = !empty($data['ctype'])? $data['ctype']:0;
                $data['country'] = config('cityid')[$data['country']];
                $data['dateline'] = date('Y-m-d H:i:s',time());
                //判断是修改还是添加
                if(empty($data['id'])){
                    //添加
                    $src = model('ForumCfadset') -> save($data);
                }else{
                    //修改
                    $src = model('ForumCfadset') -> isUpdate()->save($data);
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
                    'Additional'     => url('adWapList',['base' => $data['base'] ,'source' => $data['source']])
                ];

                //调用行为监听
                $logData = [
                    'type' => (!empty($data['id'])?2:1),
                    'uid' => $this -> AdminUser['id'],
                    'content' => '用户'.$this -> AdminUser['admin_name'].(!empty($data['id'])?'修改':'添加').'Wap广告：'.$data['name'],
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
            $Validate = validate('AdTube');
            //验证字段
            if(!$Validate -> scene('del')-> check($data)){
                $return = [
                    'status'    => false,
                    'msg'       => $Validate -> getError(),
                    'token' => request()->token()
                ];
                return $return;
            }
            //判断是否是分类广告
            if($data['base'] == 'ClassAd'){
                $src = model('HrjAdset') -> where(['id' => $data['id']]) -> delete();
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
                    'Additional'     => url('adPcList',['base' => $data['base'] ,'source' => $data['source']])
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

    //修改论坛过期标志
    public function updateTime($data){
        foreach ($data as $sk => $sv){
            if($sv['overflag'] == 0){
                $tmpdata['id'] = $sv['id'];
                if(time() > strtotime($sv['endtime'])){
                    $tmpdata['overflag'] = 1;
                    model('forum_cfadset') -> isUpdate()->save($tmpdata);
                }else {
                    $tmpdata['overflag'] = 0;
                    model('forum_cfadset') -> isUpdate()->save($tmpdata);
                }
            }
        }

    }

}

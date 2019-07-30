<?php
namespace admin\myweb\validate;

use think\Validate;

class Nav extends Validate
{
    //验证规则
    protected $rule = [
        'nav_name'     =>  'require',
        'nav_mark'     =>  'require',
        'nav_sort'     =>  'number',
        '__token__'    =>  'require|token',
        'id'           =>  'number',

    ];

    //返回提示
    protected $message = [
        'nav_name.require'     =>  '请输入导航名称！',
        'nav_mark.require'     =>  '请输入导航标识！',
        'nav_sort.number'      =>  '排序格式错误！',
        '__token__.require'    =>  'Token不存在！',
        '__token__.token'      =>  'Token错误！',
        'id.number'            =>  '用户ID参数错误！',
    ];

    //验证场景
    protected $scene = [
        'shut' => ['id','__token__']
    ];

}
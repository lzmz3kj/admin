<?php
namespace admin\myweb\validate;

use think\Validate;

class Ad extends Validate
{
    //验证规则
    protected $rule = [
        'title'        =>  'require',
        'url'          =>  'require',
        'sort'         =>  'number',
        '__token__'    =>  'require|token',
        'id'           =>  'number',

    ];

    //返回提示
    protected $message = [
        'title.require'        =>  '请输入轮播图标题！',
        'url.require'          =>  '请输入跳转路径！',
        'sort.number'          =>  '排序格式错误！',
        '__token__.require'    =>  'Token不存在！',
        '__token__.token'      =>  'Token错误！',
        'id.number'            =>  '用户ID参数错误！',
    ];

    //验证场景
    protected $scene = [
        'del' => ['id','__token__']
    ];

}
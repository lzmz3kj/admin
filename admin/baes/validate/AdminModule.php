<?php
namespace admin\baes\validate;

use think\Validate;

class AdminModule extends Validate
{
    //验证规则
    protected $rule = [
        'name'    =>  'require|max:25',
        'pid'     =>  'number',
        'sort'    =>  'number',
        'status'  =>  'number',
        '__token__'   =>  'require|token',
        'id'      =>  'number',

    ];

    //返回提示
    protected $message = [
        'name.require'   =>  '模块名不能为空！',
        'name.max'       =>  '模块名不能大于25字节！',
        'pid.number'     =>  '所属模块参数错误！',
        'sort.number'    =>  '排序必须为数字！',
        'status.number'  =>  '状态参数错误！',
        '__token__.require'  =>  'Token不存在！',
        '__token__.token'    =>  'Token错误！',
        'id.number'      =>  '用户ID参数错误！',
    ];

    //验证场景
    protected $scene = [
        'shut' => ['id','status','__token__']
    ];

}
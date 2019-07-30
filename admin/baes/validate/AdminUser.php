<?php
namespace admin\baes\validate;

use think\Validate;

class AdminUser extends Validate
{
    //验证规则
    protected $rule = [
        'admin_name'   =>  'require|max:25',
        'admin_pass'   =>  'require',
        'module_id'    =>  'require',
        '__token__'    =>  'require|token',
        'id'           =>  'number',
        'limit'        =>  'number',

    ];

    //返回提示
    protected $message = [
        'admin_name.require'   =>  '用户名不能为空！',
        'admin_name.max'       =>  '用户名不能大于25字节！',
        'admin_pass.require'   =>  '登录密码不能为空！',
        'module_id.require'    =>  '请选择开启模块！',
        '__token__.require'    =>  'Token不存在！',
        '__token__.token'      =>  'Token错误！',
        'id.number'            =>  '用户ID参数错误！',
        'limit.number'         =>  '非法参数！',
    ];

    //验证场景
    protected $scene = [
        'shut' => ['status','__token__']
    ];

}
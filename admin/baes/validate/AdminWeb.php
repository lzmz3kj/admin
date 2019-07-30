<?php
namespace admin\baes\validate;

use think\Validate;

class AdminWeb extends Validate
{
    //验证规则
    protected $rule = [
        'web_name'    =>  'require',
        'type'        =>  'require',
        'hostname'    =>  'require',
        'database'    =>  'require',
        'username'    =>  'require',
        'password'    =>  'require',
        'hostport'    =>  'require',
        'prefix'      =>  'require',
        'status'      =>  'number',
        '__token__'   =>  'require|token',
        'id'          =>  'number',

    ];

    //返回提示
    protected $message = [
        'web_name.require'  =>  '请输入子站名称！',
        'type.require'      =>  '请选择数据库类型！',
        'hostname.require'  =>  '请输入数据库地址！',
        'database.require'  =>  '请输入数据库名！',
        'username.require'  =>  '请输入用户名！',
        'password.require'  =>  '请输入密码！',
        'hostport.require'  =>  '请输入端口！',
        'prefix.require'    =>  '请输入表前缀！',
        'status.number'     =>  '状态参数错误！',
        '__token__.require' =>  'Token不存在！',
        '__token__.token'   =>  'Token错误！',
        'id.number'         =>  '用户ID参数错误！',
    ];

    //验证场景
    protected $scene = [

    ];

}
<?php
namespace admin\baes\validate;

use think\Validate;

class AdminLogin extends Validate
{
    //验证规则
    protected $rule = [
        'admin_name' => 'require',
        'admin_pass' => 'require',
        'verify' => 'require|captcha',
        'exit'   =>  'require',
        '__token__'    =>  'require|token',
    ];

    //返回提示
    protected $message = [
        'admin_name.require'   =>  '请输入账号！',
        'admin_pass.require'   =>  '请输入密码！',
        'verify.require'   =>  '请输入验证码！',
        'verify.captcha'   =>  '验证码错误！',
        'exit.require'   =>  '退出标识错误！',
        '__token__.require'    =>  'Token不存在',
        '__token__.token'      =>  'Token错误',
    ];

    //验证场景
    protected $scene = [
        'ouit' => ['exit','__token__'],
        'login' => ['admin_name','admin_pass','verify','__token__'],
    ];

}
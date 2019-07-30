<?php
namespace app\ad\validate;

use think\Validate;

class AdStuff extends Validate
{
    //验证规则
    protected $rule = [
        'filedesc'   =>  'require',
        '__token__'  =>  'require|token',
        'id'         =>  'number',

    ];

    //返回提示
    protected $message = [
        'filedesc.require'   =>  '请输入文件描述！',
        '__token__.require'    =>  'Token不存在！',
        '__token__.token'      =>  'Token错误！',
        'id.number'            =>  '用户ID参数错误！',
    ];

    //验证场景
    protected $scene = [
        'submit' => ['filedesc','__token__','id'],
        'del' => ['__token__','base']
    ];

}
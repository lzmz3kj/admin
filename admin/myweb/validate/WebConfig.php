<?php
namespace admin\myweb\validate;

use think\Validate;

class WebConfig extends Validate
{
    //验证规则
    protected $rule = [
        'web_title'        =>  'require',
        'seo_keywords'     =>  'require',
        'seo_description'  =>  'require',
        'web_contact'      =>  'require',
        'web_address'      =>  'require',
        'web_copyright'    =>  'require',
        'web_record'       =>  'require',
        'web_logo'         =>  'require',
        '__token__'        =>  'require|token',
        'id'               =>  'number',

    ];

    //返回提示
    protected $message = [
        'web_title.require'        =>  '请输入站点名称！',
        'seo_keywords.require'     =>  '请输入Keywords！',
        'seo_description.require'  =>  '请输入description！',
        'web_contact.require'      =>  '请输入联系方式！',
        'web_address.require'      =>  '请输入联系地址！',
        'web_copyright.require'    =>  '请输入版权号！',
        'web_record.require'       =>  '请输入备案号！',
        'web_logo.require'         =>  '请选择图片！',
        '__token__.require'        =>  'Token不存在！',
        '__token__.token'          =>  'Token错误！',
        'id.number'                =>  '用户ID参数错误！',
    ];

    //验证场景
    protected $scene = [

    ];

}
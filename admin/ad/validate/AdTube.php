<?php
namespace app\ad\validate;

use think\Validate;

class AdTube extends Validate
{
    //验证规则
    protected $rule = [
        'name'       =>  'require',
        'adcode'     =>  'require',
        'begintime'  =>  'require',
        'endtime'    =>  'require',
        'serialno'   =>  'number',
        'income'     =>  'number',
        'sort'       =>  'number',
        'displayorder' =>  'number',
        '__token__'  =>  'require|token',
        'id'         =>  'number',
        'base'       => 'require',
        'title'      => 'require',
        'tid'        => 'require',
        'type'        => 'require',

    ];

    //返回提示
    protected $message = [
        'name.require'   =>  '请输入广告标题！',
        'adcode.require'   =>  '请输入广告代码！',
        'serialno.number'    =>  '排序格式不正确！',
        'sort.number'      =>  '排序格式不正确！',
        'begintime.require'    =>  '请设置开始时间！',
        'endtime.require'    =>  '请设置结束时间！',
        'income.number'    =>  '收入格式不正确！',
        'displayorder.number'    =>  '排序格式不正确！',
        '__token__.require'    =>  'Token不存在！',
        '__token__.token'      =>  'Token错误！',
        'id.number'            =>  '用户ID参数错误！',
        'base.require'         =>  '数据类型错误！',
        'title.require'         =>  '请输入广告标题！',
        'tid.require'         =>  '请输入帖子ID！',
        'type.require'        =>  '请选择广告类型！',
    ];

    //验证场景
    protected $scene = [
        'discuzx' => ['name','adcode','serialno','begintime','endtime','income','id','__token__'],
        'classAd' => ['name','adcode','displayorder','begintime','endtime','id','__token__'],
        'discuzxApp' => ['title','tid','serialno','endtime','id','type','__token__'],
        'classAdApp' => ['title','tid','sort','id','type','__token__'],
        'del' => ['id','__token__','base']
    ];

}
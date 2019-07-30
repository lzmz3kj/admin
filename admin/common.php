<?php
use think\Url;
// 应用公共文件
function isurl($url = '', $vars = '', $suffix = true, $domain = false){

    return Url::build($url, $vars, $suffix, $domain);
}


//多级导航树状图
function makeTree($arr,$pid = 0){
    $rse = [];
    $items = [];
    foreach ($arr as $v){
        $v['has_son'] = &$items[$v['id']];
        if($v['pid'] != 0){
            $items[$v['pid']][] = $v;
        }else{
            $rse[] = $v;
        }
    }
    return $rse;
}

/**
 * 获取客户端IP地址
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
 * @return mixed
 */
function get_client_ip($type = 0,$adv=false) {
    $type       =  $type ? 1 : 0;
    static $ip  =   NULL;
    if ($ip !== NULL) return $ip[$type];
    if($adv){
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos    =   array_search('unknown',$arr);
            if(false !== $pos) unset($arr[$pos]);
            $ip     =   trim($arr[0]);
        }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip     =   $_SERVER['HTTP_CLIENT_IP'];
        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip     =   $_SERVER['REMOTE_ADDR'];
        }
    }elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip     =   $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u",ip2long($ip));
    $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}
//判断文件是否为图片
function isImage($filename)
{
    $types = '.gif|.jpeg|.png|.bmp|.jpg'; //定义检查的图片类型
    if(file_exists('./'.$filename))
    {
        $info = getimagesize($filename);
        $ext = image_type_to_extension($info['2']);
        return stripos($types,$ext);
    } else {
        return false;
    }
}

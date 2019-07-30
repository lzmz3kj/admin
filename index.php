<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]
$url="http://".$_SERVER ['HTTP_HOST'].$_SERVER['PHP_SELF'];
preg_match("#http://(.*?)\.#i",$url,$match);
if($match[1] == 'admin'){
    // 定义应用目录
    define('APP_PATH', __DIR__ . '/admin/');
    // 定义应用命名空间
    define('APP_NAMESPACE','admin');
}else{
    // 定义应用目录
    define('APP_PATH', __DIR__ . '/index/');
    // 定义应用命名空间
    define('APP_NAMESPACE','index');
}
// 加载框架引导文件
require __DIR__ . '/thinkphp/start.php';


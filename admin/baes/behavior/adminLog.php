<?php
namespace admin\baes\behavior;

use admin\baes\model\AdminUserLog;
use think\Controller;

class adminLog  extends Controller
{
    public function run(&$params)
    {
        // 行为逻辑
        $UserInfo = session(config('AdminConfig.User'));
        $content ='用户'.$UserInfo['admin_name'].' '.$params['content'];
        $data = [
            'uid'      => $UserInfo['id'],
            'content'  => $content,
            'route'    => request()->module().'-'.request()->controller().'-'.request()->action(),
            'type'     => empty($params['type'])?5:$params['type'],
            'add_time' => time(),
            'add_ip'   => get_client_ip()
        ];
        $model = new AdminUserLog();
        $model -> addUserLog($data);
    }
}
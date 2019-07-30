<?php
namespace admin\baes\behavior;

use admin\baes\model\AdminUserLog;

class adminLog
{
    public function run(&$params)
    {
        // 行为逻辑
        $content = !empty($params['content'])?$params['content']:'用户于 '.date('Y-m-d H:i:s',time()).' 登录后台！';
        $data = [
            'uid' => !empty($params['uid'])?$params['uid']:$params['id'],
            'content' => $content,
            'type' => empty($params['type'])?4:$params['type'],
            'add_time' => time(),
            'add_ip' => get_client_ip()
        ];
        $model = new AdminUserLog();
        $model -> addUserLog($data);
    }
}
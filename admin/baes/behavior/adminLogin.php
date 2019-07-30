<?php
namespace admin\baes\behavior;

class adminLogin
{
    public function run(&$params)
    {
        // 行为逻辑
        $where = [
            'id' => $params['id']
        ];
        $data = [
            'last_login_time' => time(),
            'last_login_ip' => get_client_ip(),
        ];
        model('AdminUser') -> updateAdminUser($where,$data);
    }
}
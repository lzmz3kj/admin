<?php
namespace admin\myweb\controller;

use think\Request;
use admin\baes\controller\Baes as commmon;

class Baes extends commmon{


    public function __construct()
    {
        //继承父级构造函数
        parent::__construct();

        $this -> allow();
    }





}
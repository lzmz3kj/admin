<?php
namespace app\ad\controller;

use think\Request;
use app\admin\controller\Baes as commmon;

class Baes extends commmon{


    public function __construct()
    {
        //继承父级构造函数
        parent::__construct();

        $this -> allow();
    }





}
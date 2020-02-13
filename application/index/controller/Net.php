<?php


namespace app\index\controller;
use think\Request;
use think\Session;
use app\index\model\Nets;
use think\db;

class Net
{
    public function  index(){
        $obj =new Nets();
        $res=$obj->loads();

    }

}
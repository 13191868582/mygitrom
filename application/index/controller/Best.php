<?php
/*
网销宝模块
时间：2019年10月12日16:22:21
*/
namespace app\index\controller;
use app\index\model\Bests;
use think\controller;
use think\Request;
use think\Session;
use think\db;

class Best extends Common
{
    //网销宝客户展示
    public function index(){
        $search=Request::instance()->post('search');
        $obj=new Bests();
        $data=$obj->lists($search);
        $time=strtotime(date('Y-m-d',time()));
       return $this->fetch('',['data'=>$data,'time'=>$time,'search'=>$search]);
    }
    //网销宝订单添加界面
    public function worder(){
        $id=Request::instance()->get('id');
        $obj=new Bests();
        $data=$obj->worder($id);
        $ordernum=$this->createOrderNum();
        $date=date('Y-m-d',time());
        return $this->fetch('',['data'=>$data,'ordernum'=>$ordernum,'date'=>$date]);
    }
    //网销宝订单执行录入
    public function worderadd(){
        $data=Request::instance()->post();
        $obj=new Bests();
        $res=$obj->worderadd($data);
        if($res){
            $arr = array('code' =>200,'msg'=>'添加成功！');
        }else{
            $arr = array('code' =>404,'msg'=>'添加失败！');
        }
        return returnmsg($arr);
    }
    //网销宝订单查询
    public function worderlist(){
        $search=Request::instance()->post('search');
        $obj=new Bests();
        $data=$obj->worderlist($search);
        return $this->fetch('',['data'=>$data,'search'=>$search]);
    }

    //修改订单通过状态
    public function changeStatus1(){
        $id = input("post.id");
        $obj=new Bests();
        $res=$obj->changeStatus1($id);
        if ($res){
            $arr = array('code' =>200,'msg'=>'审核成功！');
        }else{
            $arr = array('code' =>201,'msg'=>'审核失败！');
        }
        return returnmsg($arr);
    }

//生成订单号
    public function createOrderNum(){
        $timestap = time();
        $arr = [0,1,2,3,4,5,6,7,8,9];
        shuffle($arr);
        $tenRandNum = implode("",$arr);
        return $timestap.$tenRandNum;
    }

}
<?php
namespace app\index\controller;
use app\index\model\Counts;
use think\controller;
use think\Request;
use think\Session;
use think\db;
use app\index\model\Newcustomer;
use think\Paginator;

class Count extends Common
{
    public $time="";
    //统计展示列表
    public function index(){
        $brand_id = Session::get('user.branch_id');
        $time=Request::instance()->get('date');
        $obj=new Counts();
        $result=$obj->selectcount($time);
        $page=$result['page'];
        $sum=$result['sum'];
        $c_visits=$result['c_visits'];
        $c_visitstel=$result['c_visitstel'];
        $c_intention=$result['c_intention'];
        $c_coomnum=$result['c_coomnum'];
        return $this->fetch('',['data'=>$result['data'],
            'page'=>$page,
            'sum'=>$sum,
            'time'=>$time,
            'c_visits'=>$c_visits,
            'c_visitstel'=>$c_visitstel,
            'c_intention'=>$c_intention,
            'brand_id'=>$brand_id,
            'c_coomnum'=>$c_coomnum
            ]);
    }
    //渲染每日统计页面
    public function  worklist(){
        return $this->fetch('');
    }
    //执行日报内容添加
    public function  worklist_do(){
        $request=Request::instance();
        $data=$request->param();
        $obj=new Counts();
        $result=$obj->countadd($data);
        return $result;
    }
    //查询到单列表
    public  function single(){
        $time=Request::instance()->get('date');
        $obj = new Counts();
        $data=$obj->getsingle($time);
       return $this->fetch('',['data'=>$data]);
    }
    //查询开通列表
    public  function getopens(){
        $time=Request::instance()->get('date');
        $obj = new Counts();
        $data=$obj->getopen($time);
        return $this->fetch('',['data'=>$data]);
    }

}
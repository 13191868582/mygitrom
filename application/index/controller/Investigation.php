<?php

namespace app\index\controller;

use think\Session;
use think\Request;

class investigation extends Common
{
    //品控协查首页
    public function index()
    {
        $admin = Session::get('admin');
        $user = Session::get('user');
        $res = Request::instance()->param();
        $start = Request::instance()->param('start');
        $end= Request::instance()->param('end');
        if ($res){
            $andtime = strtotime($res['start']);
            $endtime = strtotime($res['end']);
        }
        if ($admin){

        }else{
            if ($user['dd_postion'] == '经理' || $user['dd_postion'] == '总监'){
                $where['p.branch_id'] = $user['branch_id'];
            }else{
                $result = [];
                return $this->fetch('',['result'=>$result,'and'=>$start,'end'=>$end]);
            }

        }
        if (empty($where)){
            if (empty($andtime)){
                $result = db('productcontrol')
                    ->alias('p')
                    ->join('silver_branch_office b','b.id = p.branch_id')
                    ->field('b.name,p.*')
                    ->where('p.static',4)
                    ->paginate(30,false,['query' => request()->param()]);
            }else{
                $result = db('productcontrol')
                    ->alias('p')
                    ->join('silver_branch_office b','b.id = p.branch_id')
                    ->field('b.name,p.*')
                    ->where('p.static',4)
                    ->where('p.addtime','>=',$andtime)
                    ->where('p.addtime','<=',$endtime)
                    ->paginate(30,false,['query' => request()->param()]);
            }

        }else{
            if (empty($andtime)){
                $result = db('productcontrol')
                    ->alias('p')
                    ->join('silver_branch_office b','b.id = p.branch_id')
                    ->field('b.name,p.*')
                    ->where('p.static',4)
                    ->where($where)
                    ->paginate(30,false,['query' => request()->param()]);
            }else{
                $result = db('productcontrol')
                    ->alias('p')
                    ->join('silver_branch_office b','b.id = p.branch_id')
                    ->field('b.name,p.*')
                    ->where('p.static',4)
                    ->where('p.addtime','>=',$andtime)
                    ->where('p.addtime','<=',$endtime)
                    ->where($where)
                    ->paginate(30,false,['query' => request()->param()]);
            }

        }

        return $this->fetch('',['result'=>$result,'and'=>$start,'end'=>$end]);


    }

    //查询具体信息
    public function check()
    {
        $id = Request::instance()->param('id');
        $result = db('productcontrol')
            ->alias('p')
            ->join('problem pro','pro.id = p.p_type')
            ->where('p.id',$id)
            ->find();


        return  $result;
    }

    //执行协查方法
    public  function res()
    {
        $result = Request::instance()->param();

        $res = db('productcontrol')->where('id',$result['id'])->update(['static'=>'5','l_time'=>time(),'l_result'=>$result['l_result']]);
        return $res;
    }
}
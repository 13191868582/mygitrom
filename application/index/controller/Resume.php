<?php

namespace app\index\controller;

use app\index\model\Resumes;
use think\Session;
use think\Request;

class Resume extends Common
{
    public function index()
    {
        $result = Request::instance()->param('branch');
        $name = Request::instance()->param('name');
        $resume = new Resumes();
        $res = $resume->index($result,$name);
        return $this->fetch('',['result'=>$result,'name'=>$name,'branch'=>$res['branch'],'resume'=>$res['resume'],'result'=>$result]);
    }

    public function add()
    {
        $resume = new Resumes();
        $result = $resume->add();
        return $this->fetch('',['result'=>$result]);
    }

    public function doadd()
    {
        $result = Request::instance()->param();
        $resume = new Resumes();
        $res = $resume->doadd($result);
        if ($res == 5){
            $msg = ['code'=>500,'msg'=>'添加失败'];
        }else{
            if ($res) {
                $msg = ['code'=>200,'msg'=>'添加成功'];

            }else{
                $msg = ['code'=>500,'msg'=>'添加失败'];

            }
        }

        echo json_encode($msg);
    }

    public function edit($id)
    {
        $res = db('resume')->where('id',$id)->find();
        $resume = new Resumes();
        $result = $resume->add();
//        print_r($res['statuc']);die;
        return $this->fetch('edit',['res'=>$res,'result'=>$result]);
    }

    public function doedit()
    {
        $result = Request::instance()->param();
        $user = Session::get('user');
        $result['time'] = strtotime($result['time']);
        $id = $result['id'];
        $resume = db('resume')->where('id',$id)->find();
        if ($resume['statuc'] != $result['statuc']){

            $res = db('resume')->where('id',$id)->update($result);
            if ($res){
                $arr['resume_id'] = $id;
                $arr['statuc'] = $result['statuc'];
                $arr['type'] = 1;
                $arr['addtime'] = time();
                $arr['user_id'] = $user['id'];
                $res1 = db('resume_type')->insert($arr);
                if ($res1){
                    $msg = ['code'=>200,'msg'=>'添加成功'];
                }
            }else{
                $msg = ['code'=>500,'msg'=>'添加失败'];
            }

        }else{
            $res = db('resume')->where('id',$id)->update($result);
            if ($res) {
                $msg = ['code'=>200,'msg'=>'添加成功'];

            }else{
                $msg = ['code'=>500,'msg'=>'添加失败'];

            }
        }

        echo json_encode($msg);


    }

    public function preview()
    {
        $result = Request::instance()->param('id');

        $res = db('resume')->where('id',$result)->find();

        return $res;
    }
}
<?php
/*
 * 内容：荣誉系统
 * 时间：2019年11月2日08:47:16
 * 代码编写者：段文明
 */
namespace app\index\controller;
use app\index\model\Honor;
use think\Request;
use think\Session;
class Honors extends Common
{
    //分公司奥斯卡添加
    public function index(){
        $obj=new Honor();
        if (Request::instance()->isPost()){
            $data=Request::instance()->param();
            $res=$obj->index($data);
            if ($res){
                $arr = array('code' =>200,'msg'=>'添加成功！');
            }else{
                $arr = array('code' =>201,'msg'=>'添加失败！');
            }
            return returnmsg($arr);
        }else{
            $branch=branch_name();
            return $this->fetch('',[
                'branch'=>$branch,
            ]);
        }
    }
    //员工豆豆名人堂添加
    public function  best(){
        $obj=new Honor();
        if (Request::instance()->isPost()){
            $data=Request::instance()->param();
            $res=$obj->best($data);
            if ($res){
                $arr = array('code' =>200,'msg'=>'添加成功！');
            }else{
                $arr = array('code' =>404,'msg'=>'添加失败！');
            }
            return returnmsg($arr);
        }else{
            $branch=branch_name();
            return $this->fetch('',[
                'branch'=>$branch,
            ]);
        }
    }
    //经理名人堂添加
    public function  wbest(){
        $obj=new Honor();
        if (Request::instance()->isPost()){
            $data=Request::instance()->param();
            $res=$obj->wbest($data);
            if ($res){
                $arr = array('code' =>200,'msg'=>'添加成功！');
            }else{
                $arr = array('code' =>404,'msg'=>'添加失败！');
            }
            return returnmsg($arr);
        }else{
            $branch=branch_name();
            return $this->fetch('',[
                'branch'=>$branch,
            ]);
        }
    }
//王牌VS王牌
    public function  trump(){
        $obj=new Honor();
        if (Request::instance()->isPost()){
            $data=Request::instance()->param();
            $res=$obj->trump($data);
            if ($res){
                $arr = array('code' =>200,'msg'=>'添加成功！');
            }else{
                $arr = array('code' =>404,'msg'=>'添加失败！');
            }
            return returnmsg($arr);
        }else{
            $branch=branch_name();
            return $this->fetch('',[
                'branch'=>$branch,
            ]);
        }
    }
    public function lists(){
        $obj=new Honor();
        $honor=$obj->hlist();
        return $this->fetch('',[
            'honor'=>$honor,
        ]);
    }
    public function ylists(){
        $obj=new Honor();
        $fame=$obj->fame();
        return $this->fetch('',[
            'fame'=>$fame
        ]);
    }

    public function jlists(){
        $obj=new Honor();
        $jlists=$obj->jlists();
        return $this->fetch('',[
            'jlists'=>$jlists
        ]);
    }

    public function wlists(){
        $obj=new Honor();
        $trump=$obj->trumps();
        return $this->fetch('',[
            'trump'=>$trump
        ]);
    }

    //修改分公司奥斯卡
    public function editlists(){
        $request=Request::instance();
        $id=$request->get('id');
        $branch=branch_name();
        $obj=new Honor();
        $data=$obj->editlistsone($id);
        return $this->fetch('',[
            'branch'=>$branch,
            'data'=>$data
        ]);
    }
    public  function  editlistsdo(){
        $request=Request::instance();
        $pram=$request->post();
        $obj=new Honor();
        $result=$obj->editlistsdo($pram);
        if ($result){
            $arr = array('code' =>200,'msg'=>'修改成功！');
        }else{
            $arr = array('code' =>404,'msg'=>'修改失败！');
        }
        return returnmsg($arr);
    }
    public function  deletelists(){
        $request=Request::instance();
        $id=$request->post('id');
        $obj=new Honor();
        $res=$obj->deletelists($id);
        if ($res){
            $arr = array('code' =>200,'msg'=>'删除成功！');
        }else{
            $arr = array('code' =>404,'msg'=>'删除失败！');
        }
        return returnmsg($arr);
    }

    //修改员工荣誉榜
    public function editwlists(){
        $request=Request::instance();
        $id=$request->get('id');
        $branch=branch_name();
        $obj=new Honor();
        $data=$obj->editwlists($id);
        return $this->fetch('',[
            'branch'=>$branch,
            'data'=>$data
        ]);
    }
    public  function editwistsdo(){
        $request=Request::instance();
        $pram=$request->post();
        $obj=new Honor();
        $result=$obj->editwistsdo($pram);
        if ($result){
            $arr = array('code' =>200,'msg'=>'修改成功！');
        }else{
            $arr = array('code' =>404,'msg'=>'修改失败！');
        }
        return returnmsg($arr);
    }
    public function deletewlists(){
        $request=Request::instance();
        $id=$request->post('id');
        $obj=new Honor();
        $res=$obj->deletewlists($id);
        if ($res){
            $arr = array('code' =>200,'msg'=>'删除成功！');
        }else{
            $arr = array('code' =>404,'msg'=>'删除失败！');
        }
        return returnmsg($arr);
    }
    //经理
    public  function  editjlists(){
        $request=Request::instance();
        $id=$request->get('id');
        $branch=branch_name();
        $obj=new Honor();
        $data=$obj->editjlists($id);
        return $this->fetch('',[
            'branch'=>$branch,
            'data'=>$data
        ]);
    }

    public  function editjlistsdo(){
        $request=Request::instance();
        $pram=$request->post();
        $obj=new Honor();
        $result=$obj->editjlistsdo($pram);
        if ($result){
            $arr = array('code' =>200,'msg'=>'修改成功！');
        }else{
            $arr = array('code' =>404,'msg'=>'修改失败！');
        }
        return returnmsg($arr);
    }

    public function deletejlists(){
        $request=Request::instance();
        $id=$request->post('id');
        $obj=new Honor();
        $res=$obj->deletejlists($id);
        if ($res){
            $arr = array('code' =>200,'msg'=>'删除成功！');
        }else{
            $arr = array('code' =>404,'msg'=>'删除失败！');
        }
        return returnmsg($arr);
    }

    //王牌VS王牌
    public function editvlists(){
        $request=Request::instance();
        $id=$request->get('id');
        $branch=branch_name();
        $obj=new Honor();
        $data=$obj->editvlists($id);
        return $this->fetch('',[
            'branch'=>$branch,
            'data'=>$data
        ]);
    }
    public  function editvlistsdo(){
        $request=Request::instance();
        $pram=$request->post();
        $obj=new Honor();
        $result=$obj->editvlistsdo($pram);
        if ($result){
            $arr = array('code' =>200,'msg'=>'修改成功！');
        }else{
            $arr = array('code' =>404,'msg'=>'修改失败！');
        }
        return returnmsg($arr);
    }

    public function deletevlists(){
        $request=Request::instance();
        $id=$request->post('id');
        $obj=new Honor();
        $res=$obj->deletevlists($id);
        if ($res){
            $arr = array('code' =>200,'msg'=>'删除成功！');
        }else{
            $arr = array('code' =>404,'msg'=>'删除失败！');
        }
        return returnmsg($arr);
    }
}
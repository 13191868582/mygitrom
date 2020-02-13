<?php
/*
 * 业绩管理
 * 编写人：段文明
 * 时间：2019年9月23日11:45:56
*/
namespace app\index\controller;
use app\index\model\Achievement;
use think\Request;
use think\Controller;
use think\Session;
use think\db;

class  Achievements extends Common
{
    //月业务管理页面渲染
    public function  index(){
        $obj=new Achievement();
        $result=$obj->addlist();
        return $this->fetch('',['result'=>$result]);
    }
    //季度业务
    public function  sadd(){
        $obj=new Achievement();
        $result=$obj->addlist();
        return $this->fetch('',['result'=>$result]);
    }
    //月实行添加
    public function add_do(){
        $request=Request::instance();
        $data=$request->param();
        $obj=new Achievement();
        $result=$obj->add_do($data);
        return $result;
    }
    //季度任务添加
    public function sadd_do(){
        $request=Request::instance();
        $data=$request->param();
        $obj=new Achievement();
        $result=$obj->sadd_do($data);
        return $result;
    }
    //展示月度已分配业务
    public function  lists(){
        $obj=new Achievement();
        $result=$obj->lists();
        return $this->fetch('',['result'=>$result]);
    }

    //展示季度已分配业务
    public function  slists(){
        $obj=new Achievement();
        $result=$obj->slists();
        return $this->fetch('',['result'=>$result]);
    }
    //修改分配业务
    public function edit(){
        $request=Request::instance();
        $id=$request->param('id');
        $obj=new Achievement();
        $result=$obj->edit($id);
        $result['a_time']=date('Y-m',$result['a_time']);
        return $this->fetch('',['result'=>$result]);
    }
    //执行修改
    public function  edit_do(){
        $request=Request::instance();
        $data=$request->param();
        $obj=new Achievement();
        $result=$obj->edit_do($data);
        return $result;
    }
    //查看业绩完成度
    public function ac_complete(){
        $date=Request::instance()->param('date');
        if ($date){
            $date= Request::instance()->param('date');
        }else{
            $date=date('Y-m',time());
        }
        $obj=new Achievement();
        $result=$obj->ac_complete($date);
        //print_r($result);die;
        return $this->fetch('complete',[
            'result'=>$result,
            'date'=>$date
        ]);
    }

    public function pk(){
        $date=Request::instance()->param('date');
        if ($date){
            $date= Request::instance()->param('date');
        }else{
            $date=date('Y-m',time());
        }
        $obj=new Achievement();
        $result=$obj->pks($date);
        return $this->fetch('',[
            'date'=>$date,
            'result'=>$result
        ]);
    }
    //业绩列表添加
    public function  add(){
        if (Request::instance()->isAjax()){
            $data=Request::instance()->post();
            $obj=new Achievement();
            $res=$obj->addorder($data);
            if($res){
                $arr = array('code' =>200,'msg'=>'操作成功！');
            }else{
                $arr = array('code' =>201,'msg'=>'操作失败！');
            }
            return returnmsg($arr);
        }else{
            $obj= new \app\index\controller\Customers;
            //订单号
            $per_aliorder=$obj->createOrderNum();
            return $this->fetch('',['per_aliorder'=>$per_aliorder]);
        }
    }
    //业绩列表展示
    public function  perlists(){
        $postsearch=Request::instance()->post('postsearch');
        $obj=new Achievement();
        $list=$obj->perlists($postsearch);
        //print_r($list);die;
        return $this->fetch('',['list'=>$list,'postsearch'=>$postsearch]);
    }
    //订单确认
    public  function changeStatus1(){
        $id=Request::instance()->post('id');
        $obj=new Achievement();
        $res=$obj->changeStatus1($id);
        if($res==1){
            $arr = array('code' =>200,'msg'=>'审核成功！');
        }else{
            $arr = array('code' =>201,'msg'=>'审核不成功！');
        }
        return returnmsg($arr);
    }
    //订单拒绝
    public  function changeStatusr1(){
        $id=Request::instance()->post('id');
        $obj=new Achievement();
        $res=$obj->changeStatusr1($id);
        if($res==1){
            $arr = array('code' =>200,'msg'=>'拒绝成功！');
        }else{
            $arr = array('code' =>201,'msg'=>'拒绝失败！');
        }
        return returnmsg($arr);
    }
    //订单查看详情
    public function uplists(){
        $obj=new Achievement();
        if (Request::instance()->isAjax()){
            $data=Request::instance()->post();
            $res=$obj->up($data);
            if($res){
                $arr = array('code' =>200,'msg'=>'修改成功！');
            }else{
                $arr = array('code' =>201,'msg'=>'修改失败！');
            }
            return returnmsg($arr);
        }else{
            $id=Request::instance()->get('id');
            $data=$obj->uplists($id);
            return $this->fetch('',['data'=>$data]);
        }
    }
    public function task(){
        $obj=new Achievement();
        $data=$obj->mytask();
        $this->assign('data',$data);
        return $this->fetch('');
    }

}
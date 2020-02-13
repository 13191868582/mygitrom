<?php
namespace app\index\controller;
use think\Controller;
use think\Session;
use think\db;
class Common extends Controller
{
    public function __construct()
    {
    	parent::__construct();
    	if(!Session::get('user') && !Session::get('admin')){
    		$this->redirect('Login/index');
    	}
    	// if(Session::get('user.id')){
    	// 	$data['uid']=Session::get('user.id');
    	// 	$data['route']=$_SERVER['REQUEST_URI'];
     //        $data['starttime']=time();
     //        $id=db('access_log')->where('uid',$data['uid'])->order('id desc')->find();
     //        if($id){
     //            $time['endtime']=time();
     //            db('access_log')->where('id',$id['id'])->update($time);
     //        }
     //        db('access_log')->insert($data);
    	// }
    	//var_dump($_SERVER['REQUEST_URI']);
    	$role=Session::get('role');
		$this->assign('rolecommon',$role);
    }
}

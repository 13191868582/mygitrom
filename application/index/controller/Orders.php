<?php
namespace app\index\controller;

use think\controller;
use think\Request;
use app\index\model\Order;
use think\Session;

/**
 * 订单管理
 */
class Orders extends Common
{
	/**
	 * 订单列表
	 * @return [type] [description]
	 */
	public function index()
	{
		$order = new Order();
		$user = Session::get("user");

		$job_num = trim(input("post.job_num"));

		if (empty($user)) {
			$result = $order->index($job_num);
		}else{
			$result = $order->userorder($user);
		}				
		return $this->fetch('',['res'=>$result,'job_num'=>$job_num,'user' => $user]);
	}
	/**
	 * 收货操作
	 * @return [type] [description]
	 */
	public function receiving()
	{
		$request=Request::instance();
		$data=$request->param('user');
		
		$order=new Order();
		$res=$order->receiving($data);
		
		
		if($res){
            $arr = array('code' =>200,'msg'=>'收货成功！');
        }else{
            $arr = array('code' =>404,'msg'=>'收货失败！');
        }

        return returnmsg($arr);
	}
	/**
	 * 发货操作
	 * @return [type] [description]
	 */
	public function edit()
	{
		$request=Request::instance();
		$data=$request->param('user');
		$order=new Order();
		$res=$order->edit($data);
		if($res){
            $arr = array('code' =>200,'msg'=>'发货成功！');
        }else{
            $arr = array('code' =>404,'msg'=>'发货失败！');
        }
        return returnmsg($arr);
	}

}

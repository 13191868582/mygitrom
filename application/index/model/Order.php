<?php
namespace app\index\model;

use think\Model;
use think\db;

/**
 * 订单管理
 */
class Order extends Model
{
	/**
	 * 管理员获取订单列表
	 */
	public function index($job_num = '')
	{
		if ($job_num == '') {
			$order = db('order')
						->alias('o')
						->join('silver_user u','o.uid = u.id')
						->join('silver_branch_office b','o.bid = b.id')
						->field('o.id,u.username,u.job_num,b.name,o.order_num,o.order_address,o.order_time,o.order_status,o.order_receiving')
						->paginate(20);
		}else{
			$user = db('user')->field('id')->where('job_num',$job_num)->find();
			$order = db('order')
						->alias('o')
						->join('silver_user u','o.uid = u.id')
						->join('silver_branch_office b','o.bid = b.id')
						->field('o.id,u.username,u.job_num,b.name,o.order_num,o.order_address,o.order_time,o.order_status,o.order_receiving')
						->where('o.uid',$user['id'])
						->paginate(20);
		}
		

		return $order;
	}

	/**
	 * 用户获取订单列表
	 * @param  [type] $user [用户信息]
	 * @return [type]       [description]
	 */
	public function userorder($user)
	{
		$order = db('order')
					->alias('o')
					->join('silver_user u','o.uid = u.id')
					->join('silver_branch_office b','o.bid = b.id')
					->field('o.id,u.username,u.job_num,b.name,o.order_num,o.order_address,o.order_time,o.order_status,o.order_receiving')
					->where('o.uid',$user['id'])
					->paginate(20);

		return $order;

	}

	public function receiving($id)
	{
		
	
		$res = db('order')->where('id',$id)->update(['order_receiving'=>1]);
			
		if($res){
			return true;
		}else{
			return false;
		}
	}

	public function edit($id)
	{
		$res = db('order')->where('id',$id)->update(['order_status'=>1]);
			
		if($res){
			return true;
		}else{
			return false;
		}
	}



}
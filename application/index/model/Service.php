<?php
namespace app\index\model;
use think\Model;
use think\db;
use think\Session;
/**
* 服务model类
*/
class Service extends Model
{
	protected $service_month;
	protected $uid;
	protected $cid;
	//服务月列表
	public function month_list()
	{
		$month=Db::name('service_month')->select();
		return $month;
	}
	//添加操作
	public function doadd($data)
	{
		$res=Db::name('service_month')->insert($data);
		if($res){
			return "1";
			
		}else{
			return "2";

		}
	}
	//服务项列表
	public function service_list()
	{
		$res=Db::name('service')->paginate(30);
		return $res;
	}
	//添加服务
	public function do_addservice($data)
	{
		$res=Db::name('service')->insert($data);
		if($res){
			return "1";
			
		}else{
			return "2";

		}
	}
	//编辑操作
	public function edit_service($id)
	{
		$res=Db::name('service')->where('id',$id)->find();
		return $res;
	}
	//执行修改
	public function updates($data,$id)
	{
		$res=Db::name('service')->where('id',$id)->update($data);
		if($res){
			return '1';
		}else{
			return '2';
		}
	}

	//客户规定服务列表
	public function service_cum1($id)
	{
		$endtime=time();
		$data=Db::name('customer')->field("sign_date")->where("id",$id)->where('expires','>',$endtime)->find();
		$branch_id=Db::name('relation_company')->field('oid')->where('cid',$id)->find();
		$oid=$branch_id['oid'];
		$time=$data['sign_date'];
		$month=date('m',$time);
		$year=date('Y',$time);
		$now_month=date('m',time());
		$now_year=date('Y',time());
		//客户入网时间
		$cum_month=($now_year-$year)*12+$now_month-$month+1;

		$ser=Db::name('service')->where('service_month',$cum_month)->where('branch_id',$oid)->where('status',1)->where('type',1)->select();
		// var_dump($ser);
		// 			die;
		return $ser;
	}
	//客户可选服务列表
	public function service_cum2($id)
	{
		$data=Db::name('customer')->field("sign_date")->where("id",$id)->find();
		$branch_id=Db::name('relation_company')->field('oid')->where('cid',$id)->find();
		$oid=$branch_id['oid'];
		$time=$data['sign_date'];
		$month=date('m',$time);
		$year=date('Y',$time);
		$now_month=date('m',time());
		$now_year=date('Y',time());
		//客户入网时间
		$cum_month=($now_year-$year)*12+$now_month-$month+1;
		$ser=Db::name('service')->where('service_month',$cum_month)->where('branch_id',$oid)->where('status',1)->where('type',2)->select();
		return $ser;
	}
	//查询所有服务应完成的id值并写入数据库

	public function insert_service($data)
	{
		//查询对应公司对应的服务项
		$service['service_month']=$data['service_month'];
		$service['branch_id']=Session::get('user.branch_id');
		$service['status']=1;
		//规定服务项
		$data_ser=Db::name('service')->field('id')->where($service)->where('type',1)->select();
		foreach ($data_ser as $k => $v) {
			$arr1[]=$v['id'];
		}
		$add['service1']=implode(',',$arr1);
		//规定服务完成量用于做比率
		$add['p_num']=count($arr1);
		//可选服务项
		$data_rea=Db::name('service')->field('id')->where($service)->where('type',2)->select();
		if($data_rea){
			foreach ($data_rea as $k => $v) {
			$arr2[]=$v['id'];
		}
		$add['service2']=implode(',',$arr2);
		//可选服务完成量
		$add['o_num']=count($arr2);
		}
		
		$add['service_month']=$data['service_month'];
		$add['uid']=Session::get('user.id');
		$this->service_month=$data['service_month'];
		$this->uid=Session::get('user.id');
		$add['cid']=$data['cid'];
		$add['addtime']=time();
		$this->cid=$data['cid'];
		$where['cid']=$data['cid'];
		$where['service_month']=$data['service_month'];
		$res=Db::name('service_completion')->where($where)->find();
		if($res){
			return false;
		}else{
			Db::name('service_completion')->insert($add);
			return true;
		}

	}

	//存入员工操作的服务项
	public function update_service($data)
	{
		// $where['uid']=$this->uid;
		$where['cid']=$this->cid;
		$data['updatetime']=time();
		$where['service_month']=$this->service_month;
		$res=Db::name('service_completion')->where($where)->update($data);
		return $res ? true : false;
	}

	//客户入网时间

	public function net_age($id)
	{
		$data=Db::name('customer')->field("sign_date")->where("id",$id)->find();
		$time=$data['sign_date'];
		$month=date('m',$time);
		$year=date('Y',$time);
		$now_month=date('m',time());
		$now_year=date('Y',time());
		//客户入网时间
		$cum_month=($now_year-$year)*12+$now_month-$month+1;
		// echo $cum_month;
		// die;
		return $cum_month;
	}

	
	//删除服务
	public function service_del($id)
	{
		$res=Db::name('service_month')->where('id',$id)->delete();
		return $res ? true : false;
	}

}
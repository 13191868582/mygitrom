<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Service;
/**
* 服务标准设置类

*/
class Services extends Common
{
	//服务月列表
	public function month_list()
	{
		$month=new Service();
		$data=$month->month_list();
		return $this->fetch("month_list",['data'=>$data]);
	}
	//添加服务月
	public function doadd()
	{
		$data=$_POST;
		$month=new Service();
		$res=$month->doadd($data);
		if($res=="1"){
			$arr=array('code'=>200,'msg'=>'添加成功！');
			return returnmsg($arr);
		}else{
			$arr=array('code'=>404,'msg'=>'添加失败！');
			return returnmsg($arr);
		}

	}
	//编辑服务月
	// public function edit_service()
	// {
	// 	$id=$_POST['id'];
	// 	$month=new Service();
	// 	$res=$month->edit_service();
	// 	if()
	// }
	//服务列表
	public function service_list()
	{
		$service=new Service();
		$res=$service->service_list();
		return $this->fetch("service_list",['service_list'=>$res]);
	}
	//添加服务
	public function addservice()
	{
		$service=new Service();
		$res=$service->month_list();
		return $this->fetch("add_service",['res'=>$res]);
	}
	//执行添加操作
	public function do_addservice()
	{
		$data=$_POST;
		$month=new Service();
		$res=$month->do_addservice($data);
		if($res=="1"){
			$arr=array('code'=>200,'msg'=>'添加成功！');
			return returnmsg($arr);
		}else{
			$arr=array('code'=>404,'msg'=>'添加失败！');
			return returnmsg($arr);
		}
	}
	//修改
	public function edit_service()
	{
		$id=$_GET['id'];
		$service=new Service();
		$res=$service->month_list();
		$service=$service->edit_service($id);
		return $this->fetch('',['res'=>$res,'service'=>$service]);
	}
	//执行修改

	public function do_editservice()
	{
		$data=$_POST;
		$id=$data['sid'];
		$update['name']=$data['name'];
		$update['type']=$data['type'];
		$update['service_month']=$data['service_month'];
		$update['status']=$data['status'];
		$update['branch_id']=$data['branch_id'];
		$service=new Service();
		$res=$service->updates($update,$id);
		if($res=='1'){
			$arr=array('code'=>200,'msg'=>'修改成功！');
			return returnmsg($arr);
		}else{
			$arr=array('code'=>404,'msg'=>'修改失败！');
			return returnmsg($arr);
		}
	}

	//客户服务列表
	public function service_cum()
	{
		//根据输入网时间查询
		$id=$_GET['id'];
		$service=new Service();
		$time=$service->net_age($id);
		//查询已完成的服务
		$res1=$service->service_cum1($id);
		$res2=$service->service_cum2($id);
		if($res1 || $res2){
			return $this->fetch("",['res1'=>$res1,'res2'=>$res2,'cid'=>$id,'times'=>$time]);
		}else{
			echo "<script>alert('暂时没有服务');window.history.back(-1); </script>";
		}
	}

	//勾选服务
	public function doservice()
	{
		$data=$_POST;
		$id=$data['cid'];
		$service=new Service();
		$ser_insert=$service->insert_service($data);
		$res1=$service->service_cum1($id);
		foreach ($res1 as $k => $v) {
			$arr1[]=$v['id'];
		}
		$res2=$service->service_cum2($id);
		if($res2){
			foreach ($res2 as $k => $v) {
			$arr2[]=$v['id'];
		}
		$list2=array_intersect($re,$arr2);
		$add['o_nums']=count($list2);
		$add['option_service']=implode(",",$list2);
		}
		$re=array_keys($data);
		foreach ($re as $k => $v) {
			if($v=='cid'){
				unset($re[$k]);
			}
		}
		$list1=array_intersect($re,$arr1);
		
		$add['provisional_services']=implode(",",$list1);
		//实际完成服务数量
		$add['p_nums']=count($list1);
		
		
		$upres=$service->update_service($add);
		if($upres){
			$arr=array('code'=>200,'msg'=>'服务成功！');
			return returnmsg($arr);
		}else{
			$arr=array('code'=>404,'msg'=>'服务错误！！');
			return returnmsg($arr);
		}



	}
	//删除包月
	public function service_del()
	{
		$id=$_POST['id'];
		$service=new Service();
		$res=$service->service_del($id);
		if($res){
			$arr=array('code'=>200,'msg'=>'删除成功！');
			return returnmsg($arr);
		}else{
			$arr=array('code'=>404,'msg'=>'删除失败！');
			return returnmsg($arr);
		}
	}

}
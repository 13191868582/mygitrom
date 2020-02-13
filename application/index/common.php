<?php
use think\Db;
use think\Session;
//评分范围函数
function score($id)
{
	$data=Db::name('score')->where('id',$id)->find();
	return $data['score'];
}
//员工数量函数
function e_num($id)
{
	$data=Db::name('e_num')->where('id',$id)->find();
	return $data['num'];
}
//p4p消耗函数
function p4p($id)
{
	$data=Db::name('p4p')->where('id',$id)->find();
	return $data['consume'];
}
//利润区间函数
function profit($id)
{
	$data=Db::name('profit')->where('id',$id)->find();
	return $data['profit'];
}
//营业额区间
function turnover($id)
{
	$data=Db::name('turnover')->where('id',$id)->find();
	return $data['turnover'];
}
//入网月份
function service_month($id)
{
	$data=Db::name('service_month')->where('month',$id)->find();
	return '入网'.$data['month'].'个月';
}
//二级分类名
function industry2($id)
{
	$data=Db::name('industry')->where('id',$id)->find();
	return $data['class_industry'];
}
//完成率算法

//客户现在完成的规定的服务
function service_complete1($id,$month,$sid,$uid)
{
	$data=Db::name('service_completion')->field('provisional_services')->where('cid',$id)->where('uid',$uid)->where('service_month',$month)->find();
	$arr=explode(',',$data['provisional_services']);
	if(in_array($sid,$arr)){
		return true;
	}else{
		return false;
	}

}
//客户完成的可选的服务
function service_complete2($id,$month,$sid,$uid)
{
	$data=Db::name('service_completion')->field('option_service')->where('cid',$id)->where('uid',$uid)->where('service_month',$month)->find();
	$arr=explode(',',$data['option_service']);
	if(in_array($sid,$arr)){
		return true;
	}else{
		return false;
	}
}

function auth1($id)
{
	$data=Db::name('author')->field('name')->where('id',$id)->find();
	return $data['name'];
}

function auth2($id)
{
	$data=Db::name('author')->field('title')->where('id',$id)->find();
	return $data['title'];
}

//职级
function rank($rank)
{
	$data=Db::name('role')->field('title')->where('id',$rank)->find();
	return $data['title'];
}

function sendmsg($type,$msg){
    $ret['ok'] = $type;
    $ret['msg'] = $msg;
    echo json_encode($ret);
    exit();
}
//服务按钮显示与否
function service_list($id)
{
	$expires=Db::name('customer')->field("expires")->where("id",$id)->find();
	if($expires['expires']<time()){
		// Db::name('service_completion')->where('cid',$id)->delete();
		return false;
	}else{
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
		$ser=Db::name('service')->where('service_month',$cum_month)->where('branch_id',$oid)->where('status',1)->where('type',1)->select();
		return $ser ? true : false;
	}
	
}


//到期时间
function endtime($id)
{
	$data=Db::name('customer')->field("sign_date")->where("id",$id)->find();
}
//用户id（用于导入数据）
function getuserid($data)
{
	$user=Db::name('user')
        ->field('id')
        ->where('username',$data)
        ->find();
	return $user['id'];
}
//获取用户名
function getuser($id)
{
	$user=Db::name('user')->field('username')->where('id',$id)->find();
	return $user['username'];
}
//区域公司id
function boid($data)
{
	if($data=='石家庄'){
		$bid=1;
	}
	if($data=='邯郸'){
		$bid=2;
	}
	if($data=='衡水'){
		$bid=3;
	}
	if($data=='沧州'){
		$bid=4;
	}
	if($data=='廊坊'){
		$bid=5;
	}
	if($data=='保定'){
		$bid=6;
	}
	if($data=='白沟'){
		$bid=7;
	}
	if($data=='邢台'){
		$bid=8;
	}
	if($data=='西安'){
		$bid=9;
	}
	return $bid;
}

//到单量总数
function getnewsum($brand_id,$time){
    $startime = strtotime("$time" . "00:00:00");
    $endtime = strtotime("$time" . "23:59:59");
    $res = Db::name('neworderform')
        ->where('bid', $brand_id)
        ->where('status', 1)
        ->whereTime('totime', 'between', ["$startime", "$endtime"])
        ->count('status');
    return $res;

}
//开通量总数
function gettongsum($brand_id,$time){
    $startime = strtotime("$time" . "00:00:00");
    $endtime = strtotime("$time" . "23:59:59");
    $res = Db::name('neworderform')
        ->where('bid', $brand_id)
        ->where('status', 2)
        ->whereTime('totime', 'between', ["$startime", "$endtime"])
        ->count('status');
    return $res;
}
function branch_user($id){
	$user=Db::name('user')->where('branch_id',$id)->field('job_num,username')->where('rank','in','16,9,11,1,17')->select();
	return $user;
}
function getname($id){
    $data=Db::name('newcustomer_status')->where('c_id',$id)
        ->field('job_num')
        ->find();
    $job_num=$data['job_num'];
    $username=Db::name('user')->where('job_num',$job_num)
        ->field('username')
        ->find();
    return $username['username'];
}
//续费状态按钮显示
function xufei($id)
{
	$status=Db::name('renewal')->where('did',$id)->where('status','<','2')->field('status')->find();
	if($status){
		return false;
	}else{
		return true;
	}
}


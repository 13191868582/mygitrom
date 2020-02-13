<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Statistic;
use \think\Request;
use think\Session;
use app\index\model\Useranalysi;
/**
* 服务完结率统计
*/
class Statistics extends Common
{
	//公司规定服务完结率
	public function completeRate()
	{
		
		$request=Request::instance();
		$date=$request->post('date');

		if($date){
			$arr=explode("-",$date);
			$year=$arr[0];
			$month=$arr[1];
			$arr2=mFristAndLast($year,$month);
			$start=$arr2['firstday'];
			$end=$arr2['lastday'];
		}else{
			$date=date("Y-m",time());
			$arr=explode("-",$date);
			$year=$arr[0];
			$month=$arr[1];
			$arr2=mFristAndLast($year,$month);
			$start=$arr2['firstday'];
			$end=$arr2['lastday'];
		}
		if(Session::get('user.rank')>1){
			$bid=$_SESSION['think']['user']['branch_id'];
		}else{
			$bid=$request->post('branch_id');
		}
		
		$job_num=$request->post('job_num');
		$sta=new Statistic();
		$company=Statistic::companyList();
		$list=$sta->rate($bid,$job_num,$start,$end);
		// echo "<pre>";
		// print_r($date);
		// exit();
		return $this->fetch('prate',['company'=>$company,'bid'=>$bid,'date'=>$date,'lists'=>$list,'bid'=>$bid]);
	}
	//个人服务完结率
	public function personalEnd()
	{
		$request=Request::instance();
		$date=$request->post('date');

		if($date){
			$arr=explode("-",$date);
			$year=$arr[0];
			$month=$arr[1];
			$arr2=mFristAndLast($year,$month);
			$start=$arr2['firstday'];
			$end=$arr2['lastday'];
		}else{
			$date=date("Y-m",time());
			$arr=explode("-",$date);
			$year=$arr[0];
			$month=$arr[1];
			$arr2=mFristAndLast($year,$month);
			$start=$arr2['firstday'];
			$end=$arr2['lastday'];
		}
		$rank=Session::get('user.rank');
		if($rank==5){
			$bid=Session::get('user.branch_id');
			$job_num=Session::get('user.id');
		}elseif($rank==2 || $rank==3){
			$bid=Session::get('user.branch_id');
			$job_num=$request->post('industry2');
		}else{
			$bid=$request->post('industry1');
			$job_num=$request->post('industry2');
		}
		// print_r($start);
		// echo "<hr>";
		// print_r($date);
		// exit;
		
		$sta=new Statistic();
		$company=Statistic::companyList();
		$userlist=$sta->userLists($bid);
		$list=$sta->prerate($bid,$job_num,$start,$end);
//		print_r($bid);
//		echo "<hr>";
//        print_r($job_num);
//        echo "<hr>";
//        print_r($start);
//        echo "<hr>";
//
//		echo "<pre>";
//		print_r($list);
//		die;
		return $this->fetch('preprate',['company'=>$company,'date'=>$date,'job_num'=>$job_num,'bid'=>$bid,'lists'=>$list,'rank'=>$rank,'users'=>$userlist]);
	}
	public function userLists()
	{
		$request=Request::instance();
		$fid=$request->post('fid');
		$sta=new Statistic();
		$res=$sta->userLists($fid);
		if($res){
			$arr = array('code' =>200,'data'=>$res);
		}else{
			$arr = array('code' =>404,'msg'=>'修改失败！');
		}

		return returnmsg($arr);
	}

	public function export()
	{
		$request=Request::instance();
		$date=$request->post('date');

		if($date){
			$arr=explode("-",$date);
			$year=$arr[0];
			$month=$arr[1];
			$arr2=mFristAndLast($year,$month);
			$start=$arr2['firstday'];
			$end=$arr2['lastday'];
		}else{
			$date=date("Y-m",time());
			$arr=explode("-",$date);
			$year=$arr[0];
			$month=$arr[1];
			$arr2=mFristAndLast($year,$month);
			$start=$arr2['firstday'];
			$end=$arr2['lastday'];
		}
		$rank=Session::get('user.rank');
		if($rank==5){
			$bid=Session::get('user.branch_id');
			$job_num=Session::get('user.id');
		}elseif($rank==2 || $rank==3){
			$bid=Session::get('user.branch_id');
			$job_num=$request->post('industry2');
		}else{
			$bid=$request->post('industry1');
			$job_num=$request->post('industry2');
		}
		// print_r($start);
		// echo "<hr>";
		// print_r($end);
		// exit;
		
		$sta=new Statistic();
		$company=Statistic::companyList();
		$userlist=$sta->userLists($bid);
		$list=$sta->export($bid,$job_num,$start,$end);
		foreach ($list as $key => $value) {
		    $name = db('branch_office') ->where('id',$value['branch_id'])->find();
			$array[$key]['name'] = $name['name'];
			$array[$key]['username'] = $value['username'];
			$array[$key]['job_num'] = (string)$value['job_num'];
			$array[$key]['p_num'] = $value['p_num'];
			if (isset($value['p_nums'])){
                $array[$key]['p_nums'] = $value['p_nums'];
            }else{
                $array[$key]['p_nums'] = 0;
            }

			if ($value['p_num'] != 0) {
				$array[$key]['guiding'] = round($value['p_nums']/$value['p_num']*100,2)."%";
			}else{
				$array[$key]['guiding'] = '0%';
			}
			if (isset($value['o_num'])){
                $array[$key]['o_num'] = $value['o_num'];
            }else{
                $array[$key]['o_num'] = 0;
            }
            if (isset($value['o_nums'])){
                $array[$key]['o_nums'] = $value['o_nums'];
            }else{
                $array[$key]['o_nums'] = 0;
            }

			if ($value['o_num'] != 0) {
				$array[$key]['kexuan'] = round($value['o_nums']/$value['o_num']*100,2)."%";
			}else{
				$array[$key]['kexuan'] = '0%';
			}
			
		}


		$name = '个人服务完结率';
		$header  = ['分公司','员工名称','员工工号','规定服务','实际完成','完成率','可选服务','实际完成','完成率'];
		$userran=new Useranalysi();
		$userran->getExcel($name,$header,$array);
		
	}

	public function allExport()
	{

		$request=Request::instance();
		$date=$request->post('date');

		if($date){
			$arr=explode("-",$date);
			$year=$arr[0];
			$month=$arr[1];
			$arr2=mFristAndLast($year,$month);
			$start=$arr2['firstday'];
			$end=$arr2['lastday'];
		}else{
			$date=date("Y-m",time());
			$arr=explode("-",$date);
			$year=$arr[0];
			$month=$arr[1];
			$arr2=mFristAndLast($year,$month);
			$start=$arr2['firstday'];
			$end=$arr2['lastday'];
		}
		if(Session::get('user.rank')>1){
			$bid=$_SESSION['think']['user']['branch_id'];
		}else{
			$bid=$request->post('branch_id');
		}
		
		$job_num=$request->post('job_num');
		$sta=new Statistic();
		$company=Statistic::companyList();
		$list=$sta->rate($bid,$job_num,$start,$end);
		foreach ($list as $key => $value) {
			if (empty($value[0]['name'])) {
				$array[$key]['name'] = '所有公司';
			}else{
				$array[$key]['name'] = $value[0]['name'];
			}
			if ($value[0]['p_nums'] != 0) {
				$array[$key]['p_nums'] = $value[0]['p_nums'];
			}else{
				$array[$key]['p_nums'] = 0;
			}
			if ($value[0]['p_num'] != 0) {
				$array[$key]['p_num'] = $value[0]['p_num'];
				$array[$key]['guiding'] = round($value[0]['p_nums']/$value[0]['p_num']*100,2)."%";
			}else{
				$array[$key]['p_num'] = 0;
				$array[$key]['guiding'] = '0%';
			}
			
			

			if ($value[0]['o_nums'] != 0) {
				$array[$key]['o_nums'] = $value[0]['o_nums'];
			}else{
				$array[$key]['o_nums'] = 0;
			}
			if ($value[0]['o_num'] != 0) {
				$array[$key]['o_num'] = $value[0]['o_num'];
				$array[$key]['kexuan'] = round($value[0]['o_nums']/$value[0]['o_num']*100,2)."%";
			}else{
				$array[$key]['o_num'] =0;
				$array[$key]['kexuan'] = '0%';
			}
		}

		$name = '公司规定服务完结率';
		$header  = ['公司','实际完成','规定服务','完成率','可选服务','实际完成','完成率'];
		$userran=new Useranalysi();
		$userran->getExcel($name,$header,$array);

	}



}
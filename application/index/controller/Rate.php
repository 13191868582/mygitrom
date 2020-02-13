<?php

namespace app\index\controller;
use app\index\model\Finances;
use app\index\model\Rates;
use think\Session;
use think\Page;
class Rate extends Common
{
	//个人续签率
	public function index(){
		//获取当前登录的信息
		$user=Session::get("user");
		$admin=Session::get("admin");
		$branch = db("branch_office")->select();   
		//分公司id
		$keywords = input("get.keywords");


		//选择时间
		$keywords1 = input("get.keywords1");
		if ($user == '' && $keywords == '') {
			$data = [];
		}else{
			if ($user == '' && $keywords != '') {
				$branch_id = $keywords;
			}
			if ($user != '' && $keywords == '') {
				//获取当前登录的分公司id
				$branch_id = $user['branch_id'];
			}

				
			
			
			//查询当前分公司续签客服 如果管理员登录默认页面没有数据
			$job_num = db('user')->field('job_num,id,username')->where('branch_id',$branch_id)->where('department',3)->select();


			if ($keywords1 == '') {
				//当月时间
				$time = mktime(0,0,0,date('m'),1,date('Y'));
				
				//下个月的时间
				$date2 = strtotime(date('Y-m-01 00:00:00',strtotime('1 month')));
				//下下个月的时间
				$date3 = strtotime(date('Y-m-01 00:00:00',strtotime('2 month')));
				//下下下个月的时间
				$date4 = strtotime(date('Y-m-01 00:00:00',strtotime('3 month')));
			}else{
				$asd = $keywords1 . '-01';
				$time = strtotime($asd);
				//下个月的时间
				$date2 = strtotime(date('Y-m-01 00:00:00',strtotime($asd . '1 month')));
				//下下个月的时间
				$date3 = strtotime(date('Y-m-01 00:00:00',strtotime($asd . '2 month')));
				//下下下个月的时间
				$date4 = strtotime(date('Y-m-01 00:00:00',strtotime($asd . '3 month')));
			}
			if (!empty($job_num)) {
				foreach ($job_num as $key => $value) {
					//这个月的续签人数
					$data[$key]['Mx'] = db("achievement")->where("username",$value['username'])->where("type",'续签')
						->where("yuantime","egt",$time)->where("yuantime","lt",$date2)->count();
					//下月的续签人数
					$data[$key]['M+1x'] = db("achievement")->where("username",$value['username'])->where("type",'续签')
						->where("yuantime","egt",$date2)->where("yuantime","lt",$date3)->count();
					//下下月的续签人数
					$data[$key]['M+2x'] = db("achievement")->where("username",$value['username'])->where("type",'续签')
						->where("yuantime","egt",$date3)->where("yuantime","lt",$date4)->count();
					//回签客户的人数
					$data[$key]['cliend'] = db("achievement")->where("username",$value['username'])->where("type",'续签')
						->where("yuantime","lt",$time)
						->where("totime","egt",$time)
						->where("totime","lt",$date2)->count();
					$data[$key]['id'] = $value['id'];
					$data[$key]['job_num'] = $value['job_num'];
					$data[$key]['username'] = $value['username'];

					//当月的
					$zxc= db('customer')
						->alias('a')->join('silver_employee_customer w','a.id = w.cid')
						->where("a.expires","egt",$time)->where("a.expires","lt",$date2)
						->where('w.uid',$value['id'])
						->where('w.status',1)
						->count();
					$asd = db("achievement")->where("username",$value['username'])->where("type",'续签')
						->where("yuantime","egt",$time)->where("yuantime","lt",$date2)->count();

					$data[$key]['Mi']  = $zxc + $asd;


					//下个月的
					$zxc1 = db('customer')
						->alias('a')->join('silver_employee_customer w','a.id = w.cid')
						->where("a.expires","egt",$date2)->where("a.expires","lt",$date3)
						->where('w.uid',$value['id'])
						->where('w.status',1)
						->count();

					$asd1 = db("achievement")->where("username",$value['username'])->where("type",'续签')
						->where("yuantime","egt",$date2)->where("yuantime","lt",$date3)->count();

					$data[$key]['M+1i']  = $zxc1 + $asd1;
					//下下个月的
					$zxc2 = db('customer')
						->alias('a')->join('silver_employee_customer w','a.id = w.cid')
						->where("a.expires","egt",$date3)->where("a.expires","lt",$date4)
						->where('w.uid',$value['id'])
						->where('w.status',1)
						->count();

					$asd2 = db("achievement")->where("username",$value['username'])->where("type",'续签')
						->where("yuantime","egt",$date3)->where("yuantime","lt",$date4)->count();

					$data[$key]['M+2i']  = $zxc2 + $asd2;

					//公司名称
					$name = db('branch_office')->field('name')->where('id',$branch_id)->find();
					$data[$key]['name'] = $name['name'];

				}




				//续签率的计算
				foreach ($data as $key => $value) {
					if ($value['Mx']+$value['cliend'] == 0 && $value['Mi'] == 0) {
						$data[$key]['Ml'] = '0%';
					}else{
						if ($value['Mx']+$value['cliend'] != 0 && $value['Mi'] == 0) {
							$data[$key]['Ml'] = '错误';
						}else{
							$data[$key]['Ml'] = round(($value['Mx']+$value['cliend'])/$value['Mi']*100,2).'%';
						}
						
					}
					if ($value['M+1x'] == 0 && $value['M+1i'] == 0) {
						$data[$key]['M+1l'] = '0%';
					}else{
						if ($value['M+1x'] != 0 && $value['M+1i'] == 0) {
							 $data[$key]['M+1l'] = '错误';
						}else{
							$data[$key]['M+1l'] = round(($value['M+1x'])/$value['M+1i']*100,2).'%';
						}
						
					}
					if ($value['M+2x'] == 0 && $value['M+2i'] == 0) {
						$data[$key]['M+2l'] = '0%';
					}else{
						if ($value['M+2x'] != 0 && $value['M+2i'] == 0) {
							 $data[$key]['M+2l'] = '错误';
						}else{
							$data[$key]['M+2l'] = round(($value['M+2x'])/$value['M+2i']*100,2).'%';
						}
					}   
					
				}  
			}else{
				$data = [];
			}

		}
		
		
		
	   

		return $this->fetch('',['data'=>$data,'user'=>$user,'branch'=>$branch,'keywords'=>$keywords,'time'=>$keywords1]);

	}
	//公司的续签率
	public function company(){
		//获取所有公司
		$branch = db('branch_office')->select();
		//选择时间
		$keywords1 = input("get.keywords");
		if ($keywords1 == '') {
			//当月时间
			$time = mktime(0,0,0,date('m'),1,date('Y'));
			//下个月的时间
			$date2 = strtotime(date('Y-m-01 00:00:00',strtotime('1 month')));
			//下下个月的时间
			$date3 = strtotime(date('Y-m-01 00:00:00',strtotime('2 month')));
			//下下下个月的时间
			$date4 = strtotime(date('Y-m-01 00:00:00',strtotime('3 month')));
		}else{
			$asd = $keywords1 . '-01';
			$time = strtotime($asd);
			//下个月的时间
			$date2 = strtotime(date('Y-m-01 00:00:00',strtotime($asd . '1 month')));
			//下下个月的时间
			$date3 = strtotime(date('Y-m-01 00:00:00',strtotime($asd . '2 month')));
			//下下下个月的时间
			$date4 = strtotime(date('Y-m-01 00:00:00',strtotime($asd . '3 month')));
		}

		foreach ($branch as $key => $value) {
			//这个月的续签人数
			$data[$key]['Mx'] = db("achievement")->where("oid",$value['id'])->where("type",'续签')
				->where("yuantime","egt",$time)->where("yuantime","lt",$date2)->count();
			//下月的续签人数
			$data[$key]['M+1x'] = db("achievement")->where("oid",$value['id'])->where("type",'续签')
				->where("yuantime","egt",$date2)->where("yuantime","lt",$date3)->count();
			//下下月的续签人数
			$data[$key]['M+2x'] = db("achievement")->where("oid",$value['id'])->where("type",'续签')
				->where("yuantime","egt",$date3)->where("yuantime","lt",$date4)->count();
			//回签客户的人数
			$data[$key]['cliend'] = db("achievement")->where("oid",$value['id'])->where("type",'续签')
				->where("yuantime","lt",$time)
				->where("totime","egt",$time)
				->where("totime","lt",$date2)->count();

			$data[$key]['username'] = $value['name'];


			 // 获取分公司下所有员工id
			$user_id = db('user')->field('id')->where('branch_id',$value['id'])->where('department',3)->select();
			

			if (!empty($user_id)) {
				foreach ($user_id as $k => $v) {
					//当月的
					$zxc = db('customer')
						->alias('a')->join('silver_employee_customer w','a.id = w.cid')
						->where("a.expires","egt",$time)->where("a.expires","lt",$date2)
						->where('w.uid',$v['id'])
						->where('w.status',1)
						->count();
					$asd = db("achievement")->where("oid",$value['id'])->where("type",'续签')
							->where("yuantime","egt",$time)->where("yuantime","lt",$date2)->count();
					

					$arr[$key]['Mi'][$k] = $zxc + $asd;

					
					//下个月的
					$zxc1 = db('customer')
						->alias('a')->join('silver_employee_customer w','a.id = w.cid')
						->where("a.expires","egt",$date2)->where("a.expires","lt",$date3)
						->where('w.uid',$v['id'])
						->where('w.status',1)
						->count();
					$asd1 = db("achievement")->where("oid",$value['id'])->where("type",'续签')
								->where("yuantime","egt",$date2)->where("yuantime","lt",$date3)->count();

						$arr[$key]['M+1i'][$k] = $zxc1 + $asd1;
					//下下个月的
					$zxc2 = db('customer')
						->alias('a')->join('silver_employee_customer w','a.id = w.cid')
						->where("a.expires","egt",$date3)->where("a.expires","lt",$date4)
						->where('w.uid',$v['id'])
						->where('w.status',1)
						->count();
					$asd2 = db("achievement")->where("oid",$value['id'])->where("type",'续签')
							->where("yuantime","egt",$date3)->where("yuantime","lt",$date4)->count();

					$arr[$key]['M+2i'][$k] = $zxc2 + $asd2;

					// echo "<pre>";
					// print_r($arr[$key]['M+2i']);

					$data[$key]['Mi'] = array_sum($arr[$key]['Mi']);
					$data[$key]['M+1i'] = array_sum($arr[$key]['M+1i']);
					$data[$key]['M+2i'] = array_sum($arr[$key]['M+2i']);



				   
				}
				// exit();
			}else{
				$data[$key]['Mi'] = 0;
				$data[$key]['M+1i'] = 0;
				$data[$key]['M+2i'] = 0;

			}
			

		   


			 //续签率的计算
				foreach ($data as $key => $value) {

					if ($value['Mx']+$value['cliend'] == 0 && $value['Mi'] == 0) {
						$data[$key]['Ml'] = '0%';
					}else{

						if ($value['Mx']+$value['cliend'] != 0 && $value['Mi'] == 0) {
							$data[$key]['Ml'] = '错误';
						}else{
							$data[$key]['Ml'] = round(($value['Mx']+$value['cliend'])/$value['Mi']*100,2).'%';
						}
	
					}

					if ($value['M+1x'] == 0 && $value['M+1i'] == 0) {
						$data[$key]['M+1l'] = '0%';
					}else{

						if ($value['M+1x'] != 0 && $value['M+1i'] == 0) {
							 $data[$key]['M+1l'] = '错误';
						}else{
							$data[$key]['M+1l'] = round(($value['M+1x'])/$value['M+1i']*100,2).'%';
						}

						
					}

					if ($value['M+2x'] == 0 && $value['M+2i'] == 0) {
						$data[$key]['M+2l'] = '0%';
					}else{

						if ($value['M+2x'] != 0 && $value['M+2i'] == 0) {
							 $data[$key]['M+2l'] = '错误';
						}else{
							$data[$key]['M+2l'] = round(($value['M+2x'])/$value['M+2i']*100,2).'%';
						}

					}   	
				} 	
		}

		// echo "<pre>";
		// print_r($data);
		// exit();

		return $this->fetch('',['data'=>$data]);

	}
}

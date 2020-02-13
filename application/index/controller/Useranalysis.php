<?php 
namespace app\index\controller;

use think\db;
use \think\Request;
use app\index\model\Useranalysi;
use think\Session;
use app\index\model\Newcustomer;

class Useranalysis extends Common
{
	public function index()
	{
		$company = db('branch_office')->select();
		return $this->fetch('',['company'=>$company]);
	}

	public function userLists()
	{
		$request=Request::instance();
		$fid=$request->post('fid');
		$sta=new Useranalysi();
		$res=$sta->userLists($fid);
		if($res){
			$arr = array('code' =>200,'data'=>$res);
		}else{
			$arr = array('code' =>404,'msg'=>'修改失败！');
		}

		return returnmsg($arr);

	}

	public function sel()
	{
		$request=Request::instance();
		$date=$request->param();
		
		

		$sta=new Useranalysi();
		$res=$sta->sel1($date);
		
		
		
		foreach ($res as $key => $value) {
			if (array_key_exists('ali_app',$value)) {
				if ($value['ali_app'] != 1) {
					$res[$key]['app'] = '未安装';
				}else{
					$res[$key]['app'] = '已安装';
				}
				unset($res[$key]['ali_app']);
			}
			
			if (array_key_exists('strength_businessmen',$value)) {
				if ($value['strength_businessmen'] == 1) {
					$res[$key]['strength'] = '存在';
				}else{
					$res[$key]['strength'] = '不存在';
				}
				unset($res[$key]['strength_businessmen']);
			}
			
			if (array_key_exists('p4p',$value)) {
				if ($value['p4p'] == 1) {
					$res[$key]['p'] = '已签约';
				}else{
					$res[$key]['p'] = '未签约';
				}
				unset($res[$key]['p4p']);
			}

			if (array_key_exists('p4p_consume',$value)) {
				if ($value['p4p_consume'] == 1) {
					$res[$key]['p_consume'] = '是';
				}else{
					$res[$key]['p_consume'] = '否';
				}
				unset($res[$key]['p4p_consume']);
			}
				
			if (array_key_exists('buy_insurance',$value)) {
				if ($value['buy_insurance'] == 0) {
					$res[$key]['b_insurance'] = '空';
				}elseif($value['buy_insurance'] == 1){
					$res[$key]['b_insurance'] = '是';
				}elseif($value['buy_insurance'] == 2){
					$res[$key]['b_insurance'] = '否';
				}

				unset($res[$key]['buy_insurance']);
			}

			if (array_key_exists('current_level',$value)) {
				if ($value['current_level'] == 1) {
					$res[$key]['c_level'] = '核心层级';
				}elseif ($value['current_level'] == 2) {
					$res[$key]['c_level'] = '活跃商家';
				}else{
					$res[$key]['c_level'] = '潜力商家';
				}
				unset($res[$key]['current_level']);
			}
			if (array_key_exists('shop_status',$value)) {
				if ($value['shop_status'] == 1) {
					$res[$key]['s_status'] = '托管';
				}else{
					$res[$key]['s_status'] = '未托管';
				}
				unset($res[$key]['shop_status']);
			}

			if (array_key_exists('auto_activities',$value)) {
				if ($value['auto_activities'] == 1) {
					$res[$key]['a_activities'] = '自主参加的活动';
				}elseif ($value['auto_activities'] == 2) {
					$res[$key]['a_activities'] = '网站大促';
				}else{
					$res[$key]['a_activities'] = '未参加活动';
				}
				unset($res[$key]['auto_activities']);
			}

			if (array_key_exists('c_type',$value)) {
				if ($value['c_type'] == 1) {
					$res[$key]['c_t'] = '未过期';
				}else{
					$res[$key]['c_t'] = '已过期';
				}
				unset($res[$key]['c_type']);
			}

			if (array_key_exists('importance',$value)) {
				if ($value['importance'] == 1) {
					$res[$key]['impor'] = '一般';
				}elseif ($value['importance'] == 2) {
					$res[$key]['impor'] = '重视';
				}elseif ($value['importance'] == 3) {
					$res[$key]['impor'] = '非常重视';
				}else{
					$res[$key]['impor'] = '未知';
				}
				unset($res[$key]['importance']);
			}

			if (array_key_exists('public_number',$value)) {
				if ($value['public_number'] == 1) {
					$res[$key]['number'] = '已建设';
				}elseif ($value['public_number'] == 2) {
					$res[$key]['number'] = '未建设';
				}elseif ($value['public_number'] == 3) {
					$res[$key]['number'] = '已建设未托管';
				}elseif ($value['public_number'] == 4){
					$res[$key]['number'] = '已建设已托管';
				}else{
					$res[$key]['number'] = '空';
				}
				unset($res[$key]['public_number']);
			}


		}

		

		
	
			$a = array('aliname'=>'阿里用户名');
		
		
			$b = ['a_level'=>'A等级'];
			$header = $a + $b;

		
		if (!empty($date['products'])) {
			$c = ['products'=>'主营产品'];
			$header = $header + $c;
		}

		
		if (!empty($date['hangye'])) {
			$c = ['class_industry'=>'行业分类'];
			$header = $header + $c;
			
		}
		if (!empty($date['wp_score'])) {
			$c = ['wp_score'=>'旺铺评分'];
			$header = $header + $c;
			
		}
		// print_r($header);die();
		if (!empty($date['violation_record'])) {
			$c = ['violation_record'=>'违规记录'];
			$header = $header + $c;
			
		}
		if (!empty($date['five_offer'])) {
			$c = ['five_offer'=>'五星offer'];
			$header = $header + $c;
			
		}
		if (!empty($date['seven_offer'])) {
			$c = ['seven_offer'=>'七星offer'];
			$header = $header + $c;
			
		}
		if (!empty($date['corporate_name'])) {
			$c = ['corporate_name'=>'公司名称'];
			$header = $header + $c;
			
		}
		if (!empty($date['company_address'])) {
			$c = ['company_address'=>'公司地址'];
			$header = $header + $c;
			
		}
		if (!empty($date['company_personnel'])) {
			$c = ['num'=>'人员数量'];
			$header = $header + $c;
			
		}
		if (!empty($date['annual_profit'])) {
			$c = ['profit'=>'年营业额'];
			$header = $header + $c;
			
		}
		if (!empty($date['y_turnover'])) {
			$c = ['turnover'=>'上年营业额'];
			$header = $header + $c;
			
		}
		if (!empty($date['integrity_level'])) {
			$c = ['level'=>'企业诚信等级'];
			$header = $header + $c;
		}
		if (!empty($date['old_signsale'])) {
			$c = ['username'=>'原签单销售'];
			$header = $header + $c;
			
		}
		if (!empty($date['old_p4p'])) {
			$c = ['user'=>'原p4p客服'];
			$header = $header + $c;
			
		}
		if (!empty($date['old_p4pconsume'])) {
			$c = ['o_user'=>'p4p消耗客服'];
			$header = $header + $c;
			
		}
		if (!empty($date['brank'])) {
			$c = ['name'=>'所属分公司'];
			$header = $header + $c;
			
		}
		// if (!empty($date['user'])) {
		// 	$c = ['name'=>'所属分公司'];
		// 	$header = $header + $c;
		// 	array_push($header,'所属客服');
		// }
		if (!empty($date['ali_app'])) {
			$c = ['app'=>'阿里app'];
			$header = $header + $c;
			
		}
		if (!empty($date['strength_businessmen'])) {
			$c = ['strength'=>'是否实商'];
			$header = $header + $c;
			
		}
		if (!empty($date['p4p'])) {
			$c = ['p'=>'p4p'];
			$header = $header + $c;
			
		}
		if (!empty($date['p4p_consume'])) {
			$c = ['p_consume'=>'30天P4P消耗'];
			$header = $header + $c;
			
		}
		if (!empty($date['buy_insurance'])) {
			$c = ['b_insurance'=>'是否开通买宝'];
			$header = $header + $c;
			
		}
		if (!empty($date['current_level'])) {
			$c = ['c_level'=>'当前层级'];
			$header = $header + $c;
			
		}
		if (!empty($date['shop_status'])) {
			$c = ['s_status'=>'店铺托管状态'];
			$header = $header + $c;
			
		}
		if (!empty($date['auto_activities'])) {
			$c = ['a_activities'=>'参与的活动'];
			$header = $header + $c;
			
		}
		if (!empty($date['c_type'])) {
			$c = ['c_t'=>'是否过期'];
			$header = $header + $c;
			
		}
		if (!empty($date['importance'])) {
			$c = ['impor'=>'用户重视程度'];
			$header = $header + $c;
			
		}
		if (!empty($date['public_number'])) {
			$c = ['number'=>'公众号建设程度'];
			$header = $header + $c;
			
		}

		// print_r($header);die();
		$name = '用户体质分析面板';

		$Newcustomer = new Newcustomer();

		
		$sta->getExcel($name,$header,$res);

		

		
			


	}



	
}
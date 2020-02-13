<?php 
namespace app\index\model;

use think\Model;
use think\db;
class Useranalysi extends Model
{
	public function userLists($id)
	{
		$res=Db::name('user')->field('id,username')->where('branch_id',$id)->select();
		return $res;
	}



	public function sel1($data)
	{
		//$data['industry1'] 选择公司 $data['industry2']选择客服
		if ($data['industry1'] == 0) {
			//查询所有公司的
			$e_custome = 0;
		}else{
			if (empty($data['industry2'])) {
				$e_custome = $data['industry1'];
			}else{
				$e_custome = db('user')->field('id,username')->where(['id' => $data['industry2']])->find();
				
			}

		}


		

		$arr['aliname'] = $this->fie1($data['aliname'],$e_custome);
		
		if (!empty($data['a_level'])) {

			$arr['a_level'] = $this->fie1($data['a_level'],$e_custome);
			$asd = array_map('array_merge', $arr['aliname'], $arr['a_level']);
		}

		if (!empty($data['kefu'])) {
			$arr['kefu'] = $this->user($e_custome);

			$asd = array_map('array_merge', $asd, $arr['kefu']);
		
		}
		if (!empty($data['products'])) {
			$arr['products'] = $this->fie1($data['products'],$e_custome);

			$asd = array_map('array_merge', $asd, $arr['products']);
			
		}
		if (!empty($data['hangye'])) {
			$arr['hangye'] = $this->hangye($e_custome);

			$asd = array_map('array_merge', $asd, $arr['hangye']);
			
		}
		if (!empty($data['ali_app'])) {
			$arr['ali_app'] = $this->fie1($data['ali_app'],$e_custome);
			$asd = array_map('array_merge', $asd, $arr['ali_app']);
		
		}
		if (!empty($data['wp_score'])) {
			$arr['wp_score'] = $this->fie1($data['wp_score'],$e_custome);
			$asd = array_map('array_merge', $asd, $arr['wp_score']);
		
		}
		if (!empty($data['violation_record'])) {
			$arr['violation_record'] = $this->fie1($data['violation_record'],$e_custome);
			$asd = array_map('array_merge', $asd, $arr['violation_record']);
		
		}
		if (!empty($data['five_offer'])) {
			$arr['five_offer'] = $this->fie1($data['five_offer'],$e_custome);
			$asd = array_map('array_merge', $asd, $arr['five_offer']);
		
		}
		if (!empty($data['seven_offer'])) {
			$arr['seven_offer'] = $this->fie1($data['seven_offer'],$e_custome);
			$asd = array_map('array_merge', $asd, $arr['seven_offer']);
		
		}
		if (!empty($data['strength_businessmen'])) {
			$arr['strength_businessmen'] = $this->fie1($data['strength_businessmen'],$e_custome);
			$asd = array_map('array_merge', $asd, $arr['strength_businessmen']);
		
		}
		if (!empty($data['p4p'])) {
			$arr['p4p'] = $this->fie1($data['p4p'],$e_custome);
			$asd = array_map('array_merge', $asd, $arr['p4p']);
		
		}
		if (!empty($data['p4p_consume'])) {
			$arr['p4p_consume'] = $this->fie1($data['p4p_consume'],$e_custome);
			$asd = array_map('array_merge', $asd, $arr['p4p_consume']);
		
		}
		if (!empty($data['buy_insurance'])) {
			$arr['buy_insurance'] = $this->fie1($data['buy_insurance'],$e_custome);
			$asd = array_map('array_merge', $asd, $arr['buy_insurance']);
		
		}
		if (!empty($data['current_level'])) {
			$arr['current_level'] = $this->fie1($data['current_level'],$e_custome);
			$asd = array_map('array_merge', $asd, $arr['current_level']);
		
		}
		if (!empty($data['shop_status'])) {
			$arr['shop_status'] = $this->fie1($data['shop_status'],$e_custome);
			$asd = array_map('array_merge', $asd, $arr['shop_status']);
		
		}
		if (!empty($data['auto_activities'])) {
			$arr['auto_activities'] = $this->fie1($data['auto_activities'],$e_custome);
			$asd = array_map('array_merge', $asd, $arr['auto_activities']);
		
		}
		if (!empty($data['c_type'])) {
			$arr['c_type'] = $this->fie1($data['c_type'],$e_custome);
			$asd = array_map('array_merge', $asd, $arr['c_type']);
		
		}
		if (!empty($data['corporate_name'])) {
			$arr['corporate_name'] = $this->fie1($data['corporate_name'],$e_custome);
			$asd = array_map('array_merge', $asd, $arr['corporate_name']);
		
		}
		if (!empty($data['company_address'])) {
			$arr['company_address'] = $this->fie1($data['company_address'],$e_custome);
			$asd = array_map('array_merge', $asd, $arr['company_address']);
		
		}
		if (!empty($data['company_personnel'])) {
			$arr['company_personnel'] = $this->company_personnel($e_custome);
			$asd = array_map('array_merge', $asd, $arr['company_personnel']);
		
		}
		if (!empty($data['importance'])) {
			$arr['importance'] = $this->fie1($data['importance'],$e_custome);
			$asd = array_map('array_merge', $asd, $arr['importance']);
		
		}
		if (!empty($data['annual_profit'])) {
			$arr['annual_profit'] = $this->annual_profit($e_custome);
			$asd = array_map('array_merge', $asd, $arr['annual_profit']);
		
		}
		if (!empty($data['y_turnover'])) {
			$arr['y_turnover'] = $this->y_turnover($e_custome);
			$asd = array_map('array_merge', $asd, $arr['y_turnover']);
		
		}
		if (!empty($data['public_number'])) {
			$arr['public_number'] = $this->fie1($data['public_number'],$e_custome);
			$asd = array_map('array_merge', $asd, $arr['public_number']);
		
		}
		if (!empty($data['integrity_level'])) {
			$arr['integrity_level'] = $this->integrity_level($e_custome);
			$asd = array_map('array_merge', $asd, $arr['integrity_level']);
		
		}
		if (!empty($data['old_signsale'])) {
			$arr['old_signsale'] = $this->old_signsale($e_custome);
			$asd = array_map('array_merge', $asd, $arr['old_signsale']);
		
		}
		if (!empty($data['old_p4p'])) {
			$arr['old_p4p'] = $this->old_p4p($e_custome);
			$asd = array_map('array_merge', $asd, $arr['old_p4p']);
		
		}
		if (!empty($data['old_p4pconsume'])) {
			$arr['old_p4pconsume'] = $this->old_p4pconsume($e_custome);
			$asd = array_map('array_merge', $asd, $arr['old_p4pconsume']);
		
		}
		if (!empty($data['brank'])) {
			$arr['brank'] = $this->brank($e_custome);

			$asd = array_map('array_merge', $asd, $arr['brank']);
		
		}
		
		

		
		return $asd;




	}


	public function fie1($data,$e_custome)
	{
		

		$data1 = 'c.'.$data;
		if ($e_custome == 0) {
			$arr = db('customer')
					->alias('c')
					->join('silver_relation_company r','r.cid = c.id')
					->field($data1)
					->where(['r.status'=>1])
					->select();
		}else{
			if (empty($e_custome['id'])) {
				$arr = db('customer')
					->alias('c')
					->join('silver_relation_company r','r.cid = c.id')
					->field($data1)
					->where(['r.oid' => $e_custome])
					->where(['r.status'=>1])
					->select();
					
					
			}else{
				$arr = db('employee_customer')
					->alias('r')
					->join('silver_customer c','r.cid = c.id')
					->where(['r.uid' => $e_custome['id']])
					->where(['r.status'=>1])
					->field($data1)
					->select();

					
			}
		}
		return $arr;

	}

	public function hangye($e_custome)
	{
		
		if ($e_custome == 0) {
			$arr = db('customer')
					->alias('c')
					->join('silver_relation_company r','r.cid = c.id')
					->field('c.industry1')
					->where(['r.status'=>1])
					->select();
			foreach ($arr as $key => $value) {
				if ($value['industry1'] == "") {
					$asd[$key] = ['class_industry'=>'无'];
				}else{
					$asd[$key] = db('industry')->field('class_industry')->where('id',$value['industry1'])->find();
				}
			}
			
		}else{
			if (empty($e_custome['id'])) {
				$arr = db('customer')
					->alias('c')
					->join('silver_relation_company r','r.cid = c.id')					
					->field('c.industry1')
					->where(['r.oid' => $e_custome])
					->where(['r.status'=>1])
					->select();
			foreach ($arr as $key => $value) {
				if ($value['industry1'] == "") {
					$asd[$key] = ['class_industry'=>'无'];
				}else{
					$asd[$key] = db('industry')->field('class_industry')->where('id',$value['industry1'])->find();
				}
			}
			}else{
				$arr = db('customer')
					->alias('c')
					->join('silver_employee_customer r','r.cid = c.id')
					->field('c.industry1')
					->where(['r.uid' => $e_custome['id']])
					->where(['r.status'=>1])
					->select();
				foreach ($arr as $key => $value) {
					if ($value['industry1'] == "") {
						$asd[$key] = ['class_industry'=>'无'];
					}else{
						$asd[$key] = db('industry')->field('class_industry')->where('id',$value['industry1'])->find();
					}
				}
			}
		}
		
		return $asd;

	}

	public function company_personnel($e_custome)
	{
		if ($e_custome == 0) {
			$arr = db('customer')
					->alias('c')
					->join('silver_relation_company r','r.cid = c.id')
					->field('c.company_personnel')
					->where(['r.status'=>1])
					->select();
			foreach ($arr as $key => $value) {
				if ($value['company_personnel'] == "") {
					$asd[$key] = ['num'=>'无'];
				}else{
					$asd[$key] = db('e_num')->field('num')->where('id',$value['company_personnel'])->find();
				}
			}
			
		}else{
			if (empty($e_custome['id'])) {
				$arr = db('customer')
					->alias('c')
					->join('silver_relation_company r','r.cid = c.id')					
					->field('c.company_personnel')
					->where(['r.oid' => $e_custome])
					->where(['r.status'=>1])
					->select();
			foreach ($arr as $key => $value) {
				if ($value['company_personnel'] == "") {
					$asd[$key] = ['num'=>'无'];
				}else{
					$asd[$key] = db('e_num')->field('num')->where('id',$value['company_personnel'])->find();
				}
			}
			}else{
				$arr = db('customer')
					->alias('c')
					->join('silver_employee_customer r','r.cid = c.id')
					->field('c.company_personnel')
					->where(['r.uid' => $e_custome['id']])
					->where(['r.status'=>1])
					->select();
				foreach ($arr as $key => $value) {
					if ($value['company_personnel'] == "") {
						$asd[$key] = ['num'=>'无'];
					}else{
						$asd[$key] = db('e_num')->field('num')->where('id',$value['company_personnel'])->find();
					}
				}
			}
		}
		
		return $asd;

	}

	public function annual_profit($e_custome)
	{
		if ($e_custome == 0) {
			$arr = db('customer')
					->alias('c')
					->join('silver_relation_company r','r.cid = c.id')
					->field('c.annual_profit')
					->where(['r.status'=>1])
					->select();
			foreach ($arr as $key => $value) {
				if ($value['annual_profit'] == "") {
					$asd[$key] = ['profit'=>'无'];
				}else{
					$asd[$key] = db('profit')->field('profit')->where('id',$value['annual_profit'])->find();
				}
			}
			
		}else{
			if (empty($e_custome['id'])) {
				$arr = db('customer')
					->alias('c')
					->join('silver_relation_company r','r.cid = c.id')					
					->field('c.annual_profit')
					->where(['r.oid' => $e_custome])
					->where(['r.status'=>1])
					->select();
				foreach ($arr as $key => $value) {
					if ($value['annual_profit'] == "") {
						$asd[$key] = ['profit'=>'无'];
					}else{
						$asd[$key] = db('profit')->field('profit')->where('id',$value['annual_profit'])->find();
					}
				}
			}else{
				$arr = db('customer')
					->alias('c')
					->join('silver_employee_customer r','r.cid = c.id')
					->field('c.annual_profit')
					->where(['r.uid' => $e_custome['id']])
					->where(['r.status'=>1])
					->select();
				foreach ($arr as $key => $value) {
					if ($value['annual_profit'] == "") {
						$asd[$key] = ['profit'=>'无'];
					}else{
						$asd[$key] = db('profit')->field('profit')->where('id',$value['annual_profit'])->find();
					}
				}
			}
		}
		
		return $asd;

	}

	public function y_turnover($e_custome)
	{
		if ($e_custome == 0) {
			$arr = db('customer')
					->alias('c')
					->join('silver_relation_company r','r.cid = c.id')
					->field('c.y_turnover')
					->where(['r.status'=>1])
					->select();
			foreach ($arr as $key => $value) {
				if ($value['y_turnover'] == "") {
					$asd[$key] = ['turnover'=>'无'];
				}else{
					$asd[$key] = db('turnover')->field('turnover')->where('id',$value['y_turnover'])->find();
				}
			}
			
		}else{
			if (empty($e_custome['id'])) {
				$arr = db('customer')
					->alias('c')
					->join('silver_relation_company r','r.cid = c.id')					
					->field('c.y_turnover')
					->where(['r.oid' => $e_custome])
					->where(['r.status'=>1])
					->select();
				foreach ($arr as $key => $value) {
					if ($value['y_turnover'] == "") {
						$asd[$key] = ['turnover'=>'无'];
					}else{
						$asd[$key] = db('turnover')->field('turnover')->where('id',$value['y_turnover'])->find();
					}
				}
			}else{
				$arr = db('customer')
					->alias('c')
					->join('silver_employee_customer r','r.cid = c.id')
					->field('c.y_turnover')
					->where(['r.uid' => $e_custome['id']])
					->where(['r.status'=>1])
					->select();
				foreach ($arr as $key => $value) {
					if ($value['y_turnover'] == "") {
						$asd[$key] = ['turnover'=>'无'];
					}else{
						$asd[$key] = db('turnover')->field('turnover')->where('id',$value['y_turnover'])->find();
					}
				}
			}
		}
		
		return $asd;
	}

	public function integrity_level($e_custome)
	{
		if ($e_custome == 0) {
			$arr = db('customer')
					->alias('c')
					->join('silver_relation_company r','r.cid = c.id')
					->field('c.integrity_level')
					->where(['r.status'=>1])
					->select();
			foreach ($arr as $key => $value) {
				if ($value['integrity_level'] == "") {
					$asd[$key] = ['level'=>'无'];
				}else{
					$asd[$key] = db('sincerity')->field('level')->where('id',$value['integrity_level'])->find();
				}
			}
			
		}else{
			if (empty($e_custome['id'])) {
				$arr = db('customer')
					->alias('c')
					->join('silver_relation_company r','r.cid = c.id')					
					->field('c.integrity_level')
					->where(['r.oid' => $e_custome])
					->where(['r.status'=>1])
					->select();
				foreach ($arr as $key => $value) {
					if ($value['integrity_level'] == "") {
						$asd[$key] = ['level'=>'无'];
					}else{
						$asd[$key] = db('sincerity')->field('level')->where('id',$value['integrity_level'])->find();
					}
				}
			}else{
				$arr = db('customer')
					->alias('c')
					->join('silver_employee_customer r','r.cid = c.id')
					->field('c.integrity_level')
					->where(['r.uid' => $e_custome['id']])
					->where(['r.status'=>1])
					->select();
				foreach ($arr as $key => $value) {
					if ($value['integrity_level'] == "") {
						$asd[$key] = ['level'=>'无'];
					}else{
						$asd[$key] = db('sincerity')->field('level')->where('id',$value['integrity_level'])->find();
					}
				}
			}
		}
		
		return $asd;
	}

	public function old_signsale($e_custome)
	{
		if ($e_custome == 0) {
			$arr = db('customer')
					->alias('c')
					->join('silver_relation_company r','r.cid = c.id')
					->field('c.old_signsale')
					->where(['r.status'=>1])
					->select();
			foreach ($arr as $key => $value) {
				if (empty($value['old_signsale'])) {
					$asd[$key] = ['username'=>'无'];
				}else{
					$asd[$key] = db('user')->field('username')->where('id',$value['old_signsale'])->find();
				}
			}
			
		}else{
			if (empty($e_custome['id'])) {
				$arr = db('customer')
					->alias('c')
					->join('silver_relation_company r','r.cid = c.id')					
					->field('c.old_signsale')
					->where(['r.oid' => $e_custome])
					->where(['r.status'=>1])
					->select();
				foreach ($arr as $key => $value) {
					if (empty($value['old_signsale'])) {
						$asd[$key] = ['username'=>'无'];
					}else{
						$asd[$key] = db('user')->field('username')->where('id',$value['old_signsale'])->find();
					}
				}
			}else{
				$arr = db('customer')
					->alias('c')
					->join('silver_employee_customer r','r.cid = c.id')
					->field('c.old_signsale')
					->where(['r.uid' => $e_custome['id']])
					->where(['r.status'=>1])
					->select();
				foreach ($arr as $key => $value) {
					if (empty($value['old_signsale'])) {
						$asd[$key] = ['username'=>'无'];
					}else{
						$asd[$key] = db('user')->field('username')->where('id',$value['old_signsale'])->find();
					}
				}
			}
		}
		
		return $asd;

	}

	public function old_p4p($e_custome)
	{


		if ($e_custome == 0) {
			$arr = db('customer')
					->alias('c')
					->join('silver_relation_company r','r.cid = c.id')
					->field('c.old_p4p')
					->where(['r.status'=>1])
					->select();
			foreach ($arr as $key => $value) {
				if (empty($value['old_p4p'])) {
					$asd[$key] = ['user'=>'无'];
				}else{
					$zxc = db('user')->field('username')->where('job_num',$value['old_p4p'])->find();
						$asd[$key]['user'] = $zxc['username'];
				}
			}
			
		}else{
			if (empty($e_custome['id'])) {
				$arr = db('customer')
					->alias('c')
					->join('silver_relation_company r','r.cid = c.id')					
					->field('c.old_p4p')
					->where(['r.oid' => $e_custome])
					->where(['r.status'=>1])
					->select();
				foreach ($arr as $key => $value) {
					if (empty($value['old_p4p'])) {
						$asd[$key] = ['user'=>'无'];
					}else{
						$zxc = db('user')->field('username')->where('job_num',$value['old_p4p'])->find();
						$asd[$key]['user'] = $zxc['username'];
					}
				}
			}else{
				$arr = db('customer')
					->alias('c')
					->join('silver_employee_customer r','r.cid = c.id')
					->field('c.old_p4p')
					->where(['r.uid' => $e_custome['id']])
					->where(['r.status'=>1])
					->select();


				foreach ($arr as $key => $value) {
					if (empty($value['old_p4p'])) {
						$asd[$key] = ['user'=>'无'];

					}else{
						
						$zxc = db('user')->field('username')->where('job_num',$value['old_p4p'])->find();
						$asd[$key]['user'] = $zxc['username'];
					}
				}
			}
		}
		
		return $asd;
	}

	public function old_p4pconsume($e_custome)
	{


		if ($e_custome == 0) {
			$arr = db('customer')
					->alias('c')
					->join('silver_relation_company r','r.cid = c.id')
					->field('c.old_p4pconsume')
					->where(['r.status'=>1])
					->select();
			foreach ($arr as $key => $value) {
				if (empty($value['old_p4pconsume'])) {
					$asd[$key] = ['o_user'=>'无'];
				}else{
					$zxc = db('user')->field('username')->where('job_num',$value['old_p4pconsume'])->find();
						$asd[$key]['o_user'] = $zxc['username'];
				}
			}
			
		}else{
			if (empty($e_custome['id'])) {
				$arr = db('customer')
					->alias('c')
					->join('silver_relation_company r','r.cid = c.id')					
					->field('c.old_p4pconsume')
					->where(['r.oid' => $e_custome])
					->where(['r.status'=>1])
					->select();
				foreach ($arr as $key => $value) {
					if (empty($value['old_p4pconsume'])) {
						$asd[$key] = ['o_user'=>'无'];
					}else{
						$zxc = db('user')->field('username')->where('job_num',$value['old_p4pconsume'])->find();
						$asd[$key]['o_user'] = $zxc['username'];
					}
				}
			}else{
				$arr = db('customer')
					->alias('c')
					->join('silver_employee_customer r','r.cid = c.id')
					->field('c.old_p4pconsume')
					->where(['r.uid' => $e_custome['id']])
					->where(['r.status'=>1])
					->select();


				foreach ($arr as $key => $value) {
					if (empty($value['old_p4pconsume'])) {
						$asd[$key] = ['o_user'=>'无'];

					}else{
						
						$zxc = db('user')->field('username')->where('job_num',$value['old_p4pconsume'])->find();
						$asd[$key]['o_user'] = $zxc['username'];
					}
				}
			}
		}
		
		return $asd;
	}


	public function brank($e_custome)
	{
		if ($e_custome == 0) {
			$arr = db('customer')
					->alias('c')
					->join('silver_relation_company r','r.cid = c.id')
					->field('r.oid')
					->where(['r.status'=>1])
					->select();
			foreach ($arr as $key => $value) {
				if (empty($value['oid'])) {
					$asd[$key] = ['name'=>'无'];
				}else{
					$asd[$key] = db('branch_office')->field('name')->where('id',$value['oid'])->find();
						
				}
			}
			
		}else{
			if (empty($e_custome['id'])) {
				$arr = db('customer')
					->alias('c')
					->join('silver_relation_company r','r.cid = c.id')
					->field('r.oid')
					->where(['r.oid' => $e_custome])
					->where(['r.status'=>1])
					->select();

				foreach ($arr as $key => $value) {
					if (empty($value['oid'])) {
						$asd[$key] = ['name'=>'无'];
					}else{
						$asd[$key] = db('branch_office')->field('name')->where('id',$value['oid'])->find();
							
					}
				}
			}else{
				$arr = db('customer')
					->alias('c')
					->join('silver_employee_customer r','r.cid = c.id')
					->field('r.uid')
					->where(['r.uid' => $e_custome['id']])
					->where(['r.status'=>1])
					->select();


				foreach ($arr as $key => $value) {
					if (empty($value['uid'])) {
						$asd[$key] = ['name'=>'无'];

					}else{
						
						$zxc = db('user')->field('branch_id')->where('id',$value['uid'])->find();
						$asd[$key] = db('branch_office')->field('name')->where('id',$zxc['branch_id'])->find();
						
					}
				}
			}
		}

		
		
		return $asd;
	}


	// public function user($e_custome)
	// {
	// 	if ($e_custome == 0) {
	// 		$arr = db('customer')->alias('c')->join('silver_employee_customer o','o.cid = c.id')->where('o.status',1)->field('o.uid')->select();

			
	// 	}else{
	// 		if (empty($e_custome['id'])) {
	// 			$arr = db('customer')
	// 				->alias('c')
	// 				->join('silver_relation_company r','r.cid = c.id')
	// 				->join('silver_employee_customer o','o.cid = c.id')
	// 				->field('o.uid')
	// 				->where(['r.oid' => $e_custome])
	// 				->where(['r.status'=>1])
	// 				->where(['o.status'=>1])
	// 				->select();

	// 			foreach ($arr as $key => $value) {
	// 				if (empty($value['uid'])) {
	// 					$asd[$key] = ['name1'=>'无'];
	// 				}else{
	// 					$zxc = db('user')->field('username')->where('id',$value['uid'])->find();
	// 					$asd[$key]['name1'] = $zxc['username'];
							
	// 				}
	// 			}


	// 		}else{
	// 			$arr = db('customer')
	// 				->alias('c')
	// 				->join('silver_employee_customer r','r.cid = c.id')
	// 				->field('r.uid')
	// 				->where(['r.uid' => $e_custome['id']])
	// 				->where(['r.status'=>1])
	// 				->select();


	// 			foreach ($arr as $key => $value) {
	// 				if (empty($value['uid'])) {
	// 					$asd[$key] = ['name1'=>'无'];

	// 				}else{
						
	// 					$zxc = db('user')->field('username')->where('id',$value['uid'])->find();
	// 					$asd[$key]['name1'] = $zxc['username'];
						
	// 				}
	// 			}
	// 		}
	// 	}


		
		
	// 	return $asd;
	// }
	// 
	
	function getExcel($filename, $tileArray=[], $dataArray=[]){
		ini_set('memory_limit','512M');
	    ini_set('max_execution_time',0);
	    ob_end_clean();
	    ob_start();
	    header("Content-Type: text/csv");
	    header("Content-Disposition:filename=".$filename.".xls");
	    $fp=fopen('php://output','w');
	    fwrite($fp, chr(0xEF).chr(0xBB).chr(0xBF));//转码 防止乱码(比如微信昵称(乱七八糟的))
	    fputcsv($fp,$tileArray);
	    $index = 0;
	    foreach ($dataArray as $item) {
	        if($index==1000){
	            $index=0;
	            ob_flush();
	            flush();
	        }
	        $index++;
	        fputcsv($fp,$item);
	    }
	 
	    ob_flush();
	    flush();
	    ob_end_clean();


	}

}
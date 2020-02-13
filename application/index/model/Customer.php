<?php
namespace app\index\model;
use think\Model;
use think\db;
use think\Session;
use app\index\model\Service;
/**
* 
*/
class Customer extends Model
{
	//查询客户
	public function customer($data,$type,$userid)
	{


		//分公司客户列表
		$user=Session::get('user');
		//用户级别
		$rank=$user['rank'];


		//用户id
		$uid=$user['id'];
		//用户公司
		$time=time();
		$branch=$user['branch_id'];

		
		if($rank==5 || $rank == 14){

			if($data['corporate_name'] || $data['start']){
					if($data['corporate_name']){
						$page=Db::name('customer')->alias('c')->join('employee_customer e','c.id=e.cid')->field('c.*')->where('e.uid',$uid)->where('c.corporate_name|c.aliname', 'like', "%".$data['corporate_name']."%")->where('e.status',1)->order('expires asc')->paginate(100,false,['query' => request()->param()]);
					}elseif($data['start']){
						$page=Db::name('customer')->alias('c')->join('employee_customer e','c.id=e.cid')->field('c.*')->where('e.uid',$uid)->where('c.expires','between time',[$data['start'],$data['end']])->where('e.status',1)->order('expires asc')->paginate(100,false,['query' => request()->param()]);
					}
				}elseif($type){
					if($type==3){
						$page=Db::name('customer')->alias('c')->join('employee_customer e','c.id=e.cid')->field('c.*')->where('e.uid',$uid)->where("expires",'<',$time)->where('e.status',1)->order('expires asc')->paginate(100,false,['query' => request()->param()]);
					}elseif($type==4){
						$page=Db::name('customer')->alias('c')->join('employee_customer e','c.id=e.cid')->field('c.*')->where('e.uid',$uid)->where("expires",'>',$time)->where('e.status',1)->order('expires asc')->paginate(100,false,['query' => request()->param()]);
					}else{

						$page=Db::name('customer')->alias('c')->join('employee_customer e','c.id=e.cid')->field('c.*')->where('e.uid',$uid)->where("relation_status",$type)->where('e.status',1)->order('expires asc')->paginate(100,false,['query' => request()->param()]);
					}
				}else{
					$page=Db::name('customer')->alias('c')->join('employee_customer e','c.id=e.cid')->field('c.*')->where('e.uid',$uid)->where('e.status',1)->order('expires asc')->paginate(100,false,['query' => request()->param()]);
			}
		}elseif($rank==1 || $rank==2 || $rank==3 || $rank ==12 ||$rank== 13){

			if($data['corporate_name'] || $data['start'] || $userid){

				if ($data['corporate_name'] && $data['start'] && $userid) {
					$page=Db::name('customer')
							->alias('c')
							->join('employee_customer u','c.id=u.cid','LEFT')
							->join('relation_company r','c.id=r.cid')
							->field('c.*')
							->where('c.expires','between time',[$data['start'],$data['end']])
							->where('r.status',1)->where('r.oid',$branch)
							->where('c.corporate_name|c.aliname', 'like', "%".$data['corporate_name']."%")
							->where('u.uid',$userid)
							->order('expires asc')
							->paginate(100,false,['query' => request()->param()]);
				}else{
					if ($data['corporate_name'] && $data['start']) {
						$page=Db::name('customer')->alias('c')->join('relation_company r','c.id=r.cid')->field('c.*')->where('r.status',1)->where('r.oid',$branch)->where('c.expires','between time',[$data['start'],$data['end']])->where('c.corporate_name|c.aliname', 'like', "%".$data['corporate_name']."%")->order('expires asc')->paginate(100,false,['query' => request()->param()]);
						
					}elseif ($data['corporate_name'] && $userid) {

						$page=Db::name('customer')->alias('c')->join('employee_customer u','c.id=u.cid','LEFT')->join('relation_company r','c.id=r.cid')->field('c.*')->where('r.status',1)->where('r.oid',$branch)->where('c.corporate_name|c.aliname', 'like', "%".$data['corporate_name']."%")->where('u.uid',$userid)->order('expires asc')->paginate(100,false,['query' => request()->param()]);

					}elseif($userid && $data['start']){

						$page=Db::name('customer')->alias('c')->join('employee_customer u','c.id=u.cid','LEFT')->join('relation_company r','c.id=r.cid')->field('c.*')->where('r.status',1)->where('r.oid',$branch)->where('c.expires','between time',[$data['start'],$data['end']])->where('u.uid',$userid)->order('expires asc')->paginate(100,false,['query' => request()->param()]);


					}else{
						if($data['corporate_name']){
							$page=Db::name('customer')->alias('c')->join('relation_company r','c.id=r.cid')->field('c.*')->where('r.status',1)->where('r.oid',$branch)->where('c.corporate_name|c.aliname', 'like', "%".$data['corporate_name']."%")->order('expires asc')->paginate(100,false,['query' => request()->param()]);
						}elseif($data['start']){
							$page=Db::name('customer')->alias('c')->join('relation_company r','c.id=r.cid')->field('c.*')->where('r.status',1)->where('r.oid',$branch)->where('c.expires','between time',[$data['start'],$data['end']])->order('expires asc')->paginate(100,false,['query' => request()->param()]);
						}elseif($userid){
							$page=Db::name("customer")->alias('a')->join('employee_customer u','a.id=u.cid','LEFT')->field('a.*')->where('u.uid',$userid)->where('u.status',1)->paginate(100,false,['query' => request()->param()]);
						}
					}
				}
				

			}elseif($type){

					if($type==3){
						$page=Db::name('customer')->alias('c')->join('relation_company r','c.id=r.cid')->field('c.*')->where('r.status',1)->where('r.oid',$branch)->where("c.expires",'<',$time)->order('expires asc')->paginate(100,false,['query' => request()->param()]);
					}elseif($type==4){
						$page=Db::name('customer')->alias('c')->join('relation_company r','c.id=r.cid')->field('c.*')->where('r.status',1)->where('r.oid',$branch)->where("c.expires",'>',$time)->order('expires asc')->paginate(100,false,['query' => request()->param()]);
					}else{
						$page=Db::name('customer')->alias('c')->join('relation_company r','c.id=r.cid')->field('c.*')->where('r.status',1)->where('r.oid',$branch)->where("c.c_status",$type)->order('expires asc')->paginate(100,false,['query' => request()->param()]);
						// var_dump($page);
					}
				}else{

					$page=Db::name('customer')
								->alias('c')
								->join('relation_company r','c.id=r.cid')
								->field('c.*')->where('r.status',1)
								->where('r.oid',$branch)
								->order('expires asc')
								->paginate(100,false,['query' => request()->param()]);
			}



		}else{


			if($data['corporate_name'] || $data['start'] || $userid){

					if($data['corporate_name']){
						$page=Db::name('customer')->where('corporate_name|aliname','like',"%".$data['corporate_name']."%")->paginate(100,false,['query'=>request()->param()]);
					}elseif($data['start']){
						$page=Db::name('customer')->where('expires','between time',[$data['start'],$data['end']])->paginate(100,false,['query' => request()->param()]);
					}elseif($userid){
						$page=Db::name("customer")->alias('a')->join('silver_employee_customer u','a.id=u.cid','LEFT')->field('a.*')->where('u.uid',$userid)->where('u.status',1)->paginate(100,false,['query' => request()->param()]);
					}
				}elseif($type){

					if($type==3){
						$page=Db::name('customer')->where("expires",'<',$time)->order('expires asc')->paginate(100,false,['query' => request()->param()]);
					}elseif($type==4){
						$page=Db::name('customer')->where("expires",'>',$time)->order('expires asc')->paginate(100,false,['query' => request()->param()]);
					}else{
						$page=Db::name('customer')->where("relation_status",$type)->order('expires asc')->paginate(100,false,['query' => request()->param()]);
					}
				}else{
					
					$page=Db::name('customer')
								->order('expires asc')
								->paginate(100,false,['query' => request()->param()]);

			}


		}
		
		return $page;
	}
	//续签员工网销宝订单添加
    public  function wadd($id){
        $customer=Db::name('customer')
            ->where('id',$id)
            ->find();
        return $customer;
    }
    public function bran($id){
	    $branch_name=Db::name('branch_office')
            ->where('id',$id)
            ->value('name');
	    return $branch_name;
    }
    public function wadd_do($pram){
	    $pram['time']=strtotime($pram['time']);
	    $pram['type']=2;
	    $pram['date']=date("Y-m-d",time());
	    $pram['user_id']=Session::get('user.id');
	    $pram['branch_id']=Session::get('user.branch_id');
	    $res=Db::name('renewal')
            ->insert($pram);
	    if ($res){
	        $up['w_sta']=2;
	        Db::name('customer')->where('id',$pram['did'])->update($up);
	        return 1;
        }else{
	        return 0;
        }

    }


	// //查询客户
	// public function customer($data,$type)
	// {
	// 	//分公司客户列表
	// 	$user=Session::get('user');
	// 	//用户级别
	// 	$rank=$user['rank'];
	// 	//用户id
	// 	$uid=$user['id'];
	// 	//用户公司
	// 	$time=time();
	// 	$branch=$user['branch_id'];
	// 	if($rank==5){
	// 		if($data['corporate_name'] || $data['start']){
	// 				if($data['corporate_name']){
	// 					$page=Db::name('customer')->alias('c')->join('employee_customer e','c.id=e.cid')->field('c.*')->where('e.uid',$uid)->where('c.corporate_name|c.aliname', 'like', "%".$data['corporate_name']."%")->order('expires asc')->paginate(100,false,['query' => request()->param()]);
	// 				}elseif($data['start']){
	// 					$page=Db::name('customer')->alias('c')->join('employee_customer e','c.id=e.cid')->field('c.*')->where('e.uid',$uid)->where('c.expires','between time',[$data['start'],$data['end']])->order('expires asc')->paginate(100,false,['query' => request()->param()]);
	// 				}
	// 			}elseif($type){
	// 				if($type==3){
	// 					$page=Db::name('customer')->alias('c')->join('employee_customer e','c.id=e.cid')->field('c.*')->where('e.uid',$uid)->where("expires",'<',$time)->order('expires asc')->paginate(100,false,['query' => request()->param()]);
	// 				}elseif($type==4){
	// 					$page=Db::name('customer')->alias('c')->join('employee_customer e','c.id=e.cid')->field('c.*')->where('e.uid',$uid)->where("expires",'>',$time)->order('expires asc')->paginate(100,false,['query' => request()->param()]);
	// 				}else{

	// 					$page=Db::name('customer')->alias('c')->join('employee_customer e','c.id=e.cid')->field('c.*')->where('e.uid',$uid)->where("relation_status",$type)->order('expires asc')->paginate(100,false,['query' => request()->param()]);
	// 				}
	// 			}else{
	// 				$page=Db::name('customer')->alias('c')->join('employee_customer e','c.id=e.cid')->field('c.*')->where('e.uid',$uid)->order('expires asc')->paginate(100,false,['query' => request()->param()]);
	// 		}
	// 	}elseif($rank==1 || $rank==2 || $rank==3){
	// 		if($data['corporate_name'] || $data['start']){
	// 			if($data['corporate_name']){
	// 				$page=Db::name('customer')->alias('c')->join('relation_company r','c.id=r.cid')->field('c.*')->where('r.status',1)->where('r.oid',$branch)->where('c.corporate_name|c.aliname', 'like', "%".$data['corporate_name']."%")->order('expires asc')->paginate(100,false,['query' => request()->param()]);
	// 			}elseif($data['start']){
	// 				$page=Db::name('customer')->alias('c')->join('relation_company r','c.id=r.cid')->field('c.*')->where('r.status',1)->where('r.oid',$branch)->where('c.expires','between time',[$data['start'],$data['end']])->order('expires asc')->paginate(100,false,['query' => request()->param()]);
	// 			}
	// 			}elseif($type){
	// 				if($type==3){
	// 					$page=Db::name('customer')->alias('c')->join('relation_company r','c.id=r.cid')->field('c.*')->where('r.status',1)->where('r.oid',$branch)->where("c.expires",'<',$time)->order('expires asc')->paginate(100,false,['query' => request()->param()]);
	// 				}elseif($type==4){
	// 					$page=Db::name('customer')->alias('c')->join('relation_company r','c.id=r.cid')->field('c.*')->where('r.status',1)->where('r.oid',$branch)->where("c.expires",'>',$time)->order('expires asc')->paginate(100,false,['query' => request()->param()]);
	// 				}else{
	// 					$page=Db::name('customer')->alias('c')->join('relation_company r','c.id=r.cid')->field('c.*')->where('r.status',1)->where('r.oid',$branch)->where("c.c_status",$type)->order('expires asc')->paginate(100,false,['query' => request()->param()]);
	// 					// var_dump($page);
	// 				}
	// 			}else{
	// 				$page=Db::name('customer')->alias('c')->join('relation_company r','c.id=r.cid')->field('c.*')->where('r.status',1)->where('r.oid',$branch)->order('expires asc')->paginate(100,false,['query' => request()->param()]);
	// 		}
	// 	}else{
	// 		if($data['corporate_name'] || $data['start']){
	// 			if($data['corporate_name']){
	// 				$page=Db::name('customer')->where('corporate_name|aliname','like',"%".$data['corporate_name']."%")->paginate(100,false,['query'=>request()->param()]);
	// 			}elseif($data['start']){
	// 				$page=Db::name('customer')->where('expires','between time',[$data['start'],$data['end']])->paginate(100,false,['query' => request()->param()]);
	// 			}
	// 			}elseif($type){
	// 				if($type==3){
	// 					$page=Db::name('customer')->where("expires",'<',$time)->order('expires asc')->paginate(100,false,['query' => request()->param()]);
	// 				}elseif($type==4){
	// 					$page=Db::name('customer')->where("expires",'>',$time)->order('expires asc')->paginate(100,false,['query' => request()->param()]);
	// 				}else{
	// 					$page=Db::name('customer')->where("relation_status",$type)->order('expires asc')->paginate(100,false,['query' => request()->param()]);
	// 				}
	// 			}else{
	// 				$page=Db::name('customer')->order('expires asc')->paginate(100,false,['query' => request()->param()]);

	// 		}
	// 	}
	// 	return $page;
	// }
	//执行修改客户
	public function edit_customer($data)
	{
		$id=$data['did'];
		$update['username']=$data['username'];
		$update['aliname']=$data['aliname'];
		$update['age']=$data['age'];
		$update['moblie']=$data['moblie'];
		$update['position']=$data['position'];
		$update['weixin']=$data['weixin'];
		$update['qq']=$data['qq'];
		$update['notes']=$data['notes'];
		$update['qniu']=$data['qniu'];
		$update['products']=$data['products'];
		$update['industry1']=$data['industry1'];
		$update['industry2']=$data['industry2'];
		$update['ali_app']=$data['ali_app'];
		$update['m_shop']=$data['m_shop'];
		$update['shop_address']=$data['shop_address'];
		$update['e_address']=$data['e_address'];
		$update['trade_medal']=$data['trade_medal'];
		$update['wp_score']=$data['wp_score'];
		$update['violation_record']=$data['violation_record'];
		$update['five_offer']=$data['five_offer'];
		$update['seven_offer']=$data['seven_offer'];
		$update['strength_businessmen']=$data['strength_businessmen'];
		$update['p4p']=$data['p4p'];
		$update['p4p_consume']=$data['p4p_consume'];
		$update['shop_status']=$data['shop_status'];
		$update['managed_information']=$data['managed_information'];
		$update['auto_activities']=$data['auto_activities'];
		$update['shop_package']=$data['shop_package'];
		$update['corporate_name']=$data['corporate_name'];
		$update['company_address']=$data['company_address'];
		$update['company_personnel']=$data['company_personnel'];
		$update['importance']=$data['importance'];
		$update['annual_profit']=$data['annual_profit'];
		$update['y_turnover']=$data['y_turnover'];
		$update['public_number']=$data['public_number'];
		$update['integrity_level']=$data['integrity_level'];
		$update['consumption_level']=$data['consumption_level'];
		$update['current_level']=$data['current_level'];
		$update['buy_insurance']=$data['buy_insurance'];
		$update['sign_date']=strtotime($data['sign_date']);
		$update['expires']=strtotime($data['expires']);
		// var_dump($update);
		// die;
		$res=Db::name('customer')->where('id',$id)->update($update);
		return $res;
	}
	//查看客户所有信息
	public function detail($id)
	{
		$data=Db::name('customer')->where('id',$id)->find();
		return $data;
	}

	//评分标准
	public function score()
	{
		$score=Db::name('score')->select();
		return $score;
	}
	//p4p消耗标准
	public function p4p()
	{
		$score=Db::name('p4p')->select();
		return $score;
	}

	//公司人员数量范围
	public function e_num()
	{
		$enum=Db::name('e_num')->select();
		return $enum;
	}
	//年利润区间表
	public function profit()
	{
		$profit=Db::name('profit')->select();
		return $profit;
	}
	//营业额区间
	public function turnover()
	{
		$turnover=Db::name('turnover')->select();
		return $turnover;
	}
	//诚信星级表
	public function sincerity()
	{
		$sincerity=Db::name('sincerity')->select();
		return $sincerity;
	}
	//行业分类表
	public function industry()
	{
		$industry=Db::name('industry')->select();
		return $industry;
	}

	//行业分类表
	public function industry1($id)
	{
		$industry=Db::name('industry')->where('pid',$id)->select();

		return $industry;
	}

	//添加客户
	public function add_customer($id)
	{

		$industry=Db::name('contacts')->insert($id);

		return $industry;
	}

	//客户列表
	public function cus_list($id)
	{

		$cus1=Db::name('contacts')->where('cid',$id)->select();
		return $cus1;
	}

	//分公司列表
	public function branch_office()
	{
		$office=Db::name('branch_office')->select();
		return $office;
	}

	//分公司员工列表
	public function branch_user($bid)
	{
		// $where['rank']=5;
		$where['status']=1;
		if($bid){
			$where['department']=Session::get('user.department');
			$where['branch_id']=$bid;
		}
		$user=Db::name('user')->field('id,job_num,username')->where($where)->select();
		return $user;
	}
	//分配操作
	public function fenpei($data)
	{
		$data['addtime']=time();
		$data['updatetime']=time();
		$res=Db::name('relation_company')->insert($data);
		if($res){
			$status['relation_status']=1;
			Db::name('customer')->where('id',$data['cid'])->update($status);
		}
		return $res ? true : false;
	}
	//分配客户到员工
	public function fenda($data)
	{
		$data['addtime']=time();
		$data['updatetime']=time();
		$res=Db::name('employee_customer')->insert($data);
		if($res){
			$status['c_status']=1;
			Db::name('customer')->where('id',$data['cid'])->update($status);
			$service=new Service();
			$cid=$data['cid'];
			//服务月
			$add['service_month']=$service->net_age($cid);
			$add['cid']=$data['cid'];
			$add['uid']=$data['uid'];
			$add['addtime']=time();
			//规定服务
			$service1=$service->service_cum1($cid);
			foreach ($service1 as $k => $v) {
				$arr1[]=$v['id'];
			}
			if($service1){
				$add['service1']=implode(',',$arr1);
				//规定服务完成量用于做比率
				$add['p_num']=count($arr1);
				//可选服务
				$service2=$service->service_cum2($cid);
				if($service2){
					foreach ($service2 as $k => $v) {
						$arr2[]=$v['id'];
					}
					$add['service2']=implode(',',$arr2);
					//可选服务完成量
					$add['o_num']=count($arr2);
				}
				
				$where['cid']=$data['cid'];
				$where['service_month']=$add['service_month'];
				$res1=Db::name('service_completion')->where($where)->find();
				if($res1){
				}else{
					Db::name('service_completion')->insert($add);
				}
			}
			
		}
			return $res ? true : false;

	}
	//分配信息

	public function relation($id)
	{
		$msg=Db::name('relation_company')->field('a.addtime,a.updatetime,b.name')->alias('a')->join('branch_office b','a.oid=b.id')->where('a.cid',$id)->select();
		return $msg;
	}
	//员工分配信息
	public function relation1($id)
	{
		$msg=Db::name('employee_customer')->alias('a')->field('a.cid,a.addtime,a.status,a.updatetime,b.username')->join('user b','a.uid=b.id')->where('a.cid',$id)->select();
		return $msg;
	}
	//转移客户
	public function tranCustomer($uid,$cid)
	{
		// $where['cid']=$cid;
		// $update['uid']=$uid;
		$update['status']=2;
		$update['updatetime']=time();
		$dd=Db::name('employee_customer')->where('cid',$cid)->where('status',1)->update($update);
		$com['uid']=$uid;
		$com['cid']=$cid;
		$com['addtime']=time();
		$com['updatetime']=time();
		$res=Db::name('employee_customer')->insert($com);
		return $res;
	}
	//续费状态
public function add($data){
    $id=$data['did'];
    $update['status']=$data['status'];
    $update['examine']=3;
    $res=Db::name('customer')->where('id',$id)->update($update);
    return $res;
}
public function add1($data){
    $id=$data['did'];
    $update['status']=$data['status'];
    $res=Db::name('customer')->where('id',$id)->update($update);
    return $res;
}
//多选转移客户
	public function alltrans($data)
	{
		$array=$data['array'];
		$user=$data['user'];
		$res=Db::name('employee_customer')->where('cid','in',$array)->where('uid',$user)->select();
		if($res){
			//有重复的数据
			return 1;
		}else{
			$all=explode(',', $array);
			foreach ($all as $k => $v){
				$update['status']=2;
				$update['updatetime']=time();
				$dd=Db::name('employee_customer')->where('cid',$v)->where('status',1)->update($update);
				$com['uid']=$user;
				$com['cid']=$v;
				$com['addtime']=time();
				$com['updatetime']=time();
				Db::name('employee_customer')->insert($com);
			}
			return 2;
		}
	}
	//添加客户信息操作
	public function inserted($data)
	{
		$data['sign_date']=strtotime($data['sign_date']);
		$data['expires']=strtotime($data['expires']);
		$data['relation_status']=1;
		$cid=Db::name('customer')->insertGetId($data);
		$com['cid']=$cid;
        $com['oid']=Session::get('user.branch_id');
        $com['addtime']=time();
        $com['updatetime']=time();
        $res=Db::name('relation_company')->insert($com);
        return $res ? true :false;
	}
	//批量删除客户
	public function all_del($data)
	{
		$all=explode(',',$data);
		foreach($all as $k=>$v){
			$update['relation_status']=2;
			$update['c_status']=2;
			Db::name('customer')->where('id',$v)->update($update);
			Db::name('relation_company')->where('cid',$v)->delete();
			$res=Db::name('employee_customer')->where('cid',$v)->delete();
			return $res ? true : false;

		}
	}
	//续签订单重新提交
	public function renewOrder($id)
	{
		$res=Db::name('renewal')->where('did',$id)->where('status','<>',1)->find();
		if($res){
			return $res;
		}else{
			return false;
		}
	}
	//重新提交续签订单
	public function cqorder($data)
	{
		$oid=$data['oid'];
		$update['money']=$data['money'];
		$update['status']=0;
		Db::name('customer')->where('id',$data['did'])->update(['examine'=>3]);
		$res=Db::name('renewal')->where('id',$oid)->update($update);
		return $res ? true : false;
	}
}
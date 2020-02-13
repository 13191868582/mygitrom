<?php
namespace app\index\controller;
use app\index\model\Newcustomer;
use think\controller;
use think\Request;
use think\Db;
// use app\index\model\Order;
use think\Session;
/**
 * 
 */
class Orderlist extends Common
{
    public  $base0=2000;
    //1-2单底薪
    public $base=2000;
    //3-6单
    public  $base1=2500;
    //7-8单
    public  $base2=2800;
	public function Count()
	{
		$basetime=date('Y-m-01', strtotime(date("Y-m-d")));
		$start=strtotime($basetime);
		$end=strtotime(date('Y-m-d 23:59:59', strtotime("$basetime +1 month -1 day")));
		//当月未续签客户数-----------------------------------------------------开始
		$customer=db('customer')
					->alias('a')
					->join('silver_relation_company r','a.id=r.cid','left')
					->where('r.status',1)
					->where('r.oid','in',[1,2,3,4,5,6,7,8,9])
					->where('expires','between time',[$start,$end])
					->field('a.id,a.aliname,a.sign_date,a.expires')
					->select();
		foreach ($customer as $k => $v) {
			$sign_date=strtotime(date("Y-m-d",$v['sign_date']));
			$expires=strtotime(date("Y-m-d",$v['expires']));
			$sd=date('d',$sign_date);
			$ed=date('d',$expires);
			$sm=date('m',$sign_date);
			$em=date('m',$expires);
			$sy=date('Y',$sign_date);
			$ey=date('Y',$expires);
			$num_d=$ed-$sd;
			$num_m=$em-$sm;
			$num_y=$ey-$sy;
			if($num_m==0 || ($num_m==1 && $num_d<1)){
				//首年
				if($num_y==1){
					$arr1[]=$v['id'];
				}
				//二年
				if($num_y==2){
					$arr2[]=$v['id'];
				}
				//多年
				if($num_y>2){
					$arr3[]=$v['id'];
				}
				$arr4[]=$v['id'];
			}
			
		}
		//当月未续签客户数--------------------------------------------------------结束
		//当月续签客户数---------------------------------------------------------开始
		$x_data=db('achievement')
				->alias('a')
				->join('silver_customer c','a.aliname=c.aliname','left')
				->where('a.yuantime','between time',[$start,$end])
				->where('c.expires','>',0)
				->where('a.type','续签')
				->where('a.ptype','诚信通服务')
				->field('a.aliname,c.sign_date,a.yuantime')
				->select();
		// var_dump($x_data);
		// die;
		foreach ($x_data as $k => $v) {
			$x_sign_date=strtotime(date("Y-m-d",$v['sign_date']));
			$x_expires=strtotime(date("Y-m-d",$v['yuantime']));
			$x_sd=date('d',$x_sign_date);
			$x_ed=date('d',$x_expires);
			$x_sm=date('m',$x_sign_date);
			$x_em=date('m',$x_expires);
			$x_sy=date('Y',$x_sign_date);
			$x_ey=date('Y',$x_expires);
			$x_num_d=$x_ed-$x_sd;
			$x_num_m=$x_em-$x_sm;
			$x_num_y=$x_ey-$x_sy;
			if($x_num_m==0 || ($x_num_m==1 && $x_num_d<1)){
				//首年
				if($x_num_y==1){
					$x_arr1[]=$v['aliname'];
					// var_dump($x_arr1);
					$x_arr4[]=$v['aliname'];
				}
				//二年
				if($x_num_y==2){
					$x_arr2[]=$v['aliname'];
					$x_arr4[]=$v['aliname'];
				}
				//多年
				if($x_num_y>2){
					$x_arr3[]=$v['aliname'];
					$x_arr4[]=$v['aliname'];
				}

			}else{
				// dump($v['aliname']);
			}
		}
		// du  吧 
		//当月续签客户数----------------------------------------------------------结束
		$data1=db('customer')
				->alias('a')
				->join('silver_relation_company w','a.id=w.cid','left')
				->where('w.status',1)
				->where('a.id','in',$arr1)
				->field('w.oid,count(a.id) as num')
				->group('w.oid')
				->select();
		$data1_2=array_column($data1,'num','oid');
		$data1_1=db('achievement')
				->where('yuantime','between time',[$start,$end])
				->where('type','续签')
				->where('ptype','诚信通服务')
				->where('aliname','in',$x_arr1)
				->field('oid,count(id) as numd')
				->group('oid')
				->select();
		$data1_3=array_column($data1_1,'numd','oid');

		$bl_1=array();
		for ($i=1; $i <10; $i++) { 
			if(!empty($data1_3[$i]) && !empty($data1_2[$i])){
				$bl_1[$i]=round($data1_3[$i]/($data1_2[$i]+$data1_3[$i])*100,1);
				$data1_2[$i]=$data1_2[$i]+$data1_3[$i];
			}else{
				if(empty($data1_3[$i])){
					$data1_3[$i]=0;
				}
				if(empty($data1_2[$i])){
					$data1_2[$i]=0;
				}
				$bl_1[$i]=0;

			}
		}
		// print_r($data1_2);die();
		// var_dump($bl_1);
		// die;
		$this->assign('data1_3',$data1_3);
		$this->assign('data1_2',$data1_2);
		//二年
		$data2=db('customer')
				->alias('a')
				->join('silver_relation_company w','a.id=w.cid','left')
				->where('w.status',1)
				->where('a.id','in',$arr2)
				->field('w.oid,count(a.id) as num')
				->group('w.oid')
				->select();
		$data2_2=array_column($data2,'num','oid');
		$data2_1=db('achievement')
				->where('yuantime','between time',[$start,$end])
				->where('type','续签')
				->where('ptype','诚信通服务')
				->where('aliname','in',$x_arr2)
				->field('oid,count(id) as numd')
				->group('oid')
				->select();
		$data2_3=array_column($data2_1,'numd','oid');
		$bl_2=array();
		for ($i=1; $i <10; $i++) { 
			if(!empty($data2_3[$i]) && !empty($data2_2[$i])){
				$bl_2[$i]=round($data2_3[$i]/($data2_2[$i]+$data2_3[$i])*100,1);
				$data2_2[$i]=$data2_2[$i]+$data2_3[$i];
			}else{
				$bl_2[$i]=0;
				if(empty($data2_3[$i])){
					$data2_3[$i]=0;
				}
				if(empty($data2_2[$i])){
					$data2_2[$i]=0;
				}
			}
		}
		$this->assign('data2_3',$data2_3);
		$this->assign('data2_2',$data2_2);
		// //多年
		$data3=db('customer')
				->alias('a')
				->join('silver_relation_company w','a.id=w.cid','left')
				->where('w.status',1)
				->where('a.id','in',$arr3)
				->field('w.oid,count(a.id) as num')
				->group('w.oid')
				->select();
		$data3_2=array_column($data3,'num','oid');
		$data3_1=db('achievement')
				->where('yuantime','between time',[$start,$end])
				->where('type','续签')
				->where('ptype','诚信通服务')
				->where('aliname','in',$x_arr3)
				->field('oid,count(id) as numd')
				->group('oid')
				->select();
		$data3_3=array_column($data3_1,'numd','oid');
		$bl_3=array();
		for ($i=1; $i <10; $i++) { 
			if(!empty($data3_3[$i]) && !empty($data3_2[$i])){
				$bl_3[$i]=round($data3_3[$i]/($data3_2[$i]+$data3_3[$i])*100,1);
				$data3_2[$i]=$data3_2[$i]+$data3_3[$i];
			}else{
				$bl_3[$i]=0;
				if(empty($data3_3[$i])){
					$data3_3[$i]=0;
				}
				if(empty($data3_2[$i])){
					$data3_2[$i]=0;
				}
			}
		}
		$this->assign('data3_3',$data3_3);
		$this->assign('data3_2',$data3_2);
		// //回签
		$data4_1=db('achievement')
		       ->where('fintime','between time',[$start,$end])
		       ->where('yuantime','<',$start)
		       ->where('type','续签')
		       ->field('count(id) as numd,oid')
		       ->group('oid')
		       ->select();
		$data4_3=array_column($data4_1,'numd','oid');
		for ($i=1; $i <10; $i++) { 
			if(!empty($data4_3[$i])){
				$data4_3[$i]=$data4_3[$i];
			}else{
				$data4_3[$i]=0;
			}
		}
		// //综合
		// 
		$data5=db('customer')
				->alias('a')
				->join('silver_relation_company w','a.id=w.cid','left')
				->where('w.status',1)
				->where('a.id','in',$arr4)
				->field('w.oid,count(a.id) as num')
				->group('w.oid')
				->select();
		$data5_2=array_column($data5,'num','oid');
		$data5_1=db('achievement')
				->where('yuantime','between time',[$start,$end])
				->where('type','续签')
				->where('ptype','诚信通服务')
				->where('aliname','in',$x_arr4)
				->field('oid,count(id) as numd')
				->group('oid')
				->select();
		$data5_3=array_column($data5_1,'numd','oid');
		$bl_5=array();
		for ($i=1; $i <10; $i++) { 
			if(!empty($data5_3[$i]) && !empty($data5_2[$i])){
				$bl_5[$i]=round(($data5_3[$i]+$data4_3[$i])/($data5_2[$i]+$data5_3[$i])*100,1);
				$data5_2[$i]=$data5_2[$i]+$data5_3[$i];
				$data5_3[$i]=$data5_3[$i]+$data4_3[$i];
			}else{
				$bl_5[$i]=0;
				if(empty($data5_3[$i])){
					$data5_3[$i]=0;
				}
				if(empty($data5_2[$i])){
					$data5_2[$i]=0;
				}
			}
		}
		$this->assign('data5_3',$data5_3);
		$this->assign('data5_2',$data5_2);
		//集团比率
		//首年
		$zsnum1_1=count($arr1);
		// var_dump($x_arr1);
		// die;
		$zsnum1_2=count($x_arr1);
		if(!$zsnum1_1){
			$zsnum1_1=1;
		}
		$bl_1[10]=round($zsnum1_2/($zsnum1_1+$zsnum1_2)*100,1);
		$this->assign('zsnum1_2',$zsnum1_2);
		$this->assign('zsnum1_1',$zsnum1_1);
		//二年
		$zsnum2_1=count($arr2);
		$zsnum2_2=count($x_arr2);
		if(!$zsnum2_1){
			$zsnum2_1=1;
		}
		$bl_2[10]=round($zsnum2_2/($zsnum2_1+$zsnum2_2)*100,1);
		$this->assign('zsnum2_2',$zsnum2_2);
		$this->assign('zsnum2_1',$zsnum2_1);
		//多年
		$zsnum3_1=count($arr3);
		$zsnum3_2=count($x_arr3);
		if(!$zsnum3_1){
			$zsnum3_1=1;
		}
		$bl_3[10]=round($zsnum3_2/($zsnum3_1+$zsnum3_2)*100,1);
		$this->assign('zsnum3_2',$zsnum3_2);
		$this->assign('zsnum3_1',$zsnum3_1);
		//回签
		$data4_4=db('achievement')
		       ->where('fintime','between time',[$start,$end])
		       ->where('yuantime','<',$start)
		       ->where('type','续签')
		       ->field('count(id) as numd')
		       ->select();
		$data4_3[10]=$data4_4[0]['numd'];
		//综合
		$zsnum4_1=count($arr4);
		$zsnum4_2=count($x_arr4);
		if(!$zsnum4_1){
			$zsnum4_1=1;
		}
		$bl_5[10]=round($zsnum4_2/($zsnum4_1+$zsnum4_2)*100,1);

		$this->assign('zsnum4_2',$zsnum4_2);
		$this->assign('zsnum4_1',$zsnum4_1);
		$this->assign('bl_1',$bl_1);
		$this->assign('bl_2',$bl_2);
		$this->assign('bl_3',$bl_3);
		$this->assign('data4_3',$data4_3);
		$this->assign('bl_5',$bl_5);
		/*分公司A等级占比开始-----------------------------------------------------------------*/
		$medal = $this->getMedal();
		$matching = $this->matching();

		$this->assign('matching',$matching);
		$this->assign('medal',$medal);
		/*分公司A等级占比结束-----------------------------------------------------------------*/
		return $this->fetch();
	}

	public function matching()
	{
		$branch = db('branch_office')->select();
		foreach ($branch as $key => $value) {
			$arr[$value['name']]['tp'] = db('relation_company')
									->alias('r')
									->where('r.oid',$value['id'])
									->join('customer c','c.id = r.cid')
									->where('c.expires','>',time())
									->count();
			$arr[$value['name']]['num'] = db('user')
									->where('branch_id',$value['id'])
									->where('department',3)
									->where('rank','neq',2)
									->count();
			

		}
		foreach ($arr as $key => $value) {
			if ($value['tp'] != 0) {

				if ($value['num'] != 0) {
					$data['title'][] = $key;
					$data['data'][] = round($value['tp']/$value['num'],2);
				}else{
					$data['title'][] = $key;
					$data['data'][] = 0;
				}
			}
			
		}

		return $data;
	}




	/**
	 *获取分公司A等级
	 */
	public function getMedal()
	{
		$branch = db('branch_office')->select();
		foreach ($branch as $key => $value) {
			$arr[$value['name']]['tp'] = db('relation_company')
									->alias('r')
									->where('r.oid',$value['id'])
									->join('customer c','c.id = r.cid')
									->where('c.expires','>',time())
									->count();
			$arr[$value['name']]['0'] = db('relation_company')
									->alias('r')
									->where('r.oid',$value['id'])
									->join('customer c','c.id = r.cid')
									->where('c.expires','>',time())
									->where('c.a_level',0)
									->count();
			$arr[$value['name']]['1'] = db('relation_company')
									->alias('r')
									->where('r.oid',$value['id'])
									->join('customer c','c.id = r.cid')
									->where('c.expires','>',time())
									->where('c.a_level',1)
									->count();
			$arr[$value['name']]['2'] = db('relation_company')
									->alias('r')
									->where('r.oid',$value['id'])
									->join('customer c','c.id = r.cid')
									->where('c.expires','>',time())
									->where('c.a_level',2)
									->count();
			$arr[$value['name']]['3'] = db('relation_company')
									->alias('r')
									->where('r.oid',$value['id'])
									->join('customer c','c.id = r.cid')
									->where('c.expires','>',time())
									->where('c.a_level',3)
									->count();
			$arr[$value['name']]['4'] = db('relation_company')
									->alias('r')
									->where('r.oid',$value['id'])
									->join('customer c','c.id = r.cid')
									->where('c.expires','>',time())
									->where('c.a_level',4)
									->count();
			$arr[$value['name']]['5'] = db('relation_company')
									->alias('r')
									->where('r.oid',$value['id'])
									->join('customer c','c.id = r.cid')
									->where('c.expires','>',time())
									->where('c.a_level',5)
									->count();
			

		}
		
		return $arr;


	}

	//业绩订单统计
//	public function list()
//	{
//		$admin=Session::get('admin');
//		$user=Session::get('user');
//		$request=Request::instance();
//		if($request->param('corporate_name') || $request->param('username')){
//			if($request->param('corporate_name')){
//			$where['aliname|company']=['like',"%".$request->param('corporate_name')."%"];
//			}
//			if($request->param('username')){
//			$where['username']=$request->param('username');
//			$ddname=$request->param('username');
//			$this->assign('ddname',$ddname);
//			}
//		}else{
//			$where=array();
//		}
//		if($admin){
//			$data=db('achievement')->order('id')->paginate(30,false,['query' => request()->param()]);
//		}
//		$firstday = date("Y-m-01",time());
//     	$lastday = date("Y-m-d",strtotime("$firstday +1 month 0 day"));
//     	$start=strtotime($firstday);
//     	$end=strtotime($lastday);
//		if($user){
//			//续签签总监
//			if($user['rank']==2){
//				$username=db('user')
//						  ->where('rank',5)
//						  ->where('branch_id',$user['branch_id'])
//						  ->column('username');
//				$data=db('achievement')
//					  ->where('oid',$user['branch_id'])
//					  ->where('type','续签')
//					  ->where('status','in',[0,4])
//					  ->where($where)
//					  ->order('id')
//					  ->paginate(30,false,['query' => request()->param()]);
//
//			}
//			if($user['rank']==1){
//				$username=db('user')
//						  ->where('rank',5)
//						  ->where('branch_id',$user['branch_id'])
//						  ->column('username');
//				$data=db('achievement')
//					  ->where('oid',$user['branch_id'])
//					  ->where('status','in',[0,4])
//					  ->where($where)
//					  ->order('id')
//					  ->paginate(30,false,['query' => request()->param()]);
//
//			}
//			//新签总监
//			if($user['rank']==11){
//				$username=db('user')
//						 ->where('rank','in',[9,11])
//						 ->where('branch_id',$user['branch_id'])
//						 ->column('username');
//				$data=db('achievement')
//					 ->where('oid',$user['branch_id'])
//					 ->where('type','新签')
//					 ->where($where)
//					 ->where('status','in',[0,4])
//					 ->order('id')
//					 ->paginate(30,false,['query' => request()->param()]);
//			}
//			//续签员工
//			if($user['rank']==5){
//				$username=$user['username'];
//				$data=db('achievement')
//					  ->where('username',$username)
//					  ->where('permonth',$start)
//					  ->where($where)
//					  ->where('status',1)
//					  ->order('id')
//					  ->paginate(30,false,['query' => request()->param()]);
//			}
//			//新签员工
//			if($user['rank']==9){
//				$username=$user['username'];
//				$data=db('achievement')
//					  ->where('username',$username)
//					  ->where('permonth',$start)
//					  ->where($where)
//					  ->where('status',1)
//					  ->order('id')
//					  ->paginate(30,false,['query' => request()->param()]);
//			}
//			$this->assign('username',$username);
//
//		}
//		$this->assign('user',$user['username']);
//		$this->assign('rank',$user['rank']);
//		$this->assign('data',$data);
//		return $this->fetch();
//	}
//    public  function  list(){
//	    $aliorde=Request::instance()->get('aliorde');
//        $username=Request::instance()->get('username');
//        $bid=Request::instance()->param('bid');
//        $aliname=Request::instance()->param('aliname');
//	    $where=[];
//	    if ($aliorde){
//	        $where['aliorder']=$aliorde;
//        }
//	    if($username){
//            $where['username']=$username;
//        }
//	    if($bid){
//	        $where['oid']=$bid;
//        }
//        if ($aliname){
//            $where['aliname']=$aliname;
//        }
//        $data=db('achievement')
//            ->where($where)
//            ->where('status',0)
//            ->order('status asc')
//            ->paginate(50,false,['query' => request()->param()]);
//        $newcustomer=new Newcustomer();
//        $branch=$newcustomer->branch();
//        $this->assign('data',$data);
//        $this->assign('aliorde',$aliorde);
//        $this->assign('username',$username);
//        $this->assign('branch',$branch);
//        $this->assign('bid',$bid);
//        $this->assign('aliname',$aliname);
//        return $this->fetch('');
//    }
    public function list(){
        $login_id=Request::instance()->get('login_id');
        $bid=Request::instance()->param('bid');
        $contract=Request::instance()->param('contract');
        $customer_name=Request::instance()->param('customer_name');
        $where=[];
        if($login_id){
            $where['login_id']=$login_id;
        }
        if($bid){
            $where['oid']=$bid;
        }
        if ($contract){
            $where['contract']=$contract;
        }
        if ($customer_name){
            $where['customer_name']=$customer_name;
        }
        //当前时间上个月的开始时间和结束时间
        $strtime = strtotime(date('Y-m-01 00:00:00',strtotime('-1 month')));
	    $data=Db::name('achievement')
            ->where($where)
            ->where('commission_base','>',0)
            ->where('commission_overmonth','>=',$strtime)
            ->where('status',0)
            ->order('sale_name','desc')
            ->paginate(50,false,['query' => request()->param()]);
        //$newcustomer=new Newcustomer();
        $branch=br();
        $this->assign('login_id',$login_id);
        $this->assign('contract',$contract);
        $this->assign('branch',$branch);
        $this->assign('customer_name',$customer_name);
        $this->assign('bid',$bid);
	    $this->assign('data',$data);
	    return $this->fetch('');
    }
    public function zlist(){
	    $data=array();
	    $search=Request::instance()->get('search');
	    $where=[];
	    if ($search){
	        $where['contract|login_id|customer_name']=$search;
        }
        $find=db('achievement')
            ->where($where)
            ->find();
	    if ($find){
            $data=db('achievementover')
                ->alias('aco')
                ->join('achievement ac','aco.ach_id=ac.id')
                ->where('aco.ach_id',$find['id'])
                ->select();
            if($data){
                $this->assign('data',$data);
                $this->assign('search',$search);
                return $this->fetch('zlist');
            }else{
                $data=db('achievement')
                    ->where($where)
                    ->order('status asc')
                    ->select();
                $this->assign('data',$data);
                $this->assign('search',$search);
                return $this->fetch('rlist');
            }
        }else{
            echo"<script>alert('阿里巴巴本月未结算此单佣金，请联系财务部合适情况！');location.href='/index/orderlist/list.html';</script>";
        }
    }
	public function distribution()
	{
		$request=Request::instance();
		$id=$request->param('id');
		$user=db('achievement')
			  ->where('id',$id)
			  ->field('username')
			  ->find();
		$username=$user['username'];
		$this->assign('user1',$username);
		$where['status']=1;
		$where['department']=Session::get('user.department');
		$where['branch_id']=Session::get('user.branch_id');
		$user2=Db::name('user')->field('id,job_num,username')->where($where)->select();
		$this->assign('pid',$id);
		$this->assign('user2',$user2);
		return $this->fetch();
	}
	//分发月份确认
	public function distribute()
	{
		$request=Request::instance();
		$id=$request->param('id');
        $strtime = strtotime(date('Y-m-01 00:00:00',strtotime('-1 month')));
        $strtime=date("Y-m-d",$strtime);
        $this->assign('strtime',$strtime);
		$this->assign('id',$id);
		return $this->fetch();
	}
	//订单确认操作
	public function adopt()
	{
		$request=Request::instance();
		$id=$request->param('id');
		$update['status']=$request->param('status');
		$res=db('achievement')->where('id',$id)->update($update);
		if($res){
			return returnmsg(array('code'=>200,'msg'=>'操作成功！'));
		}else{
			return returnmsg(array('code'=>500,'msg'=>'操作失败！'));
		}
	}

	//业绩月份确认
	public function fenfa()
	{
		$request=Request::instance();
		$pid=$request->param('pid');
		$data=strtotime($request->param('date'));
		$user=Session::get('user.username');
		$job_num=Session::get('user.job_num');
		$iss=Db::name('wagsto')->where('job_num',$job_num)->find();
		if ($iss){
            return returnmsg(array('code'=>500,'msg'=>'操作失败,您已经确认工资,如有问题请联系主管！'));

        }else{
            $arr=[
                'ach_id'=>$pid,
                'username'=>$user,
                'permonth'=>$data,
                'job_num'=>$job_num,
                'ltime'=>time()
            ];
            $res=db('achievement')->where('id',$pid)->update(['permonth'=>$data,'status'=>1]);
            if($res){
                $result=db('achievementover')->where('ach_id',$pid)->find();
                if ($result){
                    return returnmsg(array('code'=>500,'msg'=>'操作失败,该订单已被确认！'));
                }else{
                    $cbase=Db::name('achievement')->where('id',$pid)
                        ->field('commission_base,commission_money,type')
                        ->find();
                    $arr['commission_base']=$cbase['commission_base'];
                    $arr['commission_money']=$cbase['commission_money'];
                    $arr['type']=$cbase['type'];
                    $resl=db('achievementover')->insert($arr);
                    if ($resl){
                        return returnmsg(array('code'=>200,'msg'=>'操作成功！'));
                    }else{
                        return returnmsg(array('code'=>500,'msg'=>'操作失败！'));
                    }
                }
            }else{
                return returnmsg(array('code'=>500,'msg'=>'操作失败！'));
            }
        }

	}

	//员工查看自己确认的订单
    public function ulist(){
	    $date=Request::instance()->get('date');
	    $where=[];
	    if ($date){
	        $where['aco.permonth']=strtotime($date);
        }
	    //提成
	    //当前时间上个月的开始时间和结束时间
        $strtime = strtotime(date('Y-m-01 00:00:00',strtotime('-1 month')));
        $endtime = strtotime(date("Y-m-d 23:59:59", strtotime(-date('d').'day')));
        //底薪
        //当前时间上上个月的开始时间和结束时间
        $strtime1 =date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m")-2,1,date("Y")));
        $end_time1=date("Y-m-d H:i:s",mktime(23,59,59,date("m")-1 ,0,date("Y")));
        $strtime1=strtotime($strtime1);
        $endtime1=strtotime($end_time1);
        $user=Session::get('user.username');
	    $job_num=Session::get('user.job_num');
        $data=db('achievementover')
            ->alias('aco')
            ->join('achievement ac','aco.ach_id=ac.id','LEFT')
            ->where('aco.username',$user)
            ->where('aco.permonth',$strtime)
            ->where($where)
            ->order('aco.permonth')
            ->paginate(50,false,['query' => request()->param()]);
        $base=db('achievementover')
            ->where('commission_base',6688)
            ->where('job_num',$job_num)
            ->whereTime('permonth','between',[$strtime1,$endtime1])
            ->count();
        $basemoney=0;
        $royalty=0;
        if ($base>=1&&$base<=2){
            $basemoney=$this->base;
        }elseif ($base>=3&&$base<=6){
            $basemoney=$this->base1;
        }elseif ($base>=7){
            $basemoney=$this->base2;
        }else{
            $basemoney=$this->base0;
        }
        //提成
        //诚信通
        $croyalty=db('achievementover')
            ->where('commission_base',6688)
            ->where('job_num',$job_num)
            ->whereTime('permonth','between',[$strtime,$endtime])
            ->field("count(*) as ccount,round(sum(commission_base),2) as cmoney")
            ->find();
        //网销宝
        $wroyalty=db('achievementover')
            ->where('type',"网销宝")
            ->where('job_num',$job_num)
            ->whereTime('permonth','between',[$strtime,$endtime])
            ->field("count(*) as wcount,round(sum(commission_base),2) as wmoney")
            ->find();
        //1单网销宝折合为0.5单诚信通
        $wroyalty['wcount']=$wroyalty['wcount']/2;
        $royaltycount=$croyalty['ccount']+$wroyalty['wcount'];
        $royaltymoney=$croyalty['cmoney']+$wroyalty['wmoney'];
        if ($royaltycount==1){
            $royalty=$royaltymoney*0.03;
        }elseif ($royaltycount==1.5){
            $royalty=$royaltymoney*0.055;
        }elseif ($royaltycount==2){
            $royalty=$royaltymoney*0.08;
        }elseif ($royaltycount==2.5){
            $royalty=$royaltymoney*0.095;
        }elseif ($royaltycount==3){
            $royalty=$royaltymoney*0.11;
        }elseif ($royaltycount==3.5){
            $royalty=$royaltymoney*0.115;
        }elseif ($royaltycount==4){
            $royalty=$royaltymoney*0.12;
        }elseif ($royaltycount==4.5){
            $royalty=$royaltymoney*0.125;
        }elseif ($royaltycount==5){
            $royalty=$royaltymoney*0.13;
        }elseif ($royaltycount==5.5){
            $royalty=$royaltymoney*0.14;
        }elseif ($royaltycount>=6){
            $royalty=$royaltymoney*0.15;
        }
        $royalty=round($royalty,2);
        $total=$basemoney+$royalty;
        $atime=strtotime(date('Y-m',time()));
        $status=Db::name('wagsto')->where('job_num',$job_num)->where('time',$atime)->value('status');
        $this->assign('royalty',$royalty);
        $this->assign('basemoney',$basemoney);
        $this->assign('total',$total);
        $this->assign('data',$data);
        $this->assign('date',$date);
        $this->assign('status',$status);
        return $this->fetch('');
    }
    //回退
    public function changestatus(){
	    $id=input('id');
	    $res=db('achievementover')->where('ach_id',$id)->delete();
	    if ($res){
            $result=db('achievement')->where('id',$id)->update(['status'=>0]);
            if ($result){
                return returnmsg(array('code'=>200,'msg'=>'回退成功！'));
            }else{
                return returnmsg(array('code'=>500,'msg'=>'回退失败！'));
            }
        }else{
            return returnmsg(array('code'=>500,'msg'=>'回退失败！'));
        }
    }

    public function importto(){
	    return $this->fetch('');
    }

    //工资导入
    public function upload_imports(){
        $insertsuccess=0;
        $request = \think\Request::instance();
        $file = $request->file('file');
        $save_path = ROOT_PATH . 'uploads/';
        $info = $file->move($save_path);
        $filename=$save_path . DIRECTORY_SEPARATOR . $info->getSaveName();
        if(file_exists($filename)) {//如果文件存在
            import('phpexcel.PHPExcel', EXTEND_PATH);
            if(strstr($filename,'.xlsx')){
                $PHPReader = new \PHPExcel_Reader_Excel2007();
            }else{
                $PHPReader = new \PHPExcel_Reader_Excel5();
            }
            //载入excel文件
            $PHPExcel = $PHPReader->load($filename);
            $sheet = $PHPExcel->getActiveSheet(0);//获得sheet
            $highestRow = $sheet->getHighestRow(); // 取得共有数据数
            $data=$sheet->toArray();
            for($i=2;$i<$highestRow;$i++){
                //财务到账日期
                $import['finance_arrival']=strtotime($data[$i][1]);
                //客户名称
                $import['customer_name']=$data[$i][2];
                //登录Id
                $import['login_id']=$data[$i][3];
                //销售人员Id
                $import['sale_id']=$data[$i][4];
                //销售人员姓名
                $import['sale_name']=$data[$i][5];
                //分公司id
                $import['oid']=Db::name('user')
                    ->where('username',$import['sale_name'])
                    ->value('branch_id');
                //佣金基数
                $import['commission_base']=$data[$i][6];
                //业绩客户数
                $import['performance_num']=$data[$i][7];
                //服务开始日期
                $import['service_starttime']=strtotime($data[$i][8]);
                //服务结束日期
                $import['service_endtime']=strtotime($data[$i][9]);
                //新续属性
                $import['type']=$data[$i][10];
                //合同编号
                $import['contract']=$data[$i][11];
                //费用用途
                $import['cost_use']=$data[$i][12];
                //续签年限
                $import['xu_year']=$data[$i][13];
                //佣金核算月份
                $import['commission_overmonth']=strtotime($data[$i][14].'01');
                //订单月份
                $import['order_month']=strtotime($data[$i][15].'01');
                //牌级名称
                $import['brand_name']=$data[$i][16];
                //佣金金额
                $import['commission_money']=$data[$i][17];
                //牌级保护
                $import['brand_over']=$data[$i][18];
                //订单序号
                $import['order_id']=$data[$i][19];
                $res=db('achievement')->insert($import);
                if ($res){
                    $insertsuccess++;
                }
            }
            $highestRow=$highestRow-2;
            if($highestRow){
                $arr = array('code' =>200,'msg'=>'上传'.$highestRow.'条数据！'.'成功:'.$insertsuccess);
            }else{
                $arr = array('code' =>404,'msg'=>'上传数据为空');
            }
            return returnmsg($arr);
            // return $ret;
        }else{
            return array("resultcode" => -5, "resultmsg" => "文件不存在", "data" => null);
        }
    }

    //员工确认业绩薪资
    public function  dowages(){
        $data=input('');
        $data['per']=strtotime(date('Y-m-01 00:00:00',strtotime('-1 month')));
        $data['username']=Session::get('user.username');
        $data['branch_id']=Session::get('user.branch_id');
        $data['department']=Session::get('user.department');
        $data['job_num']=Session::get('user.job_num');
        $data['time']=strtotime(date('Y-m',time()));
        $data['status']=1;
        $data['rtime']=time();
        $result=Db::name('wagsto')->where('job_num',$data['job_num'])->where('time',$data['time'])->find();
        if ($result){
            return returnmsg(array('code'=>500,'msg'=>'您本月已经确认！'));
        }else{
            $res=Db::name('wagsto')->insert($data);
            if ($res){
                return returnmsg(array('code'=>200, 'msg'=>'操作成功！'));
            }else{
                return returnmsg(array('code'=>500,'msg'=>'操作失败！'));
            }
        }
    }

    //业绩调整
    public function adjustment(){
        $contract=Request::instance()->get('contract');
        $where=[];
        if($contract){
            $where['ach.contract']=$contract;
        }
        $data=Db::name('achievementover')
            ->alias('aco')
            ->join('achievement ach','aco.ach_id=ach.id')
            ->where($where)
            ->order('ach.permonth desc')
            ->field('aco.id,aco.ach_id,aco.username,aco.permonth,ach.contract,ach.login_id,ach.customer_name,
            ach.commission_base,ach.commission_money,ach.sale_name,ach.type,ach.finance_arrival,ach.status')
            ->paginate(50,false,['query' => request()->param()]);
        $this->assign('data',$data);
        $this->assign('contract',$contract);
        return $this->fetch();
    }
    //渲染执行调整页面
    public function adj(){
        $request=Request::instance();
        $id=$request->param('id');
        $br=Db::name('branch_office')->select();
        $this->assign('br',$br);
        $this->assign('id',$id);
	    return $this->fetch('');
    }

    //执行调整
    public function adjdo(){
        $data=input('');
        $user=Db::name('user')->where('id',$data['u_id'])->field('username,job_num')->find();
        Db::startTrans();
        try{
            $res=Db::name('achievementover')
                ->where('id',$data['pid'])
                ->update([
                    'username'=>$user['username'],
                    'job_num'=>$user['job_num'],
                    'ttime'=>time(),
                ]);
            //提成
            //当前时间上个月的开始时间和结束时间
            $strtime = strtotime(date('Y-m-01 00:00:00',strtotime('-1 month')));
            $endtime = strtotime(date("Y-m-d 23:59:59", strtotime(-date('d').'day')));
            //底薪
            //当前时间上上个月的开始时间和结束时间
            $strtime1 =date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m")-2,1,date("Y")));
            $end_time1=date("Y-m-d H:i:s",mktime(23,59,59,date("m")-1 ,0,date("Y")));
            $strtime1=strtotime($strtime1);
            $endtime1=strtotime($end_time1);
            $base=db('achievementover')
                ->where('commission_base',6688)
                ->where('job_num',$user['job_num'])
                ->whereTime('permonth','between',[$strtime1,$endtime1])
                ->count();
            $basemoney=0;
            $royalty=0;
            if ($base>=1&&$base<=2){
                $basemoney=$this->base;
            }elseif ($base>=3&&$base<=6){
                $basemoney=$this->base1;
            }elseif ($base>=7){
                $basemoney=$this->base2;
            }
            //提成
            //诚信通
            $croyalty=db('achievementover')
                ->where('commission_base',6688)
                ->where('job_num',$user['job_num'])
                ->whereTime('permonth','between',[$strtime,$endtime])
                ->field("count(*) as ccount,round(sum(commission_base),2) as cmoney")
                ->find();
            //网销宝
            $wroyalty=db('achievementover')
                ->where('type',"网销宝")
                ->where('job_num',$user['job_num'])
                ->whereTime('permonth','between',[$strtime,$endtime])
                ->field("count(*) as wcount,round(sum(commission_base),2) as wmoney")
                ->find();
            //1单网销宝折合为0.5单诚信通
            $wroyalty['wcount']=$wroyalty['wcount']/2;
            $royaltycount=$croyalty['ccount']+$wroyalty['wcount'];
            $royaltymoney=$croyalty['cmoney']+$wroyalty['wmoney'];
            if ($royaltycount==1){
                $royalty=$royaltymoney*0.03;
            }elseif ($royaltycount==1.5){
                $royalty=$royaltymoney*0.055;
            }elseif ($royaltycount==2){
                $royalty=$royaltymoney*0.08;
            }elseif ($royaltycount==2.5){
                $royalty=$royaltymoney*0.095;
            }elseif ($royaltycount==3){
                $royalty=$royaltymoney*0.11;
            }elseif ($royaltycount==3.5){
                $royalty=$royaltymoney*0.115;
            }elseif ($royaltycount==4){
                $royalty=$royaltymoney*0.12;
            }elseif ($royaltycount==4.5){
                $royalty=$royaltymoney*0.125;
            }elseif ($royaltycount==5){
                $royalty=$royaltymoney*0.13;
            }elseif ($royaltycount==5.5){
                $royalty=$royaltymoney*0.14;
            }elseif ($royaltycount>=6){
                $royalty=$royaltymoney*0.15;
            }
            $royalty=round($royalty,2);
            $total=$basemoney+$royalty;
            $up=array();
            $up['basemoney']=$basemoney;
            $up['royalty']=$royalty;
            $up['total']=$total;
            $up['ttime']=time();
            $r=Db::name('wagsto')
                ->where('job_num',$user['job_num'])
                ->update($up);
            // 提交事务
            Db::commit();
            return returnmsg(array('code'=>200,'msg'=>'操作成功！'));
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return returnmsg(array('code'=>500,'msg'=>'操作失败！'));
        }
    }

    //查询所属分公司下的员工
    public function fusername(){
        $brid=input('fid');
        $data['data']=Db::name('user')
            ->where('branch_id',$brid)
            ->field('id,username')
            ->select();
        return $data;
    }
    public function one(){
	    return $this->fetch('');
    }
    public function ones(){
        $request = \think\Request::instance();
        $file = $request->file('file');
        $save_path = ROOT_PATH . 'uploads/';
        $info = $file->move($save_path);
        $filename=$save_path . DIRECTORY_SEPARATOR . $info->getSaveName();
        if(file_exists($filename)) {//如果文件存在
            import('phpexcel.PHPExcel', EXTEND_PATH);
            if( strstr($filename,'.xlsx')){
                $PHPReader = new \PHPExcel_Reader_Excel2007();
            }else{
                $PHPReader = new \PHPExcel_Reader_Excel5();
            }
            //载入excel文件
            $PHPExcel = $PHPReader->load($filename);
            $sheet = $PHPExcel->getActiveSheet(0);//获得sheet
            $highestRow = $sheet->getHighestRow(); // 取得共有数据数
            $data=$sheet->toArray();
            for($i=1;$i<$highestRow;$i++){
                // $customer['username']=$data[$i][0];
                if ($data[$i][1]==0){
                    continue;
                }else{
                    $customer['username']=$data[$i][0];//excel中的第一栏
                    $customer['job_num']=db::name('user')
                        ->where('username',$customer['username'])
                        ->value('job_num');
                    $customer['ach_id']=0;
                    $customer['permonth']=1572537600;
                    $customer['commission_base']=6688;
                    for ($j=1;$j<=$data[$i][1];$j++){
                        db('achievementover')->insert($customer);
                    }
                }
            }
            $highestRow=$highestRow-1;
            if($highestRow){
                $arr = array('code' =>200,'msg'=>'上传'.$highestRow.'条数据！');
            }else{
                $arr = array('code' =>404,'msg'=>'上传数据为空');
            }
            return returnmsg($arr);
            // return $ret;
        }else{
            return array("resultcode" => -5, "resultmsg" => "文件不存在", "data" => null);
        }
    }
}
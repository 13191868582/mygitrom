<?php 
namespace app\index\model;
use think\Model;
use think\db;
use think\Session;
use think\NodeTree;
use app\index\model\Service;
/**
* 
*/
class User extends  Model
{
	//登陆
	public function dologin($data)
	{
		$username=$data['username'];
		$pwd=$data['password'];
		$user=Db::name('user')->where('job_num|mobile',$username)->find();
		$admin=Db::name('admin')->where('username',$username)->find();
		$stystem=Db::name('system')->find();
		Session::set('system',$stystem);
		if($admin && (md5($pwd)== $admin['password'])){
			$rank=4;
			$role1=Db::name('role')->where('id',$rank)->find();
			$role=explode(',', $role1['auths']);
			foreach ($role as $k => $v) {
				$pid=Db::name('author')->where('id',$v)->find();
				if($pid['pid']==0){
					$arr[]=$v;
				}
			}
			foreach ($arr as $k => $v) {
				
				$pid=Db::name('author')->field('id,name,title')->where('pid',$v)->where('id','IN',$role1['auths'])->select();
				$arr2[$v][]=$pid;
			}
			$add['job_num']=0;
			$add['username']=$username;
			$add['logintime']=time();
			$add['loginip']=$_SERVER["REMOTE_ADDR"];
			Db::name('record')->insert($add);
			Session::set('role',$arr2);
			Session::set('admin',$admin);
			return "3";
		}
		if(!$user){
			return  "0";
		}elseif(md5($pwd) != $user['pawd']){
			return "1";
		}elseif($user['status']==4){
			return "2";
		}else{
			$rank=$user['rank'];
			$role1=Db::name('role')->where('id',$rank)->find();
			$role=explode(',', $role1['auths']);
			foreach ($role as $k => $v) {
				$pid=Db::name('author')->where('id',$v)->find();
				if($pid['pid']==0){
					$arr[]=$v;
				}
			}
			foreach ($arr as $k => $v) {
				$pid=Db::name('author')->field('id,name,title')->where('pid',$v)->where('id','IN',$role1['auths'])->select();
				$arr2[$v][]=$pid;
			}
			$add['job_num']=$user['job_num'];
			$add['username']=$user['username'];
			$add['logintime']=time();
			$add['loginip']=$_SERVER["REMOTE_ADDR"];
			Db::name('record')->insert($add);
			$start_time=strtotime(date("Y-m-d",time()));
			//当天结束之间
			$end_time=$start_time+60*60*24;
			$num=Db::name('record')->where('job_num',$user['job_num'])->where('logintime','between time',[$start_time,$end_time])->count();
			//系统登陆赠豆数
			$beanlogin=Session::get('system.bean_login');
			if($num<3){
				$res=Db::name('user')->where('id',$user['id'])->setInc('bean_coin',$beanlogin);
				$bean['uid']=$user['id'];
				$bean['bean']=$beanlogin;
				$bean['actime']=time();
				$bean['content']=$username.'登陆赠送'.$beanlogin.'个';
				Db::name('bean_log')->insert($bean);
			}
			Session::set('role',$arr2);
			Session::set('user',$user);
			$this->addservice();
			return "3";
		}

	}

	//扫码执行登陆
	public function dologined($user)
	{
			$stystem=Db::name('system')->find();
			Session::set('system',$stystem);
			$rank=$user['rank'];
			$role1=Db::name('role')->where('id',$rank)->find();
			$role=explode(',', $role1['auths']);
			foreach ($role as $k => $v) {
				$pid=Db::name('author')->where('id',$v)->find();
				if($pid['pid']==0){
					$arr[]=$v;
				}
			}
			foreach ($arr as $k => $v) {
				$pid=Db::name('author')->field('id,name,title')->where('pid',$v)->where('id','IN',$role1['auths'])->select();
				$arr2[$v][]=$pid;
			}
			$add['job_num']=$user['job_num'];
			$add['username']=$user['username'];
			$add['logintime']=time();
			$add['loginip']=$_SERVER["REMOTE_ADDR"];
			Db::name('record')->insert($add);
			$start_time=strtotime(date("Y-m-d",time()));
			//当天结束之间
			$end_time=$start_time+60*60*24;
			$num=Db::name('record')->where('job_num',$user['job_num'])->where('logintime','between time',[$start_time,$end_time])->count();
			//系统登陆赠豆数
			$beanlogin=Session::get('system.bean_login');
			if($num<3){
				$res=Db::name('user')->where('id',$user['id'])->setInc('bean_coin',$beanlogin);
				$bean['uid']=$user['id'];
				$bean['bean']=$beanlogin;
				$bean['actime']=time();
				$bean['content']=$user['username'].'登陆赠送'.$beanlogin.'个';
				Db::name('bean_log')->insert($bean);
			}
			Session::set('role',$arr2);
			Session::set('user',$user);
			$this->addservice();
			return "3";
	}
	//自动添加服务
	public function addservice()
	{
		$service=new Service();
		if(Session::get('user.id')){
			$uid=Session::get('user.id');
			$res=Db::name("employee_customer")->field('cid')->where('uid',$uid)->select();
			foreach ($res as $k => $v) {
				$cid=$v['cid'];
				$add['service_month']=$service->net_age($cid);
				$add['cid']=$cid;
				$add['uid']=$uid;
				$add['addtime']=time();
				//规定服务
				$service1=$service->service_cum1($cid);				
				if($service1){
					$ids = array(); 
					$ids = array_map('array_shift', $service1); 
					// die;
					$add['service1']=implode(',',$ids);
					//规定服务完成量用于做比率
					$add['p_num']=count($service1);
					// var_dump(count($service1));
					// //可选服务
					// $service2=$service->service_cum2($cid);
					// if($service2){
					// 	foreach ($service2 as $k => $v) {
					// 		$arr2[]=$v['id'];
					// 	}
					// 	$add['service2']=implode(',',$arr2);
					// 	//可选服务完成量
					// 	$add['o_num']=count($arr2);
					// }
					$where['uid']=$uid;
					$where['cid']=$cid;
					$where['service_month']=$add['service_month'];
					$res1=Db::name('service_completion')->where($where)->find();

					if($res1){
					}else{
						Db::name('service_completion')->insert($add);
					}
				}
			}
		}

	}
	//员工添加
	public function add_user($data)
	{
		$add['job_num']=$data['job_num'];
		$add['username']=$data['username'];
		$add['mobile']=$data['mobile'];
		$add['pawd']=md5(123456);
		$add['sex']=$data['sex'];
		// $add['age']=$data['age'];
		$add['identity_card']=$data['identity_card'];
		$add['id_pic']=$data['id_pic'];
		$add['id_pic1']=$data['id_pic1'];
		$add['branch_id']=$data['branch_id'];
		$add['department']=$data['department'];
		$add['addtime']=strtotime($data['addtime']);
		$add['rank']=$data['rank'];
		$add['status']=$data['status'];
		$job_num=Db::name('user')->where('job_num',$data['job_num'])->find();
		if($job_num){
			return "3";
		}
		$identity_card=Db::name('user')->where('identity_card',$data['identity_card'])->find();
		if($identity_card){
			return "4";
		}
		$res=Db::name('user')->insert($add);
		if($res){
		    $arr=array();
		    $u=Session::get('user.username');
		    $ad=Session::get('admin.username');
		    $arr['name']=isset($u)?$u:$ad;
		    $arr['type']=1;
		    $arr['time']=time();
		    $arr['job_num']=$add['job_num'];
		    db('userlog')->insert($arr);
			return "1";
		}else{
			return "2";
		}
	}
	//角色列表
	public function role()
	{
		$res=Db::name('role')->where('status',1)->select();
		return $res;
	}
	//靓照列表
	public function pic()
	{
		$uid=Session::get('user.id');
		$pic=Db::name('user')->where('id',$uid)->field('department,id_pic1,username')->find();
		return $pic;
	}
	//修改页面

	public function edit($id)
	{
		$data=Db::name('user')->where('id',$id)->find();
		if($data){
			return $data;
		}else{
			return "1";
		}
	}
	//修改操作
	public function doedit($data)
	{
		$id=$data['did'];
		$add['job_num']=$data['job_num'];
		$add['username']=$data['username'];
		// $add['pawd']=md5(123456);
		$add['sex']=$data['sex'];
		// $add['age']=$data['age'];
		$add['mobile']=$data['mobile'];
		$add['identity_card']=$data['identity_card'];
		$add['id_pic']=$data['id_pic'];
		$add['id_pic1']=$data['id_pic1'];
		$add['branch_id']=$data['branch_id'];
		$add['department']=$data['department'];
		$add['addtime']=strtotime($data['addtime']);
		$add['rank']=$data['rank'];
		$add['status']=$data['status'];
		$res=Db::name('user')->where('id',$id)->update($add);
		if($res){
            $arr=array();
            $u=Session::get('user.username');
            $ad=Session::get('admin.username');
            $arr['name']=isset($u)?$u:$ad;
            $arr['type']=2;
            $arr['time']=time();
            $arr['job_num']=$add['job_num'];
            db('userlog')->insert($arr);
			return "1";
		}else{
			return "2";
		}
	}
	//员工列表
	public function user_list($data)
	{
		
		if($data != ''){
			if ($data['job_num'] != '' && $data['username'] == "") {
				$page=Db::name('user')
					->alias('u')
					->join('silver_branch_office b','u.branch_id = b.id')
					->join('silver_department d','u.department = d.d_id')
					->field('u.*, b.name ,d.d_name')
	                ->where('job_num','like',"%".$data['job_num']."%")
	                ->paginate(30,false,['query' => request()->param()]);
			}
			if ($data['job_num'] == '' && $data['username'] != "") {
				$page=Db::name('user')
					->alias('u')
					->join('silver_branch_office b','u.branch_id = b.id')
					->join('silver_department d','u.department = d.d_id')
					->field('u.*, b.name ,d.d_name')
	                ->where('username','like',"%".$data['username']."%")
	                ->paginate(30,false,['query' => request()->param()]);
			}
			if ($data['job_num'] != '' && $data['username'] != "") {
				$page=Db::name('user')
					->alias('u')
					->join('silver_branch_office b','u.branch_id = b.id')
					->join('silver_department d','u.department = d.d_id')
					->field('u.*, b.name ,d.d_name')
				 	->where('job_num','like',"%".$data['job_num']."%")
	                ->where('username','like',"%".$data['username']."%")
	                ->paginate(30,false,['query' => request()->param()]);
			}
			if ($data['job_num'] == '' && $data['username'] == "") {
				$page=Db::name('user')
					->alias('u')
					->join('silver_branch_office b','u.branch_id = b.id')
					->join('silver_department d','u.department = d.d_id')
					->field('u.*, b.name ,d.d_name')
                ->paginate(30,false,['query' => request()->param()]);
			}
			
		}else{
			$page=Db::name('user')
				->alias('u')
				->join('silver_branch_office b','u.branch_id = b.id')
				->join('silver_department d','u.department = d.d_id')
				->field('u.*, b.name ,d.d_name')
                ->paginate(30,false,['query' => request()->param()]);
		}

		return $page;
	}

	//修改密码
   public function editNode($data){
        $da['pawd'] = md5($data['password']);
       if(db("user")->where("id",$data['id'])->update($da)){
           $ret['ok']=true;
           $ret['msg']='成功';
           return $ret;
       }else{
           $ret['ok']=false;
           $ret['msg']='失败';
           return $ret;
       }
   }

   //首页pk榜数据
   //集团续签率榜首
   public function pkList()
   {
   		$date=lastTime();
   		$start=$date['start'];
   		$end=$date['end'];
   		$pklist=Db::name('user')->where('rank',5)->field('id,username')->select();
   		foreach ($pklist as $k=>$v){
   			$num=Db::name('renewal')->where('user_id',$v['id'])->where('expires','between time',[$start,$end])->count();
   			$num1=Db::name('employee_customer')->where('uid',$v['id'])->where('maturitys','between time',[$start,$end])->count();
   			if($num1){
   				$pklist[$k]['num']=$num/$num1*100;
   			}else{
   				$pklist[$k]['num']=0;
   			}
   			
   		}
   		if($pklist){
   			$arr=arraySort($pklist,'num','desc');
   		}else{
   			$arr=array();
   		}
   		return $arr;
   }
   //分公司续签率榜首
   public function pkCompany()
   {
   		$date=lastTime();
   		$start=$date['start'];
   		$end=$date['end'];
   		$branch_id=Session::get('user.branch_id');
   		$pklist=Db::name('user')->where('rank',5)->where('branch_id',$branch_id)->field('id,username')->select();
   		foreach ($pklist as $k=>$v){
   			$num=Db::name('renewal')->where('user_id',$v['id'])->where('expires','between time',[$start,$end])->count();
   			$num1=Db::name('employee_customer')->where('uid',$v['id'])->where('maturitys','between time',[$start,$end])->count();
   			if($num1){
   				$pklist[$k]['num']=$num/$num1*100;
   			}else{
   				$pklist[$k]['num']=0;
   			}
   			
   		}
   		if($pklist){
   			$arr1=arraySort($pklist,'num','desc');
   		}else{
   			$arr1=array();
   		}
   		return $arr1;
   }

   //集团服务完结率榜首
   public function serviceFirst()
   {
   		$date=lastTime();
   		$start=$date['start'];
   		$end=$date['end'];
   		$user=Db::name('user')->where('rank',5)->field('id,username')->select();
   		foreach ($user as $k => $v) {
   			$num=Db::name('service_completion')->where('uid',$v['id'])->where('addtime','between time',[$start,$end])->field('p_num,p_nums')->find();
   			if($num['p_num']){
   				$user[$k]['ratio']=$num['p_nums']/$num['p_num']*100;
   			}else{
   				$user[$k]['ratio']=0;
   			}
   			
   		}
   		if($user){
   			$ratio=arraySort($user,'ratio','desc');
   		}else{
   			$ratio=array();
   		}
   		return $ratio;
   }
   //公司服务完结率榜首
   public function serCompany()
   {
   		$date=lastTime();
   		$start=$date['start'];
   		$end=$date['end'];
   		$branch_id=Session::get('user.branch_id');
   		$user=Db::name('user')->where('rank',5)->where('branch_id',$branch_id)->field('id,username')->select();
   		foreach ($user as $k => $v) {
   			$num=Db::name('service_completion')->where('uid',$v['id'])->where('addtime','between time',[$start,$end])->field('p_num,p_nums')->find();
   			if($num['p_num']){
   				$user[$k]['ratio']=$num['p_nums']/$num['p_num']*100;
   			}else{
   				$user[$k]['ratio']=0;
   			}
   		}
   		if($user){
   			$ratio1=arraySort($user,'ratio','desc');
   		}else{
   			$ratio1=array();
   		}
   		return $ratio1;
   }

   // //退出更新时间
   public function exit()
   {
   		if(Session::get('user.job_num')){
   			$job_num=Session::get('user.job_num');
   			$id=Db::name('record')->where('job_num',$job_num)->order('id desc')->find();
   			$id=$id['id'];
   			$update['exitime']=time();
   			$res=Db::name('record')->where('id',$id)->update($update);
   			return $res;
   		}else{
   			$job_num=0;
   			$id=Db::name('record')->where('job_num',$job_num)->order('id desc')->find();
   			$id=$id['id'];
   			$update['exitime']=time();
   			$res=Db::name('record')->where('id',$id)->update($update);
   			return $res;
   		}
   }

   //查询问题
   public function problem()
   {
   		//判断题
   		$id1=Db::name('topic')->where('question_type',1)->select();
   		$k=array_rand($id1);
   		$nid1=$id1[$k]['id'];
   		$problem1=Db::name('topic')->where('id',$nid1)->find();
   		$problem1['options']=explode("|", $problem1['options']);
   		//单选题
   		$id2=Db::name('topic')->where('question_type',2)->select();
   		$k=array_rand($id2);
   		$nid2=$id2[$k]['id'];
   		$problem2=Db::name('topic')->where('id',$nid2)->find();
   		$problem2['options']=explode("|", $problem2['options']);
   		// //多选题
   		// $id3=Db::name('topic')->where('question_type',3)->select();
   		// $k=array_rand($id3);
   		// $nid3=$id3[$k]['id'];
   		// $problem3=Db::name('topic')->where('id',$nid3)->find();
   		// $problem3['options']=explode("|", $problem3['options']);
   		$res=array($problem1,$problem2);
   		return $res;
   		// $ids=Db::name('topic')->column('id');
   		// $rand_list = array_rand($ids,3);
   		// $tuijian_array = array();
     //    foreach ((array)$rand_list as $key) {
     //        $tuijian_array[] = $ids[$key];
     //    }
     //    $randArr = Db::name('topic')->where('id','in',$tuijian_array)->select();
     //    foreach ($randArr as $k => $v) {
     //    	$randArr[$k]['options']=explode('|',$v['options']);
     //    }
     //    // var_dump($randArr);
     //    return $randArr;
   }
   //保存问题
   public function problemed($data)
   {
   		$problem1=$data['problem1'];
		$pid1=$data['pid1'];
		$bean_answer=Session::get('system.bean_answer');
		$answer1=Db::name('topic')->where('id',$pid1)->field('answer')->find();
		if($answer1['answer']==$problem1){
			$num1=$bean_answer;
			$res['num1']=$bean_answer;
		}else{
			$num1=0;
			$res['num1']=0;
		}
		$problem2=$data['problem2'];
		$pid2=$data['pid2'];
		$answer2=Db::name('topic')->where('id',$pid2)->field('answer')->find();
		if($answer2['answer']==$problem2){
			$num2=$bean_answer;
			$res['num2']=$bean_answer;
		}else{
			$num2=0;
			$res['num2']=0;
		}
		
		// $problem3=$data['problem3'];
		// $pid3=$data['pid3'];
		// $answer3=Db::name('topic')->where('id',$pid3)->field('answer')->find();
		// if($answer3['answer']==$problem3){
		// 	$num3=$bean_answer;
		// 	$res['num3']=$bean_answer;
		// }else{
		// 	$num3=0;
		// 	$res['num3']=0;
		// }
		$data['uid']=Session::get('user.id');
		$data['num']=$num=$num1+$num2;
		$data['addtime']=time();
		$uid=Session::get('user.id');
		$bean['uid']=Session::get('user.id');
		$bean['bean']=$num;
		$bean['actime']=time();
		$bean['content']='答对了'.$num.'个问题，赠送'.$num.'个豆币';
		if($num1 !=0 && $num2 !=0){
			$res['as']=Db::name('answer_record')->insert($data);
			Db::name('bean_log')->insert($bean);
			Db::name('user')->where('id',$uid)->setInc('bean_coin',$num);
		}
		return $res;
   }

   //是否作答
   public function prp()
   {
   		//当天开始时间
   		$start_time=strtotime(date("Y-m-d",time()));
		//当天结束之间
		$end_time=$start_time+60*60*24;
		$uid=Session::get('user.id');
		$count=Db::name('answer_record')->where('uid',$uid)->where('addtime','between time',[$start_time,$end_time])->count();
		return $count;
   }
   //豆币列表
   public function beanlist($id)
   {
   		$res=Db::name('user')->where('id',$id)->field('bean_coin')->find();
   		return $res;
   }


   //分公司奥斯卡月度
    public function  month(){
	    $time=strtotime(date('Y-m',time()));
//	    $data['one']=Db::name('honor')->where('ranking',1)->where('h_month',$time)->find();
//	    $data['two']=Db::name('honor')->where('ranking',2)->where('h_month',$time)->find();
//       $data['three']=Db::name('honor')->where('ranking',3)->where('h_month',$time)->find();

        $arr=array(1,2,3);
        $data=Db::name('honor')->where('ranking','in',$arr)->where('h_month',$time)->select();
        return $data;
    }
    //分公司奥斯卡季度
    public function  season($q){
        $time=date("Y",time());
	    if ($q=="S1"||$q=="S2"){
	        $where['h_halfyear']=$time."-".$q;
        }
	    if ($q=="Q1"||$q=="Q2"||$q=="Q3"||$q=="Q4"){
            $where['h_season']=$time."-".$q;
        }
	    if ($q=="year"){
            $where['h_year']=$time;
        }
        $arr=array(1,2,3);
	    $data=Db::name("honor")
            ->where('ranking','in',$arr)
            ->where($where)
            ->select();
        return $data;
    }


    //名人堂TOP123
    public function bestnew1(){
        $arr=array(1,2,3);
        $where=array();
        $where['department']="新签";
        $data=Db::name('fame')
            ->where('ranking','in',$arr)
            ->where($where)
            ->order('ranking asc')
            ->order('people_time desc')
            ->limit(0,3)
            ->select();
        return $data;
    }
    public function bestnew($m){
        $arr=array(1,2,3);
        $where=array();
        if ($m!=""){
            $where['department']=$m;
        }
	    $data=Db::name('fame')
            ->where('ranking','in',$arr)
            ->where($where)
            ->order('ranking asc')
            ->order('people_time desc')
            ->limit(0,3)
            ->select();
        return $data;
    }
    //优秀领军人
    public function wbest($y){
	    $data=Db::name('wbest')
            ->where('w_rank',$y)
            ->order('w_time desc')
            ->find();
//        $data['j']=Db::name('wbest')->where('w_rank',"节点精英")
//            ->order('w_time desc')
//            ->find();
        return $data;
    }

    //王牌VS王牌月度
    public function trump($w){
        $time=strtotime(date('Y-m',time()));
        $arr=array(1,2,3);
	    $data=Db::name('trump')
            ->where('ranking','in',$arr)
            ->where('department',$w)
            ->where('h_month',$time)
            ->select();
        return $data;
    }
    public function trump1($w){
        $arr=array(1,2,3);
        $time=strtotime(date('Y-m',time()));
        $data=Db::name('trump')
            ->where('ranking','in',$arr)
            ->where('department',"新签")
            ->where('h_month',$time)
            ->select();
        return $data;
    }
    public function seasontrump($b,$c){
        $time=date("Y",time());
        $where=array();
        $arr=array(1,2,3);
        if ($b=="Q1"||$b=="Q2"||$b=="Q3"||$b=="Q4"){
            $where['h_season']=$time."-".$b;
        }
        if ($b=="S1"||$b=="S2"){
            $where['h_halfyear']=$time."-".$b;
        }
	    $data=Db::name('trump')
            ->where('ranking','in',$arr)
            ->where('department',$c)
            ->where($where)
            ->select();
        return $data;
    }



    public function yeartrump($v,$n){
        $time=date("Y",time());
        $arr=array(1,2,3);
        $data=Db::name('trump')
            ->where('ranking','in',$arr)
            ->where('department',$n)
            ->where('h_year',$time)
            ->select();
        return $data;
    }
}
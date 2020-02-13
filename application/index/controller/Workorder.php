<?php
namespace app\index\controller;
use think\controller;
use app\api\controller\Notice;
use app\index\model\Workorders;
use think\Request;
use think\Session;
use think\Db;
/**
 * 
 */
class Workorder extends Common
{
	//工单列表
	public function index()
	{
		$request=Request::instance();
		$bid=Session::get('user.branch_id');
		$per_dd_status=$request->param('per_dd_status');
		if($per_dd_status != ''){
			$data=Db::name('per_order')
			  ->where('per_status',3)
			  ->where('per_type',3)
			  ->where('per_bid',$bid)
			  ->where('per_dd_status',$per_dd_status)
			  ->paginate(100,false,['query' => request()->param()]);
		}else{
			$data=Db::name('per_order')
			  ->where('per_status',3)
			  ->where('per_type',3)
			  ->where('per_bid',$bid)
			  ->paginate(100,false,['query' => request()->param()]);
		}
		
		$this->assign('data',$data);
		return $this->fetch();
	}
	//分配任务页面
	public function work()
	{
		$request=Request::instance();
		$id=$request->param('id');
		$bid=$request->param('bid');
		// var_dump($bid);
		// $bid=14;
		$user=Db::name('user')
			  ->where('branch_id',$bid)
			  ->where('dd_postion','美工')
			  ->field('id,username')
			  ->select();
		$this->assign('users',$user);
		$this->assign('pid',$id);
		return $this->fetch();
	}
	//下发任务
	public function dowork()
	{
		$request=Request::instance();
		$data=$request->param();
		$per_id=$data['oid'];
		$uid=$data['user'];
		$per_create_time=strtotime($data['date']);
		$update['per_create_time']=$per_create_time;
		$update['per_dd_status']=1;
		$update['per_stop_time']=strtotime($data['date1']);
		$work['oid']=$per_id;
		$work['uid']=$uid;
		$work['ctime']=$per_create_time;
		$work['stime']=strtotime($data['date1']);
		$work['content']=$data['content'];
		//创建时间
		Db::startTrans();
 		try{
 		Db::name('per_order')->where('per_id',$per_id)->update($update);
 		$res=Db::name('work')->insert($work);
		 // 提交事务
	    Db::commit();   
       } catch (\Exception $e) {
	    // 回滚事务
	    Db::rollback();
       };
       if($res){
       	$arr = array('code' =>200,'msg'=>'下发成功！');
       }else{
       	$arr = array('code' =>404,'msg'=>'下发失败！');
       }
       return returnmsg($arr);
	}

	//查看任务
	public function cha()
	{
		$request=Request::instance();
		$data=$request->param();
		$id=$data['id'];
		$a=Db::name('work')->where('oid',$id)->find();
		$b=Db::name('user')->where('id',$a['uid'])->find();
		$a['username']=$b['username'];
		$this->assign('a',$a);
		return $this->fetch();
	}
	//接收任务
	public function jiework()
	{
		$id=Session::get('user.id');
		$data=Db::name('work')
			 ->alias('w')
			 ->join('per_order o','w.oid=o.per_id','LEFT')
			 ->where('w.uid',$id)
			 ->paginate(100,false,['query' => request()->param()]);
		$this->assign('data',$data);
		return $this->fetch();
	}
	//接收
	public function jie()
	{
		$request=Request::instance();
		$id=$request->param('id');
		$a=Db::name('work')->where('oid',$id)->find();
		$this->assign('a',$a);
		return $this->fetch();
	}
	//执行接收任务
	public function dojie()
	{
		$request=Request::instance();
		$data=$request->param();
		$pid=$data['pid'];
		$update['per_dd_status']=2;
		$work['retime']=time();
		//创建时间
		Db::startTrans();
 		try{
 		Db::name('per_order')->where('per_id',$pid)->update($update);
 		$res=Db::name('work')->where('oid',$pid)->update($work);
		 // 提交事务
	    Db::commit();   
       } catch (\Exception $e) {
	    // 回滚事务
	    Db::rollback();
       };
       if($res){
       	$arr = array('code' =>200,'msg'=>'下发成功！');
       }else{
       	$arr = array('code' =>404,'msg'=>'下发失败！');
       }
       return returnmsg($arr);
	}
	//提交任务
	public function ti()
	{
		$request=Request::instance();
		$id=$request->param('id');
		$a=Db::name('work')->where('oid',$id)->find();
		$this->assign('a',$a);
		return $this->fetch();
	}
	//执行提交任务
	public function doti()
	{
		$request=Request::instance();
		$data=$request->param();
		$pid=$data['pid'];
		$update['per_dd_status']=3;
		$work['ftime']=time();
		//创建时间
		Db::startTrans();
 		try{
 		Db::name('per_order')->where('per_id',$pid)->update($update);
 		$res=Db::name('work')->where('oid',$pid)->update($work);
		 // 提交事务
	    Db::commit();   
       } catch (\Exception $e) {
	    // 回滚事务
	    Db::rollback();
       };
       if($res){
       	$arr = array('code' =>200,'msg'=>'提交成功！');
       }else{
       	$arr = array('code' =>404,'msg'=>'提交失败！');
       }
       return returnmsg($arr);
	}
	//任务盯一下
	public function ding()
	{
		$request=Request::instance();
		$data=$request->param();
		$uid=$data['id'];
		$user=Db::name('user')->where('id',$uid)->find();
		$dingding = db('dingding')->where('mobile','13463816347')->find();
	 	$msg = [
            'msgtype' => "text",
            "text" => [
                'content'=>"工作通知:你有新的美工任务,请登录公司系统接收任务",
            ],
        ];
        $api = new Notice();
        $result=$api->ding($dingding['userid'],json_encode($msg));
    	 if($result){
	       	$arr = array('code' =>200,'msg'=>'提交成功！');
	       }else{
	       	$arr = array('code' =>404,'msg'=>'提交失败！');
	       }
       	return returnmsg($arr);

	}
	//销售确认任务
	public function userwork()
	{
		$request=Request::instance();
		$uid=Session::get('user.id');
		$per_dd_status=$request->param('per_dd_status');
		if($per_dd_status != ''){
			$data=Db::name('per_order')
			  ->where('per_status',3)
			  ->where('per_type',3)
			  ->where('per_uid',$uid)
			  ->where('per_dd_status',$per_dd_status)
			  ->order('per_dd_status desc')
			  ->paginate(100,false,['query' => request()->param()]);
		}else{
			$data=Db::name('per_order')
			  ->where('per_status',3)
			  ->where('per_type',3)
			  ->where('per_uid',$uid)
			  ->order('per_dd_status desc')
			  ->paginate(100,false,['query' => request()->param()]);
		}
		
		$this->assign('data',$data);
		return $this->fetch();
	}

	public function finish()
	{
		$request=Request::instance();
		$id=$request->param('id');
		$update['per_dd_status']=4;
		$res=Db::name('per_order')->where('per_id',$id)->update($update);
		if($res){
	       	$arr = array('code' =>200,'msg'=>'确认成功！');
	       }else{
	       	$arr = array('code' =>404,'msg'=>'确认失败！');
	       }
       	return returnmsg($arr);

	}
}
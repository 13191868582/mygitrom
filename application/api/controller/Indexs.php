<?php 
namespace app\api\controller;
use think\Controller;
use think\Session;
use app\api\model\Index;
use think\Request;

/**
 * 首页接口
 */
class Indexs extends controller
{
	/**
	 * 新签 续签 P4P
	 * @return [type] [description]
	 */
	public function index()
	{
		// var_dump(Session::get('a_user'));
		$index = new Index();
		$res['n_month'] = $index->nmonth();
		$res['n_day'] = $index->nday();
        $res['ns_day'] = $index->nsday();
        $res['ns_month'] = $index->nsmonth();
		$res['c_day'] = $index->cday();
		$res['c_month'] = $index->cmonth();
        $res['cs_day'] = $index->csday();
        $res['cs_month'] = $index->csmonth();
		$res['p_day'] = $index->p4pday();
		$res['p_month'] =$index->p4pmonth();
        $res['cp_day'] = $index->cp4pday();
        $res['cp_month'] =$index->cp4pmonth();
        $res['p4p']=$index->p4p();
		return json_encode($res);

	}

	/**
	 * 公海
	 * @return [type] [description]
	 */
	public function coom()
	{
		$index = new Index();
		$res = $index->coom();
		return json_encode($res);
	}

	/**
	 * 销售锦囊
	 * @return [type] [description]
	 */
	public function salespackage()
	{
		$index = new Index();
		$res = $index->salespackage();
		return json_encode($res);
	}

	/**
	 * 我的客户
	 * @return [type] [description]
	 */
	public function myclient()
	{
		$request=Request::instance();
        $data=$request->param();
        $res=new Index();
        $result=$res->myclient($data);
        if($result==1){
            $arr = array('code' =>0,'msg'=>'添加成功！');
        }else if($result==3){
            $arr = array('code' =>1,'msg'=>'数据溢出！');
        }else if($result==4){
            $arr = array('code' =>2,'msg'=>'手机号已存在！');
        }else{
            $arr = array('code' =>3,'msg'=>'添加失败！');
        }

        return returnmsg($arr);
	}

	/**
	 * 撞单查询
	 * @return [type] [description]
	 */
	public function collisionCheck()
	{
		$request=Request::instance();
        $data=$request->param();
        $res=new Index();
        $result=$res->collisionCheck($data);
        if ($result != '') {
        	$arr = [
        		'code' => 0,
        		'msg' => '已存在'
        	];
        }else{
        	$arr = [
        		'code' => 1,
        		'msg' => '不存在'
        	];
        }

        return returnmsg($arr);

	}

	public function workSchedule()
	{
		$request=Request::instance();
        $data=$request->param();
        $res=new Index();
        $result=$res->workSchedule($data);
        if ($result) {
        	$arr = [
        		'code' => 0,
        		'msg' => '成功'
        	];
        }else{
        	$arr = [
        		'code' => 1,
        		'msg' => '失败'
        	];
        }

        return returnmsg($arr);

	}

}
<?php
namespace app\index\controller;
use think\controller;
use app\index\model\Good;
use think\Request;
use think\Session;
/**
 * 商品管理
 */
class Goods extends Common
{
	//添加商品
	public function add()
	{
		return $this->fetch();
	}
	//执行添加商品
	public function doadd()
	{
		$request=Request::instance();
        $data=$request->param();
		if ($data['goods_name'] == '') {
			$arr = array('code' =>404,'msg'=>'请输入商品名！');
		}elseif ($data['price'] == '') {
			$arr = array('code' =>404,'msg'=>'请输入价格！');
		}else{
			$good=new Good();
			$res=$good->add($data);
			if($res){
	            $arr = array('code' =>200,'msg'=>'添加成功！');
	        }else{
	            $arr = array('code' =>404,'msg'=>'添加失败！');
	        }
		}
		
        return returnmsg($arr);
	}
	//商品列表
	public function list()
	{
		$good=new Good();
		$res=$good->list();
		return $this->fetch('',['page'=>$res]);
	}
	//商城列表
	public function shoplist()
	{
		$good=new Good();
		$res=$good->list();
		return $this->fetch('',['page'=>$res]);
	}
	//商城购买
	public function buy()
	{
		$request=Request::instance();
		$data=$request->param('id');
		$good=new Good();
		$res=$good->buy($data);
		$obj= new \app\index\controller\Customers;
        //订单号
        $res['createOrderNum']=$obj->createOrderNum();
		return $this->fetch('',['data'=>$res]);
	}
	//购买添加信息
	public function tobuy()
	{
		$request=Request::instance();
		$data=$request->param();
		$user=Session::get("user");
		$data['uid'] = $user['id'];
		$data['bid'] = $user['branch_id'];
		$good=new Good();
		$res=$good->tobuy($data);

		if($res == 0){
            $arr = array('code' => 200,'msg' => '购买成功！');
        }else if($res == 1){
            $arr = array('code' => 404,'msg' => '购买失败！');
        }else if ($res == 2) {
        	$arr = array('code' => 500,'msg' => '豆币不足！');
        }
        return returnmsg($arr);
	}
	//修改商品
	public function edit()
	{
		$request=Request::instance();
		$id=$request->param('id');
		$good=new Good();
		$res=$good->edit($id);
		return $this->fetch('',['good'=>$res]);
	}
	//修改商品操作
	public function doedit()
	{
		$request=Request::instance();
		$data=$request->param();
		if ($data['goods_name'] == '') {
			$arr = array('code' =>404,'msg'=>'请输入商品名！');
		}elseif ($data['price'] == '') {
			$arr = array('code' =>404,'msg'=>'请输入价格！');
		}else{
			$good=new Good();
			$res=$good->doedit($data);
			if($res){
	            $arr=array('code'=>200,'msg'=>'修改成功！');
	        }else{
	            $arr=array('code'=>404,'msg'=>'修改失败！');
	        }
		}		
        return returnmsg($arr);
	}
	//删除商品西信息
	public function del()
	{
		$request=Request::instance();
		$data=$request->param('id');
		$good=new Good();
		$res=$good->del($data);
		if($res){
			$arr=array('code'=>200,'msg'=>'删除成功！');
		}else{
			$arr=array('code'=>404,'msg'=>'删除失败！');
		}
		return returnmsg($arr);
	}

	/**
	 * 商品详情
	 * @return [type] [description]
	 */
	public function goodlist()
	{
		$request=Request::instance();
		$data=$request->param('id');
		$good = db('goods')->where('id',$data)->find();
		
		return $this->fetch('',['good'=>$good]);
	}
}
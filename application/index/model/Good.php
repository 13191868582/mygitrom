<?php
namespace app\index\model;
use think\Model;
use think\db;
use think\Session;
/**
 * 商品类别
 */
class Good extends Model
{
	//添加
	public function add($data)
	{
		$goods['goods_name']=$data['goods_name'];
		$goods['goods_pic']=$data['goods_pic'];
		$goods['content']=$data['zcx'];
		$goods['price']=$data['price'];
        $res=Db::name('goods')->insert($goods);
        return $res;
	}
	//列表
	public function list()
	{
		$res=Db::name('goods')->paginate(30,false,['query' => request()->param()]);
		return $res;
	}
	//购买添加地址页面
	public function buy($id)
	{
		$res=Db::name('goods')->where('id',$id)->find();
		return $res;
	}
	//修改商品信息
	public function edit($id)
	{
		$res=Db::name('goods')->where('id',$id)->find();
		return $res;
	}
	//修改商品信息操作
	public function doedit($data)
	{
		$id=$data['gid'];
		unset($data['gid']);

		$res=Db::name('goods')->where('id',$id)->update($data);
		
		if($res){
			return true;
		}else{
			return false;
		}
	}
	//删除商品信息
	public function del($id)
	{
		$pic=Db::name('goods')->where('id',$id)->field('goods_pic')->find();
		if(file_exists($pic['goods_pic'])){
			$this->unlink($_SERVER['SERVER_NAME'].$pic['goods_pic']);
		}
		$res=Db::name('goods')->where('id',$id)->delete();
		if($res){
			return true;
		}else{
			return false;
		}
	}

	//添加订单
	public function tobuy($data)
	{
		//查询用户表
		$user = db('user')->where('id',$data['uid'])->find();
		if ($user['bean_coin'] >= $data['price']) {
			Db::startTrans();
			try {
				$order = [
					'uid' => $data['uid'],
					'bid' => $data['bid'],
					'order_num' => $data['ordernum'],
					'order_address' => $data['address'],
					'order_time' => time(),
					'order_status' => 0,

				];

				$res = db('order')->insert($order);
				$bean_coin = $user['bean_coin'] - $data['price'];
				$asd = [
					'bean_coin' => $bean_coin
				];
				db('user')->where('id',$user['id'])->update($asd);

				
				Db::commit();
			} catch (\Exception $e) {
            	// 回滚事务
           		// dump($e->getMessage()); //打印错误
            	Db::rollback(); //同时回滚，将不会插入任何一条
        	}



        	if ($res) {
				return 0;
			}else{
				return 1;
			}


			
		}else{
			return 2;
		}
		
	}
}
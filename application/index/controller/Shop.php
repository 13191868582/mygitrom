<?php

namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Session;


class Shop extends Common
{
	public function index()
	{

		$user = Session::get('user');
		$admin = Session::get('admin');

        if ($admin){

        }else{
                if ($user['rank'] == 22 || $user['rank'] == 23 || $user['rank'] == 18){
                    $where['branck_id'] = $user['branch_id'];
                }
                if ( $user['rank'] == 17)
                {
                    $where['branck_id'] = $user['branch_id'];
                    $where['dd_number'] = $user['dd_number'];
                }
                if ($user['rank'] == 24 || $user['rank'] == 16){
                    $where['user_id'] = $user['id'];
                }
        }



		if (!empty($where)) {
			$result = db('shop')
                        ->field('id,shop_id,corporate_name,username,phone')
                        ->where('expire_time','egt',time())
                        ->where($where)
                        ->paginate(20);
			$count = db('shop')
                ->field('id,shop_id,corporate_name,username,phone')
                ->where('expire_time','egt',time())
                ->where($where)
                ->count();
		}else{

			$result = db('shop')
                        ->field('id,shop_id,corporate_name,username,phone')
                        ->where('expire_time','egt',time())
                        ->paginate(20);
            $count = db('shop')
                ->field('id,shop_id,corporate_name,username,phone')
                ->where('expire_time','egt',time())
                ->count();
		}
		
		return $this->fetch('index',['result'=>$result,'status'=>0,'count'=>$count]);
	}

	public function doadd()
	{
		return $this->fetch();
	}

	public function add()
	{
		$result = Request::instance()->param();
		$admin = Session::get('admin');
		$user = Session::get('user');

		if ($user == "") {
			$result['user_id'] = 'admin';
		}else{
			$result['user_id'] = $user['id'];
            $result['branck_id'] = $user['branch_id'];
		}
		
		$result['cooperation_time'] = strtotime($result['cooperation_time']);
		$result['expire_time'] = strtotime($result['expire_time']);
		$result['add_time'] = time();


		$res = db('shop')->insert($result);
		return $res;

	}

	public function date()
	{
		$id = Request::instance()->param('id');
		$result = db('shop')->where('id',$id)->find();
		$result['cooperation_time'] = date('Y-m-d',$result['cooperation_time']);
		return $result;
	}

	public function orderall($id)
    {
        $user = Session::get('user');
        $admin = Session::get('admin');
        if ($admin || $user['rank'] == 22){
            $orderDate = db('order_data')
                ->where('shop_id',$id)
                ->where('expire_time','>',time())
                ->paginate(20,false,['query' => request()->param()]);
            $result = db('shop')
                ->field('id,shop_id,corporate_name,username,phone')
                ->where('expire_time','egt',time())
                ->select();

        }else{
            $result = db('shop')
                ->field('id,shop_id,corporate_name,username,phone')
                ->where('expire_time','egt',time())
                ->where('user_id',$user['id'])
                ->select();
            $data =  strtotime(date("Y-m-d",strtotime("-30 day")));
            $orderDate = db('order_data')
                ->where('addtime','>',$data)
                ->where('shop_id',$id)
                ->where('expire_time','>',time())
                ->paginate(20,false,['query' => request()->param()]);

        }

        return $this->fetch('',['id'=>$id,'orderdate'=>$orderDate,'result'=>$result]);

    }

    public function orderSearch()
    {
        $user = Session::get('user');
        $admin = Session::get('admin');
        $result = Request::instance()->param();
        $where = ['shop_id'=>$result['id']];
        if (!empty($result['order_id'])){
            $where = ['order_id'=>$result['order_id']];
        }
        if (!empty($result['order_time'])) {
            $where = ['order_time' => strtotime($result['order_time'])];
        }

        if ($admin || $user['rank'] == 22){
            $orderDate = db('order_data')
                ->where($where)
                ->where('expire_time','>',time())
                ->paginate(20);
            $res = db('shop')
                ->field('id,shop_id,corporate_name,username,phone')
                ->where('expire_time','egt',time())
                ->select();

        }else{
            $res = db('shop')
                ->field('id,shop_id,corporate_name,username,phone')
                ->where('expire_time','egt',time())
                ->where('user_id',$user['id'])
                ->select();
            $data =  strtotime(date("Y-m-d",strtotime("-30 day")));
            $orderDate = db('order_data')
                ->where('addtime','>',$data)
                ->where('expire_time','>',time())
                ->where($where)
                ->paginate(20);

        }
        return $this->fetch('orderall',['id'=>$result['id'],'order_id'=>$result['order_id'],'order_time'=>$result['order_time'],'orderdate'=>$orderDate,'result'=>$res]);
    }

	public function orderdate($id)
	{
		$corporate_name = db('shop')->field('corporate_name')->where('id',$id)->find();
		// print_r($corporate_name);die;
		return $this->fetch('',['corporate_name'=>$corporate_name,'id'=>$id]);
	}

	public function doorder()
	{
		$result = Request::instance()->param();
		// print_r($result);die;
		$order = db('order_data')->where('order_id',$result['order_id'])->find();
		if ($order)
        {
            $msg = ['code'=>500,'msg'=>'订单存在'];
        }else{
            $result['order_time'] = strtotime($result['order_time']);
            $result['addtime'] = time();
            $expire_time = db('shop')->field('expire_time')->where('id',$result['shop_id'])->find();
            $result['expire_time'] = $expire_time['expire_time'];
            $user = Session::get('user');
            if ($user == "") {
                $result['staff_id'] = 'admin';
            }else{
                $result['staff_id'] = $user['id'];
                $result['branck_id'] = $user['branch_id'];
            }

            $res = db('order_data')->insert($result);
            if ($res) {
                $msg = ['code'=>200,'msg'=>'添加成功'];

            }else{
                $msg = ['code'=>500,'msg'=>'添加失败'];

            }
        }


		echo json_encode($msg);
	}

	public function edit($id)
    {
        $result = db('shop')->where('id',$id)->find();
        $result['cooperation_time'] = date('Y-m-d',$result['cooperation_time']);
        $result['expire_time'] = date('Y-m-d',$result['expire_time']);
        return $this->fetch('',['result'=>$result]);

    }

    public function do_edit()
    {
        $result = Request::instance()->param();
        $data['shop_id'] = $result['shop_id'];
        $data['password'] = $result['password'];
        $data['corporate_name'] = $result['corporate_name'];
        $data['phone'] = $result['phone'];
        $data['username'] = $result['username'];
        $data['cooperation_mode'] = $result['cooperation_mode'];
        $data['cooperation_time'] = strtotime($result['cooperation_time']);
        $data['expire_time'] = strtotime($result['expire_time']);
        $res = db('shop')->where('id',$result['id'])->update($data);
        if ($res) {
            $msg = ['code'=>200,'msg'=>'修改成功'];

        }else{
            $msg = ['code'=>500,'msg'=>'修改失败'];

        }
        echo json_encode($msg);

    }

    public function expire()
    {
        $user = Session::get('user');
        $admin = Session::get('admin');
        if ($admin){

        }else{
            if ($user['branch_id'] == 15){
                if ($user['rank'] == 22 || $user['rank'] == 23 || $user['rank'] == 17 || $user['rank'] == 18){
                    $where['branck_id'] = $user['branch_id'];
                }
                if( $user['rank'] == 17){

                }
                if ($user['rank'] == 24 || $user['rank'] == 16){
                    $where['user_id'] = $user['id'];
                }
            }else{
                if ($user['rank'] == 22 || $user['rank'] == 23 || $user['rank'] == 17 || $user['rank'] == 18){
                    $where['branck_id'] = $user['branch_id'];
                }
                if ($user['rank'] == 24 || $user['rank'] == 16){
                    $where['user_id'] = $user['id'];
                }
            }
        }

        if (!empty($where)) {
            $result = db('shop')
                ->field('id,shop_id,corporate_name,username,phone')
                ->where('expire_time','<',time())
                ->where($where)
                ->paginate(20);
            $count = db('shop')
                ->field('id,shop_id,corporate_name,username,phone')
                ->where('expire_time','<',time())
                ->where($where)
                ->count();
        }else{

            $result = db('shop')
                ->field('id,shop_id,corporate_name,username,phone')
                ->where('expire_time','<',time())
                ->paginate(20);
            $count = db('shop')
                ->field('id,shop_id,corporate_name,username,phone')
                ->where('expire_time','<',time())
                ->count();
        }

        return $this->fetch('index',['result'=>$result,'status'=>1,'count'=>$count]);

    }

    


}
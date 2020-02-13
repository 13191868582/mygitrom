<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request;

class Apicustomers
{
    public function customers()
    {
        $request=Request::instance();
        $data=$request->param();
        $time = time();

        if ($data['user'] == 'admin' && $data['pwd'] == md5("a123456")) {
            $customers = db('customer')->field('id,aliname,shop_address,corporate_name')->where('expires','gt',$time)->select();
            // echo "<pre>";
            // print_r($customers);

            $json = json_encode($customers);
        }else{
            $arr = ['账号密码错误'];
            $json = json_encode($arr);
        }

        return $json;
    }
    public function shop_null()
    {
        $request=Request::instance();
        $data=$request->param();
        $time = time();

        if ($data['user'] == 'admin' && $data['pwd'] == md5("a123456")) {
            $customers = db('customer')->field('id,aliname,shop_address,corporate_name')->where('expires','gt',$time)->where(['shop_address'=>""])->select();

            $json = json_encode($customers);
        }else{
            $arr = ['账号密码错误'];
            $json = json_encode($arr);
        }

        return $json;

    }

    public function upload_shop()
    {
        $request=Request::instance();
        $data=$request->param();


        db('customer')->where(['id'=>$data['id']])->update(['shop_address'=>$data['url']]);


    }

    public function alidps()
    {
        $request=Request::instance();
        $data=$request->param();
        db('customer')->where('id',$data['c_id'])->update(['a_level'=>$data['A_medal']]);
        $time = date("Y-m",time());
        $alidp = db('alidp')->where('time',"like",$time."%")->where('c_id',$data['c_id'])->count();

        if ($alidp == 0) {
            if(!empty($data['hms'])){
                if ($data['hms'] == "下降") {
                    $data['hms'] = 2;
                }elseif ($data['hms'] == '增长') {
                    $data['hms'] = 1;
                }
            }
            if(!empty($data['xys'])){
                if ($data['xys'] == "下降") {
                    $data['xys'] = 2;
                }elseif ($data['xys'] == '增长') {
                    $data['xys'] = 1;
                }
            }
            if(!empty($data['fhs'])){
                if ($data['fhs'] == "下降") {
                    $data['fhs'] = 2;
                }elseif ($data['fhs'] == '增长') {
                    $data['fhs'] = 1;
                }
            }

            $data['time'] = date("Y-m-d",time());
            unset($data['ids']);
            unset($data['medal']);
            unset($data['name']);


            if ($data['c_id'] != "") {
                db('alidp')->insert($data);
            }
        }else{
            $cid = db('alidp')->where(['c_id'=>$data['c_id']])->where('time',"like",$time."%")->max('id');
            $medal = db('alidp')->field('id,A_medal')->where('id',$cid)->find();


            if($medal['A_medal']  != $data['A_medal']){
                //获取公司id
                if(!empty($data['hms'])){
                    if ($data['hms'] == "下降") {
                        $data['hms'] = 2;
                    }elseif ($data['hms'] == '增长') {
                        $data['hms'] = 1;
                    }
                }
                if(!empty($data['xys'])){
                    if ($data['xys'] == "下降") {
                        $data['xys'] = 2;
                    }elseif ($data['xys'] == '增长') {
                        $data['xys'] = 1;
                    }
                }
                if(!empty($data['fhs'])){
                    if ($data['fhs'] == "下降") {
                        $data['fhs'] = 2;
                    }elseif ($data['fhs'] == '增长') {
                        $data['fhs'] = 1;
                    }
                }

                $data['time'] = date("Y-m-d",time());
                unset($data['ids']);
                unset($data['medal']);
                unset($data['name']);


                if ($data['c_id'] != "") {
                    db('alidp')->insert($data);
                }

            }else{
                if ($data['c_id'] != "") {
                    db('alidp')->where('id',$medal['id'])->update(['count'=>$data['count'],'amount'=>$data['amount']]);
                }
            }
        }

    }


    public function orderDate()
    {
        $request=Request::instance();
        $data=$request->param();


        $result = db('user')->where('mobile',$data['username'])->find();
        if ($result) {


            $result = db('user')->where('mobile',$data['username'])->find();


            $shop = db('shop')->field('id,expire_time')->where('corporate_name',$data['company_name'])->find();
            if ($shop) {
                $arr['order_id'] = $data['order_id'];
                $arr['user_id'] = $data['user_id'];
                $arr['phone'] = $data['phone'];
                $arr['user'] = $data['user'];
                $arr['address'] = $data['address'];
                $arr['order_time'] = strtotime($data['order_time']);
                $arr['num'] = $data['num'];
                $arr['money_all'] = $data['money_all'];
                $arr['category'] = $data['category'];
                $arr['addtime'] = time();
                $arr['shop_id'] = $shop['id'];
                $arr['staff_id'] = $result['id'];
                $arr['expire_time'] = $shop['expire_time'];
                $arr['branch_id'] = $result['branch_id'];
                $order = db('order_data')->where('order_id',$data['order_id'])->find();
                if ($order) {
                    $msg = ['code'=>0,'msg'=>'订单重复',];
                }else{
                    $res = db('order_data')->insert($arr);
                    if ($res) {
                        $msg = ['code'=>1,'msg'=>'录入成功',];
                    }else{
                        $msg = ['code'=>0,'msg'=>'录入失败',];
                    }
                }


            }else{
                $msg = [
                    'code' => 0,
                    'msg' => '店铺不存在',
                ];
            }

        }else{
            $msg = [
                'code' => 0,
                'msg' => '员工不存在',
            ];
        }


        return json_encode($msg);







    }






}
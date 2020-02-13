<?php

namespace app\index\controller;

use app\index\model\Censued;
use think\Session;
use think\Request;
use app\index\controller\PaginationArray;

class Census extends Common
{
    public  function  index()
    {
        $result = Request::instance()->param();
        $id = Request::instance()->param('id');
        $order_id = Request::instance()->param('order_id');
        $order_time = Request::instance()->param('order_time');
        $user = Session::get('user');
        $census = new Censued();
        $data = $census->orderSearch($result);


        return $this->fetch('',[
            'result'=>$data['result'],
            'orderData'=>$data['orderData'],
            'id'=>$id,
            'order_id'=>$order_id,
            'order_time'=>$order_time,
            'rank' => $user['rank'],
        ]);
    }

    public function repeat()
    {
        $result = Request::instance()->param('id');
        $census = new Censued();
        $data = $census->repeat($result);
//        echo "<pre>";
//        print_r($data);die;

        return $this->fetch('',['result'=>$data['result'],'orderData'=>$data['orderData'],'id'=>$result]);

    }

    public function gmv()
    {

        $result = Request::instance()->param('id');
        $gmv = Request::instance()->param('gmv');
        $census = new Censued();
        if ($gmv == 'm'){
            $data = $census->monthGmv($result);
        }
        if ($gmv == 'w'){
            $data = $census->weekGmv($result);
        }
        if ($result){
            return $this->fetch('',['time'=>$gmv,'result'=>$data['result'],'orderData'=>$data['orderData1'],'id'=>$result]);
        }else{
            foreach ($data['res'] as $key=>$value){

                foreach($data['orderData1'] as $k=>$v){
                    if ($value['id'] == $k){
//                    print_r($v[0]['money_all']);
                        if (empty($v)){

                            $value['money_all'] = 0;
                        }else{


                            $value['money_all'] = $v[0]['money_all'];
                        }
                    }
                }
                $order[] = $value;
            }
            if (empty($order)){
                $order = [];
            }

            $page = $data['res']->render();
            return $this->fetch('',['time'=>$gmv,'result'=>$data['result'],'orderData'=>$order,'id'=>$result,'page'=>$page]);
        }



    }

    public function compareGmv()
    {

        $result = Request::instance()->param('id');

        $census = new Censued();
        $data = $census->compareGmv($result);

        if ($result){
            $order[0]['corporate_name'] = $data['orderData1'][0]['corporate_name'];
            $order[0]['money_all'] = $data['orderData1'][0]['money_all'];
            $order[0]['money'] = $data['orderData'][0]['money_all'];
            $order[0]['name'] = $data['name'][0]['name'];
            return $this->fetch('',['result'=>$data['result'],'orderData'=>$order,'id'=>$result]);
        }else{
            foreach ($data['res'] as $key=>$value){
                foreach($data['name'] as $k=>$v){
                    if ($value['id'] == $k){
//                    print_r($v[0]['money_all']);
                        if (empty($v)){
                            $value['name'] = 0;
                        }else{
                            $value['name'] = $v[0]['name'];
                        }
                    }
                }
                foreach($data['orderData1'] as $k=>$v){
                    if ($value['id'] == $k){
//                    print_r($v[0]['money_all']);
                        if (empty($v)){
                            $value['money_all'] = 0;
                        }else{
                            $value['money_all'] = $v[0]['money_all'];
                        }
                    }
                }
                foreach($data['orderData'] as $k=>$v){
                    if ($value['id'] == $k){
//                    print_r($v[0]['money_all']);
                        if (empty($v)){
                            $value['money'] = 0;
                        }else{
                            $value['money'] = $v[0]['money_all'];
                        }
                    }
                }
                $order[] = $value;
            }

            if (empty($order)){
                $order = [];
            }
            $page = $data['res']->render();

            return $this->fetch('',['result'=>$data['result'],'orderData'=>$order,'id'=>$result,'page'=>$page]);
        }


    }

    public function warning()
    {
        $result = Request::instance()->param('grade');

        $census = new Censued();
        $data = $census->warning($result);


        return $this->fetch('',['id'=>$result,'result'=>$data['result'],'orderData' => $data['orderData']]);
    }
    //更新订单到期时间信息
    public function test()
    {
        $shop = db('shop')->field('id,expire_time,user_id')->select();

        foreach ($shop as $key=>$value){
            $order = db('order_data')->where('shop_id',$value['id'])->select();
            foreach ($order as $k=>$v){
                db('order_data')->where('id',$v['id'])->update(['expire_time'=>$value['expire_time']]);
            }

            if ($value['user_id'] != 'admin'){
                $user = db('user')->where('id',$value['user_id'])->find();
            }


            db('shop')->where('id',$value['id'])->update(['branck_id'=>$user['branch_id']]);
            db('order_data')->where('staff_id',$user['id'])->update(['branch_id'=>$user['branch_id']]);
        }
    }


}
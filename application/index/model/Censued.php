<?php
namespace app\index\model;

use think\Model;
use think\Session;
use think\Db;

class Censued extends Model
{
    public function orderSearch($result)
    {
        if (!empty($result['id'])){
            $Rwhere = ['shop_id'=>$result['id']];
        }
        if (!empty($result['order_id'])){
            $Rwhere = ['order_id'=>$result['order_id']];
        }
        if (!empty($result['order_time'])) {
            $Rwhere = ['order_time' => strtotime($result['order_time'])];
        }

        $admin = Session::get('admin');
        $user = Session::get('user');

        if ($admin){

        }else{
                if ($user['rank'] == 22 || $user['rank'] == 23 || $user['rank'] == 18){
                    $where['branck_id'] = $user['branch_id'];
                    $resWhere['brancn_id'] = $user['branch_id'];

                }
                if ($user['rank'] == 17){
                    $where['branck_id'] = $user['branch_id'];
                    $where['dd_number'] = $user['dd_number'];
                    $resWhere['brancn_id'] = $user['branch_id'];
                    $resWhere['dd_number'] = $user['dd_number'];
                }

                if ($user['rank'] == 24 || $user['rank'] == 16){
                    $where['user_id'] = $user['id'];
                    $resWhere['staff_id'] = $user['id'];
                }

        }
        if (empty($where)) {
            //没有判断条件
            $data['result'] = db('shop')
                ->where('expire_time','>',time())
                ->paginate(30,false,['query' => request()->param()]);
        }else{
            //存在判断条件
            $data['result'] = db('shop')
                ->where('expire_time','>',time())
                ->where($where)
                ->paginate(30,false,['query' => request()->param()]);
        }
        if (empty($resWhere)){
            if (empty($Rwhere)) {
                $data['orderData'] = db('order_data')
                    ->where('expire_time','>',time())
                    ->paginate(30,false,['query' => request()->param()]);
            }else{
                $data['orderData'] = db('order_data')
                    ->where('expire_time','>',time())
                    ->where($Rwhere)
                    ->paginate(30,false,['query' => request()->param()]);
            }

        }else{
            if (empty($Rwhere)) {
                $data['orderData'] = db('order_data')
                    ->where('expire_time','>',time())
                    ->where($resWhere)
                    ->paginate(30,false,['query' => request()->param()]);
            }else{
                $data['orderData'] = db('order_data')
                    ->where('expire_time','>',time())
                    ->where($resWhere)
                    ->where($Rwhere)
                    ->paginate(30,false,['query' => request()->param()]);
            }

        }


        return  $data;
    }

    public function repeat($result)
    {
        $admin = Session::get('admin');
        $user = Session::get('user');
        if ($admin){
            if(!empty($result)){
                $orderWhere['o.shop_id'] = $result;
            }
        }else{
            if ($user['rank'] == 23 || $user['rank'] == 18 || $user['rank'] == 22 ) {
                $resWhere['branck_id'] = $user['branch_id'];
                if (!empty($result)) {
                    $orderWhere['o.shop_id'] = $result;
                }
                $orderWhere['o.branch_id'] = $user['branch_id'];
            }
            if ($user['rank'] == 17){
                $resWhere['dd_number'] = $user['dd_number'];
                if (!empty($result)) {
                    $orderWhere['o.shop_id'] = $result;
                }
                $orderWhere['o.dd_number'] = $user['dd_number'];
            }
            if ($user['rank'] == 24 || $user['rank'] == 16) {
                $resWhere['user_id'] = $user['id'];
                if (!empty($result)) {
                    $orderWhere['o.shop_id'] = $result;
                }
                $orderWhere['o.staff_id'] = $user['id'];
            }
        }


            if (!empty($resWhere)){
                $data['result'] = db('shop')
                    ->where('expire_time','>',time())
                    ->where($resWhere)
                    ->select();
            }else{
                $data['result'] = db('shop')
                    ->where('expire_time','>',time())
                    ->select();
            }

        if (!empty($orderWhere)){
            $data['orderData'] =  Db::name('order_data')
                ->field('o.*,s.corporate_name')
                ->alias('o')
                ->join('silver_shop s' , 's.id = o.shop_id')
                ->where('o.expire_time','>',time())
                ->where('o.user_id','IN',function($query){
                    $query->name('order_data')->field('user_id')->group('user_id')->having('count(user_id) > 1');
                })
                ->where($orderWhere)
                ->order('o.user_id desc')
                ->paginate(100);
        }else{

            $data['orderData'] =  Db::name('order_data')
                ->field('o.*,s.corporate_name')
                ->alias('o')
                ->join('silver_shop s' , 's.id = o.shop_id')
                ->where('o.expire_time','>',time())
                ->where('o.user_id','IN',function($query){
                    $query->name('order_data')->field('user_id')->group('user_id')->having('count(user_id) > 1');
                })
                ->order('o.user_id desc')
                ->paginate(100);


        }







        return $data;

    }

    public function monthGmv($result)
    {
        $time = strtotime(date('Y-m-01 00:00:00', time()));
        $admin = Session::get('admin');
        $user = Session::get('user');
        if ($admin){
            if ($result){
                $resWhere['shop_id'] = $result;
            }
        }else{
            if ($user['rank'] == 22 || $user['rank'] == 23 ||$user['rank'] == 18) {
                $oWhere['branck_id'] = $user['branch_id'];
                $resWhere['branch_id'] = $user['branch_id'];
                if ($result){
                    $resWhere['shop_id'] = $result;
                }
            }
            if ($user['rank'] == 17){
                $oWhere['dd_number'] = $user['dd_number'];
                $resWhere['dd_number'] = $user['dd_number'];
                if ($result){
                    $resWhere['shop_id'] = $result;
                }
            }
            if ($user['rank'] == 24 || $user['rank'] == 16){
                $oWhere['user_id'] = $user['id'];
                $resWhere['staff_id'] = $user['id'];
                if ($result){
                    $resWhere['shop_id'] = $result;
                }
            }
        }


        if (!empty($oWhere)){
            $data['result'] = db('shop')
                        ->where('expire_time','>',time())
                        ->where($oWhere)
                        ->select();
        }else{
            $data['result'] = db('shop')
                ->where('expire_time','>',time())
                ->select();
        }
        if (!empty($oWhere)){
            $data['res'] = db('shop')
                ->where('expire_time','>',time())
                ->field('id,corporate_name')
                ->where($oWhere)
                ->paginate(20,false,['query' => request()->param()]);
        }else{
            $data['res'] = db('shop')
                ->where('expire_time','>',time())
                ->field('id,corporate_name')
                ->paginate(20,false,['query' => request()->param()]);
        }


        if ($result){
            $data['orderData1'] = db('order_data')
                ->field(['shop_id','SUM(money_all) as money_all'])
                ->where('order_time','>=',$time)
                ->where($resWhere)
                ->group('shop_id')
                ->select();
            if(array_key_exists(0,$data['orderData1'])){
                foreach ($data['result'] as $key=>$value){
                    if($value['id'] == $data['orderData1'][0]['shop_id']){
                        $data['orderData1'][0]['corporate_name'] = $value['corporate_name'];
                    }
                }
            }else{
                $corporate_name = db('shop')->field('corporate_name')->where('id',$result)->find();
                $data['orderData1'][0]['corporate_name'] = $corporate_name['corporate_name'];
                $data['orderData1'][0]['money_all'] = 0;
            }
        }else{
            foreach ($data['result'] as $key=>$value){
                if (!empty($resWhere)){

                    $data['orderData1'][$value['id']] = db('order_data')
                        ->field(['shop_id','SUM(money_all) as money_all'])
                        ->where('shop_id',$value['id'])
                        ->where('order_time','>=',$time)
                        ->where($resWhere)
                        ->group('shop_id')
                        ->select();



                }else{
                    $data['orderData1'][$value['id']] = db('order_data')
                        ->field(['shop_id','SUM(money_all) as money_all'])
                        ->where('shop_id',$value['id'])
                        ->where('order_time','>=',$time)
                        ->group('shop_id')
                        ->select();
                }
            }
        }



        return $data;
    }

    public function weekGmv($result)
    {
        $time = strtotime(date('Y-m-d 00:00:00',(time()-((date('w',time())==0?7:date('w',time()))-1)*24*3600)));
        $admin = Session::get('admin');
        $user = Session::get('user');
        if ($admin){
            if ($result){
                $resWhere['shop_id'] = $result;
            }
        }else{
            if ($user['rank'] == 22 || $user['rank'] == 23 ||$user['rank'] == 18) {
                $oWhere['branck_id'] = $user['branch_id'];
                $resWhere['branch_id'] = $user['branch_id'];
                if ($result){
                    $resWhere['shop_id'] = $result;
                }
            }
            if ($user['rank'] == 17){
                $oWhere['dd_number'] = $user['dd_number'];
                $resWhere['dd_number'] = $user['dd_number'];
                if ($result){
                    $resWhere['shop_id'] = $result;
                }
            }
            if ($user['rank'] == 24 || $user['rank'] == 16){
                $oWhere['user_id'] = $user['id'];
                $resWhere['staff_id'] = $user['id'];
                if ($result){
                    $resWhere['shop_id'] = $result;
                }
            }
        }





        if (!empty($oWhere)){
            $data['result'] = db('shop')
                ->where('expire_time','>',time())
                ->where($oWhere)
                ->select();
        }else{
            $data['result'] = db('shop')
                ->where('expire_time','>',time())
                ->select();
        }
        if (!empty($oWhere)){
            $data['res'] = db('shop')
                ->where('expire_time','>',time())
                ->field('id,corporate_name')
                ->where($oWhere)
                ->paginate(20,false,['query' => request()->param()]);
        }else{
            $data['res'] = db('shop')
                ->where('expire_time','>',time())
                ->field('id,corporate_name')
                ->paginate(20,false,['query' => request()->param()]);
        }


        if ($result){
            $data['orderData1'] = db('order_data')
                ->field(['shop_id','SUM(money_all) as money_all'])
                ->where('order_time','>=',$time)
                ->where($resWhere)
                ->group('shop_id')
                ->select();
            if(array_key_exists(0,$data['orderData1'])){
                foreach ($data['result'] as $key=>$value){
                    if($value['id'] == $data['orderData1'][0]['shop_id']){
                        $data['orderData1'][0]['corporate_name'] = $value['corporate_name'];
                    }
                }
            }else{
                $corporate_name = db('shop')->field('corporate_name')->where('id',$result)->find();
                $data['orderData1'][0]['corporate_name'] = $corporate_name['corporate_name'];
                $data['orderData1'][0]['money_all'] = 0;
            }
        }else{
            foreach ($data['result'] as $key=>$value){
                if (!empty($resWhere)){

                    $data['orderData1'][$value['id']] = db('order_data')
                        ->field(['shop_id','SUM(money_all) as money_all'])
                        ->where('shop_id',$value['id'])
                        ->where('order_time','>=',$time)
                        ->where($resWhere)
                        ->group('shop_id')
                        ->select();



                }else{
                    $data['orderData1'][$value['id']] = db('order_data')
                        ->field(['shop_id','SUM(money_all) as money_all'])
                        ->where('shop_id',$value['id'])
                        ->where('order_time','>=',$time)
                        ->group('shop_id')
                        ->select();
                }
            }
        }



        return $data;
    }

    public function compareGmv($result)
    {
        $data2 = strtotime(date('Y-m-01 00:00:00', strtotime('-1 month')));

        $data1 = strtotime(date('Y-m-t 00:00:00', strtotime('-1 month')));

        $time = strtotime(date('Y-m-01 00:00:00', time()));
        $admin = Session::get('admin');
        $user = Session::get('user');
        if ($admin) {
            if ($result){
                $resWhere['shop_id'] = $result;
                $rWhere['o.id'] = $result;
            }
        }else{
            if ($user['rank'] == 22 || $user['rank'] == 23 || $user['rank'] == 18) {
                $oWhere['branck_id'] = $user['branch_id'];
                $resWhere['branch_id'] = $user['branch_id'];
                $rWhere['o.branck_id'] = $user['branch_id'];
                if ($result){
                    $resWhere['shop_id'] = $result;
                    $rWhere['o.id'] = $result;
                }
            }
            if ($user['rank'] == 17){
                $oWhere['dd_number'] = $user['dd_number'];
                $resWhere['dd_number'] = $user['dd_number'];
                $rWhere['o.dd_number'] = $user['dd_number'];
                if ($result){
                    $resWhere['shop_id'] = $result;
                    $rWhere['o.id'] = $result;
                }
            }
            if ($user['rank'] == 24 || $user['rank'] == 16){
                $oWhere['user_id'] = $user['id'];
                $resWhere['staff_id'] = $user['id'];
                $rWhere['o.user_id'] = $user['id'];
                if ($result){
                    $resWhere['shop_id'] = $result;
                    $rWhere['o.id'] = $result;
                }
            }
        }



        if (!empty($oWhere)){
            $data['result'] = db('shop')
                ->where('expire_time','>',time())
                ->where($oWhere)
                ->select();
        }else{
            $data['result'] = db('shop')
                ->where('expire_time','>',time())
                ->select();
        }


        if (!empty($oWhere)){
            $data['res'] = db('shop')
                ->where('expire_time','>',time())
                ->field('id,corporate_name')
                ->where($oWhere)
                ->paginate(100,false,['query' => request()->param()]);
        }else{
            $data['res'] = db('shop')
                ->where('expire_time','>',time())
                ->field('id,corporate_name')
                ->paginate(100,false,['query' => request()->param()]);
        }



        if ($result){
            $data['orderData1'] = db('order_data')
                ->field(['shop_id','SUM(money_all) as money_all'])
                ->where('order_time','>=',$data2)
                ->where('order_time','<=',$data1)
                ->where($resWhere)
                ->group('shop_id')
                ->select();
            if(array_key_exists(0,$data['orderData1'])){
                foreach ($data['result'] as $key=>$value){
                    if($value['id'] == $data['orderData1'][0]['shop_id']){
                        $data['orderData1'][0]['corporate_name'] = $value['corporate_name'];
                    }
                }
            }else{
                $corporate_name = db('shop')->field('corporate_name')->where('id',$result)->find();
                $data['orderData1'][0]['corporate_name'] = $corporate_name['corporate_name'];
                $data['orderData1'][0]['money_all'] = 0;
            }
        }else{


            foreach ($data['result'] as $key=>$value){
                if (!empty($resWhere)){

                    $data['orderData1'][$value['id']] = db('order_data')
                        ->field(['shop_id','SUM(money_all) as money_all'])
                        ->where('shop_id',$value['id'])
                        ->where('order_time','>=',$data2)
                        ->where('order_time','<=',$data1)
                        ->where($resWhere)
                        ->group('shop_id')
                        ->select();



                }else{
                    $data['orderData1'][$value['id']] = db('order_data')
                        ->field(['shop_id','SUM(money_all) as money_all'])
                        ->where('shop_id',$value['id'])
                        ->where('order_time','>=',$data2)
                        ->where('order_time','<=',$data1)
                        ->group('shop_id')
                        ->select();
                }
            }
        }




        if ($result){
            $data['name'] = db('shop')
                ->alias('o')
                ->join('silver_branch_office b', 'o.branck_id = b.id')
                ->field(['o.id','b.name'])
                ->where($rWhere)
                ->select();
            $data['orderData'] = db('order_data')
                ->field(['shop_id','SUM(money_all) as money_all'])
                ->where('order_time','>=',$time)
                ->where($resWhere)
                ->group('shop_id')
                ->select();
            if(array_key_exists(0,$data['orderData'])){
                foreach ($data['result'] as $key=>$value){
                    if($value['id'] == $data['orderData'][0]['shop_id']){
                        $data['orderData'][0]['corporate_name'] = $value['corporate_name'];
                    }
                }
            }else{
                $corporate_name = db('shop')->field('corporate_name')->where('id',$result)->find();
                $data['orderData'][0]['corporate_name'] = $corporate_name['corporate_name'];
                $data['orderData'][0]['money_all'] = 0;
            }
        }else{
            foreach ($data['result'] as $key=>$value){
                if (!empty($resWhere)){

                    $data['orderData'][$value['id']] = db('order_data')
                        ->field(['shop_id','SUM(money_all) as money_all'])
                        ->where('shop_id',$value['id'])
                        ->where('order_time','>=',$time)
                        ->where($resWhere)
                        ->group('shop_id')
                        ->select();
                    $data['name'][$value['id']] = db('shop')
                        ->alias('o')
                        ->join('silver_branch_office b', 'o.branck_id = b.id')
                        ->field(['o.shop_id','b.name'])
                        ->where('o.shop_id',$value['id'])
                        ->where($rWhere)
                        ->select();



                }else{
                    $data['orderData'][$value['id']] = db('order_data')
                        ->field(['shop_id','SUM(money_all) as money_all'])
                        ->where('shop_id',$value['id'])
                        ->where('order_time','>=',$time)
                        ->group('shop_id')
                        ->select();
                    $data['name'][$value['id']] = db('shop')
                        ->alias('o')
                        ->join('silver_branch_office b', 'o.branck_id = b.id')
                        ->field(['o.id','b.name'])
                        ->where('o.id',$value['id'])
                        ->select();
                }
            }
        }














        return $data;
    }

    public function warning($result)
    {
        $time = strtotime(date('Y-m-01 00:00:00', time()));
        $admin = Session::get('admin');
        $user = Session::get('user');
        if ($admin){
            if ($result){
                $where['grade'] = $result;
            }
        }else{
            if ($user['rank'] == 22 || $user['rank'] == 18 || $user['rank'] == 23){
                $resWhere['branck_id'] = $user['branch_id'];
                $where['branch_id'] = $user['branch_id'];
                if ($result){
                    $where['grade'] = $result;
                }

            }
            if ($user['rank'] == 17){
                $resWhere['dd_number'] = $user['dd_number'];
                $where['dd_number'] = $user['dd_number'];
                if ($result){
                    $where['grade'] = $result;
                }
            }
            if ($user['rank'] == 24 || $user['rank'] == 16){
                $resWhere['user_id'] = $user['id'];
                $where['staff_id'] = $user['id'];
                if ($result){
                    $where['grade'] = $result;
                }

            }
        }



        if (!empty($resWhere)){
            $data['result'] = db('shop')
                ->where($resWhere)
                ->where('expire_time','>',time())
                ->select();
        }else{
            $data['result'] = db('shop')
                ->where('expire_time','>',time())
                ->select();
        }


            $data1 = db('order_data')
                ->field(['SUM(money_all) as money_all','user_id','staff_id','branch_id ',' expire_time','shop_id'])
                ->where('expire_time','>',time())
                ->group('user_id')
                ->select();


        foreach ($data1 as $key=>$value)
        {
            $res = db('grade')->where('user_id',$value['user_id'])->find();
            if ($res)
            {
                if ($res['money_all'] != $value['money_all'])
                {
                    if ($value['money_all'] >= 200000){
                        $grade = 'A';
                    }elseif($value['money_all'] < 200000 && $value['money_all'] >= 100000){
                        $grade = 'B';
                    }elseif($value['money_all'] < 100000 && $value['money_all'] >= 30000){
                        $grade = 'C';
                    }elseif($value['money_all'] < 30000){
                        $grade = 'D';
                    }
                    $save = [
                        'money_all' => $value['money_all'],
                        'grade' => $grade,
                        'updatetime' => time()
                    ];
                    db('grade')->where('id',$res['id'])->update($save);
                }
            }else{
                if ($value['money_all'] >= 200000){
                    $grade = 'A';
                }elseif($value['money_all'] < 200000 && $value['money_all'] >= 100000){
                    $grade = 'B';
                }elseif($value['money_all'] < 100000 && $value['money_all'] >= 30000){
                    $grade = 'C';
                }elseif($value['money_all'] < 30000){
                    $grade = 'D';
                }
                $save = [
                    'user_id' =>$value['user_id'],
                    'money_all' => $value['money_all'],
                    'grade' => $grade,
                    'addtime' => time(),
                    'staff_id' => $value['staff_id'],
                    'branch_id' => $value['branch_id'],
                    'expire_time' => $value['expire_time'],
                    'shop_id' => $value['shop_id'],

                ];

                db('grade')->insert($save);
            }

        }


        if (!empty($where)){
            $data['orderData'] = db('grade')
                ->where($where)
                ->where('expire_time','>',time())
                ->paginate(20);
        }else{
            $data['orderData'] = db('grade')
                ->where('expire_time','>',time())
                ->paginate(20);
        }



        return $data;


    }




}
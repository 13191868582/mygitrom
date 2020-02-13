<?php

namespace app\index\model;

use think\Model;
use think\Session;

class Productcontrols extends Model
{
    //执行查询方法
    public function index($res,$type)
    {
        $user = Session::get('user');
        $admin = Session::get('admin');
        if ($admin){
            if (!empty($res['start'])){
                $whereArr['p.addtime'] = array(array('gt',strtotime($res['start'])),array('lt',strtotime($res['end'])));
            }
            if (!empty($res['id'])){
                $whereArr['p.branch_id'] = $res['id'];
            }

            if ($type != ""){
                $whereArr['p.type'] = $res['type'];
            }
            if (!empty($res['business_type'])){
                $whereArr['p.business_type'] = $res['business_type'];
            }
            if (!empty($res['p_type'])){
                $whereArr['p.p_type'] = $res['p_type'];
            }
        }else{
            $where['p.job_num'] = $user['job_num'];
            if (!empty($res['start'])){
                $whereArr['p.addtime'] = array(array('gt',strtotime($res['start'])),array('lt',strtotime($res['end'])));
            }
            if (!empty($res['id'])){
                $whereArr['p.branch_id'] = $res['id'];
            }
            if ($type != ""){
                $whereArr['p.type'] = $res['type'];
            }
            if (!empty($res['business_type'])){
                $whereArr['p.business_type'] = $res['business_type'];
            }
            if (!empty($res['p_type'])){
                $whereArr['p.p_type'] = $res['p_type'];
            }
        }
        if (empty($where)){
            if (empty($whereArr)){
                $result = db('productcontrol')
                    ->alias('p')
                    ->join('silver_branch_office b','b.id = p.branch_id')
                    ->field('b.name,p.*')
                    ->order('p.static asc')
                    ->paginate(20,false,['query' => request()->param()]);
            }else{
                $result = db('productcontrol')
                    ->alias('p')
                    ->join('silver_branch_office b','b.id = p.branch_id')
                    ->field('b.name,p.*')
                    ->where($whereArr)
                    ->order('p.static asc')
                    ->paginate(20,false,['query' => request()->param()]);
            }

        }else{
            if (empty($whereArr)){
                $result = db('productcontrol')
                    ->alias('p')
                    ->join('silver_branch_office b','b.id = p.branch_id')
                    ->field('b.name,p.*')
                    ->order('p.static asc')
                    ->where($where)
                    ->paginate(20,false,['query' => request()->param()]);
            }else{
                $result = db('productcontrol')
                    ->alias('p')
                    ->join('silver_branch_office b','b.id = p.branch_id')
                    ->field('b.name,p.*')
                    ->order('p.static asc')
                    ->where($whereArr)
                    ->where($where)
                    ->paginate(20,false,['query' => request()->param()]);
            }

        }

//        $zxc = db('productcontrol')->getLastSql();


        return $result;



    }

    //导出
    public function export($res,$type)
    {
        $user = Session::get('user');
        $admin = Session::get('admin');
        if ($admin){
            if (!empty($res['start'])){
                $whereArr['p.addtime'] = array(array('gt',strtotime($res['start'])),array('lt',strtotime($res['end'])));
            }
            if (!empty($res['id'])){
                $whereArr['p.branch_id'] = $res['id'];
            }
            if ($type != ""){
                $whereArr['p.type'] = $res['type'];
            }
            if (!empty($res['business_type'])){
                $whereArr['p.business_type'] = $res['business_type'];
            }
            if (!empty($res['p_type'])){
                $whereArr['p.p_type'] = $res['p_type'];
            }
        }else{
            $where['p.job_num'] = $user['job_num'];
            if (!empty($res['start'])){
                $whereArr['p.addtime'] = array(array('gt',strtotime($res['start'])),array('lt',strtotime($res['end'])));
            }
            if (!empty($res['id'])){
                $whereArr['p.branch_id'] = $res['id'];
            }
            if ($type != ""){
                $whereArr['p.type'] = $res['type'];
            }
            if (!empty($res['business_type'])){
                $whereArr['p.business_type'] = $res['business_type'];
            }
            if (!empty($res['p_type'])){
                $whereArr['p.p_type'] = $res['p_type'];
            }
        }
        if (empty($where)){
            if (empty($whereArr)){
                $result = db('productcontrol')
                    ->alias('p')
                    ->join('silver_branch_office b','b.id = p.branch_id')
                    ->field('b.name,p.aliname,p.company,p.type,p.business_type,p.sendover_time,p.sales,p.remark,p.static')
                    ->order('p.static asc')
                    ->select();
            }else{
                $result = db('productcontrol')
                    ->alias('p')
                    ->join('silver_branch_office b','b.id = p.branch_id')
                    ->field('b.name,p.aliname,p.company,p.type,p.business_type,p.sendover_time,p.sales,p.remark,p.static')
                    ->where($whereArr)
                    ->order('p.static asc')
                    ->select();
            }

        }else{
            if (empty($whereArr)){
                $result = db('productcontrol')
                    ->alias('p')
                    ->join('silver_branch_office b','b.id = p.branch_id')
                    ->field('b.name,p.aliname,p.company,p.type,p.business_type,p.sendover_time,p.sales,p.remark,p.static')
                    ->order('p.static asc')
                    ->where($where)
                    ->select();
            }else{
                $result = db('productcontrol')
                    ->alias('p')
                    ->join('silver_branch_office b','b.id = p.branch_id')
                    ->field('b.name,p.aliname,p.company,p.type,p.business_type,p.sendover_time,p.sales,p.remark,p.static')
                    ->order('p.static asc')
                    ->where($whereArr)
                    ->where($where)
                    ->select();
            }

        }

//        $zxc = db('productcontrol')->getLastSql();


        return $result;



    }






    public function association()
    {
        $ThirtyOne = ['01','03','05','07','08','10','12'];
        $month= date("m", time());
        $day= date("d", time());
        $res = $this->isLeap();
        if (!$res){
            if ($day != '31'){
                if($month == 2){
                    if ($day == 28){
                        $andtime = date("Y-m-28 00:00:00", strtotime("-3 month"));
                        $endtime = date("Y-m-30 23:59:59", strtotime("-3 month"));
                    }
                }elseif ($month == 5){
                    if(!$day >= 29){
                        $andtime = date("Y-m-28 00:00:00", strtotime("-3 month"));
                        $endtime = date("Y-m-28 23:59:59", strtotime("-3 month"));
                    }
                }else{
                    if ($day == '30'){
                        if (in_array($month,$ThirtyOne)) {
                            $andtime = date("Y-m-30 00:00:00", strtotime("-3 month"));
                            $endtime = date("Y-m-31 23:59:59", strtotime("-3 month"));
                        }else{
                            $andtime = date("Y-m-30 00:00:00", strtotime("-15 month"));
                            $endtime = date("Y-m-30 23:59:59", strtotime("-15 month"));
                        }
                    }else{
                        $andtime = date("Y-m-d 00:00:00", strtotime("-3 month"));
                        $endtime = date("Y-m-d 23:59:59", strtotime("-3 month"));
                    }


                }

            }
        }else{
            if ($day != '31'){
                if($month == 2){
                    if ($day == 29){
                        $andtime = date("Y-m-29 00:00:00", strtotime("-3 month"));
                        $endtime = date("Y-m-30 23:59:59", strtotime("-3 month"));
                    }
                }elseif ($month == 5){
                    if(!$day >= 30){
                        $andtime = date("Y-m-28 00:00:00", strtotime("-3 month"));
                        $endtime = date("Y-m-28 23:59:59", strtotime("-3 month"));
                    }
                }else{
                    if ($day == '30'){
                        if (in_array($month,$ThirtyOne)) {
                            $andtime = date("Y-m-30 00:00:00", strtotime("-3 month"));
                            $endtime = date("Y-m-31 23:59:59", strtotime("-3 month"));
                        }else{
                            $andtime = date("Y-m-30 00:00:00", strtotime("-3 month"));
                            $endtime = date("Y-m-30 23:59:59", strtotime("-3 month"));
                        }
                    }else{
                        $andtime = date("Y-m-d 00:00:00", strtotime("-3 month"));
                        $endtime = date("Y-m-d 23:59:59", strtotime("-3 month"));
                    }
                }

            }
        }



        if ($res){
            //闰年
            if ($day != '31'){
                if ($month == 2){
                    if ($day == '29'){
                        $and = date("Y-02-13 00:00:00");
                        $end = date("Y-02-14 23:59:59");
                    }else{
                        $and = date("Y-m-d 00:00:00", strtotime("-16 day"));
                        $end = date("Y-m-d 23:59:59", strtotime("-16 day"));
                    }
                }elseif($month == 3){
                    if ($day == 16){

                    }else{
                        $and = date("Y-m-d 00:00:00", strtotime("-16 day"));
                        $end = date("Y-m-d 23:59:59", strtotime("-16 day"));
                    }

                }else{
                    $and = date("Y-m-d 00:00:00", strtotime("-16 day"));
                    $end = date("Y-m-d 23:59:59", strtotime("-16 day"));
                }
            }
        }else{
            //不是闰年
            if ($day != '31'){
                if ($month == 2){
                    if ($day == '28'){
                        $and = date("Y-02-12 00:00:00");
                        $end = date("Y-02-14 23:59:59");
                    }else{
                        $and = date("Y-m-d 00:00:00", strtotime("-16 day"));
                        $end = date("Y-m-d 23:59:59", strtotime("-16 day"));
                    }
                }elseif($month == 3){
                    if ($day == 16){

                    }elseif ($day == 15){

                    }else{
                        $and = date("Y-m-d 00:00:00", strtotime("-16 day"));
                        $end = date("Y-m-d 23:59:59", strtotime("-16 day"));
                    }

                }else{
                    $and = date("Y-m-d 00:00:00", strtotime("-16 day"));
                    $end = date("Y-m-d 23:59:59", strtotime("-16 day"));
                }
            }

        }






        if (!empty($andtime)){

            $this->Lastmonth($andtime,$endtime);


        }

        if (!empty($and)) {
//            $this->Xalimport($and,$end);
            $this->Newalimport($and,$end);
            $this->Palimport($and,$end);
            $this->Xnewalimport($and,$end);
        }
    }


    public function text()
    {
        $andtime = '2019-10-01 00:00:00';
        $endtime = '2019-10-14 23:59:59';
        $and = '2019-12-15 00:00:00';
        $end = '2019-12-29 23:59:59';
        $this->Lastmonth($andtime,$endtime);
//         $this->Xalimport($and,$end);
        $this->Newalimport($and,$end);

        $res = $this->Palimport($and,$end);
        $this->Xnewalimport($and,$end);
        print_r($res);
    }

    public function isLeap()
    {
        $day = date('Y');
        if ($day%4==0&&($day%100!=0 || $day%400==0)){
            return 1;
        }else{
            return 0;
        }
    }


    //M-3月的续签
    public function Lastmonth($andtime,$endtime)
    {

        $result = db('alimport')
            ->field('memberid,company,sales_executive,new_type,sendover_time,sales')
            ->where('new_type','续签')
            ->where('product_category','诚信通服务')
            ->where('sendover_time','>=',strtotime($andtime))
            ->where('sendover_time','<=',strtotime($endtime))
            ->select();
        if (count($result)>=2){
            $count = floor(count($result)/2);

            $array = array_rand($result,$count);
            foreach ($array as $key=>$value)
            {
                unset($result[$value]);
            }
        }


        $result = array_values($result);

        $start_time=strtotime(date("Y-m-d",time()));
        //当天结束之间
        $end_time=$start_time+60*60*24;
        $data = db('productcontrol')
            ->where('type',0)
            ->where('addtime','>=',$start_time)
            ->where('addtime','<=',$end_time)
            ->count();
        if ($data == 0){
            foreach ($result as $key=>$value)
            {
                $arr[$key]['aliname'] = $value['memberid'];
                $arr[$key]['company'] = $value['company'];
                if (stripos($value['sales_executive'],'/石家庄')){
                    $arr[$key]['branch_id'] = 1;
                    $arr[$key]['job_num'] = '03186666';
                }elseif (stripos($value['sales_executive'],'/邯郸')){
                    $arr[$key]['branch_id'] = 2;
                    $arr[$key]['job_num'] = '18631826973';
                }elseif (stripos($value['sales_executive'],'/衡水')){
                    $arr[$key]['branch_id'] = 3;
                    $arr[$key]['job_num'] = '18631826973';
                }elseif (stripos($value['sales_executive'],'/沧州')){
                    $arr[$key]['branch_id'] = 4;
                    $arr[$key]['job_num'] = '18631826973';
                }elseif (stripos($value['sales_executive'],'/廊坊')){
                    $arr[$key]['branch_id'] = 5;
                    $arr[$key]['job_num'] = '03186666';
                }elseif (stripos($value['sales_executive'],'/保定')){
                    $arr[$key]['branch_id'] = 6;
                    $arr[$key]['job_num'] = '18631826973';
                }elseif (stripos($value['sales_executive'],'/白沟')){
                    $arr[$key]['branch_id'] = 7;
                    $arr[$key]['job_num'] = '03186666';
                }elseif (stripos($value['sales_executive'],'/邢台')){
                    $arr[$key]['branch_id'] = 8;
                    $arr[$key]['job_num'] = '03186666';
                }elseif (stripos($value['sales_executive'],'/西安')){
                    $arr[$key]['branch_id'] = 9;
                    $arr[$key]['job_num'] = '03186666';
                }
                $arr[$key]['type'] = '0';
                $arr[$key]['business_type'] = "续签";
                $arr[$key]['static'] = 0;
                $arr[$key]['addtime'] = time();
                $arr[$key]['sendover_time'] = $value['sendover_time'];
                $arr[$key]['sales'] = $value['sales'];
            }

            $res = db('productcontrol')->insertAll($arr);


            return $res;
        }


    }

    //续签诚信通
    public function Xnewalimport($andtime,$endtime)
    {

        $result = db('authen')
            ->field('memberid,company,sales_executive,new_type,before_time,sales')
            ->where('new_type','续签')
            ->where('product_category','诚信通服务')
            ->where('before_time','>=',strtotime($andtime))
            ->where('before_time','<=',strtotime($endtime))
            ->select();


        $start_time=strtotime(date("Y-m-d",time()));
        //当天结束之间
        $end_time=$start_time+60*60*24;
        $data = db('productcontrol')
            ->where('type',1)
            ->where('business_type','续签')
            ->where('product_category','诚信通服务')
            ->where('addtime','>=',$start_time)
            ->where('addtime','<=',$end_time)
            ->count();
        if ($data == 0)
        {
            if (!empty($result))
            {
                foreach ($result as $key=>$value)
                {
                    $arr[$key]['aliname'] = $value['memberid'];
                    $arr[$key]['company'] = $value['company'];
                    if (stripos($value['sales_executive'],'/石家庄')){
                        $arr[$key]['branch_id'] = 1;
                        $arr[$key]['job_num'] = '03186666';
                    }elseif (stripos($value['sales_executive'],'/邯郸')){
                        $arr[$key]['branch_id'] = 2;
                        $arr[$key]['job_num'] = '18631826973';
                    }elseif (stripos($value['sales_executive'],'/衡水')){
                        $arr[$key]['branch_id'] = 3;
                        $arr[$key]['job_num'] = '18631826973';
                    }elseif (stripos($value['sales_executive'],'/沧州')){
                        $arr[$key]['branch_id'] = 4;
                        $arr[$key]['job_num'] = '18631826973';
                    }elseif (stripos($value['sales_executive'],'/廊坊')){
                        $arr[$key]['branch_id'] = 5;
                        $arr[$key]['job_num'] = '03186666';
                    }elseif (stripos($value['sales_executive'],'/保定')){
                        $arr[$key]['branch_id'] = 6;
                        $arr[$key]['job_num'] = '18631826973';
                    }elseif (stripos($value['sales_executive'],'/白沟')){
                        $arr[$key]['branch_id'] = 7;
                        $arr[$key]['job_num'] = '03186666';
                    }elseif (stripos($value['sales_executive'],'/邢台')){
                        $arr[$key]['branch_id'] = 8;
                        $arr[$key]['job_num'] = '03186666';
                    }elseif (stripos($value['sales_executive'],'/西安')){
                        $arr[$key]['branch_id'] = 9;
                        $arr[$key]['job_num'] = '03186666';
                    }else{
                        $arr[$key]['branch_id'] = 0;
                        $arr[$key]['job_num'] = '';
                    }
                    $arr[$key]['type'] = '1';
                    $arr[$key]['business_type'] = "续签";
                    $arr[$key]['static'] = '0';
                    $arr[$key]['addtime'] = time();
                    $arr[$key]['sendover_time'] = $value['before_time'];
                    $arr[$key]['sales'] = $value['sales'];
                }


                $res = db('productcontrol')->insertAll($arr);


                return $res;
            }


        }

    }

    //新签诚信通
    public function Newalimport($andtime,$endtime)
    {

        $result = db('authen')
            ->field('memberid,company,sales_executive,new_type,sendover_time,sales')
            ->where('new_type','新签')
            ->where('product_category','诚信通服务')
            ->where('sendover_time','>=',strtotime($andtime))
            ->where('sendover_time','<=',strtotime($endtime))
            ->select();





        $start_time=strtotime(date("Y-m-d",time()));
        //当天结束之间
        $end_time=$start_time+60*60*24;
        $data = db('productcontrol')
            ->where('type',1)
            ->where('business_type','新签')
            ->where('product_category','诚信通服务')
            ->where('addtime','>=',$start_time)
            ->where('addtime','<=',$end_time)
            ->count();
        if ($data == 0)
        {
            if (!empty($result)){
                foreach ($result as $key=>$value)
                {
                    $arr[$key]['aliname'] = $value['memberid'];
                    $arr[$key]['company'] = $value['company'];
                    if (stripos($value['sales_executive'],'/石家庄')){
                        $arr[$key]['branch_id'] = 1;
                        $arr[$key]['job_num'] = '03186666';
                    }elseif (stripos($value['sales_executive'],'/邯郸')){
                        $arr[$key]['branch_id'] = 2;
                        $arr[$key]['job_num'] = '18631826973';
                    }elseif (stripos($value['sales_executive'],'/衡水')){
                        $arr[$key]['branch_id'] = 3;
                        $arr[$key]['job_num'] = '18631826973';
                    }elseif (stripos($value['sales_executive'],'/沧州')){
                        $arr[$key]['branch_id'] = 4;
                        $arr[$key]['job_num'] = '18631826973';
                    }elseif (stripos($value['sales_executive'],'/廊坊')){
                        $arr[$key]['branch_id'] = 5;
                        $arr[$key]['job_num'] = '03186666';
                    }elseif (stripos($value['sales_executive'],'/保定')){
                        $arr[$key]['branch_id'] = 6;
                        $arr[$key]['job_num'] = '18631826973';
                    }elseif (stripos($value['sales_executive'],'/白沟')){
                        $arr[$key]['branch_id'] = 7;
                        $arr[$key]['job_num'] = '03186666';
                    }elseif (stripos($value['sales_executive'],'/邢台')){
                        $arr[$key]['branch_id'] = 8;
                        $arr[$key]['job_num'] = '03186666';
                    }elseif (stripos($value['sales_executive'],'/西安')){
                        $arr[$key]['branch_id'] = 9;
                        $arr[$key]['job_num'] = '03186666';
                    }else{
                        $arr[$key]['branch_id'] = 0;
                        $arr[$key]['job_num'] = '';
                    }
                    $arr[$key]['type'] = '1';
                    $arr[$key]['business_type'] = "新签";
                    $arr[$key]['static'] = '0';
                    $arr[$key]['addtime'] = time();
                    $arr[$key]['sendover_time'] = $value['sendover_time'];
                    $arr[$key]['sales'] = $value['sales'];

                }



                $res = db('productcontrol')->insertAll($arr);


                return $res;
            }
        }

    }

    //新签网销宝
    public function Palimport($andtime,$endtime)
    {


        $result = db('alimport')
            ->field('memberid,company,sales_executive,new_type,single_time,sales')
            ->where('new_type','新签')
            ->where('product_category','网销宝充值包')
            ->where('single_time','>=',strtotime($andtime))
            ->where('single_time','<=',strtotime($endtime))
            ->select();



        $start_time=strtotime(date("Y-m-d",time()));
        //当天结束之间
        $end_time=$start_time+60*60*24;
        $data = db('productcontrol')
            ->where('type',1)
            ->where('business_type','新签')
            ->where('product_category','网销宝充值包')
            ->where('addtime','>=',$start_time)
            ->where('addtime','<=',$end_time)
            ->count();


        if ($data == 0)
        {
            if (!empty($result)){
                foreach ($result as $key=>$value)
                {
                    $arr[$key]['aliname'] = $value['memberid'];
                    $arr[$key]['company'] = $value['company'];
                    if (stripos($value['sales_executive'],'/石家庄')){
                        $arr[$key]['branch_id'] = 1;
                        $arr[$key]['job_num'] = '03186666';
                    }elseif (stripos($value['sales_executive'],'/邯郸')){
                        $arr[$key]['branch_id'] = 2;
                        $arr[$key]['job_num'] = '18631826973';
                    }elseif (stripos($value['sales_executive'],'/衡水')){
                        $arr[$key]['branch_id'] = 3;
                        $arr[$key]['job_num'] = '18631826973';
                    }elseif (stripos($value['sales_executive'],'/沧州')){
                        $arr[$key]['branch_id'] = 4;
                        $arr[$key]['job_num'] = '18631826973';
                    }elseif (stripos($value['sales_executive'],'/廊坊')){
                        $arr[$key]['branch_id'] = 5;
                        $arr[$key]['job_num'] = '03186666';
                    }elseif (stripos($value['sales_executive'],'/保定')){
                        $arr[$key]['branch_id'] = 6;
                        $arr[$key]['job_num'] = '18631826973';
                    }elseif (stripos($value['sales_executive'],'/白沟')){
                        $arr[$key]['branch_id'] = 7;
                        $arr[$key]['job_num'] = '03186666';
                    }elseif (stripos($value['sales_executive'],'/邢台')){
                        $arr[$key]['branch_id'] = 8;
                        $arr[$key]['job_num'] = '03186666';
                    }elseif (stripos($value['sales_executive'],'/西安')){
                        $arr[$key]['branch_id'] = 9;
                        $arr[$key]['job_num'] = '03186666';
                    }else{
                        $arr[$key]['branch_id'] = 0;
                        $arr[$key]['job_num'] = '';
                    }
                    $arr[$key]['type'] = '1';
                    $arr[$key]['business_type'] = "网销宝充值包";
                    $arr[$key]['static'] = '0';
                    $arr[$key]['addtime'] = time();
                    $arr[$key]['sendover_time'] = $value['single_time'];
                    $arr[$key]['sales'] = $value['sales'];
                }


                $res = db('productcontrol')->insertAll($arr);
                return $res;
            }

        }

    }


}
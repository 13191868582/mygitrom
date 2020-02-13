<?php

namespace app\index\controller;


use think\Request;
use think\Session;

class processing extends Common
{
    public function index()
    {

        $res = Request::instance()->param();
        $id = Request::instance()->param('id');
        $start = Request::instance()->param('start');
        $end = Request::instance()->param('end');
        $type = Request::instance()->param('type');
        $business_type = Request::instance()->param('business_type');
        $p_type = Request::instance()->param('p_type');


        $user = Session::get('user');
        $admin = Session::get('admin');
        if ($admin || $user['job_num'] == '03189999'){
            if ( $start){
                $whereArr['p.addtime'] = array(array('gt',strtotime($start)),array('lt',strtotime($end)));
            }
            if ($id){
                $whereArr['p.branch_id'] = $id;
            }

            if ($type != ""){
                $whereArr['p.type'] = $type;
            }
            if ($business_type){
                $whereArr['p.business_type'] = $business_type;
            }
            if ($p_type){
                $whereArr['p.p_type'] = $p_type;
            }
            if (empty($whereArr)){
                $result  = db('productcontrol')
                    ->alias('p')
                    ->join('silver_branch_office b' ,'b.id = p.branch_id')
                    ->field('b.name,p.*')
                    ->order('p.static desc')
                    ->paginate(30,false,['query' => request()->param()]);
            }else{
                $result  = db('productcontrol')
                    ->alias('p')
                    ->join('silver_branch_office b' ,'b.id = p.branch_id')
                    ->field('b.name,p.*')
                    ->where($whereArr)
                    ->order('p.static desc')
                    ->paginate(30,false,['query' => request()->param()]);
            }



        }
//        print_r($result);die;

        $problem = db('problem')->select();
        $branch = [
            '0' => ['id'=>  3,'name'=>'衡水'],
            '1' => ['id'=>  4,'name'=>'沧州'],
            '3' => ['id'=>  2,'name'=>' 邯郸'],
            '4' => ['id'=>  6,'name'=>' 保定'],
            '5' => ['id'=>  1,'name'=>'石家庄'],
            '6' => ['id'=>  5,'name'=>'廊坊'],
            '7' => ['id'=>  7,'name'=>'白沟'],
            '8' => ['id'=>  8,'name'=>' 邢台'],
            '9' => ['id'=>  9,'name'=>' 西安'],
        ];





        return $this->fetch('',['result'=>$result,'res'=>$res,'id'=>$id,'start'=>$start,'end'=>$end,'type'=>$type,'business_type'=>$business_type,'p_type'=>$p_type,'problem'=>$problem,'branch'=>$branch,]);



    }

    public function export()
    {
        $id = Request::instance()->param('id');
        $start = Request::instance()->param('start');
        $end = Request::instance()->param('end');
        $type = Request::instance()->param('type');
        $business_type = Request::instance()->param('business_type');
        $p_type = Request::instance()->param('p_type');


        $user = Session::get('user');
        $admin = Session::get('admin');
        if ($admin || $user['job_num'] == '03189999'){
            if ( $start){
                $whereArr['p.addtime'] = array(array('gt',strtotime($start)),array('lt',strtotime($end)));
            }
            if ($id){
                $whereArr['p.branch_id'] = $id;
            }

            if ($type != ""){
                $whereArr['p.type'] = $type;
            }
            if ($business_type){
                $whereArr['p.business_type'] = $business_type;
            }
            if ($p_type){
                $whereArr['p.p_type'] = $p_type;
            }
            if (empty($whereArr)){
                $result  = db('productcontrol')
                    ->alias('p')
                    ->join('silver_branch_office b' ,'b.id = p.branch_id')
                    ->field('b.name,p.aliname,p.company,p.type,p.business_type,p.sendover_time,p.sales,p.remark,p.static')
                    ->order('p.static desc')
                    ->select();
            }else{
                $result  = db('productcontrol')
                    ->alias('p')
                    ->join('silver_branch_office b' ,'b.id = p.branch_id')
                    ->field('b.name,p.aliname,p.company,p.type,p.business_type,p.sendover_time,p.sales,p.remark,p.static')
                    ->where($whereArr)
                    ->order('p.static desc')
                    ->select();
            }



        }

        foreach($result as $key=>$value)
        {
            $arr[$key]['name'] = $value['name'];
            $arr[$key]['aliname'] = $value['aliname'];
            $arr[$key]['company'] = $value['company'];
            if ($value['type'] == 0){
                $arr[$key]['type'] = "到单";
            }
            if ($value['type'] == 1){
                $arr[$key]['type'] = "开通";
            }
            $arr[$key]['business_type'] = $value['business_type'];
            $arr[$key]['sendover_time'] = date('Y-m-d H:i:s',$value['sendover_time']);
            $arr[$key]['sales'] = $value['sales'];
            $arr[$key]['remark'] = $value['remark'];
            if ($value['static'] == 0){
                $arr[$key]['static'] = '未处理';
            }
            if ($value['static'] == 1){
                $arr[$key]['static'] = '已处理';
            }
            if ($value['static'] == 2){
                $arr[$key]['static'] = '确认违规';
            }
            if ($value['static'] == 4){
                $arr[$key]['static'] = '审核中';
            }
            if ($value['static'] == 5){
                $arr[$key]['static'] = '确认中';
            }

        }

        $header=array("区域","阿里名","公司名称","到单/开通","业务线","时间","签单人","备注","状态");
        $name="品控数据";
        $this->getExcel($name,$header,$arr);
    }

    function getExcel($filename, $tileArray=[], $dataArray=[]){
        ini_set('memory_limit','512M');
        ini_set('max_execution_time',0);
        ob_end_clean();
        ob_start();
        header("Content-Type: text/csv");
        header("Content-Disposition:filename=".$filename.".xls");
        $fp=fopen('php://output','w');
        fwrite($fp, chr(0xEF).chr(0xBB).chr(0xBF));//转码 防止乱码(比如微信昵称(乱七八糟的))
        fputcsv($fp,$tileArray);
        $index = 0;

        foreach ($dataArray as $item) {
            if($index==10000){
                $index=0;
                ob_flush();
                flush();
            }
            $index++;
            fputcsv($fp,$item);
        }

        ob_flush();
        flush();
        ob_end_clean();


    }

    public function problem()
    {
        $id = Request::instance()->param('id');
        $result = db('productcontrol')
            ->alias('p')
            ->join('problem pro','pro.id = p.p_type')
            ->where('p.id',$id)
            ->find();

        return $result;

    }

    public function through()
    {
        $id = Request::instance()->param('id');
        $res = db('productcontrol')->where('id',$id)->update(['static'=>2,'processing_time'=>time()]);
        return $res;
    }

    public function adopt()
    {
        $id = Request::instance()->param('id');
        $res = db('productcontrol')->where('id',$id)->update(['static'=>1,'processing_time'=>time()]);
        return $res;
    }

    public function statistics()
    {
        $admin = Session::get('admin');
        $user = Session::get('user');
        if ($admin){

        }else{
            if ($user['job_num'] == "666666" || $user['job_num'] == "03189999" || $user['job_num'] == "03186666" || $user['job_num'] == "18631826973"){

            }else{
                $result = [];
                return $this->fetch('',['result'=>$result]);
            }
        }

        //获取今天开始结束时间
        $dayandtime = strtotime(date('Y-m-d 00:00:00'));
        $dayendtime = strtotime(date('Y-m-d 23:59:59'));

        //获取本月开始结束时间
        $monthandtime = mktime(0,0,0,date('m'),1,date('Y'));
        $monthendtime = mktime(23,59,59,date('m'),date('t'),date('Y'));

        //获取本日所有数据
        $branch = db('branch_office')->select();
        foreach ($branch as $key=>$value)
        {
            $day = db('productcontrol')
                ->where('branch_id',$value['id'])
                ->where('addtime','>=',$dayandtime)
                ->count();
            $d_complete = db('productcontrol')
                ->where('branch_id',$value['id'])
                ->where('p_time','>=',$dayandtime)
                ->count();
            $month = db('productcontrol')
                ->where('branch_id',$value['id'])
                ->where('addtime','>=',$monthandtime)
                ->count();
            $m_complete = db('productcontrol')
                ->where('branch_id',$value['id'])
                ->where('p_time','>=',$monthandtime)
                ->count();
            if ($value['id'] <= 9){
                $result[$key]['name'] = $value['name'];
                $result[$key]['id'] = $value['id'];
                $result[$key]['day'] = $day;
                $result[$key]['d_complete'] = $d_complete;
                $result[$key]['month'] = $month;
                $result[$key]['m_complete'] = $m_complete;
            }



        }

        return $this->fetch('',['result'=>$result]);

    }

    public function Statistics_index()
    {
        $branch = db('branch_office')->select();

        foreach ($branch as $key=>$value)
        {
            $num = db('productcontrol')
                ->where('static','2')
                ->where('branch_id',$value['id'])
                ->count();
            $sum = db('productcontrol')
                ->where('branch_id',$value['id'])
                ->count();
            if ($value['id'] <= 9){
                $result[$key]['name'] = $value['name'];
                $result[$key]['num'] = $num;
                $result[$key]['sum'] = $sum;
            }
        }
        return $this->fetch('',['result'=>$result]);
    }
}
<?php

namespace app\index\controller;

use app\api\controller\Notice;
use app\index\model\Newcustomer;
use app\index\model\Productcontrols;
use think\Controller;
use think\Db;
use think\Request;
use think\Session;

class productcontrol extends Common
{
    //查询所有核查信息
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
        $pro = new productcontrols();
        $result = $pro->index($res,$type);
//        print_r($result);die;

        $problem = db('problem')->select();
        if ($admin){
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
        }else{
            if($user['job_num'] == '18631826973'){
                $branch = [
                    '0' => ['id'=>  3,'name'=>'衡水'],
                    '1' => ['id'=>  4,'name'=>'沧州'],
                    '3' => ['id'=>  2,'name'=>' 邯郸'],
                    '4' => ['id'=>  6,'name'=>' 保定'],
                ];
            }
            if ($user['job_num'] == '03186666'){
                $branch = [
                    '0' => ['id'=>  1,'name'=>'石家庄'],
                    '1' => ['id'=>  5,'name'=>'廊坊'],
                    '3' => ['id'=>  7,'name'=>'白沟'],
                    '4' => ['id'=>  8,'name'=>' 邢台'],
                    '5' => ['id'=>  9,'name'=>' 西安'],
                ];
            }
        }



        return $this->fetch('',['result'=>$result,'res'=>$res,'id'=>$id,'start'=>$start,'end'=>$end,'type'=>$type,'business_type'=>$business_type,'p_type'=>$p_type,'problem'=>$problem,'branch'=>$branch,]);


    }

    public function export()
    {
        $res = Request::instance()->param();
        $type = Request::instance()->param('type');
        $pro = new productcontrols();
        $result = $pro->export($res,$type);
//        print_r($result);die;
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


    public function timing()
    {
        $pro = new Productcontrols();
        $pro->text();
    }


    //存在品控问题
    public function problem()
    {
        $id = Request::instance()->param('id');
        $result = db('productcontrol')
            ->where('id',$id)
            ->find();
        if ($result['business_type'] == "续签"){
            $type = 3;

        }elseif ($result['business_type'] == "新签"){
            $type = 2;

        }else{
            $type = 4;

        }
        //查询公司名称
        $branch = db('branch_office')->where('id',$result['branch_id'])->find();

        $sql = "select * from silver_user where (dd_postion = '经理' or dd_postion = '总监') AND `branch_id`=".$result['branch_id']." and `department`=".$type;

        $user = Db::query($sql);



        $data['branch'] = $branch;
        $data['user'] = $user;
        return $data;
    }

    //品控问题提交方法
    public function through()
    {
        $result = Request::instance()->param();
        $res = db('productcontrol')->where('id',$result['id'])->update(['p_type'=>$result['p_type'],'static'=>4,'p_time'=>time(),'remark'=>$result['remark']]);
        if ($res){
            $user = db('user')->where('id',$result['user_id'])->find();
            $dingding =  db('dingding')->where('jobnumber',$user['job_num'])->find();
            $pro = db('productcontrol')->where('id',$result['id'])->find();

            $text = $pro['company']. "存在品控问题登录豆宝查看";
            $msg = [
                'msgtype' => "text",
                "text" => [
                    'content'=>$text,
                ],
            ];
            $api = new Notice();
            $api->ding($dingding['userid'],json_encode($msg));

        }
        return $res;

    }

    //执行审核通过方法
    public function adopt()
    {
        $result = Request::instance()->param('id');
        $res = db('productcontrol')->where('id',$result)->update(['static'=>1,'p_time'=>time()]);
        if ($res){
            $msg =['code'=>0,'msg'=>'通过成功'];
        }else{
            $msg =['code'=>1,'msg'=>'通过失败'];
        }

        return $msg;
    }
    

    public function problems()
    {
        $result = db('problem')->select();

        return $this->fetch('',['result'=>$result]);
    }

    public function problemadd()
    {
        return $this->fetch('');
    }

    public function problemdoadd()
    {
        $result = Request::instance()->param();

        $res = db('problem')->insert($result);
        if($res){
            $arr = array('code' =>200,'msg'=>'添加成功！');
        }else{
            $arr = array('code' =>404,'msg'=>'添加失败！');
        }

        return returnmsg($arr);
    }

    public function edit($id)
    {
        $result = db('problem')->where('id',$id)->find();

        return $this->fetch('',['result'=>$result]);
    }

    public function doedit()
    {
        $result = Request::instance()->param();

        $res = db('problem')->where('id',$result['id'])->update(['problem'=>$result['problem']]);
        if($res){
            $arr = array('code' =>200,'msg'=>'修改成功！');
        }else{
            $arr = array('code' =>404,'msg'=>'修改失败！');
        }

        return returnmsg($arr);
    }




}
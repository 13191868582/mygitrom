<?php

namespace app\index\controller;
use app\index\model\Finances;
use app\index\model\Rates;
use think\Session;
class Rate extends Common
{
    //个人续签率
    public function index(){
        $keywords = input("get.keywords");
        $user=Session::get("user");
        $admin=Session::get("admin");
        $branch = db("branch_office")->select();
        //当月时间
        $date = mktime(0,0,0,date('m'),1,date('Y'));
        //下个月的时间
        $date2 = strtotime(date('Y-m-01 00:00:00',strtotime('1 month')));
        //下下个月的时间
        $date3 = strtotime(date('Y-m-01 00:00:00',strtotime('2 month')));
        //这个月的续签人数
        $data = db("renewal")->where("user_id",$user['id'])->where("status",1)->where("time","eq",$date)->count();
        //下月的续签人数
        $data2 = db("renewal")->where("user_id",$user['id'])->where("status",1)->where("expire","eq",$date2)->count();
        //下下月的续签人数
        $data3 = db("renewal")->where("user_id",$user['id'])->where("status",1)->where("expire","eq",$date3)->count();
        //回签客户的人数
        $Client= db("renewal")->where("user_id",$user['id'])->where("status",1)->
        where("expires","lt",$date)->where("time","eq",$date)->count();
        //分配的数量
        //当月的
        $info = db('customer')
            ->alias('a')->join('silver_employee_customer w','a.id = w.cid')->where('w.maturitys',$date)
            ->where('w.uid',$user['id'])
            ->count();
        //下个月的
        $info2 = db('customer')
            ->alias('a')->join('silver_employee_customer w','a.id = w.cid')->where('w.maturitys',$date2)
            ->where('w.uid',$user['id'])
            ->count();
         //下下个月的
        $info3 = db('customer')
            ->alias('a')->join('silver_employee_customer w','a.id = w.cid')->where('w.maturitys',$date3)
            ->where('w.uid',$user['id'])
            ->count();

        if($info==0){
            $monthly=0;
        }else{
            //当月的续签率
            $monthly=round(($data+$Client)/$info*100).'%';
        }
        if($info2==0){
            $monthly2=0;
        }else{
            //下个月的续签率
            $monthly2=round(($data2)/$info2*100).'%';
        }
        if($info3==0){
            $monthly3=0;
        }else{
            //下个月的续签率
            $monthly3=round(($data3)/$info3*100).'%';
        }

        //查询全部的人的续签率
        $reids=db('user')->where('branch_id',$user['branch_id'])->field('id')->field('username')->field('job_num')->field('branch_id')->select();
        if(!empty($keywords)){
            $data = $reids=db('user')->where("branch_id","like","%$keywords%")->field('id')->field('username')->field('job_num')->field('branch_id')->select();
        }else{
            //对数据进行分页  方法名称：paginate() 每页显示数量
            $data = $reids=db('user')->where('branch_id',$user['branch_id'])->field('id')->field('username')->field('job_num')->field('branch_id')->select();
        }
        foreach ($data as $k => $v) {
            $user_id=$v['id'];
            $user_name= $v['username'];
            $user_num= $v['job_num'];
            $user_branch= $v['branch_id'];
            $data00 = db("renewal")->where("user_id",$user_id)->where("status",1)->where("time","eq",$date)->count();
            //下月的续签人数
            $data02 = db("renewal")->where("user_id",$user_id)->where("status",1)->where("expire","eq",$date2)->count();
            //下下月的续签人数
            $data03 = db("renewal")->where("user_id",$user_id)->where("status",1)->where("expire","eq",$date3)->count();
            //回签客户的人数
            $Client00= db("renewal")->where("user_id",$user_id)->where("status",1)->
            where("expires","lt",$date)->where("time","eq",$date)->count();
//        $dats = db("renewal")->where("user_id",$admin['id'])->where("status",1)->where("time","eq",$date)->select();
            //分配的数量
            //当月的
            $info00 = db('customer')
                ->alias('a')->join('silver_employee_customer w','a.id = w.cid')->where('w.maturitys',$date)
                ->count();
            //下个月的
            $info02 = db('customer')
                ->alias('a')->join('silver_employee_customer w','a.id = w.cid')->where('w.maturitys',$date2)
                ->count();
//              //下下个月的
            $info03 = db('customer')
                ->alias('a')->join('silver_employee_customer w','a.id = w.cid')->where('w.maturitys',$date3)
                ->count();
            //分配的总人数
//        $allocated = db("employee_customer")->where("uid",$user['id'])->where("")->count();
            $uss[$k] = array(
                'username'=>$user_name,
                'num'=>$user_num,
                'branch_id'=>$user_branch,
                //当月的续签率
                'monthly00' => $monthly00=round(($data00+$Client00)/$info00*100).'%',
                //下个月的续签率
                'monthly02' => $monthly02=round(($data02)/$info02*100).'%',
                //下个月的续签率
                'monthly03' => $monthly03=round(($data03)/$info03*100).'%',
                'client00'=>$Client00
            );
        }
        $this->assign("uss",$uss);
        $this->assign(["monthly"=>$monthly,"monthly2"=>$monthly2,"monthly3"=>$monthly3,"Client"=>$Client]);
        $this->assign(["admin"=>$admin,"user"=>$user,"branch"=>$branch]);
        return $this->fetch();
    }
    //公司的续签率
    public function company(){
        $user=Session::get("user");
        $admin=Session::get("admin");
        //当月时间
        $date = mktime(0,0,0,date('m'),1,date('Y'));
        //下个月的时间
        $date2 = strtotime(date('Y-m-01 00:00:00',strtotime('1 month')));
        //下下个月的时间
        $date3 = strtotime(date('Y-m-01 00:00:00',strtotime('2 month')));
        $data = db('renewal')
            ->alias('a')->join('silver_relation_company w','a.did = w.cid')
            ->where("a.status",1)->where("a.time","eq",$date)->where('w.oid',$user['branch_id'])
            ->count();

        $data2 = db('renewal')
            ->alias('a')->join('silver_relation_company w','a.did = w.cid')
            ->where("a.status",1)->where("a.expire","eq",$date2)->where('w.oid',$user['branch_id'])
            ->count();

        $data3 = db('renewal')
            ->alias('a')->join('silver_relation_company w','a.did = w.cid')
            ->where("a.status",1)->where("a.expire","eq",$date3)->where('w.oid',$user['branch_id'])
            ->count();
        //回签客户的人数
        $Client = db('renewal')
            ->alias('a')->join('silver_relation_company w','a.did = w.cid')
            ->where('w.oid',$user['branch_id'])
            ->where("a.status",1)->where("a.expires","lt",$date)->where("a.time","eq",$date)
            ->count();
//        $dats = db("renewal")->where("user_id",$admin['id'])->where("status",1)->where("time","eq",$date)->select();
        //分配的数量
        //当月的
        $info = db('customer')
            ->alias('a')->join('silver_relation_company w','a.id = w.cid')->where('w.maturitys',$date)
            ->count();

        //下个月的
        $info2 = db('customer')
            ->alias('a')->join('silver_relation_company w','a.id = w.cid')->where('w.maturitys',$date2)

            ->count();

//        //下下个月的
        $info3 = db('customer')
            ->alias('a')->join('silver_relation_company w','a.id = w.cid')->where('w.maturitys',$date3)
            ->count();
        if($info==0){
            $monthly=0;
        }else{
            //当月的续签率
            $monthly=round(($data+$Client)/$info*100).'%';
        }
        if($info2==0){
            $monthly2=0;
        }else{
            //下个月的续签率
            $monthly2=round(($data2)/$info2*100).'%';
        }
        if($info3==0){
            $monthly3=0;
        }else{
            //下个月的续签率
            $monthly3=round(($data3)/$info3*100).'%';
        }
        $office=db('branch_office')->field('id')->field('name')->select();
        foreach ($office as $kk => $v){
            $user_id = $v['id'];
            $user_name = $v['name'];
            $data00 = db('renewal')
                ->alias('a')->join('silver_relation_company w','a.did = w.cid')
                ->where("a.status",1)->where("a.time","eq",$date)->where("w.oid",$user_id)
                ->count();
            $data02 = db('renewal')
                ->alias('a')->join('silver_relation_company w','a.did = w.cid')
                ->where("a.status",1)->where("a.expire","eq",$date2)->where("w.oid",$user_id)
                ->count();

            $data03 = db('renewal')
                ->alias('a')->join('silver_relation_company w','a.did = w.cid')
                ->where("a.status",1)->where("a.expire","eq",$date3)->where("w.oid",$user_id)
                ->count();
//
            //回签客户的人数
            $Client00 = db('renewal')
                ->alias('a')->join('silver_relation_company w','a.did = w.cid')->where("w.oid",$user_id)
                ->where("a.status",1)->where("a.expires","lt",$date)->where("a.time","eq",$date)
                ->count();
            //分配的数量
            //当月的
            $info00 = db('customer')
                ->alias('a')->join('silver_relation_company w','a.id = w.cid')
                ->where('w.maturitys',$date)
                ->count();

            //下个月的
            $info02 = db('customer')
                ->alias('a')->join('silver_relation_company w','a.id = w.cid')
                ->where('w.maturitys',$date2)
                ->count();

            //下下个月的
            $info03 = db('customer')
                ->alias('a')->join('silver_relation_company w','a.id = w.cid')
                ->where('w.maturitys',$date3)
                ->count();
            $uss[$kk] = array(
                'name'=>$user_name,
                //当月的续签率
                'monthly00' => $monthly00=round(($data00+$Client00)/$info00*100).'%',
                //下个月的续签率
                'monthly02' => $monthly02=round(($data02)/$info02*100).'%',
                //下个月的续签率
                'monthly03' => $monthly03=round(($data03)/$info03*100).'%',
                'client00'=>$Client00
            );

        }
        $this->assign("uss",$uss);
        $this->assign(["monthly"=>$monthly,"monthly2"=>$monthly2,"monthly3"=>$monthly3]);
        $this->assign("Client",$Client);
        $this->assign("user",$user);
        $this->assign("admin",$admin);

        return $this->fetch();
    }

}

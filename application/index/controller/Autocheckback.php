<?php
namespace app\index\controller;

use think\db;
use think\Controller;


class Autocheckback extends Controller
{
	 /**
     * 自动执行过期用户添加到回签部
     * @return [type] [description]
     */
    public function auto_checkback()
    {
        $beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
        $todayend = strtotime(date('Y-m-d'.'00:00:00',time()+3600*24));
        $time = time();

            $customer = db('customer')->where("expires","egt",$beginToday)->where("expires","lt",$todayend)->select();
            foreach ($customer as $key => $value) {
                $employee_customer = db('employee_customer')
                                        ->alias('c')
                                        ->join('silver_user u','u.id = c.uid')
                                        ->where('c.cid',$value['id'])
                                        ->field('u.branch_id,c.id')
                                        ->where('c.status',1)
                                        ->find();

                

                if ($employee_customer['branch_id'] != 10) {
                    $result = db('employee_customer')->where('id',$employee_customer['id'])->update(['status'=>2,'updatetime'=>$time]);

                }

                $relation_company = db('relation_company')->where('cid',$value['id'])->where('status',1)->find();

               

                if ($relation_company['oid'] != 10) {
                    $res = db('relation_company')->where('id',$relation_company['id'])->update(['status'=>2,'updatetime'=>$time]);
                    if ($res) {
                        $data['cid'] = $value['id'];
                        $data['oid'] = 10;
                        $data['addtime'] = $time;
                        $data['status'] = 1;
                        $result = db('relation_company')->insert($data);
                        
                    }
                }



            }
        

    }
}
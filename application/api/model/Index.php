<?php
namespace app\api\model;

use think\db;
use think\Model;
use think\Session;

/**
 * 首页
 */
class Index extends Model
{
    /**
     * 当天新签量
     * @return [type] [description]
     */
    public function nday()
    {
        $user = Session::get('a_user');
        $admin = Session::get('a_admin');
        $time = strtotime(date('Y-m-d',time()).' 00:00:00');
        $time1 = strtotime(date('Y-m-d',time()).' 00:00:00')+86400;
        //查询所有的新签客服
        if ($user == "" && $admin != "") {
            $job_num = db('user')->field('username')->where('department',2)->select();
        }elseif ($user != "" && $admin == "") {
            $branch_id = $user['branch_id'];
            if($user['dd_postion']=='总监' && $user['department']==2){
                $job_num = db('user')->field('username')->where('branch_id',$branch_id)->where('department',2)->select();
            }elseif($user['dd_postion']=='经理'){
                $job_num = db('user')->field('username')->where('branch_id',$branch_id)->where('dd_number',$user['dd_number'])->select();
            }elseif($user['dd_postion']=='销售'){
                $job_num = db('user')->field('username')->where('id',$user['id'])->select();
            }elseif($user['dd_postion']=='集团董事长'||$user['dd_postion']=='集团总经理'||$user['dd_postion']=='集团副总经理'){
                $num = db('alimport')
                    ->where('new_type',"新签")
                    ->where('product_category',"诚信通服务")
                    ->where("single_time","egt",$time)
                    ->where("single_time","lt",$time1)
                    ->count();
                return $num;

            }elseif($user['dd_postion']=='总经理'){
                $job_num = db('user')->field('username')->where('branch_id',$branch_id)->select();
            }

        }else{
            return $arr=array('code'=>0,'msg'=>'未登录');
        }
        if(empty($job_num)){
            $num=0;
        }else{
            foreach ($job_num as $key => $value) {
                $renewal[$key] = db('alimport')
                    ->where('sales',$value['username'])
                    ->where('new_type',"新签")
                    ->where('product_category',"诚信通服务")
                    ->where("single_time","egt",$time)
                    ->where("single_time","lt",$time1)
                    ->count();
            }
            $num = array_sum($renewal);
        }
        return $num;
    }
    /**
     * 当月新签量
     * @return [type] [description]
     */
    public function nmonth()
    {
        $user = Session::get('a_user');
        $admin = Session::get('a_admin');
        $time = mktime(0,0,0,date('m'),1,date('Y'));
        $time1 = strtotime(date('Y-m-01 00:00:00',strtotime('1 month')));
        //查询所有的新签客服
        if ($user == "" && $admin != "") {
            $job_num = db('user')->field('username')->where('department',2)->select();
        }elseif ($user != "" && $admin == "") {
            $branch_id = $user['branch_id'];
            if($user['dd_postion']=='总监' && $user['department']==2){
                $job_num = db('user')->field('username')->where('branch_id',$branch_id)->where('department',2)->select();
            }elseif($user['dd_postion']=='经理'){
                $job_num = db('user')->field('username')->where('branch_id',$branch_id)->where('dd_number',$user['dd_number'])->select();
            }elseif($user['dd_postion']=='销售'){
                $job_num = db('user')->field('username')->where('id',$user['id'])->select();
            }elseif($user['dd_postion']=='集团董事长'||$user['dd_postion']=='集团总经理'||$user['dd_postion']=='集团副总经理'){
                $num = db('alimport')
                    ->where('new_type',"新签")
                    ->where('product_category',"诚信通服务")
                    ->where("single_time","egt",$time)
                    ->where("single_time","lt",$time1)
                    ->count();
                return $num;
            }elseif($user['dd_postion']=='总经理'){
                $job_num = db('user')->field('username')->where('branch_id',$branch_id)->select();
            }
        }else{
            return $arr=array('code'=>0,'msg'=>'未登录');
        }
        if(empty($job_num)){
            $num=0;
        }else{
            foreach ($job_num as $key => $value) {
                $renewal[$key] = db('alimport')
                    ->where('single_time','between time',[$time,$time1])
                    ->where('single_time','between time',[$time,$time1])
                    ->where('sales',$value['username'])
                    ->where('new_type',"新签")
                    ->where('product_category',"诚信通服务")
                    ->count();

            }
            $num = array_sum($renewal);
        }

        return $num;
    }
    /**
     * 当天续签量
     * @return [type] [description]
     */
//	public function cday()
//	{
//		$user = Session::get('a_user');
//		$time = strtotime(date('Y-m-d',time()).' 00:00:00');
//		$time1 = strtotime(date('Y-m-d',time()).' 00:00:00')+86400;
//		// var_dump($user);
//		// die;
//		//续签订单
//		if($user['dd_postion']=='集团董事长' || $user['dd_postion']=="副总经理"){
//			$num=Db::name('per_order')->where('per_department',3)->where('per_type',1)->where('per_addtime','between time',$time,$time1)->count();
//		}
//		if($user['dd_postion']=='总经理'){
//			$num=Db::name('per_order')->where('per_department',3)->where('per_type',1)->where('per_bid',$user['branch_id'])->where('per_addtime','between time',$time,$time1)->count();
//		}
//		if($user['dd_postion']=='总监'){
//			$num=Db::name('per_order')->where('per_department',3)->where('per_type',1)->where('per_ranks',$user['id'])->whereOr('per_uid',$user['id'])->where('per_addtime','between time',$time,$time1)->count();
//		}
//		if($user['dd_postion']=='经理'){
//			$num=Db::name('per_order')->where('per_department',3)->where('per_type',1)->where('per_number',$user['dd_number'])->where('per_addtime','between time',$time,$time1)->count();
//		}
//		if($user['dd_postion']=='客服'){
//			$num=Db::name('per_order')->where('per_uid',$user['id'])->where('per_type',1)->count();
//		}
//		return $num ? $num : 0;
//	}
    public function cday()
    {
        $user = Session::get('a_user');
        $admin = Session::get('a_admin');
        $time = strtotime(date('Y-m-d',time()).' 00:00:00');
        $time1 = strtotime(date('Y-m-d',time()).' 00:00:00')+86400;
        // var_dump($user);
        // die;
        //续签订单
        if ($user == "" && $admin != "") {
            $job_num = db('user')->field('username')->where('department',3)->select();
        }elseif ($user != "" && $admin == "") {
            $branch_id = $user['branch_id'];
            if($user['dd_postion']=='总监' && $user['department']==3){
                $job_num = db('user')->field('username')->where('branch_id',$branch_id)->where('department',3)->select();
            }elseif($user['dd_postion']=='经理'){
                $job_num = db('user')->field('username')->where('branch_id',$branch_id)->where('dd_number',$user['dd_number'])->select();
            }elseif($user['dd_postion']=='销售'){
                $job_num = db('user')->field('username')->where('id',$user['id'])->select();
            }elseif($user['dd_postion']=='集团董事长'||$user['dd_postion']=='集团总经理'||$user['dd_postion']=='集团副总经理'){
                $num = db('alimport')
                    ->where('new_type',"续签")
                    ->where('product_category',"诚信通服务")
                    ->where("single_time","egt",$time)
                    ->where("single_time","lt",$time1)
                    ->count();
                return $num;
            }elseif($user['dd_postion']=='总经理'){
                $job_num = db('user')->field('username')->where('branch_id',$branch_id)->select();
            }
        }else{
            return $arr=array('code'=>0,'msg'=>'未登录');
        }
        if(empty($job_num)){
            $num=0;
        }else{
            foreach ($job_num as $key => $value) {
                $renewal[$key] = db('alimport')
                    ->where('sales',$value['username'])
                    ->where('new_type',"续签")
                    ->where('product_category',"诚信通服务")
                    ->where("single_time","egt",$time)
                    ->where("single_time","lt",$time1)
                    ->count();
            }
            $num = array_sum($renewal);
        }

        return $num;
    }
    /**
     * 当月续签量
     * @return [type] [description]
     */
//	public function cmonth()
//	{
//		$user = Session::get('a_user');
//		$time = mktime(0,0,0,date('m'),1,date('Y'));
//		$time1 = strtotime(date('Y-m-01 00:00:00',strtotime('1 month')));
//		//查询所有的签客服
//		//续签订单
//		if($user['dd_postion']=='集团董事长' || $user['dd_postion']=="副总经理"){
//			$num=Db::name('per_order')->where('per_department',3)->where('per_type',1)->where('per_addtime','between time',$time,$time1)->count();
//		}
//		if($user['dd_postion']=='总经理'){
//			$num=Db::name('per_order')->where('per_department',3)->where('per_type',1)->where('per_bid',$user['branch_id'])->where('per_addtime','between time',$time,$time1)->count();
//		}
//		if($user['dd_postion']=='总监'){
//			$num=Db::name('per_order')->where('per_department',3)->where('per_type',1)->where('per_ranks',$user['id'])->whereOr('per_uid',$user['id'])->where('per_addtime','between time',$time,$time1)->count();
//		}
//		if($user['dd_postion']=='经理'){
//			$num=Db::name('per_order')->where('per_department',3)->where('per_type',1)->where('per_number',$user['dd_number'])->where('per_addtime','between time',$time,$time1)->count();
//		}
//		if($user['dd_postion']=='客服'){
//			$num=Db::name('per_order')->where('per_uid',$user['id'])->where('per_type',1)->count();
//		}
//		return $num ? $num : 0;
//	}

    public function cmonth()
    {
        $user = Session::get('a_user');
        $admin = Session::get('a_admin');
        $time = mktime(0,0,0,date('m'),1,date('Y'));
        $time1 = strtotime(date('Y-m-01 00:00:00',strtotime('1 month')));
        //查询所有的签客服
        //续签订单
        if ($user == "" && $admin != "") {
            $job_num = db('user')->field('username')->where('department',3)->select();
        }elseif ($user != "" && $admin == "") {
            $branch_id = $user['branch_id'];
            if($user['dd_postion']=='总监' && $user['department']==3){
                $job_num = db('user')->field('username')->where('branch_id',$branch_id)->where('department',3)->select();
            }elseif($user['dd_postion']=='经理'){
                $job_num = db('user')->field('username')->where('branch_id',$branch_id)->where('dd_number',$user['dd_number'])->select();
            }elseif($user['dd_postion']=='销售'){
                $job_num = db('user')->field('username')->where('id',$user['id'])->select();
            }elseif($user['dd_postion']=='集团董事长'||$user['dd_postion']=='集团总经理'||$user['dd_postion']=='集团副总经理'){
                $num = db('alimport')
                    ->where('new_type',"续签")
                    ->where('product_category',"诚信通服务")
                    ->where("single_time","egt",$time)
                    ->where("single_time","lt",$time1)
                    ->count();
                //echo db('alimport')->getLastSql();die;
                return $num;
            }elseif($user['dd_postion']=='总经理'){
                $job_num = db('user')->field('username')->where('branch_id',$branch_id)->select();
            }
        }else{
            return $arr=array('code'=>0,'msg'=>'未登录');
        }
        if(empty($job_num)){
            $num=0;
        }else{
            foreach ($job_num as $key => $value) {
                $renewal[$key] = db('alimport')
                    ->where('sales',$value['username'])
                    ->where('new_type',"续签")
                    ->where('product_category',"诚信通服务")
                    ->where("single_time","egt",$time)
                    ->where("single_time","lt",$time1)
                    ->count();
            }
            $num = array_sum($renewal);
        }

        return $num;
    }

    /**
     * 当日新签认证通过
     * @return [type] [description]
     */
    public function nsday(){
        $user = Session::get('a_user');
        $admin = Session::get('a_admin');
        $time = strtotime(date('Y-m-d',time()).' 00:00:00');
        $time1 = strtotime(date('Y-m-d',time()).' 00:00:00')+86400;
        //查询所有的新签客服
        if ($user == "" && $admin != "") {
            $job_num = db('user')->field('username')->where('department',2)->select();
        }elseif ($user != "" && $admin == "") {
            $branch_id = $user['branch_id'];
            if($user['dd_postion']=='总监' && $user['department']==2){
                $job_num = db('user')->field('username')->where('branch_id',$branch_id)->where('department',2)->select();
            }elseif($user['dd_postion']=='经理'){
                $job_num = db('user')->field('username')->where('branch_id',$branch_id)->where('dd_number',$user['dd_number'])->select();
            }elseif($user['dd_postion']=='销售'){
                $job_num = db('user')->field('username')->where('id',$user['id'])->select();
            }elseif($user['dd_postion']=='集团董事长'||$user['dd_postion']=='集团总经理'||$user['dd_postion']=='集团副总经理'){
                $num = db('authen')
                    ->where('new_type',"新签")
                    ->where('product_category',"诚信通服务")
                    ->where("single_time","egt",$time)
                    ->where("single_time","lt",$time1)
                    ->count();
                //echo db('alimport')->getLastSql();die;
                return $num;
            }elseif($user['dd_postion']=='总经理'){
                $job_num = db('user')->field('username')->where('branch_id',$branch_id)->select();
            }

        }else{
            return $arr=array('code'=>0,'msg'=>'未登录');
        }
        if(empty($job_num)){
            $num=0;
        }else{
            foreach ($job_num as $key => $value) {
                $renewal[$key] = db('authen')
                    ->where('sales',$value['username'])
                    ->where('new_type',"新签")
                    ->where('product_category',"诚信通服务")
                    ->where("sendover_time","egt",$time)
                    ->where("sendover_time","lt",$time1)
                    ->count();
            }
            $num = array_sum($renewal);
        }
        return $num;
    }

    /**
     * 当月新签认证通过
     * @return [type] [description]
     */
    public function nsmonth(){
        $user = Session::get('a_user');
        $admin = Session::get('a_admin');
        $time = mktime(0,0,0,date('m'),1,date('Y'));
        $time1 = strtotime(date('Y-m-01 00:00:00',strtotime('1 month')));
        //查询所有的新签客服
        if ($user == "" && $admin != "") {
            $job_num = db('user')->field('username')->where('department',3)->select();
        }elseif ($user != "" && $admin == "") {
            $branch_id = $user['branch_id'];
            if($user['dd_postion']=='总监' && $user['department']==3){
                $job_num = db('user')->field('username')->where('branch_id',$branch_id)->where('department',3)->select();
            }elseif($user['dd_postion']=='经理'){
                $job_num = db('user')->field('username')->where('branch_id',$branch_id)->where('dd_number',$user['dd_number'])->select();
            }elseif($user['dd_postion']=='销售'){
                $job_num = db('user')->field('username')->where('id',$user['id'])->select();
            }elseif($user['dd_postion']=='集团董事长'||$user['dd_postion']=='集团总经理'||$user['dd_postion']=='集团副总经理'){
                $num = db('authen')
                    ->where('new_type',"新签")
                    ->where('product_category',"诚信通服务")
                    ->where("single_time","egt",$time)
                    ->where("single_time","lt",$time1)
                    ->count();
                //echo db('alimport')->getLastSql();die;
                return $num;
            }elseif($user['dd_postion']=='总经理'){
                $job_num = db('user')->field('username')->where('branch_id',$branch_id)->select();
            }

        }else{
            return $arr=array('code'=>0,'msg'=>'未登录');
        }
        if(empty($job_num)){
            $num=0;
        }else{
            foreach ($job_num as $key => $value) {
                $renewal[$key] = db('authen')
                    ->where('sales',$value['username'])
                    ->where('new_type',"新签")
                    ->where('product_category',"诚信通服务")
                    ->where("sendover_time","egt",$time)
                    ->where("sendover_time","lt",$time1)
                    ->count();
            }
            $num = array_sum($renewal);
        }
        return $num;
    }

    /**
     * 当日续签认证通过
     * @return [type] [description]
     */
    public function csday(){
        $user = Session::get('a_user');
        $admin = Session::get('a_admin');
        $time = strtotime(date('Y-m-d',time()).' 00:00:00');
        $time1 = strtotime(date('Y-m-d',time()).' 00:00:00')+86400;
        //查询所有的新签客服
        if ($user == "" && $admin != "") {
            $job_num = db('user')->field('username')->where('department',3)->select();
        }elseif ($user != "" && $admin == "") {
            $branch_id = $user['branch_id'];
            if($user['dd_postion']=='总监' && $user['department']==3){
                $job_num = db('user')->field('username')->where('branch_id',$branch_id)->where('department',3)->select();
            }elseif($user['dd_postion']=='经理'){
                $job_num = db('user')->field('username')->where('branch_id',$branch_id)->where('dd_number',$user['dd_number'])->select();
            }elseif($user['dd_postion']=='销售'){
                $job_num = db('user')->field('username')->where('id',$user['id'])->select();
            }elseif($user['dd_postion']=='集团董事长'||$user['dd_postion']=='集团总经理'||$user['dd_postion']=='集团副总经理'){
                $num = db('authen')
                    ->where('new_type',"续签")
                    ->where('product_category',"诚信通服务")
                    ->where("single_time","egt",$time)
                    ->where("single_time","lt",$time1)
                    ->count();
                //echo db('alimport')->getLastSql();die;
                return $num;
            }elseif($user['dd_postion']=='总经理'){
                $job_num = db('user')->field('username')->where('branch_id',$branch_id)->select();
            }

        }else{
            return $arr=array('code'=>0,'msg'=>'未登录');
        }
        if(empty($job_num)){
            $num=0;
        }else{
            foreach ($job_num as $key => $value) {
                $renewal[$key] = db('authen')
                    ->where('sales',$value['username'])
                    ->where('new_type',"续签")
                    ->where('product_category',"诚信通服务")
                    ->where("sendover_time","egt",$time)
                    ->where("sendover_time","lt",$time1)
                    ->count();
            }
            $num = array_sum($renewal);
        }
        return $num;
    }

    /**
     * 当月续签认证通过
     * @return [type] [description]
     */
    public function csmonth(){
        $user = Session::get('a_user');
        $admin = Session::get('a_admin');
        $time = mktime(0,0,0,date('m'),1,date('Y'));
        $time1 = strtotime(date('Y-m-01 00:00:00',strtotime('1 month')));
        //查询所有的新签客服
        if ($user == "" && $admin != "") {
            $job_num = db('user')->field('username')->where('department',3)->select();
        }elseif ($user != "" && $admin == "") {
            $branch_id = $user['branch_id'];
            if($user['dd_postion']=='总监' && $user['department']==3){
                $job_num = db('user')->field('username')->where('branch_id',$branch_id)->where('department',3)->select();
            }elseif($user['dd_postion']=='经理'){
                $job_num = db('user')->field('username')->where('branch_id',$branch_id)->where('dd_number',$user['dd_number'])->select();
            }elseif($user['dd_postion']=='销售'){
                $job_num = db('user')->field('username')->where('id',$user['id'])->select();
            }elseif($user['dd_postion']=='集团董事长'||$user['dd_postion']=='集团总经理'||$user['dd_postion']=='集团副总经理'){
                $num = db('authen')
                    ->where('new_type',"续签")
                    ->where('product_category',"诚信通服务")
                    ->where("single_time","egt",$time)
                    ->where("single_time","lt",$time1)
                    ->count();
                //echo db('alimport')->getLastSql();die;
                return $num;
            }elseif($user['dd_postion']=='总经理'){
                $job_num = db('user')->field('username')->where('branch_id',$branch_id)->select();
            }
        }else{
            return $arr=array('code'=>0,'msg'=>'未登录');
        }
        if(empty($job_num)){
            $num=0;
        }else{
            foreach ($job_num as $key => $value) {
                $renewal[$key] = db('authen')
                    ->where('sales',$value['username'])
                    ->where('new_type',"续签")
                    ->where('product_category',"诚信通服务")
                    ->where("sendover_time","egt",$time)
                    ->where("sendover_time","lt",$time1)
                    ->count();
            }
            $num = array_sum($renewal);
        }
        return $num;
    }
    public function p4pday()
    {
        $time = strtotime(date('Y-m-d',time()).' 00:00:00');
        $time1 = strtotime(date('Y-m-d',time()).' 00:00:00')+86400;
        $num['count'] = db('alimport')
            ->where('new_type',"新签")
            ->where('product_category',"网销宝充值包")
            ->where("single_time","egt",$time)
            ->where("single_time","lt",$time1)
            ->count();
        $num['sum']= db('alimport')
            ->where('new_type',"新签")
            ->where('product_category',"网销宝充值包")
            ->where("single_time","egt",$time)
            ->where("single_time","lt",$time1)
            ->sum('account_money');
        $num['sum']=round($num['sum']/10000,2);
        return $num;

    }
    //p4p每月到单
    public function p4pmonth()
    {
        $time = mktime(0,0,0,date('m'),1,date('Y'));
        $time1 = strtotime(date('Y-m-01 00:00:00',strtotime('1 month')));
        $num['count'] = db('alimport')
            ->where('new_type',"新签")
            ->where('product_category',"网销宝充值包")
            ->where("single_time","egt",$time)
            ->where("single_time","lt",$time1)
            ->count();
        $num['sum']= db('alimport')
            ->where('new_type',"新签")
            ->where('product_category',"网销宝充值包")
            ->where("single_time","egt",$time)
            ->where("single_time","lt",$time1)
            ->sum('account_money');
        $num['sum']=round($num['sum']/10000,2);
        return $num;

    }
    public function cp4pday()
    {
        $time = strtotime(date('Y-m-d',time()).' 00:00:00');
        $time1 = strtotime(date('Y-m-d',time()).' 00:00:00')+86400;
        $num['count'] = db('alimport')
            ->where('new_type',"续签")
            ->where('product_category',"网销宝充值包")
            ->where("single_time","egt",$time)
            ->where("single_time","lt",$time1)
            ->count();
        $num['sum']= db('alimport')
            ->where('new_type',"续签")
            ->where('product_category',"网销宝充值包")
            ->where("single_time","egt",$time)
            ->where("single_time","lt",$time1)
            ->sum('account_money');
        $num['sum']=round($num['sum']/10000,2);
        return $num;
    }
    //p4p每月到单
    public function cp4pmonth()
    {
        $time = mktime(0,0,0,date('m'),1,date('Y'));
        $time1 = strtotime(date('Y-m-01 00:00:00',strtotime('1 month')));
        $num['count'] = db('alimport')
            ->where('new_type',"续签")
            ->where('product_category',"网销宝充值包")
            ->where("single_time","egt",$time)
            ->where("single_time","lt",$time1)
            ->count();
        $num['sum']= db('alimport')
            ->where('new_type',"续签")
            ->where('product_category',"网销宝充值包")
            ->where("single_time","egt",$time)
            ->where("single_time","lt",$time1)
            ->sum('account_money');
        $num['sum']=round($num['sum']/10000,2);
        return $num;

    }
    public function p4p(){
        for ($i = 0; $i < 12; $i++){
            $months[][] = date("Y-m", strtotime("-$i months"));

        }
        foreach ($months as $k=>$v){
            $y=substr($v[0],0,4);
            $m=substr($v[0],-2,2);
            $months[$k]=$this->getdate($y,$m);
            $months[$k]['ncount'] = db('alimport')
                ->where('new_type',"新签")
                ->where('product_category',"网销宝充值包")
                ->where("single_time","egt",$months[$k]['firstday'])
                ->where("single_time","lt",$months[$k]['lastday'])
                ->count();
            $months[$k]['nsum']= db('alimport')
                ->where('new_type',"新签")
                ->where('product_category',"网销宝充值包")
                ->where("single_time","egt",$months[$k]['firstday'])
                ->where("single_time","lt",$months[$k]['lastday'])
                ->sum('account_money');
            $months[$k]['nsum']=round($months[$k]['nsum']/10000,2);
            $months[$k]['xcount'] = db('alimport')
                ->where('new_type',"续签")
                ->where('product_category',"网销宝充值包")
                ->where("single_time","egt",$months[$k]['firstday'])
                ->where("single_time","lt",$months[$k]['lastday'])
                ->count();
            $months[$k]['xsum']= db('alimport')
                ->where('new_type',"续签")
                ->where('product_category',"网销宝充值包")
                ->where("single_time","egt",$months[$k]['firstday'])
                ->where("single_time","lt",$months[$k]['lastday'])
                ->sum('account_money');
            $months[$k]['xsum']=round($months[$k]['xsum']/10000,2);
            unset($months[$k]['firstday']);
            $months[$k]['lastday']=date("Y-m",$months[$k]['lastday']);
            }
            return $months;
    }
    public function getdate($y,$m){
        if($y=="") $y=date("Y");
        if($m=="") $m=date("m");
        $m=sprintf("%02d",intval($m));
        $y=str_pad(intval($y),4,"0",STR_PAD_RIGHT);

        $m>12||$m<1?$m=1:$m=$m;
        $firstday=strtotime($y.$m."01000000");
        $firstdaystr=date("Y-m-01",$firstday);
        $lastday = strtotime(date('Y-m-d 23:59:59', strtotime("$firstdaystr +1 month -1 day")));
        return array("firstday"=>$firstday,"lastday"=>$lastday);
    }
    /**
     * 公海
     * @return [type] [description]
     */
    public function coom()
    {
        $user = Session::get('a_user');
        $admin = Session::get('a_admin');
        if ($user == "" && $admin != "") {
            $coom = db('newcustomer')->where('c_getcoom',0)->select();

        }elseif ($user != "" && $admin == "") {
            $coom = db('newcustomer')->where('c_getcoom',0)->where('bid',$user['branch_id'])->select();
        }else{
            return $arr=array('code'=>0,'msg'=>'未登录');
        }
        return $coom;
    }
    /**
     * 销售锦囊
     * @return [type] [description]
     */
    public function salespackage()
    {
        $user = Session::get('a_user');
        $admin = Session::get('a_admin');
        if ($user == "" && $admin == "") {
            return $arr=array('code'=>0,'msg'=>'未登录');
        }else{
            $salespackage = db('instructions')->where('type',1)->find();
            return $salespackage;
        }
    }
    /**
     * 我的客户
     * @return [type] [description]
     */
    public function myclient($data)
    {
        $data['c_address']=$data['province'].$data['city'].$data['district'].$data['c_address'];
        unset($data['province']);
        unset($data['city']);
        unset($data['district']);
        $data['c_status']=0;
        $data['c_getcoom']=1;
        $data['bid']=session('a_user.branch_id');
        $data['c_registration']=strtotime($data['c_registration']);
        //求newcustomer表中未签约的状态的总数
        $count=Db::name('newcustomer')->where('c_status',0)->count();
        if ($count>=300){
            $code=3;
            return $code;
        }else{
            $same=Db::name('newcustomer')->where('c_tel',$data['c_tel'])->select();
            if ($same){
                $code=4;
                return $code;
            }else{
                $code=1;
                $res=Db::name('newcustomer')->insertGetId($data);
                $dad['c_id']=$res;
                $dad['job_num']=Session::get('a_user.job_num');
                Db::name('newcustomer_status')->insert($dad);
            }
            return $code;
        }
    }
    /**
     * 撞单查询
     * @param  [type] $data [手机号]
     * @return [type]       [description]
     */
    public function collisionCheck($data)
    {
        $ipone = $data['tel'];

        $customer = db('newcustomer')->where('c_tel',$ipone)->find();
        return $customer;
    }
    public function workSchedule($data)
    {
        $arr['c_visits'] = $data['c_visits'];
        $arr['c_visitstel'] = $data['c_visitstel'];
        $arr['c_intention'] = $data['c_intention'];
        $arr['c_coomnum'] = $data['c_coomnum'];
        $arr['c_time'] = time();
        $user = Session::get('a_user');
        $admin = Session::get('a_admin');
        if ($user == "" && $admin == "") {
            return '未登录';
        }else{
            if ($user != "") {
                $arr['c_jobnum'] = $user['job_num'];
                $arr['c_branch_id'] = $user['branch_id'];
            }
            $res = db('coun')->insert($data);
            return $res;
        }
    }
}
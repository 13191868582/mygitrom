<?php
namespace app\index\model;
use think\Model;
use think\Db;
use think\Session;
use think\Request;



class  Counts  extends Model
{
    //查询每日统计
    public  function  selectcount($time,$admin=""){
        $startime=strtotime("$time"."00:00:00");
        $endtime=strtotime("$time"."23:59:59");
        $admin=Session::get('admin.username');
        if (!empty($admin)){
            $data['data']=Db::name('count')
                ->alias('co')
                ->join('user us','co.c_jobnum=us.job_num')
                ->join('branch_office br','co.c_branch_id=br.id')
                ->field('co.c_visits,co.c_visitstel,co.c_intention,co.c_jobnum,co.c_time,us.username,br.name,co.c_coomnum')
                ->whereTime('c_time','between',["$startime","$endtime"])
                ->paginate(10);
            $data['page'] = $data['data']->render();
            //查询总数条数
            $data['sum']=Db::name('count')
                ->whereTime('c_time','between',["$startime","$endtime"])
                ->count();
            //查询每日上门量总数
            $data['c_visits']=Db::name('count')
                ->whereTime('c_time','between',["$startime","$endtime"])
                ->sum('c_visits');
            //查询每日电话量总数
            $data['c_visitstel']=Db::name('count')
                ->whereTime('c_time','between',["$startime","$endtime"])
                ->sum('c_visitstel');
            //查询每日新增客户意向(B级以上)总数
            $data['c_intention']=Db::name('count')
                ->whereTime('c_time','between',["$startime","$endtime"])
                ->sum('c_intention');
            $data['c_coomnum']=Db::name('count')
                ->whereTime('c_time','between',["$startime","$endtime"])
                ->sum('c_coomnum');
        }else {
            //获取总监所属公司
            $brand_id = Session::get('user.branch_id');
            $data['data'] = Db::name('count')
                ->alias('co')
                ->join('user us', 'co.c_jobnum=us.job_num')
                ->join('branch_office br', 'co.c_branch_id=br.id')
                ->field('co.c_visits,co.c_visitstel,co.c_intention,co.c_jobnum,co.c_time,us.username,br.name,co.c_coomnum')
                ->where('c_branch_id', $brand_id)
                ->whereTime('c_time', 'between', ["$startime", "$endtime"])
                ->paginate(30);
            $data['page'] = $data['data']->render();
            //查询总数条数
            $data['sum'] = Db::name('count')
                ->where('c_branch_id', $brand_id)
                ->whereTime('c_time', 'between', ["$startime", "$endtime"])
                ->count();
            //查询每日上门量总数
            $data['c_visits'] = Db::name('count')
                ->where('c_branch_id', $brand_id)
                ->whereTime('c_time', 'between', ["$startime", "$endtime"])
                ->sum('c_visits');
            //查询每日电话量总数
            $data['c_visitstel'] = Db::name('count')
                ->where('c_branch_id', $brand_id)
                ->whereTime('c_time', 'between', ["$startime", "$endtime"])
                ->sum('c_visitstel');
            //查询每日新增客户意向(B级以上)总数
            $data['c_intention'] = Db::name('count')
                ->where('c_branch_id', $brand_id)
                ->whereTime('c_time', 'between', ["$startime", "$endtime"])
                ->sum('c_intention');
            $data['c_coomnum'] = Db::name('count')
                ->where('c_branch_id', $brand_id)
                ->whereTime('c_time', 'between', ["$startime", "$endtime"])
                ->sum('c_coomnum');
        }
        return $data;
    }
    //执行录入
    public function  countadd($data){
        //获取录入员工工号
        $data['c_jobnum']=Session::get('user.job_num');
        //员工所属公司
        $data['c_branch_id']=Session::get('user.branch_id');
        //录入时间
        $data['c_time']=time();
        $res=Db::name('count')->insert($data);
        if ($res){
            return $arr= array('code' =>200,'msg'=>'录入成功！');
        }else{
            return $arr= array('code' =>404,'msg'=>'录入失败！');
        }
    }
    //查询到单
    public function getsingle($time){
        $admin=Session::get('admin.username');
        if (!empty($admin)){
            $startime=strtotime("$time"."00:00:00");
            $endtime=strtotime("$time"."23:59:59");
            $data['data'] = Db::name('neworderform')
                ->alias('ne')
                ->join('branch_office br','ne.bid=br.id')
                ->join('user us', 'us.id=ne.uid')
                ->field('us.job_num,us.username,br.name,ne.totime,ne.cid,ne.aliordernum')
                ->whereTime('ne.totime', 'between', ["$startime", "$endtime"])
                ->where('ne.status', 1)
                ->paginate(30);
            $data['page'] = $data['data']->render();
            $data['getsum']=Db::name('neworderform')
                ->where('status',1)
                ->whereTime('totime', 'between', ["$startime", "$endtime"])
                ->count();
        }else{
            $startime=strtotime("$time"."00:00:00");
            $endtime=strtotime("$time"."23:59:59");
            $id = Session::get('user.branch_id');
            $data['data'] = Db::name('neworderform')
                ->alias('ne')
                ->join('branch_office br','ne.bid=br.id')
                ->join('user us', 'us.id=ne.uid')
                ->field('us.job_num,us.username,br.name,ne.totime,ne.cid,ne.aliordernum')
                ->whereTime('ne.totime', 'between', ["$startime", "$endtime"])
                ->where('ne.bid',$id)
                ->where('ne.status', 1)
                ->paginate(30);
            $data['page'] = $data['data']->render();
            $data['getsum']=Db::name('neworderform')
                ->where('bid',$id)
                ->where('status',1)
                ->whereTime('totime', 'between', ["$startime", "$endtime"])
                ->count();
        }
        return $data;
    }
    //查询开通
    public function getopen($time){
        $admin=Session::get('admin.username');
        if (!empty($admin)){
            $startime=strtotime("$time"."00:00:00");
            $endtime=strtotime("$time"."23:59:59");
            $data['data'] = Db::name('neworderform')
                ->alias('ne')
                ->join('branch_office br','ne.bid=br.id')
                ->join('user us', 'us.id=ne.uid')
                ->field('us.job_num,us.username,br.name,ne.totime,ne.cid,ne.aliordernum')
                ->whereTime('ne.totime', 'between', ["$startime", "$endtime"])
                ->where('ne.status', 2)
                ->paginate(30);
            $data['page'] = $data['data']->render();
            $data['getsum']=Db::name('neworderform')
                ->where('status',2)
                ->whereTime('totime', 'between', ["$startime", "$endtime"])
                ->count();
        }else{
            $startime=strtotime("$time"."00:00:00");
            $endtime=strtotime("$time"."23:59:59");
            $id = Session::get('user.branch_id');
            $data['data'] = Db::name('neworderform')
                ->alias('ne')
                ->join('branch_office br','ne.bid=br.id')
                ->join('user us', 'us.id=ne.uid')
                ->field('us.job_num,us.username,br.name,ne.totime,ne.cid,ne.aliordernum')
                ->whereTime('ne.totime', 'between', ["$startime", "$endtime"])
                ->where('ne.bid',$id)
                ->where('ne.status', 2)
                ->paginate(30);
            $data['page'] = $data['data']->render();
            $data['getsum']=Db::name('neworderform')
                ->where('bid',$id)
                ->where('status',2)
                ->whereTime('totime', 'between', ["$startime", "$endtime"])
                ->count();
        }
        return $data;
    }
}


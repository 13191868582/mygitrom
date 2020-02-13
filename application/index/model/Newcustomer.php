<?php
namespace app\index\model;
use think\Model;
use think\db;
use think\Session;
use think\Exception;
use think\Paginator;

class Newcustomer extends Model
{
    public function  add($data){
        $data['c_address']=$data['province'].$data['city'].$data['district'].$data['c_address'];
        $data['createtime']=strtotime(date('Y-m-d',time()));
        unset($data['province']);
        unset($data['city']);
        unset($data['district']);
        $data['c_status']=0;
        $data['c_getcoom']=1;
        $data['bid']=session('user.branch_id');
        $data['c_rank']=Session::get('user.rank');
        $data['c_registration']=strtotime($data['c_registration']);
        //求newcustomer表中未签约的状态的总数
        $count=Db::name('newcustomer')->where('c_status',0)->count();
        $same=Db::name('newcustomer')->where('c_tel',$data['c_tel'])->find();
        $c_company=Db::name('newcustomer')->where('c_company',$data['c_company'])->find();
        if ($same&&$c_company){
                $code=4;
                return $code;
        }else{
                $res=Db::name('newcustomer')->insertGetId($data);
                $dad['c_id']=$res;
                $dad['job_num']=Session::get('user.job_num');
                $res=Db::name('newcustomer_status')->insert($dad);
                if ($res){
                    $code=1;
                }
            }
            return $code;
    }
    //公海信息列表
    public function  getlists(){
        $lisRows=10;
        $admin=Session::get('admin.username');
        if(!empty($admin)){
            $result=Db::name('newcustomer')
                ->where('c_getcoom',0)
                ->field('c_id,c_username,c_tel,c_tel,c_company,c_intentionality,c_registration,c_way')
                ->paginate($lisRows,false,['query' => request()->param()]);
            $result['sum']=count($result);
            $result['page'] = $result->render();
        }else{
            $bid=session('user.branch_id');
            $result=Db::name('newcustomer')
                ->where('c_getcoom',0)
                ->where('bid',$bid)
                ->field('c_id,c_username,c_tel,c_company,c_intentionality,c_registration,c_way')
                ->paginate($lisRows,false,['query' => request()->param()]);
            $result['sum']=count($result);
            $result['page'] = $result->render();
        }
        return $result;
    }
    //加入保护入库操作
    public function  getadd($data){
        //先判断newcustomer_status是否有当前数据
        $ress=Db::name('newcustomer_status')->where('c_id',$data['c_id'])->where('job_num',$data['job_num'])->find();
        if (!$ress){
            $res=Db::name('newcustomer_status')->insert($data);
            if ($res){
                $result=Db::name('newcustomer')->where('c_id',$data['c_id'])->update(['c_getcoom'=>1]);
                if ($result){
                    $code=1;
                    return $code;
                }else{
                    return 0;
                }
            }else{
                return 0;
            }
        }else{
            return 0;
        }
    }
    //放弃保护
    public  function  deleteadd($c_id){
        $job_num=Session::get('user.job_num');
        //删除newcustomer_status表中对应的数据
        $resd=Db::name('newcustomer_status')->where('c_id',$c_id)->where('job_num',$job_num)->delete();
        if ($resd){
            //修改公海表里面的对应c_id的保护状态
            $result=Db::name('newcustomer')->where('c_id',$c_id)->update(['c_getcoom'=>0]);
            $code=1;
            return $code;
        }else{
            return 0;
        }
    }
    //新签客户列表
    public function lists($userid='',$time,$c_way,$c_type1,$c_intentionality,$createtime){
        $lisRows=20;
        $rank=Session::get('user.rank');
        if($rank==11||$rank==1){
           if(($userid)||($userid&&$time)||($userid&&$c_way)||($userid&&$time&&$c_way)||($userid&&$c_type1)||($userid&&$time&&$c_type1)||($userid&&$c_way&&$c_type1)||($userid&&$c_way&&$c_type1&&$time)){
               $where=[];
               if (!empty($c_intentionality)){
                   $where['c_intentionality']=$c_intentionality;
               }
               if(!empty($createtime)){
                   $where['createtime']=strtotime($createtime);
               }
                $pre=Db::name('newcustomer_status')
                    ->where('job_num',$userid)
                    ->column('c_id');
                    $result=Db::name('newcustomer')
                        ->where('c_getcoom',1)
                        ->where($where)
                        ->where(function($query)use($time){
                            if ($time!==""){
                                $startime=strtotime("$time"."00:00:00");
                                $endtime=strtotime("$time"."23:59:59");
                                $query->where('c_registration','between time',[$startime,$endtime]);
                            }
                    })
                        ->where(function($query)use($c_way){
                            if($c_way!==""){
                                $query->where('c_way',$c_way);
                            }
                        })
                        ->where(function($query)use($c_type1){
                            if($c_type1!==""){
                                $query->where('c_type1',$c_type1);
                            }
                        })
                        ->whereIn('c_id',$pre)
                        ->field('c_id,c_username,c_tel,c_tele,c_company,c_intentionality,c_registration,c_way,c_status,c_type,c_type1,bh_time,w_status,w_pay,d_status,z_status,c_rank,createtime')
                        ->paginate($lisRows,false,['query' => request()->param()]);
                    $result['sum']=count($result);
                    $result['page'] = $result->render();


            }else if($time||$c_way||$c_type1||$c_intentionality||$createtime){
                $bid=Session::get('user.branch_id');
               if (!empty($c_intentionality)){
                   $where['c_intentionality']=$c_intentionality;
               }
               if(!empty($createtime)){
                   $where['createtime']=strtotime($createtime);
               }
                $user=Db::name('user')
                    ->where('branch_id',$bid)
                    ->column('job_num');
                $pre=Db::name('newcustomer_status')
                    ->whereIn('job_num',$user)
                    ->column('c_id');
                $result=Db::name('newcustomer')
                    ->where('c_getcoom',1)
                    ->where($where)
                    ->where(function($query)use($time){
                        if ($time!=""){
                            $startime=strtotime("$time"."00:00:00");
                            $endtime=strtotime("$time"."23:59:59");
                            $query->where('c_registration','between time',[$startime,$endtime]);
                        }
                    })
                    ->where(function($query)use($c_way){
                        if($c_way!=""){
                            $query->where('c_way',$c_way);
                        }
                    })
                    ->where(function($query)use($c_type1){
                        if($c_type1!=""){
                            $query->where('c_type1',$c_type1);
                        }
                    })
                    ->whereIn('c_id',$pre)
                    ->field('c_id,c_username,c_tel,c_tele,c_company,c_intentionality,c_registration,c_way,c_status,c_type,c_type1,bh_time,w_status,w_pay,d_status,z_status,c_rank,createtime')
                    ->paginate($lisRows,false,['query' => request()->param()]);
                $result['sum']=count($result);
                $result['page'] = $result->render();
            }else{
               $bid=Session::get('user.branch_id');
               $user=Db::name('user')
                   ->where('branch_id',$bid)
                   ->column('job_num');
               $pre=Db::name('newcustomer_status')
                   ->whereIn('job_num',$user)
                   ->column('c_id');
               $result=Db::name('newcustomer')
                   ->where('c_getcoom',1)
                   ->whereIn('c_id',$pre)
                   ->field('c_id,c_username,c_tel,c_tele,c_company,c_intentionality,c_registration,c_way,c_status,c_type,c_type1,bh_time,w_status,w_pay,d_status,z_status,c_rank,createtime')
                   ->paginate($lisRows,false,['query' => request()->param()]);
               $result['sum']=count($result);
               $result['page'] = $result->render();

           }
        }else{
            $job_num=Session::get('user.job_num');
            //查询登录人的新签客户信息
            $pre=Db::name('newcustomer_status')
                ->where('job_num',$job_num)
                ->column('c_id');
            $result=Db::name('newcustomer')
                ->where('c_getcoom',1)
                ->whereIn('c_id',$pre)
                ->field('c_id,c_username,c_tel,c_tele,c_company,c_intentionality,c_registration,c_way,c_status,c_way,c_type,c_type1,bh_time,w_status,w_pay,d_status,z_status,c_rank,createtime')
                ->paginate($lisRows,false,['query' => request()->param()]);
            $result['sum']=count($result);
            $result['page'] = $result->render();
        }
        return $result;
    }
    //诚信通签单个人数据
    public function  getone($id){
        $result=Db::name('newcustomer')->where('c_status',0)->where('c_id',$id)->field('c_id,c_username,c_tel,c_company,c_position,c_registration,c_address')->find();
        $result['c_registration']=date("Y-m-d",$result['c_registration']);
        if ($result['c_position']==1){
            $result['c_position']="总经理";
        }elseif ($result['c_position']==2){
            $result['c_position']="KP";
        }else{
            $result['c_position']="电商负责人";
        }
        return $result;
    }

    public function getones($id){
        $result=Db::name('newcustomer')->where('c_status','>=',1)->where('c_id',$id)->field('c_id,c_username,c_tel,c_company,c_position,c_registration,c_address')->find();
        $result['c_registration']=date("Y-m-d",$result['c_registration']);
        if ($result['c_position']==1){
            $result['c_position']="总经理";
        }elseif ($result['c_position']==2){
            $result['c_position']="KP";
        }else{
            $result['c_position']="电商负责人";
        }
        return $result;
    }
    //查询修改新签客户
    public  function uplists($c_id){
        $res=Db::name('newcustomer')->where('c_id',$c_id)->find();
        $res['c_registration']=date("Y-m-d",$res['c_registration']);
        return $res;
    }
    //执行修改新签客户
    public function upstat($data){
        $c_id=$data['c_id'];
        unset($data['c_id']);
        $data['c_registration']=strtotime($data['c_registration']);
        $res=Db::name('newcustomer')->where('c_id',$c_id)->update($data);
        return $res;
    }

    //搜索分页
    public  function searchda($search){
        $lisRows=10;
        $result=Db::name('newcustomer')
            ->where('c_company|c_tel','like',"%".$search."%")
            ->where('c_getcoom',0)
            ->field('c_id,c_username,c_tel,c_company,c_intentionality,c_registration,c_way')
            ->paginate($lisRows,false,['query' => request()->param()]);
        $result['sum']=count($result);
        $result['page'] = $result->render();
        return $result;


    }
    //添加新签订单
    public function neworder($data)
    {

        $data['endtime'] = date("Y-m-d 00:00:00",strtotime("+1 year",strtotime($data['totime'])));
        $data['endtime']=strtotime($data['endtime']);
        $data['totime']=strtotime($data['totime']);
        $data['regtime']=strtotime($data['regtime']);
        $data['status']=1;
        $data['uid']=Session::get('user.id');
        $data['bid']=Session::get('user.branch_id');
        $cid=$data['cid'];
        $aliname['c_aliname']=$data['c_aliname'];
        $aliname['c_status']=1;
        $aliname['w_pay']=$data['totime'];
        $aliname['w_5pay']=$data['totime']+5*24*3600;
        $aliname['w_status']=1;
        unset($data['c_aliname']);
        Db::name('newcustomer')->where('c_id',$cid)->update($aliname);
        $res=Db::name('neworderform')->insert($data);
        return $res;

    }
    //网销宝添加订单
    public function wneworder($data){
        $data['totime']=strtotime($data['totime']);
        $data['regtime']=strtotime($data['regtime']);
        $data['ortype']=2;
        $data['wopen']=1;
        $data['uid']=Session::get('user.id');
        $data['bid']=Session::get('user.branch_id');
        $cid=$data['cid'];
        $aliname['c_aliname']=$data['c_aliname'];
        $aliname['w_status']=2;
        unset($data['c_aliname']);
        Db::name('newcustomer')->where('c_id',$cid)->update($aliname);
        $res=Db::name('neworderform')->insert($data);
        return $res;
    }

    /*
     * 建站服务包订单添加
     *
     */
    public function zorder($data){
        $data['totime']=strtotime($data['totime']);
        $data['regtime']=strtotime($data['regtime']);
        $data['ortype']=3;
        $data['uid']=Session::get('user.id');
        $data['bid']=Session::get('user.branch_id');
        $data['zopen']=1;
        $cid=$data['cid'];
        $up['z_status']=1;
        Db::name('newcustomer')->where('c_id',$cid)->update($up);
        $res=Db::name('neworderform')->insert($data);
        return $res;
    }
    /*
    * 代运营订单添加
    *
    */
    public function dorder($data){
        $data['totime']=strtotime($data['totime']);
        $data['regtime']=strtotime($data['regtime']);
        $data['ortype']=4;
        $data['uid']=Session::get('user.id');
        $data['bid']=Session::get('user.branch_id');
        $data['dopen']=1;
        $cid=$data['cid'];
        $up['d_status']=1;
        Db::name('newcustomer')->where('c_id',$cid)->update($up);
        $res=Db::name('neworderform')->insert($data);
        return $res;
    }
    public function  usernames($uid){
        $username=Db::name('user')
            ->where('id',$uid)
            ->field('username')
            ->find();
        return $username;
    }
    //新签财务订单列表
    public function neworderlist($bid,$uid,$status,$start,$end,$csearch){
        $where=array();
        $userid=Session::get('user.branch_id');
        if (isset($userid)){
            $where['bid']=$userid;
        }
        $start=strtotime($start);
        $end=strtotime($end);
        if ($bid&&$userid===null){
            $where['bid']=$bid;
        }
        if ($uid){
            $where['uid']=$uid;
        }
        if($status){
            $where['status']=$status;
        }
        if ($start&&$end){
            $where['totime']=['between time',[$start,$end]];
        }elseif($start){
            $where['totime']=['<= time',$start];
        }elseif ($end){
            $where['totime']=['<= time',$end];
        }
        $data=Db::name('neworderform')
            ->where($where)
            ->where('aliordernum|company','like','%'.$csearch.'%')
            ->where('status>=2 OR wopen>=2 OR zopen>=2 OR dopen>=2')
            ->paginate(30,false,['query' => request()->param()]);
        return $data;
    }
    public function exec($bid,$uid,$status,$start,$end){
        $where=array();
        $userid=Session::get('user.branch_id');
        if (isset($userid)){
            $where['bid']=$userid;
        }
        $start=strtotime($start);
        $end=strtotime($end);
        if ($bid&&$userid===null){
            $where['bid']=$bid;
        }
        if ($uid){
            $where['uid']=$uid;
        }
        if($status){
            $where['ne.status']=$status;
        }
        if ($start&&$end){
            $where['totime']=['between time',[$start,$end]];
        }elseif($start){
            $where['totime']=['<= time',$start];
        }elseif ($end){
            $where['totime']=['<= time',$end];
        }
        $header=array("订单号","阿里订单号","公司名称","订单金额","签单姓名","分公司名称","到单时间","到期时间","订单类型");
        $data=Db::name('neworderform')
            ->alias('ne')
            ->join('branch_office br','ne.bid=br.id')
            ->join('user us','ne.uid=us.id')
            ->field('ne.ordernum,ne.aliordernum,ne.company,ne.money,us.username,br.name,ne.totime,ne.endtime,ne.ortype')
            ->where($where)
            ->select();
        foreach ($data as $k => $v) {
                if (isset($data[$k]['totime'])) {
                    $data[$k]['totime'] = date('Y-m-d', $v['totime']);
                }
                if (isset($data[$k]['endtime'])) {
                    $data[$k]['endtime'] = date('Y-m-d', $v['endtime']);
                }
                if (isset($data[$k]['status'])) {
                    if ($data[$k]['status'] == 2) {
                        $data[$k]['status'] = "主管开通";
                    } elseif ($data[$k]['status'] == 3) {
                        $data[$k]['status'] = "通过";
                    }
                }
                if (isset($data[$k]['ortype'])){
                    if ($data[$k]['ortype']==1){
                        $data[$k]['ortype']="诚信通";
                    }elseif ($data[$k]['ortype']==2){
                        $data[$k]['ortype']="网销宝";
                    }elseif ($data[$k]['ortype']==3){
                        $data[$k]['ortype']="建站服务宝";
                    }elseif ($data[$k]['ortype']==4){
                        $data[$k]['ortype']="代运营";
                    }
                }
            }
            // print_r($data);
            // exit();
        $name='新签订单';
        $this->excelExport($name,$header,$data);
    }
    //新签订单首页分公司
    public function branch(){
        $userid=Session::get('user.branch_id');
        if (isset($userid)){
            $branch=Db::name('branch_office')
                ->where('id',$userid)
                ->select();
        }else{
            $branch=Db::name('branch_office')
                ->select();
        }


        return $branch;
    }
    public function userLists($id)
    {
        $de=Session::get('user.department');
        $res=Db::name('user')
            ->field('id,username')
            ->where('branch_id',$id)
            ->where('department',$de)
            ->select();
        return $res;
    }
    public function open($bid,$uid,$ortype,$start,$end,$search){
        $where=array();
        $userid=Session::get('user.branch_id');
        if (isset($userid)){
            $where['bid']=$userid;
        }
        $start=strtotime($start);
        $end=strtotime($end);
        if ($bid&&$userid===null){
            $where['bid']=$bid;
        }
        if ($uid){
            $where['uid']=$uid;
        }
        if($ortype){
            $where['ne.ortype']=$ortype;
        }
        if ($start&&$end){
            $where['totime']=['between time',[$start,$end]];
        }elseif($start){
            $where['totime']=['<= time',$start];
        }elseif ($end){
            $where['totime']=['<= time',$end];
        }
        $data=Db::name('neworderform')
            ->alias('ne')
            ->join('user us','ne.uid=us.id')
            ->where($where)
            ->where(function($query)use($search){
                if ($search!=""){
                    $query->where('ne.aliordernum|us.username',$search);
                }
        })
            ->field('ne.id,ne.ordernum,ne.aliordernum,ne.company,ne.money,us.username,ne.bid,ne.totime,ne.status,ne.ortype,ne.wopen,ne.zopen,ne.dopen')
            ->order(['ne.ortype'=>'asc','ne.totime'=>'desc'])
            ->paginate(100,false,['query' => request()->param()]);
        $data['sum']=count($data);
        return $data;
    }
    //新签订单页面
    public function opened($id)
    {
        $result=Db::name('neworderform')->where('id',$id)->find();
        return $result;
    }
    //去开通新签订单
    public function toopen($data)
    {
        $id=$data['id'];
        //开通时间
        $update['paytime']=strtotime($data['paytime']);
        //到期时间

        //开通状态
        $update['status']=2;
        //$up['w_status']=1;
        //$up['w_pay']=$update['paytime'];
        //$up['w_5pay']=$update['paytime']+5*24*3600;
        $up['c_status']=2;
        $c_id=Db::name("neworderform")->where('id',$id)->value('cid');
        Db::name('newcustomer')->where('c_id',$c_id)->update($up);
        $res=Db::name("neworderform")->where('id',$id)->update($update);
        if($res){
            return true;
        }else{
            return false;
        }

    }
    /*
     * 总监开通网销宝
     */
    public function wtoopen($list){
        $id=$list['id'];
        //开通时间
        //到期时间
        $en = date("Y-m-d 00:00:00",strtotime("+1 year",strtotime($list['paytime'])));
        //开通状态
        //订单表
          $update['paytime']=strtotime($list['paytime']);
          $update['endtime']=strtotime($en);
          $update['wopen']=2;
          //$update['status']=2;
          $up['w_status']=3;
          $c_id=Db::name("neworderform")->where('id',$id)->value('cid');
          $result=Db::name("neworderform")->where('id',$id)->update($update);
          if ($result){
              $res=Db::name('newcustomer')->where('c_id',$c_id)->update($up);
              if ($res){
                  return true;
              }else{
                  return false;
              }
          }else{
              return false;
          }
    }
    /*
     *总监开通建站服务包
     */
    public function ztoopen($id,$endtime){
        $en = date("Y-m-d 00:00:00",strtotime("+1 year",strtotime($endtime)));
        $update['paytime']=strtotime($endtime);
        $update['endtime']=strtotime($en);
        $update['zopen']=2;
        $up['z_status']=2;
        $c_id=Db::name("neworderform")->where('id',$id)->value('cid');
        $result=Db::name("neworderform")->where('id',$id)->update($update);
        if ($result){
            $res=Db::name('newcustomer')->where('c_id',$c_id)->update($up);
            if ($res){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    /*
    *总监开通代运营
    */
    public function dtoopen($id,$endtime){
        $en = date("Y-m-d 00:00:00",strtotime("+1 year",strtotime($endtime)));
        $update['paytime']=strtotime($endtime);
        $update['endtime']=strtotime($en);
        $update['dopen']=2;
        $up['d_status']=2;
        $c_id=Db::name("neworderform")->where('id',$id)->value('cid');
        $result=Db::name("neworderform")->where('id',$id)->update($update);
        if ($result){
            $res=Db::name('newcustomer')->where('c_id',$c_id)->update($up);
            if ($res){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }


    /*
     * 总监确认新签订单列表查看详情
     */
    public  function  openedup($id){
         $res=Db::name('neworderform')
             ->where('id',$id)
             ->find();
         return $res;
    }
    /*
     * 执行修改
     */
    public function openedup_do($data){
        $id=$data['id'];
        $data['totime']=strtotime($data['totime']);
        unset($data['id']);
        $res=Db::name('neworderform')
                ->where('id',$id)
                ->update($data);
        return $res;
    }

    //重新申请开通
    public function openstatus($id)
    {
        $update['status']=2;
        $res=Db::name('neworderform')->where('id',$id)->update($update);
        return $res ? true : false;
    }




    /*
     * 财务不通过执行重新开通查看详情
     */
    public function againup($id){
        $res=Db::name('neworderform')
            ->where('id',$id)
            ->find();
        return $res;
    }

    /*
     * 财务不通过执行重新开通查看详情执行修改
     */

    public  function againup_do($data){
        $id=$data['id'];
        $data['totime']=strtotime($data['totime']);
        $date = date("Y-m-d 00:00:00",strtotime("+1 year",strtotime($data['totime'])));
        $data['endtime']=strtotime($date);
        $cid=Db::name('neworderform')->where('id',$id)->field('cid')->find();
        if ($data['ortype']=="网销宝"){
            unset($data['ortype']);
            unset($data['id']);
            $data['wopen']=2;
            $res=Db::name('neworderform')
                ->where('id',$id)
                ->update($data);
            if ($res){
                $up['w_status']=3;
                $result=Db::name('newcustomer')->where('c_id',$cid['cid'])->update($up);
                if ($result){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }elseif ($data['ortype']=="建站服务包"){
            unset($data['ortype']);
            unset($data['id']);
            $data['zopen']=2;
            $res=Db::name('neworderform')
                ->where('id',$id)
                ->update($data);
            if ($res){
                $up['z_status']=2;
                $result=Db::name('newcustomer')->where('c_id',$cid['cid'])->update($up);
                if ($result){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }elseif ($data['ortype']=="代运营"){
            unset($data['ortype']);
            unset($data['id']);
            $data['dopen']=2;
            $res=Db::name('neworderform')
                ->where('id',$id)
                ->update($data);
            if ($res){
                $up['d_status']=2;
                $result=Db::name('newcustomer')->where('c_id',$cid['cid'])->update($up);
                if ($result){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }

    }







        public  function excelExport($fileName = '', $headArr = [], $data = []) {
            $fileName .= "_" . date("Y_m_d", time()) . ".xls";
            vendor("PHPExcel.PHPExcel");
            $objPHPExcel = new \PHPExcel();

            $objPHPExcel->getProperties();
            $key = ord("A"); // 设置表头
            foreach ($headArr as $v) {
                $colum = chr($key);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum . '1', $v);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum . '1', $v);
                $key += 1;
            }
            $column = 2;
            $objActSheet = $objPHPExcel->getActiveSheet();
            foreach ($data as $key => $rows) { // 行写入
                $span = ord("A");
                foreach ($rows as $keyName => $value) { // 列写入
                    $objActSheet->setCellValue(chr($span) . $column, $value);
                    $span++;
                }
                $column++;
            }

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
            
            $fileName = iconv("utf-8", "gb2312", $fileName); // 重命名表
            $objPHPExcel->setActiveSheetIndex(0); // 设置活动单指数到第一个表,所以Excel打开这是第一个表
            header('Content-Type: application/vnd.ms-excel');
            header("Content-Disposition: attachment;filename='$fileName'");
            header('Cache-Control: max-age=0');
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output'); // 文件通过浏览器下载
            exit;
        }

}
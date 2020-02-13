<?php
namespace app\index\controller;
use app\index\model\Customer;
use think\controller;
use think\Request;
use think\Session;
use think\db;
use app\index\model\Newcustomer;
use think\Paginator;


class Newcustomers extends Common
{

    public $result=[];
    //公海客户列表
    public function coom(){
        $res=new Newcustomer();
        $result=$res->getlists();
        $page=$result['page'];
        $sum=$result['sum'];
        unset($result['page']);
        unset($result['sum']);
        $time=time();
        $search="";
        return $this->fetch('',[
            'data'=>$result,
            'time'=>$time,
            'page'=>$page,
            'search'=>$search,
            'sum'=>$sum
        ]);
    }
    //加入保护
    public function getcoom(){
        $data['c_id']=Request::instance()->param('c_id');
        $data['job_num']=Session::get('user.job_num');
        $res=new Newcustomer();
        $result=$res->getadd($data);
        if ($result==1){
            $arr = array('code' =>200,'msg'=>'加入保护成功！');
        }else{
            $arr = array('code' =>404,'msg'=>'加入保护失败！');
        }
        return returnmsg($arr);
    }
    //放弃保护
    public function deletecoom(){
        $c_id=Request::instance()->param('c_id');
        $res=new Newcustomer();
        $result=$res->deleteadd($c_id);
        if ($result==1){
            $arr = array('code' =>200,'msg'=>'放弃保护成功！');
        }else{
            $arr = array('code' =>404,'msg'=>'放弃保护失败！');
        }
        return returnmsg($arr);
    }
    //添加新签客户
    public function add(){
        return $this->fetch();
    }
    //执行添加新签客户信息
    public function add_do(){
        $request=Request::instance();
        $data=$request->param();
        $res=new Newcustomer();
        $result=$res->add($data);
        if($result==1){
            $arr = array('code' =>200,'msg'=>'添加成功！');
        }else if($result==4){
            $arr = array('code' =>404,'msg'=>'数据已存在！');
        }else{
            $arr = array('code' =>404,'msg'=>'添加失败！');
        }
        return returnmsg($arr);
    }
    //新签客户列表
    public function lists(){
         if(Request::instance()->param('usernames')){
            $userid=Request::instance()->param('usernames');
        }else{
            $userid='';
        }
         $c_type1=Request::instance()->param('c_type1');
        $date=Request::instance()->param('date');
        $c_way=Request::instance()->param('c_way');
        $createtime=Request::instance()->param('createtime');
        $c_intentionality=Request::instance()->param('c_intentionality');
        $res=new Newcustomer();
        $result=$res->lists($userid,$date,$c_way,$c_type1,$c_intentionality,$createtime);
        $page=$result['page'];
        $sum=$result['sum'];
        unset($result['page']);
        unset($result['sum']);
        $time=time();
        $users=branch_user(Session::get('user.branch_id'));
        return $this->fetch('',['data'=>$result,
            'time'=>$time,
            'page'=>$page,
            'user'=>$users,
            'userid'=>$userid,
            'time1'=>$date,
            'c_way'=>$c_way,
            'c_type1'=>$c_type1,
            'createtime'=>$createtime,
            'c_intentionality'=>$c_intentionality,
            'sum'=>$sum
        ]);
    }
    //修改新签客户
    public function upstatus(){
        $c_id=Request::instance()->param('c_id');
        $res=new Newcustomer();
        $result=$res->uplists($c_id);
       return $this->fetch('',['data'=>$result]);
    }
    //执行修改新签客户
    public  function upstatus_do(){
        $data=Request::instance()->param();
        $res=new Newcustomer();
        $result=$res->upstat($data);
        $arr = array('code' =>200,'msg'=>'修改成功！');
        return returnmsg($arr);
    }
    //加载签单界面
    public  function getstatus(){
        $id=Request::instance()->param('c_id');
        $ob=new Newcustomer();
        $data=$ob->getone($id);
        $obj= new \app\index\controller\Customers;
        //订单号
        $res['createOrderNum']=$obj->createOrderNum();
        //到单时间为当前时间
        $res['date']=date("Y-m-d",time());
        //到期时间为当前时间延后一年
        $res['logdate']=date('Y-m-d', strtotime("+1 year"));
        return $this->fetch('getstatus',['res'=>$res,'data'=>$data]);
    }

    //网销宝签单页面
    public function wlists(){
        $id=Request::instance()->param('c_id');
        $ob=new Newcustomer();
        $data=$ob->getones($id);
        $obj= new \app\index\controller\Customers;
        //订单号
        $res['createOrderNum']=$obj->createOrderNum();
        //到单时间为当前时间
        $res['date']=date("Y-m-d",time());
        //到期时间为当前时间延后一年
        $res['logdate']=date('Y-m-d', strtotime("+1 year"));
        return $this->fetch('wlists',['res'=>$res,'data'=>$data]);
    }
    /*
     * 建站服务包签单页面
     */
    public function zlists(){
        $id=Request::instance()->param('c_id');
        $ob=new Newcustomer();
        $data=$ob->getones($id);
        $obj= new \app\index\controller\Customers;
        //订单号
        $res['createOrderNum']=$obj->createOrderNum();
        //到单时间为当前时间
        $res['date']=date("Y-m-d",time());
        //到期时间为当前时间延后一年
        $res['logdate']=date('Y-m-d', strtotime("+1 year"));
        return $this->fetch('zlists',['res'=>$res,'data'=>$data]);
    }
    /*
    *  代运营签单页面
    */
    public function dlists(){
        $id=Request::instance()->param('c_id');
        $ob=new Newcustomer();
        $data=$ob->getones($id);
        $obj= new \app\index\controller\Customers;
        //订单号
        $res['createOrderNum']=$obj->createOrderNum();
        //到单时间为当前时间
        $res['date']=date("Y-m-d",time());
        //到期时间为当前时间延后一年
        $res['logdate']=date('Y-m-d', strtotime("+1 year"));
        return $this->fetch('dlists',['res'=>$res,'data'=>$data]);
    }


    //公海客户列表搜索查询
    public  function searchdata(){
        if (!$search=""){
            $search=Request::instance()->param('search');
        }else{
            $search="";
        }
        $ob=new Newcustomer();
        $data=$ob->searchda($search);
        $time=time();
        $page=$data['page'];
        $sum=$data['sum'];
        unset($data['page']);
        unset($data['sum']);
        return $this->fetch('coom',[
            'data'=>$data,
            'time'=>$time,
            'page'=>$page,
            'search'=>$search,
            'sum'=>$sum
        ]);
    }
    //添加诚信通新签订单
    public function neworder()
    {
        $data=Request::instance()->param();
        $ob=new Newcustomer();
        $res=$ob->neworder($data);
        if($res){
            $arr = array('code' =>200,'msg'=>'提交成功！');
        }else{
            $arr = array('code' =>404,'msg'=>'数据有误');
        }
        return returnmsg($arr); 
    }

    //网销宝订单添加
    public function wneworder(){
        $data=Request::instance()->param();
        $ob=new Newcustomer();
        $res=$ob->wneworder($data);
        if($res){
            $arr = array('code' =>200,'msg'=>'提交成功！');
        }else{
            $arr = array('code' =>404,'msg'=>'数据有误');
        }
        return returnmsg($arr);
    }
    /*
     * 建站服务包订单添加
     */
    public  function  zorder(){
        $data=Request::instance()->param();
        $ob=new Newcustomer();
        $res=$ob->zorder($data);
        if($res){
            $arr = array('code' =>200,'msg'=>'提交成功！');
        }else{
            $arr = array('code' =>404,'msg'=>'数据有误');
        }
        return returnmsg($arr);
    }
    /*
 * 代运营订单添加
 */
    public  function  dorder(){
        $data=Request::instance()->param();
        $ob=new Newcustomer();
        $res=$ob->dorder($data);
        if($res){
            $arr = array('code' =>200,'msg'=>'提交成功！');
        }else{
            $arr = array('code' =>404,'msg'=>'数据有误');
        }
        return returnmsg($arr);
    }
    //总监新签订单开通
    public function open()
    {
         $search=Request::instance()->param('search');
         $bid=Request::instance()->param('bid');
         $uid=Request::instance()->param('uid');
         $ortype=Request::instance()->param('ortype');
         $start=Request::instance()->param('start');
         $end=Request::instance()->param('end');
         $ob=new Newcustomer();
         $res=$ob->open($bid,$uid,$ortype,$start,$end,$search);
         $sum=$res['sum'];
         unset($res['sum']);
         $branch=$ob->branch();
         $username=$ob->usernames($uid);
         $username=$username['username'];
         return $this->fetch("",[
             'username'=>$username,
             'ortype'=>$ortype,
             'start'=>$start,
             'end'=>$end,
             'uid'=>$uid,
             'list'=>$res,
             'search'=>$search,
             'sum'=>$sum,
             'branch'=>$branch,
             'bid'=>$bid,

         ]);
    }
    //开通新签订单页面
    public function opened()
    {
        $id=Request::instance()->param('oid');
        $ob=new Newcustomer();
        $res=$ob->opened($id);
        return $this->fetch("",['list'=>$res]);
    }

    //开通网销宝
    public function wopened(){
        $id=Request::instance()->param('oid');
        $ob=new Newcustomer();
        $res=$ob->opened($id);
        return $this->fetch("",['list'=>$res]);
    }
    public function userLists()
    {
        $request=Request::instance();
        $fid=$request->post('fid');
        $sta=new Newcustomer();
        $res=$sta->userLists($fid);
        if($res){
            $arr = array('code' =>200,'data'=>$res);
        }else{
            $arr = array('code' =>404,'msg'=>'修改失败！');
        }

        return returnmsg($arr);
    }
    //去开通
    public function toopen()
    {
        $data=Request::instance()->param();
        $open['id']=$data['id'];
        $open['paytime']=$data['paytime'];
        $ob=new Newcustomer();
        $res=$ob->toopen($open);
        if($res){
            $arr = array('code' =>200,'msg'=>'开通成功！');
        }else{
            $arr = array('code' =>404,'msg'=>'数据有误');
        }
        return returnmsg($arr); 
    }
    public function wtoopen(){
        $ob=new Newcustomer();
        $data=Request::instance()->param();
        if ($data['ortype']==3){
            $res=$ob->ztoopen($data['id'],$data['paytime']);
            if($res){
                $arr = array('code' =>200,'msg'=>'开通成功！');
            }else{
                $arr = array('code' =>404,'msg'=>'数据有误');
            }
        }elseif ($data['ortype']==4){
            $res=$ob->dtoopen($data['id'],$data['paytime']);
            if($res){
                $arr = array('code' =>200,'msg'=>'开通成功！');
            }else{
                $arr = array('code' =>404,'msg'=>'数据有误');
            }
        }else{
            $open['id']=$data['id'];
            $open['paytime']=$data['paytime'];
            $res=$ob->wtoopen($open);
            if($res){
                $arr = array('code' =>200,'msg'=>'开通成功！');
            }else{
                $arr = array('code' =>404,'msg'=>'数据有误');
            }
        }
        return returnmsg($arr);
    }

    //重新申请开通
    public function openstatus()
    {
        $da=Request::instance()->param("id");
        $ob=new Newcustomer();
        $res=$ob->openstatus($da);
        if($res){
            $arr = array('code' =>200,'msg'=>'重新开通成功');
        }else{
            $arr = array('code' =>404,'msg'=>'数据有误');
        }
        return returnmsg($arr); 
    }

    public function searchs(){
        $bid=Request::instance()->param('bid');
        $uid=Request::instance()->param('uid');
        $status=Request::instance()->param('status');
        $start=Request::instance()->param('start');
        $end=Request::instance()->param('end');
        $ob=new Newcustomer();
        $res=$ob->searchs($bid,$uid,$status,$start,$end);
        $branch=$ob->branch();
        return $this->fetch('',[
            'list'=>$res,
            'branch'=>$branch,
            'bid'=>$bid,
            'uid'=>$uid,
            'status'=>$status,
            'start'=>$start,
            'end'=>$end,
        ]);

    }
    /*
     * 总监确认新签订单列表查看详情
     */
    public function openedup(){
        $id=input('oid');
        $obj=new Newcustomer();
        $data=$obj->openedup($id);
        $this->assign('data',$data);
        return $this->fetch('');
    }

    /*
     * 执行修改
     */
    public function openedup_do(){
        $data=Request::instance()->post();
        $obj=new Newcustomer();
        $res=$obj->openedup_do($data);
        if($res){
            $arr = array('code' =>200,'msg'=>'修改成功！');
        }else{
            $arr = array('code' =>200,'msg'=>'修改成功！');
        }
        return returnmsg($arr);
    }

    /*
     * 财务拒绝重新开通查看详情
     */
    public function againup(){
        $data=Request::instance()->get();
        $id=$data['oid'];
        $obj=new Newcustomer();
        $res=$obj->againup($id);
        return $this->fetch('',['data'=>$res]);
    }

    /*
   * 财务拒绝重新开通执行修改并重新通过
   */
    public function  againup_do(){
        $data=Request::instance()->post();
        $obj=new Newcustomer();
        $res=$obj->againup_do($data);
        if($res){
            $arr = array('code' =>200,'msg'=>'重新开通成功');
        }else{
            $arr = array('code' =>202,'msg'=>'重新开通失败');
        }
        return returnmsg($arr);
    }


}
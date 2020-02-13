<?php
namespace app\index\controller;
use app\index\model\Customer;
use think\Controller;
use app\index\model\Finances;
use app\index\model\Newcustomer;
use think\Db;
use think\Request;
use think\Session;
use think\Loader;
class   Finance extends Common {
    //1-2单底薪
    public $base=2000;
    //3-6单
    public  $base1=2500;
    //7-8单
    public  $base2=2800;
    public function index(){
        $bid=Request::instance()->param('bid');
        $uid=Request::instance()->param('uid');
        $status=Request::instance()->param('status');
        $start=Request::instance()->param('start');
        $end=Request::instance()->param('end');
        $csearch=Request::instance()->param('csearch');
        $newcustomer=new Newcustomer();
        $list=$newcustomer->neworderlist($bid,$uid,$status,$start,$end,$csearch);
        $username=$newcustomer->usernames($uid);
        $username=$username['username'];
        $branch=$newcustomer->branch();
        return $this->fetch('',[
            'list'=>$list,
            'branch'=>$branch,
            'bid'=>$bid,
            'uid'=>$uid,
            'status'=>$status,
            'start'=>$start,
            'end'=>$end,
            'csearch'=>$csearch,
            'username'=>$username
        ]);
    }
    //导出
    public function exec(){
        $bid=Request::instance()->param('bid');
        $uid=Request::instance()->param('uid');
        $status=Request::instance()->param('status');
        $start=Request::instance()->param('start');
        $end=Request::instance()->param('end');
        $newcustomer=new Newcustomer();
        $res=$newcustomer->exec($bid,$uid,$status,$start,$end);
    }
    //续签页面
    public function renewal(){
        $bid=Request::instance()->param('bid');
        $username=Request::instance()->param('username');
        $status=Request::instance()->param('status');
        $start=Request::instance()->param('start');
        $end=Request::instance()->param('end');
        $newcustomer=new Newcustomer();
        $branch=$newcustomer->branch();
        $obj=new Finances();
        $datas=$obj->renewal($bid,$username,$status,$start,$end);
        $this->assign('branch',$branch);
        $this->assign('datas',$datas);
        if ($status==""){
            $status=4;
        }
        return $this->fetch('',[
            'bid'=>$bid,
            'username'=>$username,
            'status'=>$status,
            'start'=>$start,
            'end'=>$end,
        ]);
    }
    public function out(){
        $bid=Request::instance()->param('bid');
        $username=Request::instance()->param('username');
        $status=Request::instance()->param('status');
        $start=Request::instance()->param('start');
        $end=Request::instance()->param('end');
        $obj=new Finances();
        $res=$obj->out($bid,$username,$status,$start,$end);
    }

    public function edit()
    {
        $id=$_GET['id'];
        $customer=new Finances();
        $data=$customer->detail($id);
        $score=$customer->score();
        $p4p=$customer->p4p();
        $enum=$customer->e_num();
        $profit=$customer->profit();
        $turnover=$customer->turnover();
        $sincerity=$customer->sincerity();
        $industry=$customer->industry();
        $this->assign(['data'=>$data,'score'=>$score,'p4p'=>$p4p,'enum'=>$enum,'profit'=>$profit,'turnover'=>$turnover,'sincerity'=>$sincerity,'industry'=>$industry]);
        return $this->fetch();
    }
    //状态
    //通过
    public function changeStatus(){
        $id = input("post.id");
        $status = input("post.status");
        $did = input("post.did");
        $time = input("post.expire");
        if(empty($id)){
            sendmsg(false,"没有找到ID");
        }
        $nodeModel = new Finances();
        if($nodeModel->changeStatus($id,$did,$time)){
            sendmsg(true,"审核订单成功！");
        }else{
            sendmsg(false,"审核订单失败！");
        }
    }
    //不通过
    public function changeStatusr(){
        $id = input("post.id");
        $status = input("post.status");
        $did = input("post.did");
        if(empty($id)){
            sendmsg(false,"没有找到ID");
        }
        $nodeModel = new Finances();
        if($nodeModel->changeStatusr($id,$did)){
            sendmsg(true,"审核订单成功！");
        }else{
            sendmsg(false,"审核订单失败！");
        }
    }

    //新签订单通过
    public function changeStatus1()
    {
        $id = input("post.id");
        $type= input("post.type");
        $fin=new Finances();
        if ($type==1){
            $res=$fin->changeStatus1($id);
            if($res==1){
                $arr = array('code' =>200,'msg'=>'审核成功！');
            }elseif($res==2){
                $arr = array('code' =>201,'msg'=>'转移分公司库失败！');
            }elseif($res==3){
                $arr = array('code' =>202,'msg'=>'该订单已审核！');
            }
        }elseif ($type==2){
            $res=$fin->changew($id);
            if($res){
                $arr = array('code' =>200,'msg'=>'审核成功！');
            }else{
                $arr = array('code' =>202,'msg'=>'该订单已审核！');
            }
        }elseif ($type==3){
            $res=$fin->changez($id);
            if($res){
                $arr = array('code' =>200,'msg'=>'审核成功！');
            }else{
                $arr = array('code' =>202,'msg'=>'该订单已审核！');
            }
        }elseif ($type==4){
            $res=$fin->changed($id);
            if($res){
                $arr = array('code' =>200,'msg'=>'审核成功！');
            }else{
                $arr = array('code' =>202,'msg'=>'该订单已审核！');
            }
        }
        return returnmsg($arr);

    }
    //拒绝新签订单
    public function changeStatusr1()
    {
        $id = input("post.id");
        $type= input("post.type");
        $fin=new Finances();
        if ($type==1){
            $res=$fin->changeStatusr1($id);
            if($res==1){
                $arr = array('code' =>200,'msg'=>'操作成功！');
            }else{
                $arr = array('code' =>201,'msg'=>'操作失败！');
            }
        }elseif ($type==2){
            $res=$fin->nochangew($id);
            if($res==1){
                $arr = array('code' =>200,'msg'=>'操作成功！');
            }else{
                $arr = array('code' =>201,'msg'=>'操作失败！');
            }
        }elseif ($type==3){
            $res=$fin->nochangez($id);
            if($res==1){
                $arr = array('code' =>200,'msg'=>'操作成功！');
            }else{
                $arr = array('code' =>201,'msg'=>'操作失败！');
            }
        }elseif ($type==4){
            $res=$fin->nochanged($id);
            if($res==1){
                $arr = array('code' =>200,'msg'=>'操作成功！');
            }else{
                $arr = array('code' =>201,'msg'=>'操作失败！');
            }
        }


        return returnmsg($arr);
    }

    public function renewaluser(){
        $pram=input('post.corporate_name');
        $obj=new Finances();
        $newcustomer=new Newcustomer();
        $branch=$newcustomer->branch();
        $res=$obj->renewalsearch($pram);
        $this->assign('branch',$branch);
        $bid=Request::instance()->param('bid');
        $username=Request::instance()->param('username');
        $status=Request::instance()->param('status');
        $start=Request::instance()->param('start');
        $end=Request::instance()->param('end');
       return $this->fetch('renewal',[
           'datas'=>$res,
           'bid'=>$bid,
           'username'=>$username,
           'status'=>$status,
           'start'=>$start,
           'end'=>$end,
       ]);
    }

    /*
     * 业绩订单确认展示
     */
    public function  perorder(){
        $csearch=Request::instance()->get('csearch');
        $per_username=Request::instance()->get('per_username');
        $per_type=Request::instance()->get('per_type');
        $per_status=Request::instance()->get('per_status');
        $start=Request::instance()->get('start');
        $end=Request::instance()->get('end');
        $per_financial=Request::instance()->get('per_financial');
        $obj=new Finances();
        $list=$obj->perorder($csearch,$per_username,$per_type,$per_status,$start,$end,$per_financial);
        $username=$obj->branchs();
        return $this->fetch('',[
            'list'=>$list,
            'csearch'=>$csearch,
            'username'=>$username,
            'per_username'=>$per_username,
            'per_type'=>$per_type,
            'per_status'=>$per_status,
            'start'=>$start,
            'end'=>$end,
            'per_financial'=>$per_financial
        ]);
    }

    /*
      业绩订单确认导出
    */
    public  function outperorderexec(){
        $csearch=Request::instance()->get('csearch');
        $per_username=Request::instance()->get('per_username');
        $per_type=Request::instance()->get('per_type');
        $per_status=Request::instance()->get('per_status');
        $start=Request::instance()->get('start');
        $end=Request::instance()->get('end');
        $per_financial=Request::instance()->get('per_financial');
        $obj=new Finances();
        $list=$obj->outperorderexec($csearch,$per_username,$per_type,$per_status,$start,$end,$per_financial);

    }

    /*
     * 业绩通过
     */
    public function change(){
        $id=Request::instance()->post('id');
        $obj=new Finances();
        $res=$obj->change($id);
        if($res){
            $arr = array('code' =>200,'msg'=>'审核成功！');
        }else{
            $arr = array('code' =>201,'msg'=>'审核不成功！');
        }
        return returnmsg($arr);
    }

    /*
     * 业绩不通过no
     */
    public function nochange(){
        $id=Request::instance()->post('id');
        $obj=new Finances();
        $res=$obj->nochange($id);
        if($res){
            $arr = array('code' =>200,'msg'=>'拒绝成功！');
        }else{
            $arr = array('code' =>201,'msg'=>'拒绝失败！');
        }
        return returnmsg($arr);
    }

    /*
     * 业绩确认列表
     */
    public function  outorder()
    {
        $obj = new Finances();
        $csearch=Request::instance()->get('csearch');
        $type=Request::instance()->get('type');
        $start=Request::instance()->get('start');
        $ptype=Request::instance()->get('ptype');
        $list=$obj->outlist($csearch,$type,$start,$ptype);
        return $this->fetch('',[
            'list'=>$list,
            'csearch'=>$csearch,
            'type'=>$type,
            'start'=>$start,
            'ptype'=>$ptype
        ]);
    }
    /*
     * 财务业绩审核
     */
    public function outorderstatus(){
        $id=Request::instance()->post('id');
        $type=Request::instance()->post('type');
        $obj=new Finances();
        $res=$obj->outorderstatus($id,$type);
        if($res){
            $arr = array('code' =>200,'msg'=>'操作成功！');
        }else{
            $arr = array('code' =>201,'msg'=>'操作失败！');
        }
        return returnmsg($arr);
    }

    /*
     * 财务确认导出
     */
    public function outorderexec(){
        $csearch=Request::instance()->param('csearch');
        $type=Request::instance()->param('type');
        $start=Request::instance()->param('start');
        $ptype=Request::instance()->param('ptype');
        $obj = new Finances();
        $res=$obj->outorderexec($csearch,$type,$start,$ptype);
        if($res){
            $arr = array('code' =>200,'msg'=>'操作成功！');
        }else{
            $arr = array('code' =>201,'msg'=>'操作失败！');
        }
        return returnmsg($arr);
    }

    //工资导入
    public function wages(){
        return $this->fetch('');
    }
    public function upload_excel() {
        $insertsuccess=0;
        $updatesuccess=0;
        $noupdatesuccess=0;
        $request = \think\Request::instance();
        $file = $request->file('file');
        $save_path = ROOT_PATH . 'uploads/';
        $info = $file->move($save_path);
        $filename=$save_path . DIRECTORY_SEPARATOR . $info->getSaveName();
        if(file_exists($filename)) {//如果文件存在
            import('phpexcel.PHPExcel', EXTEND_PATH);
            if(strstr($filename,'.xlsx')){
                $PHPReader = new \PHPExcel_Reader_Excel2007();
            }else{
                $PHPReader = new \PHPExcel_Reader_Excel5();
            }
            //载入excel文件
            $PHPExcel = $PHPReader->load($filename);
            $PHPExcel->setActiveSheetIndex(0);
            $sheet = $PHPExcel->getActiveSheet(0);//获得sheet
            $highestRow = $sheet->getHighestRow(); // 取得共有数据数
            $data=$sheet->toArray();
            if(stripos($data[0][0],'石家庄')){
                $oid=1;
            }elseif(stripos($data[0][0],'邯郸')){
                $oid=2;
            }elseif(stripos($data[0][0],'衡水')){
                $oid=3;
            }elseif(stripos($data[0][0],'沧州')){
                $oid=4;
            }elseif(stripos($data[0][0],'廊坊')){
                $oid=5;
            }elseif(stripos($data[0][0],'保定')){
                $oid=6;
            }elseif(stripos($data[0][0],'白沟')){
                $oid=7;
            }elseif(stripos($data[0][0],'邢台')){
                $oid=8;
            }elseif(stripos($data[0][0],'西安')){
                $oid=9;
            }else{
                $oid=0;
            }
            for($i=2;$i<$highestRow-1;$i++){
                $in=Db::name('user')
                    ->where('username',$data[$i][3])
                    ->field('branch_id,department,rank')
                    ->find();
                //分公司
                $import['branch']=$oid;
                //部门
                $import['department']=$in['department'];
                //职位
                $import['rank']=$in['rank'];
                //姓名
                $import['username']=$data[$i][3];
                //基本工资
                $import['base_pay']=isset($data[$i][4])?$data[$i][4]:0;
                //提成
                $import['royalty']=isset($data[$i][5])?$data[$i][5]:0;
                //团队提成
                $import['team_promotion']=isset($data[$i][6])?$data[$i][6]:0;
                //奖金
                $import['bonus']=isset($data[$i][7])?$data[$i][7]:0;
                //补助
                $import['subsidy']=isset($data[$i][8])?$data[$i][8]:0;
                //加班工资
                $import['overtime_pay']=isset($data[$i][9])?$data[$i][9]:0;
                //考勤扣款
                $import['attendance_deduction']=isset($data[$i][10])?$data[$i][10]:0;
                //其他扣款
                $import['other_deductions']=isset($data[$i][11])?$data[$i][11]:0;
                //预发奖金
                $import['advance_bonus']=isset($data[$i][12])?$data[$i][12]:0;
                //应发工资
                $import['wages_payable']=isset($data[$i][13])?$data[$i][13]:0;
                //个人社保
                $import['personal_social_security']=isset($data[$i][14])?$data[$i][14]:0;
                //公积金
                $import['accumulation_fund']=isset($data[$i][15])?$data[$i][15]:0;
                //个税
                $import['personal_income_tax']=isset($data[$i][16])?$data[$i][16]:0;
                //实发工资
                $import['real_wages']=isset($data[$i][18])?$data[$i][18]:0;
                //备注
                $import['remarks']=isset($data[$i][19])?$data[$i][19]:0;
                $findone=Db::name('wages')
                    ->where('username',$import['username'])
                    ->where('branch',$import['branch'])
                    ->where('department',$import['department'])
                    ->where('rank',$import['rank'])
                    ->field('id')
                    ->find();
                if ($findone){
                    unset($import['username']);
                    unset($import['branch']);
                    unset($import['department']);
                    unset($import['rank']);
                    $up=Db::name('wages')
                        ->where('id',$findone['id'])
                        ->update($import);
                    if ($up){
                        $updatesuccess++;
                    }else{
                        $noupdatesuccess++;
                    }
                }else{
                    $res=Db::name('wages')
                        ->insert($import);
                    if ($res){
                        $insertsuccess++;
                    }else{
                        echo "失败";
                    }
                }
            }
            if($highestRow){
                $highestRow=$highestRow-3;
                $content['content']="总上传数据:".$highestRow."上传成功:".$insertsuccess."更新重复数据:".$updatesuccess;
                $u=Session::get('user.username');
                $ad=Session::get('admin.username');
                $content['username']=isset($u)?$u:$ad;
                $content['time']=time();
                Db::name('wages_log')
                    ->insert($content);
                $arr = array('code' =>200,'msg'=>"总上传数据:".$highestRow."上传成功:".$insertsuccess."更新重复数据:".$updatesuccess);
            }else{
                $arr = array('code' =>404,'msg'=>'上传数据为空');
            }
            return returnmsg($arr);
        }else{
            return array("resultcode" => -5, "resultmsg" => "文件不存在", "data" => null);
        }

    }

    //工资核算
    public function wagesto(){
        $branch=input('branch');
        $obj = new Finances();
        $data=$res=$obj->wagesto($branch);
        $brr=br();
        $this->assign('branch',$brr);
        $this->assign('br',$branch);
        $this->assign('data',$data);
        return $this->fetch();
    }
    //导出工资核算
    public function outwages(){
        $br=Request::instance()->get('branch');
        $obj=new Finances();
        $obj->outwages($br);
    }

}
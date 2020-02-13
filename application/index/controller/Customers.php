<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Customer;
use think\Request;
use think\Session;
use app\index\model\Finances;
use think\db;
/**
 *
 */
//客户类
class Customers extends Common
{
    //客户列表
    public function index()
    {
        $request=Request::instance();
        if($request->param('start')){
            $data['start']=strtotime($request->param('start'));
            $data['end']=strtotime($request->param('end'));
        }else{
            $data['start']='';
            $data['end']='';
        }
        if($request->param('corporate_name')){
            $data['corporate_name']=$request->param('corporate_name');
        }else{
            $data['corporate_name']='';
        }
        if($request->param('type')){
            $type=$request->param('type');
        }else{
            $type='';
        }
        if($request->param('usernames')){
            $userid=$request->param('usernames');
        }else{
            $userid='';
        }
        $user=Session::get("user");
        $admin=Session::get("admin");
        $customer=new Customer();
        $page=$customer->customer($data,$type,$userid);
        $users=$customer->branch_user(Session::get('user.branch_id'));
        $dtime=time();
        // var_dump(Session::get('user.id'));
        $this->assign('users',$users);
        $this->assign('dtime',$dtime);
        $this->assign('page',$page);
        $this->assign("user",$user);
        $this->assign("admin",$admin);
        // $this->assign("num",count($page));
        $this->assign("userid",$userid);
        return $this->fetch('list');
    }
    //查看客户详情并修改
    public function edit()
    {
        $id=$_GET['id'];
        $customer=new Customer();
        $data=$customer->detail($id);
        $score=$customer->score();
        $p4p=$customer->p4p();
        $enum=$customer->e_num();
        $profit=$customer->profit();
        $turnover=$customer->turnover();
        $sincerity=$customer->sincerity();
        $industry=$customer->industry();
        $this->assign(['data'=>$data,'score'=>$score,'p4p'=>$p4p,'enum'=>$enum,'profit'=>$profit,'turnover'=>$turnover,'sincerity'=>$sincerity,'industry'=>$industry]);
        return $this->fetch('edit_detail');
    }
//添加新客户
    public function insert()
    {
        $customer=new Customer();
        $score=$customer->score();
        $p4p=$customer->p4p();
        $enum=$customer->e_num();
        $profit=$customer->profit();
        $turnover=$customer->turnover();
        $sincerity=$customer->sincerity();
        $industry=$customer->industry();
        $this->assign(['score'=>$score,'p4p'=>$p4p,'enum'=>$enum,'profit'=>$profit,'turnover'=>$turnover,'sincerity'=>$sincerity,'industry'=>$industry]);
        return $this->fetch();
    }
//添加客户操作
    public function inserted()
    {
        $request=Request::instance();
        $data=$request->param();
        $customer=new Customer();
        $res=$customer->inserted($data);
        if($res){
            $arr = array('code' =>200,'msg'=>'添加成功！');
        }else{
            $arr = array('code' =>404,'msg'=>'添加失败！');
        }
        return returnmsg($arr);
    }
    // //执行修改客户信息
    // public function edit_customer()
    // {
    // 	$data=$_POST;
    // 	$customer=new Customer();
    // 	$res=$customer->edit_customer($data);
    // 	// var_dump($res);
    // 	// die;
    // 	if($res){
    // 		$arr = array('code' =>200,'msg'=>'修改成功！');
    // 	}else{
    // 		$arr = array('code' =>404,'msg'=>'修改失败！');
    // 	}

    // 	return returnmsg($arr);
    // }
    //执行修改客户信息
    public function edit_customer()
    {
        $data=$_POST;
        $request=Request::instance();
        $money =$request->post('money');
        $status=$request->post('status');
        if($money==""){
            $customer=new Customer();
            $res=$customer->edit_customer($data);
            if($res){
                $arr = array('code' =>200,'msg'=>'修改成功！');
            }else{
                $arr = array('code' =>404,'msg'=>'修改失败！');
            }
            return returnmsg($arr);
        }else{
            $customer=new Finances();
            $customers=new Customer();
            if($status==0){
                $res = $customers->add1($data);
                if($res){
                    $arr = array('code' =>200,'msg'=>'成功！');
                }else{
                    $arr = array('code' =>404,'msg'=>'失败！');
                }
            }else{
                $res = $customer->addNode($data);
                $res = $customers->add($data);
                if($res){
                    $arr = array('code' =>200,'msg'=>'成功！');
                }else{
                    $arr = array('code' =>404,'msg'=>'失败！');
                }
            }
            return returnmsg($arr);
        }
    }
    //续签员工网销宝到单展示
    public function wlists(){
        $id=Request::instance()->get('id');
        $obj=new Customer();
        $orderid=$this->createOrderNum();
        $data=$obj->wadd($id);
        $user=Session::get('user');
        $branch_id=Session::get('user.branch_id');
        $branch=$obj->bran($branch_id);
        $time=date("Y-m-d",time());
        return $this->fetch('',[
            'data'=>$data,
            'user'=>$user,
            'orderid'=>$orderid,
            'branch'=>$branch,
            'time'=>$time
        ]);
    }
    //续签员工网销宝到单入库
    public function wadd(){
        $pram=Request::instance()->post();
        $obj=new Customer();
        $data=$obj->wadd_do($pram);
        if($data){
            $arr = array('code' =>200,'msg'=>'成功');
        }else{
            $arr = array('code' =>404,'msg'=>'添加失败');
        }
        return returnmsg($arr);
    }

    //二级行业分类
    public function industry()
    {
        $id=$_POST['fid'];
        $customer=new Customer();
        $res=$customer->industry1($id);
        if($res){
            $arr = array('code' =>200,'data'=>$res);
        }else{
            $arr = array('code' =>404,'msg'=>'修改失败！');
        }

        return returnmsg($arr);
    }
    //添加用户
    public function add_customer()
    {
        $data=$_POST;
        $customer=new Customer();
        $res=$customer->add_customer($data);
        if($res){
            $arr = array('code' =>200,'msg'=>'新增客户成功');
        }else{
            $arr = array('code' =>404,'msg'=>'添加失败');
        }
        return returnmsg($arr);

    }

    //店铺用户列表
    public function cus_list()
    {
        $data=$_POST;
        $customer=new Customer();
        $res=$customer->cus_list($data['id']);

        if($res){
            $arr = array('code' =>200,'data'=>$res);
        }else{
            $arr = array('code' =>404,'msg'=>'查询失败');
        }
        return returnmsg($arr);
    }
    //分配客户到分公司页面
    public function distribution()
    {

        $customer=new Customer();
        $res=$customer->branch_office();
        if($res){
            $arr = array('code' =>200,'data'=>$res);
        }else{
            $arr = array('code' =>404,'msg'=>'查询失败');
        }
        return returnmsg($arr);
    }

    //分配客户到员工页面
    public function distribution1()
    {
        $bid=$_POST['bid'];
        $customer=new Customer();
        $res=$customer->branch_user($bid);
        if($res){
            $arr = array('code' =>200,'data'=>$res);
        }else{
            $arr = array('code' =>404,'msg'=>'查询失败');
        }
        return returnmsg($arr);
    }

    //分配
    public function fenpei()
    {
        $data=$_POST;
        $customer=new Customer();
        $res=$customer->fenpei($data);
        if($res){
            $arr = array('code' =>200,'msg'=>'分配成功！');
        }else{
            $arr = array('code' =>404,'msg'=>'分配失败！');
        }
        return returnmsg($arr);
    }
    //分配客户到员工信息
    public function fenda()
    {
        $data=$_POST;
        $customer=new Customer();
        $res=$customer->fenda($data);
        if($res){
            $arr = array('code' =>200,'msg'=>'分配成功！');
        }else{
            $arr = array('code' =>404,'msg'=>'分配失败！');
        }
        return returnmsg($arr);
    }
    //分配信息

    public function relation()
    {
        $id=$_POST['id'];
        $customer=new Customer();
        $res=$customer->relation($id);
        if($res){
            foreach ($res as $k => $v) {
                $res[$k]['addtime']=date('Y-m-d',$v['addtime']);
                $res[$k]['updatetime']=date('Y-m-d',$v['updatetime']);
            }
            $arr = array('code' =>200,'data'=>$res);
        }else{
            $arr = array('code' =>404,'msg'=>'查询失败');
        }
        return returnmsg($arr);
    }
    //员工客户关系
    public function relation1()
    {
        $id=$_GET['id'];
        $branch=$_GET['branch_id'];
        $customer=new Customer();
        $res=$customer->relation1($id);
        $userlist=$customer->branch_user($branch);
        if($res){
            foreach ($res as $k => $v) {
                $res[$k]['addtime']=date('Y-m-d',$v['addtime']);
                $res[$k]['updatetime']=date('Y-m-d',$v['updatetime']);
                if($res[$k]['status']==1){
                    $res[$k]['status']='属于';
                }elseif($res[$k]['status']==2){
                    $res[$k]['status']='已转移';
                }
                $cid=$v['cid'];
            }

        }
        return $this->fetch('',['data'=>$res,'userlist'=>$userlist,'cid'=>$cid]);
        //return returnmsg($arr);
    }
    //转移客户
    public function tranCustomer()
    {
        $user=$_POST;
        $cid=$_POST['cid'];
        $uid=$_POST['user'];
        $customer=new Customer();
        $res=$customer->tranCustomer($uid,$cid);
        if($res){
            $arr = array('code' =>200,'msg'=>'转移成功！');
        }else{
            $arr = array('code' =>404,'msg'=>'转移失败！');
        }
        return returnmsg($arr);
    }

    //续费页面
    public function renew(){
        $id=$_GET['id'];
        $user=Session::get("user");
        $admin=Session::get("admin");
        $customer=new Customer();
        //重新提交续签订单
        $res=$customer->renewOrder($id);
        if($res){
            $this->assign('data',$res);
            return $this->fetch('renew2');
        }else{
            $data=$customer->detail($id);
            $this->assign(['data'=>$data]);
            $orderNum = $this->createOrderNum();
            $this->assign("user",$user);
            $this->assign("admin",$admin);
            $this->assign('orderNum',$orderNum);
            return $this->fetch();
        }

    }
    /**
     * 生成订单号
     */
    public function createOrderNum(){
        $timestap = time();
        $arr = [0,1,2,3,4,5,6,7,8,9];
        shuffle($arr);
        $tenRandNum = implode("",$arr);
        return $timestap.$tenRandNum;
    }

    //导入客户信息

    public function import()
    {
        return $this->fetch();
    }
    //更新客户信息

    public function import1()
    {
        return $this->fetch();
    }

    //执行导入信息
    public function upload_excel() {
        $request = \think\Request::instance();
        $file = $request->file('file');
        $save_path = ROOT_PATH . 'uploads/';
        $info = $file->move($save_path);
        $filename=$save_path . DIRECTORY_SEPARATOR . $info->getSaveName();
        if(file_exists($filename)) {//如果文件存在
            import('phpexcel.PHPExcel', EXTEND_PATH);
            if( strstr($filename,'.xlsx')){
                $PHPReader = new \PHPExcel_Reader_Excel2007();
            }else{
                $PHPReader = new \PHPExcel_Reader_Excel5();
            }
            //载入excel文件
            $PHPExcel = $PHPReader->load($filename);
            $sheet = $PHPExcel->getActiveSheet(0);//获得sheet
            $highestRow = $sheet->getHighestRow(); // 取得共有数据数
            $data=$sheet->toArray();
            for($i=1;$i<$highestRow-1;$i++){
                // $customer['username']=$data[$i][0];
                $customer['aliname']=$data[$i][0];//excel中的第一栏
                db('customer')->where('aliname',$customer['aliname'])->update(['p4p'=>1]);
            }
            $highestRow=$highestRow-2;
            if($highestRow){
                $arr = array('code' =>200,'msg'=>'上传'.$highestRow.'条数据！');
            }else{
                $arr = array('code' =>404,'msg'=>'上传数据为空');
            }
            return returnmsg($arr);
            // return $ret;
        }else{
            return array("resultcode" => -5, "resultmsg" => "文件不存在", "data" => null);
        }

    }
    public function authen()
    {
        return $this->fetch();
    }
    //执行导入信息
    public function doauthen(){
        $request = \think\Request::instance();
        $file = $request->file('file');
        $save_path = ROOT_PATH . 'uploads/';
        $info = $file->move($save_path);
        $filename=$save_path . DIRECTORY_SEPARATOR . $info->getSaveName();
        if(file_exists($filename)) {//如果文件存在
            import('phpexcel.PHPExcel', EXTEND_PATH);
            if( strstr($filename,'.xlsx')){
                $PHPReader = new \PHPExcel_Reader_Excel2007();
            }else{
                $PHPReader = new \PHPExcel_Reader_Excel5();
            }
            //载入excel文件
            $PHPExcel = $PHPReader->load($filename);
            $sheet = $PHPExcel->getActiveSheet(0);//获得sheet
            $highestRow = $sheet->getHighestRow(); // 取得共有数据数
            $data=$sheet->toArray();

//            unset($data[0]);
//            foreach ($data as $ke=>$va){
//                if (empty($va[12])&&$va[0]!="客户数："){
//                    $arr = array('code' =>404,'msg'=>'表格上传错误');
//                    return returnmsg($arr);
//                }
//            }
            $num1=0;
            $num2=0;
            $num3=0;
            $num4=0;
            $num5=0;
            $num6=0;
            $num7=0;
            $num8=0;
            for($i=1;$i<$highestRow;$i++) {
                if ($data[$i][9] != "诚信通服务") {
                    continue;
                } else {
                    //新增通过数据
                    //用户id
                    $authen['memberid'] = $data[$i][0];
                    //公司名称
                    $authen['company'] = $data[$i][1];
                    //签单人
                    $authen['sales'] = $data[$i][2];
                    //签单主管组
                    $authen['sales_executive'] = $data[$i][3];
                    //百年期数
                    $authen['centennial_number'] = $data[$i][4];
                    //订单号
                    $authen['order_number'] = $data[$i][5];
                    //新续属性
                    $authen['new_type'] = $data[$i][6];
                    //联系次数
                    $authen['contact_times'] = $data[$i][7];
                    //到单时间
                    $authen['single_time'] = strtotime($data[$i][8]);
                    //产品类别
                    $authen['product_category'] = $data[$i][9];
                    //到账时间
                    $authen['end_time'] = strtotime($data[$i][10]);
                    //送认证时间
                    $authen['send_time'] = strtotime($data[$i][11]);
                    //认证通过时间
                    $authen['sendover_time'] = strtotime($data[$i][12]);
                    //当前签单人
                    $authen['now_sales'] = $data[$i][13];
                    //当前主管组
                    $authen['newsales_executive'] = $data[$i][14];
                    //到单金额
                    $authen['single_money'] = str_replace(',', '', $data[$i][15]);
                    //到账金额
                    $authen['account_money'] = str_replace(',', '', $data[$i][16]);
                    //到期时间
                    $authen['over_time'] = strtotime($data[$i][17]);
                    //原到期时间
                    $authen['before_time'] = strtotime($data[$i][18]);
                    //底单编号
                    $authen['bottom_number'] = $data[$i][19];
                    $oid = 0;
                    if ($authen['new_type'] == '续签') {
                        if (strripos($authen['newsales_executive'], '石家庄')) {
                            $oid = 1;
                        } elseif (strripos($authen['newsales_executive'], '邯郸')) {
                            $oid = 2;
                        } elseif (strripos($authen['newsales_executive'], '衡水')) {
                            $oid = 3;
                        } elseif (strripos($authen['newsales_executive'], '沧州')) {
                            $oid = 4;
                        } elseif (strripos($authen['newsales_executive'], '廊坊')) {
                            $oid = 5;
                        } elseif (strripos($authen['newsales_executive'], '保定')) {
                            $oid = 6;
                        } elseif (strripos($authen['newsales_executive'], '白沟')) {
                            $oid = 7;
                        } elseif (strripos($authen['newsales_executive'], '邢台')) {
                            $oid = 8;
                        } elseif (strripos($authen['newsales_executive'], '西安')) {
                            $oid = 9;
                        }
                    } else {
                        if (strripos($authen['sales_executive'], '石家庄')) {
                            $oid = 1;
                        } elseif (strripos($authen['sales_executive'], '邯郸')) {
                            $oid = 2;
                        } elseif (strripos($authen['sales_executive'], '衡水')) {
                            $oid = 3;
                        } elseif (strripos($authen['sales_executive'], '沧州')) {
                            $oid = 4;
                        } elseif (strripos($authen['sales_executive'], '廊坊')) {
                            $oid = 5;
                        } elseif (strripos($authen['sales_executive'], '保定')) {
                            $oid = 6;
                        } elseif (strripos($authen['sales_executive'], '白沟')) {
                            $oid = 7;
                        } elseif (strripos($authen['sales_executive'], '邢台')) {
                            $oid = 8;
                        } elseif (strripos($authen['sales_executive'], '西安')) {
                            $oid = 9;
                        }
                    }
                    $where['memberid'] = $authen['memberid'];
                    $where['order_number'] = $authen['order_number'];
                    $where['product_category'] = $authen['product_category'];
                    //业绩表
                    $ach['aliorder'] = $authen['order_number'];
                    $ach['aliname'] = $authen['memberid'];
                    $ach['company'] = $authen['company'];
                    $ach['type'] = $authen['new_type'];
                    $ach['ptype'] = $authen['product_category'];
                    $ach['totime'] = $authen['end_time'];
                    $ach['fintime'] = $authen['sendover_time'] + 24 * 60 * 60;
                    $ach['money'] = $authen['account_money'];
                    if ($ach['type'] == '续签') {
                        $ach['username'] = $authen['now_sales'];
                    } else {
                        $ach['username'] = $authen['sales'];
                    }
                    $ach['endtime'] = $authen['over_time'];
                    $ach['yuantime'] = $authen['before_time'];
                    $ach['oid'] = $oid;
                    $where1['aliorder'] = $ach['aliorder'];
                    $where1['aliname'] = $ach['aliname'];
                    $where1['ptype'] = $ach['ptype'];
                    $anum = db('authen')->where($where)->count();
                    if ($anum < 1) {
                        db('authen')->insert($authen);
                        //业绩分配表
//                        $res1 = db('achievement')->insert($ach);
//                        if ($res1) {
//                            $num1++;
//                        } else {
//                            $num2++;
//                        }
                    } else {
                        db('authen')->where($where)->update($authen);
//                        $res2 = db('achievement')->where($where1)->update($ach);
//                        if ($res2) {
//                            $num3++;
//                        } else {
//                            $num4++;
//                        }
                    }
                    //阿里名
                    $customer['aliname'] = $authen['memberid'];
                    $customer['corporate_name'] = $authen['company'];
                    if ($ach['type'] == '续签') {
                        if ($authen['before_time'] < $authen['sendover_time']) {
                            $authen['sendover_time'] = $authen['sendover_time'] + 24 * 3600;
                            $customer['expires'] = strtotime(date('Y-m-d', strtotime("+1 year", $authen['sendover_time'])));  //认证通过时间+1天+1年
                        } else {
                            if (empty($authen['over_time'])) {
                                $authen['before_time'] = $authen['before_time'] + 86400;
                                $customer['expires'] = strtotime(date('Y-m-d', strtotime("+1 year", $authen['before_time'])));
                            } else {
                                $customer['expires'] = $authen['over_time'];
                            }
                        }
                    } else {
                        //新签
                        //$customer['sign_date']=strtotime(date('Y-m-d', strtotime ("+1 day", $authen['sendover_time'])));
                        if (empty($authen['over_time'])) {
                            $authen['sendover_time'] = $authen['sendover_time'] + 24 * 3600;
                            $customer['expires'] = strtotime(date('Y-m-d', strtotime("+1 year", $authen['sendover_time'])));
                        } else {
                            $customer['expires'] = $authen['over_time'];
                        }
                    }
                    $num = db('customer')->where('aliname', $customer['aliname'])->count();
                    if ($num < 1) {
                        $customer['sign_date'] = strtotime(date('Y-m-d', strtotime("+1 day", $authen['sendover_time'])));
                        $com['cid'] = db('customer')->insertGetId($customer);
                        $com['oid'] = $oid;
                        $com['addtime'] = time();
                        $res3 = db('relation_company')->insert($com);
                        if ($res3) {
                            $num5++;
                        } else {
                            $num6++;
                        }
                    } else {
                        $res4 = db('customer')->where('aliname', $customer['aliname'])->update($customer);
                        if ($res4) {
                            $num7++;
                        } else {
                            $num8++;
                        }
                    }
                }
                $num9 = $highestRow - 2;
                $content['content'] = '上传总计:' . $num9 . '条数据！新增客户' . $num5 . '条,重复客户' . $num6 . '条,更新客户信息' . $num7 . '重复客户信息' . $num8 . '条';
                $content['type'] = "认证通过";
                $content['time'] = time();
                db('import_log')->insert($content);
            }
                if ($highestRow) {
                    $arr = array('code' => 200, 'msg' => $content['content']);
                } else {
                    $arr = array('code' => 404, 'msg' => '上传数据为空');
                }
                return returnmsg($arr);
        }else{
            return array("resultcode" => -5, "resultmsg" => "文件不存在", "data" => null);
        }

    }
    //批量转移
    public function all_full()
    {
        $request = \think\Request::instance();
        $branch=$request->param('branch_id');
        $array=$request->param('array');
        $customer=new Customer();
        $userlist=$customer->branch_user($branch);
        return $this->fetch('',['userlist'=>$userlist,'array'=>$array]);
    }
    //批量转移操作
    public function alltrans()
    {
        $request = \think\Request::instance();
        $data=$request->param();
        $customer=new Customer();
        $res=$customer->alltrans($data);
        if($res==2){
            $arr = array('code' =>200,'msg'=>'转移成功！');
        }else{
            $arr = array('code' =>404,'msg'=>'转移失败！');
        }
        return returnmsg($arr);
    }
    //批量删除
    public function all_del()
    {
        $request = \think\Request::instance();
        $data=$request->param('array');
        $customer=new Customer();
        $res=$customer->all_del($data);
        if($res){
            $arr = array('code' =>200,'msg'=>'删除成功！');
        }else{
            $arr = array('code' =>404,'msg'=>'删除失败！');
        }
        return returnmsg($arr);
    }
    //重新提交续签订单
    public function cqorder()
    {
        $request = \think\Request::instance();
        $data=$request->param();
        $customer=new Customer();
        $res=$customer->cqorder($data);
        if($res){
            $arr = array('code' =>200,'msg'=>'提交成功！');
        }else{
            $arr = array('code' =>404,'msg'=>'提交失败！');
        }
        return returnmsg($arr);
    }



}
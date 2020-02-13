<?php
namespace app\index\model;
use think\Model;
use think\db;
use think\Session;
class Finances extends Model{
    protected $table = "silver_renewal";
    //添加用户
    public function addNode($data){
        $time = date('Y-m-d',$data['expires']); //当前时间戳
        $date = date("Y-m-d",strtotime("+1 year",strtotime($time)));//一年后日期
        $da['aliname']=$data['aliname'];
        $da['order'] =$data['order'];
        $da['money']=$data['money'];
        $da['job_num']=$data['job_num'];
        $da['corporate_name']=$data['corporate_name'];
        $da['username'] =$data['username'];
        $da['did']=$data['did'];
        $da['branch_id']=$data['branch_id'];
        $da['user_id']=$data['user_id'];
        $da['date']=date("Y/m/d");
        $da['expire']=strtotime($date);
        $da['expires']=$data['expires'];
        $da['time']=mktime(0,0,0,date('m'),1,date('Y'));
        if(db("renewal")->insert($da)){
            $ret['ok']=true;
            $ret['msg']='成功';
            return $ret;
        }else{
            $ret['ok']=false;
            $ret['msg']='失败';
            return $ret;
        }
    }
    public function getNodeById($id){
        $data = db("renewal")->where("id",$id)->find();
        return $data;
    }
    //续签列表页面
    public function renewal($bid,$username,$status,$start,$end){
        $where=array();
        $userid=Session::get('user.branch_id');
        if (isset($userid)){
            $where['branch_id']=$userid;
        }
        if ($bid&&$userid===null){
            $where['branch_id']=$bid;
        }
        if ($username){
            $where['username']=$username;
        }
        if($status){
            $where['status']=$status;
        }elseif ($status=="0"){
            $where['status']=$status;
        }
        if ($start&&$end){
            $where['date']=['between time',[$start,$end]];
        }elseif($start){
            $where['date']=['<= time',$start];
        }elseif ($end){
            $where['date']=['<= time',$end];
        }
        $data=Db::name('renewal')
            ->where($where)
            ->order('status asc')
            ->paginate(30,false,['query' => request()->param()]);
        return $data;
    }
    public function out($bid,$username,$status,$start,$end){
        $where=array();
        $userid=Session::get('user.branch_id');
        if (isset($userid)){
            $where['branch_id']=$userid;
        }
        if ($bid&&$userid===null){
            $where['branch_id']=$bid;
        }
        if ($username){
            $where['username']=$username;
        }
        if($status&&$status!=4){
            $where['re.status']=$status;
        }elseif ($status==0){
            $where['re.status']=$status;
        }

        if ($start&&$end){
            $where['date']=['between time',[$start,$end]];
        }elseif($start){
            $where['date']=['<= time',$start];
        }elseif ($end){
            $where['date']=['<= time',$end];
        }
        $header=array("订单号","阿里订单号","公司名称","订单金额","签单工号","签单姓名","分公司名称","到账时间","状态","订单类型");
        $data=Db::name('renewal')
            ->alias('re')
            ->join('branch_office br','re.branch_id=br.id')
            ->field('re.order,re.aliname,re.corporate_name,re.money,re.job_num,re.username,br.name,re.date,re.status,re.type')
            ->where($where)
            ->select();
        foreach ($data as $k => $v) {
            if (isset($data[$k]['status'])) {
                if ($data[$k]['status'] == 0) {
                    $data[$k]['status'] = "未处理";
                } elseif ($data[$k]['status'] == 1) {
                    $data[$k]['status'] = "通过";
                }elseif ($data[$k]['status'] ==2) {
                    $data[$k]['status'] = "不通过";
                }
            }
            if (isset($data[$k]['type'])){
                if ($data[$k]['type']==1){
                    $data[$k]['type']="诚信通";
                }elseif ($data[$k]['type']==2){
                    $data[$k]['type']="网销宝";
                }elseif ($data[$k]['type']==3){
                    $data[$k]['type']="建站服务包";
                }elseif ($data[$k]['type']==4){
                    $data[$k]['type']="代运营";
                }
            }
        }
        $name='续签订单';
        $this->excelExport($name,$header,$data);
    }
    //通过
    public function changeStatus($id,$did,$time){
        $data = $this->getNodeById($id);
        if($data['status']==1){
            $status = 0;
        }else{
            $status = 1;
        }
        if(db("renewal")->where("id",$id)->setField("status",$status)){
            Db::name('renewal')->where('id',$id)->update(['c_time'=>$time]);
            $update=Db::name('customer')->where('id',$did)->field('expires')->find();
            $uptime=$update['expires'];
            $time = date('Y-m-d',$uptime); //当前时间戳
            $date = strtotime(date("Y-m-d",strtotime("+1 year",strtotime($time))));
            $res=Db::name('customer')->where('id',$did)->update(['examine'=>0]);
            $res=Db::name('customer')->where('id',$did)->update(['expires'=>$date]);
            return true;
        }else{
            return false;
        }
    }
    //不通过
    public function changeStatusr($id,$did){
        $data = $this->getNodeById($id);
        if($data['status']==2){
            $status = 0;
        }else{
            $status = 2;
        }

        if(db("renewal")->where("id",$id)->setField("status",$status)){
            $res=Db::name('customer')->where('id',$did)->update(['examine'=>2]);
            return true;
        }else{
            return false;
        }
    }
    //新签订单通过
    public function changeStatus1($id)
    {
//        $c_id=Db::name('neworderform')->where('id',$id)->field('c_id')->find();
//        $c_id=$c_id['c_id'];
//        $ch['c_status']=2;
        $update['status']=3;
        $res=Db::name('neworderform')->where('id',$id)->update($update);
        // $res=1;
        if($res){
            $update1['paytime']=time();
            Db::name('neworderform')->where('id',$id)->update($update1);
            $user=Db::name('neworderform')->where('id',$id)->find();
            $cid=$user['cid'];
            $customer=Db::name('newcustomer')->where('c_id',$cid)->find();
            $upstatus['c_status']=3;
            Db::name('newcustomer')->where('c_id',$cid)->update($upstatus);
            $add['aliname']=$customer['c_aliname'];
            $add['username']=$customer['c_username'];
            $add['moblie']=$customer['c_tel'];
            $add['position']=$customer['c_position'];
            $add['corporate_name']=$customer['c_company'];
            $add['company_address']=$customer['c_address'];
            $add['products']=$customer['c_industry'];
            $add['old_signsale']=$user['uid'];
            $add['sign_date']=$user['paytime'];
            $add['expires']=$user['endtime'];
            $add['relation_status']=1;
            $res1=Db::name('customer')->insertGetId($add);
            if($res1){
                $company['cid']=$res1;
                $company['oid']=$user['bid'];
                $company['addtime']=time();
                $company['updatetime']=time();
                $company['maturitys']=$user['endtime'];
                $re=Db::name('relation_company')->insert($company);
                if($re){
                    return 1;
                }else{
                    return 2;
                }
            }else{
                return 2;
            }
        }else{
            return 3;
        }
    }

    /*
     * 网销宝订单通过
     */
    public function  changew($id){
         $up['wopen']=3;
         $update['w_status']=4;
         $c_id=Db::name('neworderform')->where('id',$id)->field('cid')->find();
         $result=Db::name('newcustomer')->where('c_id',$c_id['cid'])->update($update);
         if ($result){
             $res=Db::name('neworderform')
                 ->where('id',$id)
                 ->update($up);
             if ($res){
                 return $res;
             }else{
                 return false;
             }

         }else{
             return false;
         }


    }

    /*
 * 建站包订单通过
 */
    public function  changez($id){
        $up['zopen']=3;
        $update['z_status']=3;
        $c_id=Db::name('neworderform')->where('id',$id)->field('cid')->find();
        $result=Db::name('newcustomer')->where('c_id',$c_id['cid'])->update($update);
        if ($result){
            $res=Db::name('neworderform')
                ->where('id',$id)
                ->update($up);
            if ($res){
                return $res;
            }else{
                return false;
            }

        }else{
            return false;
        }
    }
    /*
    * 代运营订单通过
    */
    public function  changed($id){
        $up['dopen']=3;
        $update['d_status']=3;
        $c_id=Db::name('neworderform')->where('id',$id)->field('cid')->find();
        $result=Db::name('newcustomer')->where('c_id',$c_id['cid'])->update($update);
        if ($result){
            $res=Db::name('neworderform')
                ->where('id',$id)
                ->update($up);
            if ($res){
                return $res;
            }else{
                return false;
            }

        }else{
            return false;
        }
    }





    //拒绝新签订单

    public function changeStatusr1($id)
    {
        $update['status']=4;
        $up['c_status']=4;
        $c_id=Db::name('neworderform')->where('id',$id)->field('cid')->find();
        $result=Db::name('newcustomer')->where('c_id',$c_id['cid'])->update($up);
        if ($result){
            $res=Db::name('neworderform')->where('id',$id)->update($update);
            if($res){
                return 1;
            }else{
                return 2;
            }
        }else{
            return false;
        }

    }
//网销宝不通过
    public function nochangew($id){
        $update['wopen']=4;
        $up['w_status']=5;
        $c_id=Db::name('neworderform')->where('id',$id)->field('cid')->find();
        $result=Db::name('newcustomer')->where('c_id',$c_id['cid'])->update($up);
        if ($result){
            $res=Db::name('neworderform')->where('id',$id)->update($update);
            if($res){
                return 1;
            }else{
                return 2;
            }
        }else{
            return false;
        }
    }
//建站包不通过
    public function nochangez($id){
        $update['zopen']=4;
        $up['z_status']=4;
        $c_id=Db::name('neworderform')->where('id',$id)->field('cid')->find();
        $result=Db::name('newcustomer')->where('c_id',$c_id['cid'])->update($up);
        if ($result){
            $res=Db::name('neworderform')->where('id',$id)->update($update);
            if($res){
                return 1;
            }else{
                return 2;
            }
        }else{
            return false;
        }
    }
//代运营不通过
    public function nochanged($id){
        $update['dopen']=4;
        $up['d_status']=4;
        $c_id=Db::name('neworderform')->where('id',$id)->field('cid')->find();
        $result=Db::name('newcustomer')->where('c_id',$c_id['cid'])->update($up);
        if ($result){
            $res=Db::name('neworderform')->where('id',$id)->update($update);
            if($res){
                return 1;
            }else{
                return 2;
            }
        }else{
            return false;
        }
    }

    public function renewalsearch($pram){
        $where=array();
        $userid=Session::get('user.branch_id');
        if (isset($userid)){
            $where['branch_id']=$userid;
        }

        $data=Db::name('renewal')
            ->where($where)
            ->where('aliname|corporate_name','like','%'.$pram.'%')
            ->paginate(50,false,['query' => request()->param()]);
        return $data;
    }


    /*
     * 业绩管理展示
     */
    public function  perorder($csearch,$per_username,$per_type,$per_status,$start,$end,$per_financial){
        $where=array();
        $start=strtotime($start);
        $end=strtotime($end);
        if ($csearch){
            $where['per_aliname|per_cname']=['like','%'.$csearch.'%'];
        }
        if ($per_username){
            $where['per_bid']=$per_username;
        }
        if ($per_type){
            $where['per_type']=$per_type;
        }
        if ($per_status){
            $where['per_status']=$per_status;
        }
        if ($per_status){
            $where['per_status']=$per_status;
        }
        if ($start&&$end){
            $where['per_daotime']=['between time',[$start,$end]];
        }elseif($start){
            $where['per_daotime']=['<= time',$start];
        }elseif ($end){
            $where['per_daotime']=['<= time',$end];
        }
        if ($per_financial){
            $where['per_financial']=strtotime($per_financial);
        }
        $user=Session::get('user');
        if($user['dd_postion']=="总经理"){
            $result['data']=Db::name('per_order')
                ->where('per_bid',$user['branch_id'])
                ->where('per_status=1 OR per_status=3 OR per_status=4')
                ->where($where)
                ->order('per_status asc')
                ->paginate(50,false,['query' => request()->param()]);
        }elseif($user['rank']==26) {
            $result['data']=Db::name('per_order')
                ->where('per_status=1 OR per_status=3 OR per_status=4')
                ->where($where)
                ->order('per_status asc')
                ->paginate(50,false,['query' => request()->param()]);
        }else{
                $result['data']=Db::name('per_order')
                ->where('per_bid',$user['branch_id'])
                ->where('per_department',$user['department'])
                ->where('per_status=1 OR per_status=3 OR per_status=4')
                ->where($where)
                ->order('per_status asc')
                ->paginate(30,false,['query' => request()->param()]);
        }
        $result['num']=count($result['data']);
        $result['page'] = $result['data']->render();
        return $result;
    }


    public function branchs(){
        $data=Db::name('branch_office')
            ->select();
        return $data;
    }

    public  function  outperorderexec($csearch,$per_username,$per_type,$per_status,$start,$end,$per_financial){
        $where=array();
        $start=strtotime($start);
        $end=strtotime($end);
        if ($csearch){
            $where['per_aliname|per_cname']=['like','%'.$csearch.'%'];
        }
        if ($per_username){
            $where['per_bid']=$per_username;
        }
        if ($per_type){
            $where['per_type']=$per_type;
        }
        if ($per_status){
            $where['per_status']=$per_status;
        }
        if ($per_status){
            $where['per_status']=$per_status;
        }
        if ($start&&$end){
            $where['per_daotime']=['between time',[$start,$end]];
        }elseif($start){
            $where['per_daotime']=['<= time',$start];
        }elseif ($end){
            $where['per_daotime']=['<= time',$end];
        }
        if ($per_financial){
            $where['per_financial']=strtotime($per_financial);
        }
        $data=Db::name('per_order')
            ->where('per_status=1 OR per_status=3 OR per_status=4')
            ->where($where)
            ->field('per_aliorder,per_aliname,per_cname,per_amount,per_amounted,per_type,per_username,per_department,per_bid,per_daotime,per_sex,per_year,per_status,per_financial')
            ->order('per_status asc')
            ->select();
        foreach ($data as $k=>$v){
            if (isset($data[$k]['per_daotime'])) {
                $data[$k]['per_daotime'] = date('Y-m-d', $v['per_daotime']);
            }
            if (isset($data[$k]['per_financial'])) {
                $data[$k]['per_financial'] = date('Y-m-d', $v['per_financial']);
            }
            $data[$k]['per_department']=department($data[$k]['per_department']);
            $data[$k]['per_bid']=branch($data[$k]['per_bid']);
            if ($data[$k]['per_type']==1){
                $data[$k]['per_type']="诚信通";
            }elseif ($data[$k]['per_type']==2){
                $data[$k]['per_type']="网销宝";
            }elseif ($data[$k]['per_type']==3){
                $data[$k]['per_type']="建站服务包";
            }elseif ($data[$k]['per_type']==4){
                $data[$k]['per_type']="运营包";
            }elseif ($data[$k]['per_type']==5){
                $data[$k]['per_type']="大泽ERP";
            }
            if ($data[$k]['per_status']==1){
                $data[$k]['per_status']="总监通过";
            }elseif ($data[$k]['per_status']==3){
                $data[$k]['per_status']="财务通过";
            }elseif ($data[$k]['per_status']==4){
                $data[$k]['per_status']="财务拒绝";
            }
        }
        $header=array("阿里订单号","阿里名","客户公司名称","应收金额","实收金额","订单类型","员工姓名","所属部门","分公司名称","到单时间","新续属性","年限","状态","财务通过时间");
        $name="业绩表订单确认";
        $this->excelExport($name,$header,$data);
    }


    /*
     * 业绩管理通过
     */
    public function change($id){
        $up['per_status']=3;
        $up['per_financial']=strtotime(date("Y-m-d",time()));
        $res=Db::name('per_order')
            ->where('per_id',$id)
            ->update($up);
        return $res;
    }
    /*
 * 业绩管理不通过
 */
    public function nochange($id){
        $up['per_status']=4;
        $res=Db::name('per_order')
            ->where('per_id',$id)
            ->update($up);
        return $res;
    }

    /*
     * 索条件员工姓名展示
     */
    public function br(){
        $user=Session::get('user');
        $data=Db::name('user')
            ->where('branch_id',$user['branch_id'])
            ->where('department',$user['department'])
            ->field('username')
            ->select();
        return $data;
    }

    /*
     * 业绩确认列表
     */
    public  function  outlist($csearch,$type,$start,$ptype){
        $where=array();
        if ($csearch){
            $where['aliname|company']=$csearch;
        }
        if ($type){
            $where['type']=$type;
        }
        if ($start){
            $where['permonth']=strtotime($start);
        }
        if($ptype){
            $where['ptype']=$ptype;
        }

        $list['data']=Db::name('achievement')
            ->alias('ac')
            ->join('achievementover ach','ac.id=ach.ach_id','LEFT')
            ->where('status','>=',2)
            ->where($where)
            ->order('ac.permonth desc')
            ->paginate(100,false,['query' => request()->param()]);
        $list['num']=count($list['data']);
        $list['page'] = $list['data']->render();
       return $list;
    }
    /*
     * 业绩确认审核
     */
    public function outorderstatus($id,$type){
        if ($type==1){
            $up['status']=3;
        }else{
            $up['status']=4;
        }
        $res=Db::name('achievement')
            ->where('id',$id)
            ->update($up);
        if ($res){
            return true;
        }else{
            return false;
        }
    }
    /*
    * 业绩确认导出
    */
    public function outorderexec($csearch,$type,$start,$ptype){
        $where=array();
        if ($csearch){
            $where['aliname|company']=$csearch;
        }
        if ($type){
            $where['type']=$type;
        }
        if ($start){
            $where['permonth']=strtotime($start);
        }
        if($ptype){
            $where['ptype']=$ptype;
        }
        $data=Db::name('achievement')
            ->where($where)
            ->where('status','>=',2)
            ->order('status asc')
            ->field('aliorder,aliname,company,type,ptype,totime,fintime,yuantime,username,oid,permonth,status')
            ->select();
        foreach ($data as $k=>$v){
            if ($data[$k]['type']=="续签"){
                $sign_date=Db::name('customer')->where('aliname',$data[$k]['aliname'])->field('sign_date')->find();
                $expires=Db::name('customer')->where('aliname',$data[$k]['aliname'])->field('expires')->find();
                $data[$k]['year']=floor(($expires['expires']-$sign_date['sign_date'])/86400/365);
                if ($data[$k]['year']>0){
                    $data[$k]['year']=$data[$k]['year'];
                }else{
                    $data[$k]['year']="无";
                }

            }
            if (isset($data[$k]['totime'])) {
                $data[$k]['totime'] = date('Y-m-d H:i:s', $v['totime']);
            }
            if (isset($data[$k]['fintime'])&&$data[$k]['fintime']!=0) {
                $data[$k]['fintime'] = date('Y-m-d H:i:s', $v['fintime']);
            }
            if (isset($data[$k]['yuantime'])&&$data[$k]['yuantime']!=0) {
                $data[$k]['yuantime'] = date('Y-m-d H:i:s', $v['yuantime']);
            }
            if (isset($data[$k]['oid'])) {
                $data[$k]['oid'] =branch($data[$k]['oid']);
            }
            if ($data[$k]['permonth']==0) {
                $data[$k]['permonth']=0;
            }else{
                $data[$k]['permonth'] =date('Y-m', $v['permonth']);
            }
            if ($data[$k]['status']==2){
                $data[$k]['status']="员工确认完毕";
            }
        }
        $header=array("阿里订单号","阿里名","公司名称","新续签","产品类型","到单时间","认证通过时间","原到期时间","签单人","所属公司","业绩所属月","审核状态","续签年限");
        $name="业绩表";
        $this->excelExport($name,$header,$data);
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
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
        $fileName = iconv("utf-8", "gb2312", $fileName); // 重命名表
        $objPHPExcel->setActiveSheetIndex(0); // 设置活动单指数到第一个表,所以Excel打开这是第一个表
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename='$fileName'");
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output'); // 文件通过浏览器下载
        exit();
    }

    public function wagesto($branch){
        $where=array();
        if ($branch){
            $where['branch_id']=$branch;
        }
        $data=Db::name('wagsto')
            ->where($where)
            ->paginate(50,false,['query' => request()->param()]);
        return $data;
    }

    public function outwages($br){
        $where=array();
        if($br){
            $where['branch_id']=$br;
        }
        $data=Db::name('wagsto')
            ->where($where)
            ->field('id,username,branch_id,department,basemoney,royalty,total,per')
            ->select();
        foreach ($data as $k=>$v){
            if (isset($data[$k]['branch_id'])) {
                $data[$k]['branch_id'] =Db::name('branch_office')->where('id',$data[$k]['branch_id'])->value('name');
            }
            if (isset($data[$k]['department'])) {
                $data[$k]['department'] =Db::name('department')->where('d_id',$data[$k]['department'])->value('d_name');
            }
            if (isset($data[$k]['per'])) {
                $data[$k]['per'] = date('Y-m', $v['per']);
            }
        }
        $header=array("序号","员工姓名","所属分公司","所属部门","底薪","提成","总计","业绩所属月");
        $name="业绩工资表";
        $this->excelExport($name,$header,$data);
    }
}

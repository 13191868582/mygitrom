<?php
/*
 * 业绩管理
 * 编写人：段文明
 * 时间：2019年9月23日11:45:56
*/

namespace app\index\model;
use think\helper\Arr;
use think\Model;
use think\Db;
use think\Session;
use think\Request;

class Achievement extends Model
{
    public function addlist(){
        //查询分公司
        $data['offic']=Db::name('branch_office')
            ->select();
        //查询部门
        $data['department']=Db::name('department')
            ->select();
        return $data;
    }

    public function add_do($data){
        $data['a_time']=strtotime($data['a_time']);
        $res=Db::name('achievements')
            ->where('branch_id',$data['branch_id'])
            ->where('offic_id',$data['offic_id'])
            ->where('a_time',$data['a_time'])
            ->find();
        if ($res){
            return $arr= array('code' =>404,'msg'=>'选择分公司及部门本月已有数据！');
        }else{
            $data=Db::name('achievements')
                ->insert($data);
            if ($data){
                return $arr= array('code' =>200,'msg'=>'录入成功！');
            }else{
                return $arr= array('code' =>404,'msg'=>'录入失败！');
            }
        }
    }
    public function sadd_do($data){
        $data['s_time']=$data['year']."-".$data['season'];
        unset($data['year']);
        unset($data['season']);
        $res=Db::name('achievementss')
            ->where('branch_id',$data['branch_id'])
            ->where('offic_id',$data['offic_id'])
            ->where('s_time',$data['s_time'])
            ->find();
        if ($res){
            return $arr= array('code' =>404,'msg'=>'选择分公司及部门该季度已有数据！');
        }else{
            $data=Db::name('achievementss')
                ->insert($data);
            if ($data){
                return $arr= array('code' =>200,'msg'=>'录入成功！');
            }else{
                return $arr= array('code' =>404,'msg'=>'录入失败！');
            }
        }

    }

    public function  lists(){
        $lisRows=20;
        $admin=Session::get('admin');
        if (!empty($admin)){
            $data['data']=Db::name('achievements')
                ->alias('ac')
                ->join('branch_office br','ac.branch_id=br.id')
                ->join('department de','ac.offic_id=de.d_id')
                ->field('ac.id,br.name,de.d_name,ac.a_time,ac.task_id')
                ->paginate($lisRows,false,['query' => request()->param()]);
        }else{
            $rank=Session::get('user.rank');
            if ($rank==1){
                $br=Session::get('user.branch_id');
                $data['data']=Db::name('achievements')
                    ->alias('ac')
                    ->join('branch_office br','ac.branch_id=br.id')
                    ->join('department de','ac.offic_id=de.d_id')
                    ->field('ac.id,br.name,de.d_name,ac.a_time,ac.task_id')
                    ->where('ac.branch_id',$br)
                    ->paginate($lisRows,false,['query' => request()->param()]);
            }elseif ($rank==11){
                $br=Session::get('user.branch_id');
                $data['data']=Db::name('achievements')
                    ->alias('ac')
                    ->join('branch_office br','ac.branch_id=br.id')
                    ->join('department de','ac.offic_id=de.d_id')
                    ->field('ac.id,br.name,de.d_name,ac.a_time,ac.task_id')
                    ->where('ac.branch_id',$br)
                    ->where('ac.offic_id',1)
                    ->paginate($lisRows,false,['query' => request()->param()]);
            }elseif($rank==2 || $rank == 12){
                $br=Session::get('user.branch_id');
                $data['data']=Db::name('achievements')
                    ->alias('ac')
                    ->join('branch_office br','ac.branch_id=br.id')
                    ->join('department de','ac.offic_id=de.d_id')
                    ->field('ac.id,br.name,de.d_name,ac.a_time,ac.task_id')
                    ->where('ac.branch_id',$br)
                    ->where('ac.offic_id',2)
                    ->paginate($lisRows,false,['query' => request()->param()]);
            }
        }
        $data['page'] = $data['data']->render();
        return $data;
    }

    //季度
    public function  slists(){
        $lisRows=20;
        $admin=Session::get('admin');
        if (!empty($admin)){
            $data['data']=Db::name('achievementss')
                ->alias('ac')
                ->join('branch_office br','ac.branch_id=br.id')
                ->join('department de','ac.offic_id=de.d_id')
                ->field('ac.id,br.name,de.d_name,ac.s_time,ac.task_id')
                ->paginate($lisRows,false,['query' => request()->param()]);
        }else{
            $rank=Session::get('user.rank');
            if ($rank==1){
                $br=Session::get('user.branch_id');
                $data['data']=Db::name('achievementss')
                    ->alias('ac')
                    ->join('branch_office br','ac.branch_id=br.id')
                    ->join('department de','ac.offic_id=de.d_id')
                    ->field('ac.id,br.name,de.d_name,ac.s_time,ac.task_id')
                    ->where('ac.branch_id',$br)
                    ->paginate($lisRows,false,['query' => request()->param()]);
            }elseif ($rank==11){
                $br=Session::get('user.branch_id');
                $data['data']=Db::name('achievementss')
                    ->alias('ac')
                    ->join('branch_office br','ac.branch_id=br.id')
                    ->join('department de','ac.offic_id=de.d_id')
                    ->field('ac.id,br.name,de.d_name,ac.s_time,ac.task_id')
                    ->where('ac.branch_id',$br)
                    ->where('ac.offic_id',1)
                    ->paginate($lisRows,false,['query' => request()->param()]);
            }elseif($rank==2 || $rank == 12){
                $br=Session::get('user.branch_id');
                $data['data']=Db::name('achievementss')
                    ->alias('ac')
                    ->join('branch_office br','ac.branch_id=br.id')
                    ->join('department de','ac.offic_id=de.d_id')
                    ->field('ac.id,br.name,de.d_name,ac.s_time,ac.task_id')
                    ->where('ac.branch_id',$br)
                    ->where('ac.offic_id',2)
                    ->paginate($lisRows,false,['query' => request()->param()]);
            }
        }
        $data['page'] = $data['data']->render();
        return $data;
    }
    //修改
    public function edit($id){
        $data=Db::name('achievements')
            ->alias('ac')
            ->join('branch_office br','ac.branch_id=br.id')
            ->join('department de','ac.offic_id=de.d_id')
            ->field('ac.id,ac.branch_id,ac.offic_id,ac.task_id,br.name,de.d_name,de.d_id,ac.a_time')
            ->where('ac.id',$id)
            ->find();
        return $data;
    }
    public function edit_do($data){
        $id=$data['id'];
        unset($data['id']);
        $data['a_time']=strtotime($data['a_time']);
        $res=Db::name('achievements')
            ->where('id',$id)
            ->update($data);
        if ($res){
            return $arr= array('code' =>200,'msg'=>'修改成功！');
        }else{
            return $arr= array('code' =>200,'msg'=>'修改成功！');
        }

    }

//    public function ac_complete($offic,$a_time){
//$y=substr($a_time,0,4);
//$m=substr($a_time,5,2);
//$da=$this->getdate($y,$m);
//$start_time=$da['firstday'];
//$end_time=$da['lastday'];
//        $res['offic']=Db::name('branch_office')
//            ->where('id',$offic)
//            ->field('name')
//            ->find();
//        //查询新签任务量
//        $data['data']=Db::name('achievements')
//            ->where('branch_id',$offic)
//            ->where('offic_id',1)
//            ->field('task_id')
//            ->find();
//        $newco=$data['data']['task_id'];
//        //新签完成单数（根据分公司查询）
//        $newcomplete1=Db::name('newcustomer')
//            ->where('bid',$offic)
//            ->whereTime('c_registration','between',["$start_time","$end_time"])
//            ->where('c_status',1)
//            ->count();
//        if ($newco){
//            $newco=$data['data']['task_id'];
//            $res['new']=$newcomplete1/$newco*100;
//        }else{
//            $res['new']=0;
//        }
//        //续签任务量
//        $xuqian=Db::name('achievements')
//            ->where('branch_id',$offic)
//            ->where('offic_id',2)
//            ->field('task_id')
//            ->find();
//        $xql=$xuqian['task_id'];
//        //续签完成单数（根据分公司查询）
//        $xqcomplete=Db::name('renewal')
//            ->where('branch_id',$offic)
//            ->whereTime('c_time','between',["$start_time","$end_time"])
//            ->where('status',1)
//            ->count();
//        if ($xql){
//            $res['xuq']=$xqcomplete/$xql*100;
//        }else{
//            $res['xuq']=0;
//        }
//        return $res;
//    }
    public function ac_complete($a_time){
        $y=substr($a_time,0,4);
        $m=substr($a_time,5,2);
        $da=$this->getdate($y,$m);
        $start_time=$da['firstday'];
        $end_time=$da['lastday'];
        $a_time=strtotime($a_time);
        $branch=Db::name('branch_office')
            ->select();
        foreach ($branch as $k=>$v){
            $data=Db::name('achievements')
                ->whereTime('a_time','between',["$start_time","$end_time"])
                ->select();
            $branch[$k]['newover']=0;
            $branch[$k]['xuqianover']=0;
            $branch[$k]['wangxiaobaoover']=0;
            $branch[$k]['daiyunyingover']=0;
            //根据分公司计算新签完成个数
            $branch[$k]['newover']=Db::name('neworderform')
                ->where('bid',$v['id'])
                ->whereTime('totime','between',["$start_time","$end_time"])
                ->where('ortype','=',1)
                ->count();
            $branch[$k]['xuqianover']=Db::name('neworderform')
                ->where('bid',$v['id'])
                ->whereTime('totime','between',["$start_time","$end_time"])
                ->where('ortype','=',2)
                ->count();
            $branch[$k]['wangxiaobaoover']=Db::name('neworderform')
                ->where('bid',$v['id'])
                ->whereTime('totime','between',["$start_time","$end_time"])
                ->where('ortype','=',3)
                ->count();
            $branch[$k]['daiyunyingover']=Db::name('neworderform')
                ->where('bid',$v['id'])
                ->whereTime('totime','between',["$start_time","$end_time"])
                ->where('ortype','=',4)
                ->count();
            $branch[$k]['new']=0;
            $branch[$k]['xuqian']=0;
            $branch[$k]['wangxiaobao']=0;
            $branch[$k]['daiyunying']=0;
            foreach ($data as $ke=>$val){
                if ($val['branch_id']==$v['id']){
                    if ($val['offic_id']==1){
                        $branch[$k]['new']=$val['task_id'];
                    }elseif ($val['offic_id']==2){
                        $branch[$k]['xuqian']=$val['task_id'];
                    }elseif ($val['offic_id']==3){
                        $branch[$k]['wangxiaobao']=$val['task_id'];
                    }elseif ($val['offic_id']==4){
                        $branch[$k]['daiyunying']=$val['task_id'];
                    }
                }
            }
            //完成度计算
            if ($branch[$k]['new']!=0){
                $branch[$k]['nover']=round($branch[$k]['newover']/$branch[$k]['new']*100,2);
            }else{
                $branch[$k]['nover']=0;
            }
            if ($branch[$k]['xuqian']!=0){
                $branch[$k]['xuover']=round($branch[$k]['xuqianover']/$branch[$k]['xuqian']*100,2);
            }else{
                $branch[$k]['xuover']=0;
            }
            if ($branch[$k]['wangxiaobao']!=0){
                $branch[$k]['wangover']=round($branch[$k]['wangxiaobaoover']/$branch[$k]['wangxiaobao']*100,2);

            }else{
                $branch[$k]['wangover']=0;

            }
            if ($branch[$k]['daiyunying']!=0){
                $branch[$k]['daiover']=round($branch[$k]['daiyunyingover']/$branch[$k]['daiyunying']*100,2);
            }else{
                $branch[$k]['daiover']=0;
            }


        }
        return $branch;
        //print_r($branch);die;
    }

    public function mytask(){
        //当前时间
        $ntime=date("Y-m",time());
        $y=substr($ntime,0,4);
        $m=substr($ntime,5,2);
        $first=array(1,2,3);
        $second=array(4,5,6);
        $third=array(7,8,9);
        $fourth=array(10,11,12);
        if (in_array($m,$first)){
            $da=$this->getdate($y,$first[0]);
            $mstart=$da['firstday'];
            $da=$this->getdate($y,$first[2]);
            $mend=$da['lastday'];
        }elseif(in_array($m,$second)){
            $da=$this->getdate($y,$second[0]);
            $mstart=$da['firstday'];
            $da=$this->getdate($y,$second[2]);
            $mend=$da['lastday'];
        }elseif(in_array($m,$third)){
            $da=$this->getdate($y,$third[0]);
            $mstart=$da['firstday'];
            $da=$this->getdate($y,$third[2]);
            $mend=$da['lastday'];
        }elseif(in_array($m,$fourth)){
            $da=$this->getdate($y,$fourth[0]);
            $mstart=$da['firstday'];
            $da=$this->getdate($y,$fourth[2]);
            $mend=$da['lastday'];
        }
        $da=$this->getdate($y,$m);
        $start_time=$da['firstday'];
        $end_time=$da['lastday'];
        $branch=br();
        foreach ($branch as $k=>$v){
            $data=Db::name('achievements')
                ->whereTime('a_time','between',["$start_time","$end_time"])
                ->select();
            $mdata=Db::name('achievements')
                ->whereTime('a_time','between',["$mstart","$mend"])
                ->select();
            $branch[$k]['newover']=0;
            $branch[$k]['xuqianover']=0;
            $branch[$k]['wangxiaobaoover']=0;
            $branch[$k]['daiyunyingover']=0;
            $branch[$k]['mnewover']=0;
            $branch[$k]['mxuqianover']=0;
            $branch[$k]['mwangxiaobaoover']=0;
            $branch[$k]['mdaiyunyingover']=0;
            //月度完成度
            $branch[$k]['newover']=Db::name('alimport')
                ->whereTime('end_time','between',["$start_time","$end_time"])
                ->where('sales_executive','like',"%/".$v['name']."%")
                ->where('new_type',"新签")
                ->where('product_category',"诚信通服务")
                ->count();
            $branch[$k]['xuqianover']=Db::name('alimport')
                ->whereTime('end_time','between',["$start_time","$end_time"])
                ->where('sales_executive','like',"%/".$v['name']."%")
                ->where('new_type',"续签")
                ->where('product_category',"诚信通服务")
                ->count();
            $branch[$k]['wangxiaobaoover']=Db::name('alimport')
                ->whereTime('end_time','between',["$start_time","$end_time"])
                ->where('sales_executive','like',"%/".$v['name']."%")
                ->where('product_category',"网销宝充值包")
                ->count();
            $branch[$k]['daiyunyingover']=Db::name('per_order')
                ->where('per_bid',$v['id'])
                ->whereTime('per_addtime','between',["$start_time","$end_time"])
                ->where('per_type','=',4)
                ->count();
            //季度完成度
            $branch[$k]['mnewover']=Db::name('alimport')
                ->whereTime('end_time','between',["$mstart","$mend"])
                ->where('sales_executive','like',"%/".$v['name']."%")
                ->where('new_type',"新签")
                ->where('product_category',"诚信通服务")
                ->count();
            $branch[$k]['mxuqianover']=Db::name('alimport')
                ->whereTime('end_time','between',["$mstart","$mend"])
                ->where('sales_executive','like',"%/".$v['name']."%")
                ->where('new_type',"续签")
                ->where('product_category',"诚信通服务")
                ->count();
            $branch[$k]['mwangxiaobaoover']=Db::name('alimport')
                ->whereTime('end_time','between',["$mstart","$mend"])
                ->where('sales_executive','like',"%/".$v['name']."%")
                ->where('product_category',"网销宝充值包")
                ->count();
            $branch[$k]['mdaiyunyingover']=Db::name('per_order')
                ->where('per_bid',$v['id'])
                ->whereTime('per_addtime','between',["$mstart","$mend"])
                ->where('per_type','=',4)
                ->count();

            //月度任务量
            $branch[$k]['new']=0;
            $branch[$k]['xuqian']=0;
            $branch[$k]['wangxiaobao']=0;
            $branch[$k]['daiyunying']=0;
            //季度任务量
            $branch[$k]['mnew']=0;
            $branch[$k]['mxuqian']=0;
            $branch[$k]['mwangxiaobao']=0;
            $branch[$k]['mdaiyunying']=0;
            //print_r($data);die;
            foreach ($data as $ke=>$val){
                if ($val['branch_id']==$v['id']){
                    if ($val['offic_id']==2){
                        $branch[$k]['new']=$val['task_id'];
                    }elseif ($val['offic_id']==3){
                        $branch[$k]['xuqian']=$val['task_id'];
                    }elseif ($val['offic_id']==4){
                        $branch[$k]['wangxiaobao']=$val['task_id'];
                    }elseif ($val['offic_id']==15){
                        $branch[$k]['daiyunying']=$val['task_id'];
                    }
                }
            }
            foreach ($mdata as $kes=>$values){
                if ($values['branch_id']==$v['id']){
                    if ($values['offic_id']==2){
                        $branch[$k]['mnew']+=$values['task_id'];
                    }elseif ($values['offic_id']==3){
                        $branch[$k]['mxuqian']+=$values['task_id'];
                    }elseif ($values['offic_id']==4){
                        $branch[$k]['mwangxiaobao']+=$values['task_id'];
                    }elseif ($values['offic_id']==15){
                        $branch[$k]['mdaiyunying']+=$values['task_id'];
                    }
                }
            }
        }
        //print_r($branch);die;
        return $branch;
    }


    public function pks($a_time){
        $pk=$this->ac_complete($a_time);
        //新签榜
        $res=array_column($pk,'nover','name');
        arsort($res);
        foreach ($res as $k=>$v){
            $ress[$k]=$v;
        }
       $data['n']=$ress;
        //续签榜
        $res1=array_column($pk,'xuover','name');
        arsort($res1);
        foreach ($res1 as $k=>$v){
            $ress1[$k]=$v;
        }
        $data['x']=$ress1;
        //网销宝
        $res2=array_column($pk,'wangover','name');
        arsort($res2);
        foreach ($res2 as $k=>$v){
            $ress2[$k]=$v;
        }
        $data['w']=$ress2;
        //代运营
        $res3=array_column($pk,'daiover','name');
        arsort($res3);
        foreach ($res3 as $k=>$v){
            $ress3[$k]=$v;
        }
        $data['d']=$ress3;
        return $data;
    }
//    public function pks($a_time){
//        $pk=$this->ac_complete($a_time);
//        //新签榜首
//        $res=array_column($pk,'nover','name');
//        arsort($res);
//        foreach ($res as $k=>$v){
//            $ress[$k]=$v;
//        }
//        print_r($ress);die;
//        array_multisort(array_column($pk,'nover','name'),SORT_DESC,$pk);
//        $max = $pk[0]['nover'];
//        $npkone=array_keys($res,$max);
//        $count=count($npkone);
//        if ($count!=1){
//            $data['npkone']=implode(',',$npkone);
//            $data['max']=$max;
//        }else{
//            foreach ($npkone as $k=>$v){
//                $data['npkone']=$v;
//            }
//            $data['max']=$max;
//
//        }
//        //续签榜首
//        $res=array_column($pk,'xuover','name');
//        array_multisort(array_column($pk,'xuover','name'),SORT_DESC,$pk);
//        $max = $pk[0]['xuover'];
//        $npkone=array_keys($res,$max);
//        $count=count($npkone);
//        if ($count!=1){
//            $data['xuover']=implode(',',$npkone);
//            $data['xmax']=$max;
//        }else{
//            foreach ($npkone as $k=>$v){
//                $data['xuover']=$v;
//            }
//            $data['xmax']=$max;
//        }
//        //网销宝榜首
//        $res=array_column($pk,'wangover','name');
//        array_multisort(array_column($pk,'wangover','name'),SORT_DESC,$pk);
//        $max = $pk[0]['wangover'];
//        $npkone=array_keys($res,$max);
//        $count=count($npkone);
//        if ($count!=1){
//            $data['wangover']=implode(',',$npkone);
//            $data['wmax']=$max;
//        }else{
//            foreach ($npkone as $k=>$v){
//                $data['wangover']=$v;
//            }
//            $data['wmax']=$max;
//        }
//        //代运营榜首
//        $res=array_column($pk,'daiover','name');
//        array_multisort(array_column($pk,'daiover','name'),SORT_DESC,$pk);
//        $max = $pk[0]['daiover'];
//        $npkone=array_keys($res,$max);
//        $count=count($npkone);
//        if ($count!=1){
//            $data['daiover']=implode(',',$npkone);
//            $data['dmax']=$max;
//        }else{
//            foreach ($npkone as $k=>$v){
//                $data['daiover']=$v;
//            }
//            $data['dmax']=$max;
//        }
//        return $data;
    //}
    /*
     * 业绩添加
     */
    public function  addorder($data,$array=array()){
        $result=[];
        $array['per_aliorder']=$data['per_aliorder'];
        $array['per_aliname']=$data['per_aliname'];
        $array['per_daotime']=$data['per_daotime'];
        $array['per_cname']=$data['per_cname'];
        $array['per_year']=$data['per_year'];
        $array['per_sex']=$data['per_sex'];
        unset($data['per_aliorder']);
        unset($data['per_aliname']);
        unset($data['per_daotime']);
        unset($data['per_cname']);
        unset($data['per_year']);
        unset($data['per_sex']);
        foreach ($data as $k=>$v){
            $data[$k]=array_values($v);
        }
        $itemcount=count($data['per_type']);
        for ($i=0;$i<$itemcount;$i++){
            foreach ($data as $k=>$v){
                $child[$k]=$v[$i];
            }
            $result[]=$child;
        }
        $user=Session::get('user');
        $branch_id=$user['branch_id'];
        $department=$user['department'];
        if ($user['dd_postion']!="总监"){
            $per_ranks=Db::name('user')
                ->where('branch_id',$branch_id)
                ->where('department',$department)
                ->where('dd_postion','总监')
                ->field('id')
                ->find();
        }else{
            $per_ranks['id']=0;
        }
            foreach ($result as $key=>$value){
                $result[$key]['per_aliorder']=$array['per_aliorder'];
                $result[$key]['per_aliname']=$array['per_aliname'];
                $result[$key]['per_daotime']=strtotime($array['per_daotime']);
                $result[$key]['per_cname']=$array['per_cname'];
                $result[$key]['per_uid']=$user['id'];
                $result[$key]['per_username']=$user['username'];
                $result[$key]['per_bid']=$user['branch_id'];
                $result[$key]['per_rank']=$user['rank'];
                $result[$key]['per_number']=$user['dd_number'];
                $result[$key]['per_position']=$user['dd_postion'];
                $result[$key]['per_addtime']=time();
                $result[$key]['per_ranks']=$per_ranks['id'];
                $result[$key]['per_department']=$user['department'];
                $result[$key]['per_year']=$array['per_year'];
                $result[$key]['per_sex']=$array['per_sex'];
            }
        $res=Db::name('per_order')
                ->insertAll($result);
            return $res;
    }
    /*
     * 业绩展示
     */
    public  function perlists($postsearch){
        $where=[];
        if ($postsearch!=""){
            $where['per_aliorder|per_aliname|per_username']=trim($postsearch);
        }
        $user=Session::get('user');
        if ($user['dd_postion']=="总监"){
            $result['data']=Db::name('per_order')
                ->where('per_bid',$user['branch_id'])
                ->where('per_department',$user['department'])
                ->where($where)
                ->order('per_status asc')
                ->paginate(30,false,['query' => request()->param()]);
        }elseif(Session::get('admin.id')){
            $result['data']=Db::name('per_order')
                ->where($where)
                ->order('per_status asc')
                ->paginate(30,false,['query' => request()->param()]);
        }elseif($user['dd_postion']=="总经理"){
            $result['data']=Db::name('per_order')
                ->where($where)
                ->where('per_bid',$user['branch_id'])
                ->order('per_status asc')
                ->paginate(30,false,['query' => request()->param()]);
        }else{
            $result['data']=Db::name('per_order')
                ->where('per_bid',$user['branch_id'])
                ->where('per_uid',$user['id'])
                ->where($where)
                ->order('per_status asc')
                ->paginate(30,false,['query' => request()->param()]);
        }
        $result['page'] = $result['data']->render();
        return $result;
    }
    /*
     * 订单确认
     */
    public function  changeStatus1($id){
        $up['per_status']=1;
        $res=Db::name('per_order')
                ->where('per_id',$id)
                ->update($up);
        return $res;
    }
    /*
     * 订单拒绝
     */
    public function changeStatusr1($id){
        $up['per_status']=2;
        $res=Db::name('per_order')
            ->where('per_id',$id)
            ->update($up);
        return $res;
    }
    /*
     * 订单查看详情
     */
    public function uplists($id){
        $data=Db::name('per_order')
            ->where('per_id',$id)
            ->find();
        return $data;
    }
    /*
     * 执行订单修改
     */
    public function up($data){
        $id=$data['per_id'];
        unset($data['per_id']);
        $data['per_status']=0;
        $data['per_daotime']=strtotime($data['per_daotime']);
        $res=Db::name('per_order')
            ->where('per_id',$id)
            ->update($data);
        return $res;
    }

    //计算起止时间
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


}
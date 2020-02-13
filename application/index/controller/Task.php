<?php


namespace app\index\controller;
use think\Controller;
use think\Db;


class   Task extends  Controller
{
//    public function task(){
//        //当前时间
//        $time = strtotime(date('Y-m-d',time()).' 00:00:00');
//        $time1 = strtotime(date('Y-m-d',time()).' 00:00:00')+86400;
//        $ntime=date("Y-m",time());
//        $y=substr($ntime,0,4);
//        $m=substr($ntime,5,2);
//        $first=array(1,2,3);
//        $second=array(4,5,6);
//        $third=array(7,8,9);
//        $fourth=array(10,11,12);
//        if (in_array($m,$first)){
//            $da=$this->getdate($y,$first[0]);
//            $mstart=$da['firstday'];
//            $da=$this->getdate($y,$first[2]);
//            $mend=$da['lastday'];
//            $s=$y."-"."Q1";
//        }elseif(in_array($m,$second)){
//            $da=$this->getdate($y,$second[0]);
//            $mstart=$da['firstday'];
//            $da=$this->getdate($y,$second[2]);
//            $mend=$da['lastday'];
//            $s=$y."-"."Q2";
//        }elseif(in_array($m,$third)){
//            $da=$this->getdate($y,$third[0]);
//            $mstart=$da['firstday'];
//            $da=$this->getdate($y,$third[2]);
//            $mend=$da['lastday'];
//            $s=$y."-"."Q3";
//        }elseif(in_array($m,$fourth)){
//            $da=$this->getdate($y,$fourth[0]);
//            $mstart=$da['firstday'];
//            $da=$this->getdate($y,$fourth[2]);
//            $mend=$da['lastday'];
//            $s=$y."-"."Q4";
//        }
//        $da=$this->getdate($y,$m);
//        $start_time=$da['firstday'];
//        $end_time=$da['lastday'];
//        $branch=br();
//        foreach ($branch as $k=>$v){
//            $data=Db::name('achievements')
//                ->whereTime('a_time','between',["$start_time","$end_time"])
//                ->select();
//            $mdata=Db::name('achievementss')
//                ->where('s_time',$s)
//                ->select();
//            $branch[$k]['newover']=0;
//            $branch[$k]['xuqianover']=0;
//            $branch[$k]['wangxiaobaoover']=0;
//            $branch[$k]['daiyunyingover']=0;
//            $branch[$k]['mnewover']=0;
//            $branch[$k]['mxuqianover']=0;
//            $branch[$k]['mwangxiaobaoover']=0;
//            $branch[$k]['mdaiyunyingover']=0;
//            // 新签今日到单
//            $branch[$k]['newdayover']=Db::name('alimport')
//                ->whereTime('end_time','between',["$time","$time1"])
//                ->where('sales_executive','like',"%/".$v['name']."%")
//                ->where(    'new_type',"新签")
//                ->where('product_category',"诚信通服务")
//                ->count();
//            //月度完成度
//            $branch[$k]['newover']=Db::name('alimport')
//                ->whereTime('end_time','between',["$start_time","$end_time"])
//                ->where('sales_executive','like',"%/".$v['name']."%")
//                ->where('new_type',"新签")
//                ->where('product_category',"诚信通服务")
//                ->count();
//            $branch[$k]['xuqianover']=Db::name('alimport')
//                ->whereTime('end_time','between',["$start_time","$end_time"])
//                ->where('sales_executive','like',"%/".$v['name']."%")
//                ->where('new_type',"续签")
//                ->where('product_category',"诚信通服务")
//                ->count();
//            $branch[$k]['wangxiaobaoover']=Db::name('alimport')
//                ->whereTime('end_time','between',["$start_time","$end_time"])
//                ->where('sales_executive','like',"%/".$v['name']."%")
//                ->where('new_type',"新签")
//                ->where('product_category',"网销宝充值包")
//                ->count();
//            $branch[$k]['daiyunyingover']=Db::name('per_order')
//                ->where('per_bid',$v['id'])
//                ->whereTime('per_addtime','between',["$start_time","$end_time"])
//                ->where('per_type','=',4)
//                ->where('per_status','=',3)
//                ->sum('per_amounted');
//            $branch[$k]['daiyunyingover']=round($branch[$k]['daiyunyingover']/10000,2);
//            //季度完成度
//            $branch[$k]['mnewover']=Db::name('alimport')
//                ->whereTime('end_time','between',["$mstart","$mend"])
//                ->where('sales_executive','like',"%/".$v['name']."%")
//                ->where('new_type',"新签")
//                ->where('product_category',"诚信通服务")
//                ->count();
//            $branch[$k]['mxuqianover']=Db::name('alimport')
//                ->whereTime('end_time','between',["$mstart","$mend"])
//                ->where('sales_executive','like',"%/".$v['name']."%")
//                ->where('new_type',"续签")
//                ->where('product_category',"诚信通服务")
//                ->count();
//            $branch[$k]['mwangxiaobaoover']=Db::name('alimport')
//                ->whereTime('end_time','between',["$mstart","$mend"])
//                ->where('sales_executive','like',"%/".$v['name']."%")
//                ->where('new_type',"新签")
//                ->where('product_category',"网销宝充值包")
//                ->count();
//            $branch[$k]['mdaiyunyingover']=Db::name('per_order')
//                ->where('per_bid',$v['id'])
//                ->whereTime('per_addtime','between',["$mstart","$mend"])
//                ->where('per_type','=',4)
//                ->where('per_status','=',3)
//                ->sum('per_amounted');
//            $branch[$k]['mdaiyunyingover']=round($branch[$k]['mdaiyunyingover']/10000,2);
//            //月度任务量
//            $branch[$k]['new']=0;
//            $branch[$k]['xuqian']=0;
//            $branch[$k]['wangxiaobao']=0;
//            $branch[$k]['daiyunying']=0;
//            //季度任务量
//            $branch[$k]['mnew']=0;
//            $branch[$k]['mxuqian']=0;
//            $branch[$k]['mwangxiaobao']=0;
//            $branch[$k]['mdaiyunying']=0;
//            //print_r($data);die;
//            foreach ($data as $ke=>$val){
//                if ($val['branch_id']==$v['id']){
//                    if ($val['offic_id']==2){
//                        $branch[$k]['new']=$val['task_id'];
//                    }elseif ($val['offic_id']==3){
//                        $branch[$k]['xuqian']=$val['task_id'];
//                    }elseif ($val['offic_id']==4){
//                        $branch[$k]['wangxiaobao']=$val['task_id'];
//                    }elseif ($val['offic_id']==8){
//                        $branch[$k]['daiyunying']=$val['task_id'];
//                    }
//                }
//            }
//            foreach ($mdata as $kes=>$values){
//                if ($values['branch_id']==$v['id']){
//                    if ($values['offic_id']==2){
//                        $branch[$k]['mnew']=$values['task_id'];
//                    }elseif ($values['offic_id']==3){
//                        $branch[$k]['mxuqian']=$values['task_id'];
//                    }elseif ($values['offic_id']==4){
//                        $branch[$k]['mwangxiaobao']=$values['task_id'];
//                    }elseif ($values['offic_id']==8){
//                        $branch[$k]['mdaiyunying']=$values['task_id'];
//                    }
//                }
//            }
//            for ($i = 1; $i <= 12; $i++){
//                if ($i<10){
//                    $i="0".$i;
//                }
//                $months[][] = date("Y")."-".$i;
//            }
//            foreach($months as $key=>$val){
//                $y=substr($val[0],0,4);
//                $m=substr($val[0],-2,2);
//                $branch[$k]['newyear'][]=$this->getdate($y,$m);
//                $branch[$k]['xumonthyear'][]=$this->getdate($y,$m);
//                $branch[$k]['xumonthyearto'][]=$this->getdate($y,$m);
//                $branch[$k]['newyear'][$key]=Db::name('alimport')
//                    ->whereTime('end_time','between',[$branch[$k]['newyear'][$key]['firstday'],$branch[$k]['newyear'][$key]['lastday']])
//                    ->where('sales_executive','like',"%/".$v['name']."%")
//                    ->where('new_type',"新签")
//                    ->where('product_category',"诚信通服务")
//                    ->count();
//                $branch[$k]['xumonthyear'][$key]=Db::name('alimport')
//                    ->whereTime('end_time','between',[$branch[$k]['xumonthyear'][$key]['firstday'],$branch[$k]['xumonthyear'][$key]['lastday']])
//                    ->where('sales_executive','like',"%/".$v['name']."%")
//                    ->where('new_type',"续签")
//                    ->where('product_category',"诚信通服务")
//                    ->count();
//                $branch[$k]['xumonthyearto'][$key]=Db::name('achievements')
//                    ->where('a_time',$branch[$k]['xumonthyearto'][$key]['firstday'])
//                    ->where('branch_id',$v['id'])
//                    ->where('offic_id',3)
//                    ->value('task_id');
//                if ($branch[$k]['xumonthyearto'][$key]==""){
//                    $branch[$k]['xumonthyearto'][$key]=0;
//                }
//                if($branch[$k]['xumonthyearto'][$key]==0){
//                    $branch[$k]['xulv'][$key]=0;
//                }else{
//                    $branch[$k]['xulv'][$key]=round($branch[$k]['xumonthyear'][$key]/$branch[$k]['xumonthyearto'][$key],2)*100;
//                }
//            }
//
//        }
//
//        print_r($branch[1]);die;
//
//    }
    public function task(){
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
            $s=$y."-"."Q1";
        }elseif(in_array($m,$second)){
            $da=$this->getdate($y,$second[0]);
            $mstart=$da['firstday'];
            $da=$this->getdate($y,$second[2]);
            $mend=$da['lastday'];
            $s=$y."-"."Q2";
        }elseif(in_array($m,$third)){
            $da=$this->getdate($y,$third[0]);
            $mstart=$da['firstday'];
            $da=$this->getdate($y,$third[2]);
            $mend=$da['lastday'];
            $s=$y."-"."Q3";
        }elseif(in_array($m,$fourth)){
            $da=$this->getdate($y,$fourth[0]);
            $mstart=$da['firstday'];
            $da=$this->getdate($y,$fourth[2]);
            $mend=$da['lastday'];
            $s=$y."-"."Q4";
        }
        $da=$this->getdate($y,$m);
        $start_time=$da['firstday'];
        $end_time=$da['lastday'];
        $branch=br();
        foreach ($branch as $k=>$v){
            $data=Db::name('achievements')
                ->whereTime('a_time','between',["$start_time","$end_time"])
                ->select();
            $mdata=Db::name('achievementss')
                ->where('s_time',$s)
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
                ->where('new_type',"新签")
                ->where('product_category',"网销宝充值包")
                ->count();
            $branch[$k]['daiyunyingover']=Db::name('per_order')
                ->where('per_bid',$v['id'])
                ->whereTime('per_addtime','between',["$start_time","$end_time"])
                ->where('per_type','=',4)
                ->where('per_status','=',3)
                ->sum('per_amounted');
            $branch[$k]['daiyunyingover']=round($branch[$k]['daiyunyingover']/10000,2);
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
                ->where('new_type',"新签")
                ->where('product_category',"网销宝充值包")
                ->count();
            $branch[$k]['mdaiyunyingover']=Db::name('per_order')
                ->where('per_bid',$v['id'])
                ->whereTime('per_addtime','between',["$mstart","$mend"])
                ->where('per_type','=',4)
                ->where('per_status','=',3)
                ->sum('per_amounted');
            $branch[$k]['mdaiyunyingover']=round($branch[$k]['mdaiyunyingover']/10000,2);
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
                    }elseif ($val['offic_id']==8){
                        $branch[$k]['daiyunying']=$val['task_id'];
                    }
                }
            }
            foreach ($mdata as $kes=>$values){
                if ($values['branch_id']==$v['id']){
                    if ($values['offic_id']==2){
                        $branch[$k]['mnew']=$values['task_id'];
                    }elseif ($values['offic_id']==3){
                        $branch[$k]['mxuqian']=$values['task_id'];
                    }elseif ($values['offic_id']==4){
                        $branch[$k]['mwangxiaobao']=$values['task_id'];
                    }elseif ($values['offic_id']==8){
                        $branch[$k]['mdaiyunying']=$values['task_id'];
                    }
                }
            }
        }
        //合计

        //月度目标合计
        $new=0;
        $xuqian=0;
        $wangxiaobao=0;
        $daiyunying=0;
        //月度到单合计
        $newovernum=0;
        $xuqianovernum=0;
        $wangxiaobaoovernum=0;
        $daiyunyingovernum=0;
        //季度到单合计
        $mnewovernum=0;
        $mxuqianovernum=0;
        $mwangxiaobaoovernum=0;
        $mdaiyunyingovernum=0;
        //季度目标合计
        $mnewnum=0;
        $mxuqiannum=0;
        $mwangxiaobaonum=0;
        $mdaiyunyingnum=0;

        foreach ($branch as $kk=>$vv){
            $new+=$vv['new'];
            $xuqian+=$vv['xuqian'];
            $wangxiaobao+=$vv['wangxiaobao'];
            $daiyunying+=$vv['daiyunying'];
            $newovernum+=$vv['newover'];
            $xuqianovernum+=$vv['xuqianover'];
            $wangxiaobaoovernum+=$vv['wangxiaobaoover'];
            $daiyunyingovernum+=$vv['daiyunyingover'];
            $mnewovernum+=$vv['mnewover'];
            $mxuqianovernum+=$vv['mxuqianover'];
            $mwangxiaobaoovernum+=$vv['mwangxiaobaoover'];
            $mdaiyunyingovernum+=$vv['mdaiyunyingover'];
            $mnewnum+=$vv['mnew'];
            $mxuqiannum+=$vv['mxuqian'];
            $mwangxiaobaonum+=$vv['mwangxiaobao'];
            $mdaiyunyingnum+=$vv['mdaiyunying'];
        }
        $newtime=Db::name('import_log')
            ->order('time desc')
            ->field('time')
            ->find();
        $this->assign('data',$branch);
        return $this->fetch('ltask',[
            'new'=>$new,
            'xuqian'=>$xuqian,
            'wangxiaobao'=>$wangxiaobao,
            'daiyunying'=>$daiyunying,
            'newovernum'=>$newovernum,
            'xuqianovernum'=>$xuqianovernum,
            'wangxiaobaoovernum'=>$wangxiaobaoovernum,
            'daiyunyingovernum'=>$daiyunyingovernum,
            'mnewovernum'=>$mnewovernum,
            'mxuqianovernum'=>$mxuqianovernum,
            'mwangxiaobaoovernum'=>$mwangxiaobaoovernum,
            'mdaiyunyingovernum'=>$mdaiyunyingovernum,
            'mnewnum'=>$mnewnum,
            'mxuqiannum'=>$mxuqiannum,
            'mwangxiaobaonum'=>$mwangxiaobaonum,
            'mdaiyunyingnum'=>$mdaiyunyingnum,
            'newtime'=>$newtime
        ]);
    }
    //新签排行榜
    public function newtasksort(){
        //当前时间
        $ntime=date("Y-m",time());
        $y=substr($ntime,0,4);
        $m=substr($ntime,5,2);
        $da=$this->getdate($y,$m);
        $start_time=$da['firstday'];
        $end_time=$da['lastday'];
        $branch=br();
        foreach ($branch as $k=>$v){
            $branch[$k]['newover']=0;
            //月度完成度
            $branch[$k]['newover']=Db::name('alimport')
                ->whereTime('end_time','between',["$start_time","$end_time"])
                ->where('sales_executive','like',"%/".$v['name']."%")
                ->where('new_type',"新签")
                ->where('product_category',"诚信通服务")
                ->count();
        }
        $newall=0;
        foreach ($branch as $key=>$val){
            $newall+=$val['newover'];
        }
        foreach ($branch as $kk=>$vv){
            $branch[$kk]['newall']=$newall;
            if($newall==0){
                $branch[$kk]['zb']=0;
            }else{
                $branch[$kk]['zb']=round($branch[$kk]['newover']/$newall,2)*100;
            }
        }
        $newover=array_column($branch,'newover');
        array_multisort($newover,SORT_DESC,$branch);
        $branch=array_slice($branch,0,3);
        return $branch;
    }
    //续签排行榜
    public function xutasksort(){
        //当前时间
        $ntime=date("Y-m",time());
        $y=substr($ntime,0,4);
        $m=substr($ntime,5,2);
        $da=$this->getdate($y,$m);
        $start_time=$da['firstday'];
        $end_time=$da['lastday'];
        $branch=br();
        foreach ($branch as $k=>$v){
            $branch[$k]['xuqianover']=0;
            //月度完成度
            $branch[$k]['xuqianover']=Db::name('alimport')
                ->whereTime('end_time','between',["$start_time","$end_time"])
                ->where('sales_executive','like',"%/".$v['name']."%")
                ->where('new_type',"续签")
                ->where('product_category',"诚信通服务")
                ->count();

        }
        $xuall=0;
        foreach ($branch as $key=>$val){
            $xuall+=$val['xuqianover'];
        }
        foreach ($branch as $kk=>$vv){
            if($xuall==0){
                $branch[$kk]['zb']=0;
            }else{
                $branch[$kk]['zb']=round($branch[$kk]['xuqianover']/$xuall,2)*100;
            }
        }
        $xuqianover=array_column($branch,'xuqianover');
        array_multisort($xuqianover,SORT_DESC,$branch);
        $branch=array_slice($branch,0,3);
        return $branch;
    }
    //网销宝排行榜
    public function wangtasksort(){
        //当前时间
        $ntime=date("Y-m",time());
        $y=substr($ntime,0,4);
        $m=substr($ntime,5,2);
        $da=$this->getdate($y,$m);
        $start_time=$da['firstday'];
        $end_time=$da['lastday'];
        $branch=br();
        $wangxiaobaoover=Db::name('alimport')
            ->whereTime('end_time','between',["$start_time","$end_time"])
            ->where('new_type',"新签")
            ->where('product_category',"网销宝充值包")
            ->count();
        foreach ($branch as $k=>$v){
            $branch[$k]['wangxiaobaoover']=0;
            //月度完成度
            $branch[$k]['wangxiaobaoover']=Db::name('alimport')
                ->whereTime('end_time','between',["$start_time","$end_time"])
                ->where('sales_executive','like',"%/".$v['name']."%")
                ->where('new_type',"新签")
                ->where('product_category',"网销宝充值包")
                ->count();
            if ($wangxiaobaoover==0){
                $branch[$k]['zb']=0;
            }else{
                $branch[$k]['zb']=round($branch[$k]['wangxiaobaoover']/$wangxiaobaoover,2)*100;
            }
        }
        $wangxiaobaoover=array_column($branch,'wangxiaobaoover');
        array_multisort($wangxiaobaoover,SORT_DESC,$branch);
        $branch=array_slice($branch,0,3);
        return $branch;
    }
    //代运营排行榜
    public function daitasksort(){
        //当前时间
        $ntime=date("Y-m",time());
        $y=substr($ntime,0,4);
        $m=substr($ntime,5,2);
        $da=$this->getdate($y,$m);
        $start_time=$da['firstday'];
        $end_time=$da['lastday'];
        $branch=br();
        $daiyunyingover=Db::name('per_order')
            ->whereTime('per_addtime','between',["$start_time","$end_time"])
            ->where('per_type','=',4)
            ->where('per_status','=',3)
            ->sum('per_amounted');
        foreach ($branch as $k=>$v){
            $branch[$k]['daiyunyingover']=0;
            //月度完成度
            $branch[$k]['daiyunyingover1']=Db::name('per_order')
                ->where('per_bid',$v['id'])
                ->whereTime('per_addtime','between',["$start_time","$end_time"])
                ->where('per_type','=',4)
                ->where('per_status','=',3)
                ->sum('per_amounted');
            $branch[$k]['daiyunyingover']=round($branch[$k]['daiyunyingover1']/10000,2);
            if ($daiyunyingover==0){
                $branch[$k]['zb']=0;
            }else{
                $branch[$k]['zb']=round($branch[$k]['daiyunyingover1']/$daiyunyingover,2)*100;
            }
        }
        $daiyunyingover=array_column($branch,'daiyunyingover1');
        array_multisort($daiyunyingover,SORT_DESC,$branch);
        $branch=array_slice($branch,0,3);
        return $branch;
    }
    //新签月度总和
    public function allnew()
    {
        //当前时间
        $ntime = date("Y-m", time());
        $y = substr($ntime, 0, 4);
        $m = substr($ntime, 5, 2);
        $da = $this->getdate($y, $m);
        $start_time = $da['firstday'];
        $end_time = $da['lastday'];
        $branch = br();
        $newsum = Db::name('alimport')
            ->whereTime('end_time', 'between', ["$start_time", "$end_time"])
            ->where('new_type', "新签")
            ->where('product_category', "诚信通服务")
            ->count();
        foreach ($branch as $k => $v) {
            $branch[$k]['newover'] = 0;
            //月度完成度
            $branch[$k]['newover'] = Db::name('alimport')
                ->whereTime('end_time', 'between', ["$start_time", "$end_time"])
                ->where('sales_executive', 'like', "%/" . $v['name'] . "%")
                ->where('new_type', "新签")
                ->where('product_category', "诚信通服务")
                ->count();
        }
    }
    public function sjz(){
        //石家庄分公司
        $data=array();
        $time = strtotime(date('Y-m-d',time()).' 00:00:00');
        $time1 = strtotime(date('Y-m-d',time()).' 00:00:00')+86400;
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
            $s=$y."-"."Q1";
        }elseif(in_array($m,$second)){
            $da=$this->getdate($y,$second[0]);
            $mstart=$da['firstday'];
            $da=$this->getdate($y,$second[2]);
            $mend=$da['lastday'];
            $s=$y."-"."Q2";
        }elseif(in_array($m,$third)){
            $da=$this->getdate($y,$third[0]);
            $mstart=$da['firstday'];
            $da=$this->getdate($y,$third[2]);
            $mend=$da['lastday'];
            $s=$y."-"."Q3";
        }elseif(in_array($m,$fourth)){
            $da=$this->getdate($y,$fourth[0]);
            $mstart=$da['firstday'];
            $da=$this->getdate($y,$fourth[2]);
            $mend=$da['lastday'];
            $s=$y."-"."Q4";
        }
        $da=$this->getdate($y,$m);
        $start_time=$da['firstday'];
        $end_time=$da['lastday'];
        //新签当日到单
        $data['newdayover']=Db::name('alimport')
            ->whereTime('end_time','between',["$time","$time1"])
            ->where('sales_executive','like',"%/"."石家庄"."%")
            ->where('new_type',"新签")
            ->where('product_category',"诚信通服务")
            ->count();
        //新签月度到单
        $data['newmonthover']=Db::name('alimport')
            ->whereTime('end_time','between',["$start_time","$end_time"])
            ->where('sales_executive','like',"%/"."石家庄"."%")
            ->where('new_type',"新签")
            ->where('product_category',"诚信通服务")
            ->count();
        //新签季度到单
        $data['newseasonover']=Db::name('alimport')
            ->whereTime('end_time','between',["$mstart","$mend"])
            ->where('sales_executive','like',"%/"."石家庄"."%")
            ->where('new_type',"新签")
            ->where('product_category',"诚信通服务")
            ->count();
        //新签季度目标
        $data['newseasonto']=Db::name('achievementss')
            ->where('s_time',$s)
            ->where('branch_id',1)
            ->where('offic_id',2)
            ->value('task_id');
        if ($data['newseasonto']==""){
            $data['newseasonto']=0;
        }else{
            $data['newseasonto']=$data['newseasonto'];
        }
        for ($i = 1; $i <= 12; $i++){
            if ($i<10){
                $i="0".$i;
            }
            $months[][] = date("Y")."-".$i;
        }
        foreach($months as $key=>$val){
            $y=substr($val[0],0,4);
            $m=substr($val[0],-2,2);
            $data['newyear'][]=$this->getdate($y,$m);
            $data['xumonthyear'][]=$this->getdate($y,$m);
            $data['xumonthyearto'][]=$this->getdate($y,$m);
            $data['newyear'][$key]=Db::name('alimport')
                ->whereTime('end_time','between',[$data['newyear'][$key]['firstday'],$data['newyear'][$key]['lastday']])
                ->where('sales_executive','like',"%/"."石家庄"."%")
                ->where('new_type',"新签")
                ->where('product_category',"诚信通服务")
                ->count();
            $data['xumonthyear'][$key]=Db::name('alimport')
                ->whereTime('end_time','between',[$data['xumonthyear'][$key]['firstday'],$data['xumonthyear'][$key]['lastday']])
                ->where('sales_executive','like',"%/"."石家庄"."%")
                ->where('new_type',"续签")
                ->where('product_category',"诚信通服务")
                ->count();
            $data['xumonthyearto'][$key]=Db::name('achievements')
                ->where('a_time',$data['xumonthyearto'][$key]['firstday'])
                ->where('branch_id',1)
                ->where('offic_id',3)
                ->value('task_id');
            if ($data['xumonthyearto'][$key]==""){
                $data['xumonthyearto'][$key]=0;
            }
            if($data['xumonthyearto'][$key]==0){
                $data['xulv'][$key]=0;
            }else{
                $data['xulv'][$key]=round($data['xumonthyear'][$key]/$data['xumonthyearto'][$key],2)*100;
            }
        }
        unset($data['xumonthyearto']);
        unset($data['xumonthyear']);
        //续签本月到单
        $data['xumonth']=Db::name('alimport')
            ->whereTime('before_time','between',["$start_time","$end_time"])
            ->where('sales_executive','like',"%/"."石家庄"."%")
            ->where('new_type',"续签")
            ->where('product_category',"诚信通服务")
            ->count();
        //续签本月目标
        $data['xumonthto']=Db::name('achievements')
            ->where('a_time',strtotime($ntime))
            ->where('branch_id',1)
            ->where('offic_id',3)
            ->value('task_id');
        if ($data['xumonthto']==""){
            $data['xumonthto']=0;
        }else{
            $data['xumonthto']=$data['xumonthto'];
        }
        //续签M月续费率
        if ($data['xumonthto']==0){
            $data['xm']=0;
        }else{
            $data['xm']=round($data['xumonth']/$data['xumonthto'],2)*100    ;
        }
        //续签M+1月续费率
            //续签下个月的续费单数
        $start_time1=date('Y-m-d H:i:s',$start_time);
        $end_time1=date('Y-m-d H:i:s',$end_time);
        $m1s=strtotime(date('Y-m-d H:i:s',strtotime("$start_time1+1month")));
        $m1e=strtotime(date('Y-m-d H:i:s',strtotime("$end_time1+1month")));
        //下个月完成单数
        $next=Db::name('alimport')
            ->whereTime('before_time','between',["$m1s","$m1e"])
            ->where('sales_executive','like',"%/"."石家庄"."%")
            ->where('new_type',"续签")
            ->where('product_category',"诚信通服务")
            ->count();
        //M+1月任务
        $nextover=Db::name('achievements')
            ->where('a_time',$m1s)
            ->where('branch_id',1)
            ->where('offic_id',3)
            ->value('task_id');
        if ($nextover==""){
            $data['xm1']=0;
        }else{
            $data['xm1']=round($next/$nextover,2)*100   ;
        }
        //续签M+2月续费率
        $start_time2=date('Y-m-d H:i:s',$start_time);
        $end_time2=date('Y-m-d H:i:s',$end_time);
        $m2s=strtotime(date('Y-m-d H:i:s',strtotime("$start_time2+2month")));
        $m2e=strtotime(date('Y-m-d H:i:s',strtotime("$end_time2+2month")));
        //续签M+2的续费单数
        $next2=Db::name('alimport')
            ->whereTime('before_time','between',["$m2s","$m2e"])
            ->where('sales_executive','like',"%/"."石家庄"."%")
            ->where('new_type',"续签")
            ->where('product_category',"诚信通服务")
            ->count();
        //M+2月任务
        $next2over=Db::name('achievements')
            ->where('a_time',strtotime($m2s))
            ->where('branch_id',1)
            ->where('offic_id',3)
            ->value('task_id');
        if ($next2over==""){
            $data['xm2']=0;
        }else{
            $data['xm2']=round($next2/$next2over,2)*100   ;
        }
        //续签M+3月续费率
        $start_time3=date('Y-m-d H:i:s',$start_time);
        $end_time3=date('Y-m-d H:i:s',$end_time);
        $m3s=strtotime(date('Y-m-d H:i:s',strtotime("$start_time3+3month")));
        $m3e=strtotime(date('Y-m-d H:i:s',strtotime("$end_time3+3month")));
        //续签M+3的续费单数
        $next3=Db::name('alimport')
            ->whereTime('before_time','between',["$m3s","$m3e"])
            ->where('sales_executive','like',"%/"."石家庄"."%")
            ->where('new_type',"续签")
            ->where('product_category',"诚信通服务")
            ->count();
        //M+3月任务
        $next3over=Db::name('achievements')
            ->where('a_time',strtotime($m3s))
            ->where('branch_id',1)
            ->where('offic_id',3)
            ->value('task_id');
        if ($next3over==""){
            $data['xm3']=0;
        }else{
            $data['xm3']=round($next3/$next3over,2)*100   ;
        }
        //大数据统计分析
        //全部新签本月累计销售业绩
        $data['allnew']=Db::name('alimport')
            ->whereTime('end_time','between',["$start_time","$end_time"])
            ->where('new_type',"新签")
            ->where('product_category',"诚信通服务")
            ->count();
        //print_r($data['allnew']);die;
        ////全部代运营本月累计销售业绩
        $data['alldai']=Db::name('per_order')
            ->whereTime('per_addtime','between',["$start_time","$end_time"])
            ->where('per_type','=',4)
            ->where('per_status','=',3)
            ->sum('per_amounted');
        $data['alldai']=round($data['alldai']/10000,2);
        $data['allnewp4p']=Db::name('alimport')
            ->whereTime('single_time','between',["$start_time","$end_time"])
            ->where('new_type',"新签")
            ->where('product_category',"网销宝充值包")
            ->sum('account_money');
        $data['allnewp4p']=round($data['allnewp4p']/10000,2);
        //代运营本月销售业绩
        $data['dai']=Db::name('per_order')
            ->where('per_bid',1)
            ->whereTime('per_addtime','between',["$start_time","$end_time"])
            ->where('per_type','=',4)
            ->where('per_status','=',3)
            ->sum('per_amounted');
        $data['dai']=round($data['dai']/10000,2);
        //代运营本月销售业绩目标
        $data['daito']=Db::name('achievements')
            ->where('branch_id',1)
            ->where('offic_id',8)
            ->whereTime('a_time','between',["$start_time","$end_time"])
            ->value('task_id');
        if ($data['daito']==""){
            $data['daito']=0;
        }else{
            $data['daito']=$data['daito'];
        }
        //代运营本季度销售业绩
        $data['daiji']=Db::name('per_order')
            ->where('per_bid',1)
            ->whereTime('per_addtime','between',["$mstart","$mend"])
            ->where('per_type','=',4)
            ->where('per_status','=',3)
            ->sum('per_amounted');
        $data['daiji']=round($data['daiji']/10000,2);
        //代运营本季度目标
        $data['daijito']=Db::name('achievementss')
            ->where('branch_id',1)
            ->where('offic_id',8)
            ->where('s_time',$s)
            ->value('task_id');
        if ($data['daijito']==""){
            $data['daijito']=0;
        }else{
            $data['daijito']=$data['daijito'];
        }
        //集团所有分公司
        //续签本月到单
        $data['xumonth1']=Db::name('alimport')
            ->whereTime('before_time','between',["$start_time","$end_time"])
            ->where('new_type',"续签")
            ->where('product_category',"诚信通服务")
            ->count();
        //续签本月目标
        $data['xumonthto1']=Db::name('achievements')
            ->where('a_time',strtotime($ntime))
            ->where('offic_id',3)
            ->sum('task_id');
        if ($data['xumonthto1']==""){
            $data['xumonthto1']=0;
        }else{
            $data['xumonthto1']=$data['xumonthto1'];
        }
        //续签M月续费率
        if ($data['xumonthto1']==0){
            $data['zxm']=0;
        }else{
            $data['zxm']=round($data['xumonth1']/$data['xumonthto1'],2)*100    ;
        }
        unset($data['xumonth1']);
        unset($data['xumonthto1']);
        //下个月完成单数
        $next1=Db::name('alimport')
            ->whereTime('before_time','between',["$m1s","$m1e"])
            ->where('new_type',"续签")
            ->where('product_category',"诚信通服务")
            ->count();
        //M+1月任务
        $nextover1=Db::name('achievements')
            ->where('a_time',$m1s)
            ->where('offic_id',3)
            ->sum('task_id');
        if ($nextover1==""){
            $data['zxm1']=0;
        }else{
            $data['zxm1']=round($next1/$nextover1,2)*100   ;
        }
        //续签M+2的续费单数
        $next3=Db::name('alimport')
            ->whereTime('before_time','between',["$m2s","$m2e"])
            ->where('sales_executive','like',"%/"."石家庄"."%")
            ->where('new_type',"续签")
            ->where('product_category',"诚信通服务")
            ->count();
        //M+2月任务
        $next3over=Db::name('achievements')
            ->where('a_time',$m2s)
            ->where('branch_id',1)
            ->where('offic_id',3)
            ->value('task_id');
        if ($next3over==""){
            $data['zxm2']=0;
        }else{
            $data['zxm2']=round($next3/$next3over,2)*100   ;
        }
        //分公司网销宝本月到单
        $data['mp4p']=Db::name('alimport')
                ->whereTime('end_time','between',["$start_time","$end_time"])
                ->where('sales_executive','like',"%/"."石家庄"."%")
                ->where('new_type',"新签")
                ->where('product_category',"网销宝充值包")
                ->count();
        //分公司网销宝本月目标
        $data['mp4pto']=Db::name('achievements')
                ->where('branch_id',1)
                ->where('offic_id',4)
                ->whereTime('a_time','between',["$start_time","$end_time"])
                ->value('task_id');
        if ($data['mp4pto']==""){
            $data['mp4pto']=0;
        }else{
            $data['mp4pto']=$data['mp4pto'];
        }
        //分公司网销宝本季到单
        $data['sp4p']=Db::name('alimport')
                ->whereTime('end_time','between',["$mstart","$mend"])
            ->where('sales_executive','like',"%/"."石家庄"."%")
                ->where('new_type',"新签")
                ->where('product_category',"网销宝充值包")
                ->count();
        //分公司网销宝本季目标
        $data['sp4pto']=Db::name('achievementss')
                ->where('s_time',$s)
                ->where('branch_id',1)
                ->where('offic_id',4)
                ->value('task_id');
        if ($data['sp4pto']==""){
            $data['sp4pto']=0;
        }else{
            $data['sp4pto']=$data['sp4pto'];
        }
        //分公司网销宝今日引新
        $data['dnp4p']=Db::name('alimport')
            ->whereTime('end_time','between',["$time","$time1"])
            ->where('sales_executive','like',"%/"."石家庄"."%")
            ->where('new_type',"新签")
            ->where('product_category',"网销宝充值包")
            ->count();
        //分公司网销宝今日消耗
        $data['dxp4p']=Db::name('alimport')
            ->whereTime('end_time','between',["$time","$time1"])
            ->where('sales_executive','like',"%/"."石家庄"."%")
            ->where('new_type',"续签")
            ->where('product_category',"网销宝充值包")
            ->count();
        //分公司网销宝本月消耗
        $data['mxp4p']=Db::name('alimport')
            ->whereTime('end_time','between',["$start_time","$end_time"])
            ->where('sales_executive','like',"%/"."石家庄"."%")
            ->where('new_type',"续签")
            ->where('product_category',"网销宝充值包")
            ->count();
        //新签排行榜
        $data['newsort']=$this->newtasksort();
        //续签排行榜
        $data['xusort']=$this->xutasksort();
        //网销宝排行榜
        $data['wangtasksort']=$this->wangtasksort();
        //代运营排行榜
        $data['daitasksort']=$this->daitasksort();
        //当前月份
        $data['yy']=date("m");
        //代运营柱状图
        for ($i = 0; $i < 6; $i++){
            $daimoth[][] = date("Y-m", strtotime("-$i months"));
        }
        foreach ($daimoth as $k1=>$v1){
            $y=substr($v1[0],0,4);
            $m=substr($v1[0],-2,2);
            $daimoth[$k1]=$this->getdate($y,$m);
            $data['daiz'][]=Db::name('per_order')
                ->where('per_bid',1)
                ->whereTime('per_addtime','between',[$daimoth[$k1]['firstday'],$daimoth[$k1]['lastday']])
                ->where('per_type','=',4)
                ->where('per_status','=',3)
                ->sum('per_amounted');
        }
        $data['ntime']=Db::name('import_log')
            ->order('time desc')
            ->field('time')
            ->find();
        $data['city']="石家庄";
        $this->assign('data',$data);
        return $this->fetch('task');

//        return $this->fetch('task');
        //return $data;
    }
    public function hs(){
        $city=input('city');
        $bran=input('bran');
        //石家庄分公司
        $data=array();
        $time = strtotime(date('Y-m-d',time()).' 00:00:00');
        $time1 = strtotime(date('Y-m-d',time()).' 00:00:00')+86400;
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
            $s=$y."-"."Q1";
        }elseif(in_array($m,$second)){
            $da=$this->getdate($y,$second[0]);
            $mstart=$da['firstday'];
            $da=$this->getdate($y,$second[2]);
            $mend=$da['lastday'];
            $s=$y."-"."Q2";
        }elseif(in_array($m,$third)){
            $da=$this->getdate($y,$third[0]);
            $mstart=$da['firstday'];
            $da=$this->getdate($y,$third[2]);
            $mend=$da['lastday'];
            $s=$y."-"."Q3";
        }elseif(in_array($m,$fourth)){
            $da=$this->getdate($y,$fourth[0]);
            $mstart=$da['firstday'];
            $da=$this->getdate($y,$fourth[2]);
            $mend=$da['lastday'];
            $s=$y."-"."Q4";
        }
        $da=$this->getdate($y,$m);
        $start_time=$da['firstday'];
        $end_time=$da['lastday'];
        //新签当日到单
        $data['newdayover']=Db::name('alimport')
            ->whereTime('end_time','between',["$time","$time1"])
            ->where('sales_executive','like',"%/".$city."%")
            ->where('new_type',"新签")
            ->where('product_category',"诚信通服务")
            ->count();
        //新签月度到单
        $data['newmonthover']=Db::name('alimport')
            ->whereTime('end_time','between',["$start_time","$end_time"])
            ->where('sales_executive','like',"%/".$city."%")
            ->where('new_type',"新签")
            ->where('product_category',"诚信通服务")
            ->count();
        //新签季度到单
        $data['newseasonover']=Db::name('alimport')
            ->whereTime('end_time','between',["$mstart","$mend"])
            ->where('sales_executive','like',"%/".$city."%")
            ->where('new_type',"新签")
            ->where('product_category',"诚信通服务")
            ->count();
        //新签季度目标
        $data['newseasonto']=Db::name('achievementss')
            ->where('s_time',$s)
            ->where('branch_id',$bran)
            ->where('offic_id',2)
            ->value('task_id');
        if ($data['newseasonto']==""){
            $data['newseasonto']=0;
        }else{
            $data['newseasonto']=$data['newseasonto'];
        }
        for ($i = 1; $i <= 12; $i++){
            if ($i<10){
                $i="0".$i;
            }
            $months[][] = date("Y")."-".$i;
        }
        foreach($months as $key=>$val){
            $y=substr($val[0],0,4);
            $m=substr($val[0],-2,2);
            $data['newyear'][]=$this->getdate($y,$m);
            $data['xumonthyear'][]=$this->getdate($y,$m);
            $data['xumonthyearto'][]=$this->getdate($y,$m);
            $data['newyear'][$key]=Db::name('alimport')
                ->whereTime('end_time','between',[$data['newyear'][$key]['firstday'],$data['newyear'][$key]['lastday']])
                ->where('sales_executive','like',"%/".$city."%")
                ->where('new_type',"新签")
                ->where('product_category',"诚信通服务")
                ->count();
            $data['xumonthyear'][$key]=Db::name('alimport')
                ->whereTime('end_time','between',[$data['xumonthyear'][$key]['firstday'],$data['xumonthyear'][$key]['lastday']])
                ->where('sales_executive','like',"%/".$city."%")
                ->where('new_type',"续签")
                ->where('product_category',"诚信通服务")
                ->count();
            $data['xumonthyearto'][$key]=Db::name('achievements')
                ->where('a_time',$data['xumonthyearto'][$key]['firstday'])
                ->where('branch_id',$bran)
                ->where('offic_id',3)
                ->value('task_id');
            if ($data['xumonthyearto'][$key]==""){
                $data['xumonthyearto'][$key]=0;
            }
            if($data['xumonthyearto'][$key]==0){
                $data['xulv'][$key]=0;
            }else{
                $data['xulv'][$key]=round($data['xumonthyear'][$key]/$data['xumonthyearto'][$key],2)*100;
            }
        }
        unset($data['xumonthyearto']);
        unset($data['xumonthyear']);
        //续签本月到单
        $data['xumonth']=Db::name('alimport')
            ->whereTime('before_time','between',["$start_time","$end_time"])
            ->where('sales_executive','like',"%/".$city."%")
            ->where('new_type',"续签")
            ->where('product_category',"诚信通服务")
            ->count();
        //续签本月目标
        $data['xumonthto']=Db::name('achievements')
            ->where('a_time',strtotime($ntime))
            ->where('branch_id',$bran)
            ->where('offic_id',3)
            ->value('task_id');
        if ($data['xumonthto']==""){
            $data['xumonthto']=0;
        }else{
            $data['xumonthto']=$data['xumonthto'];
        }
        //续签M月续费率
        if ($data['xumonthto']==0){
            $data['xm']=0;
        }else{
            $data['xm']=round($data['xumonth']/$data['xumonthto'],2)*100    ;
        }
        //续签M+1月续费率
        //续签下个月的续费单数
        $start_time1=date('Y-m-d H:i:s',$start_time);
        $end_time1=date('Y-m-d H:i:s',$end_time);
        $m1s=strtotime(date('Y-m-d H:i:s',strtotime("$start_time1+1month")));
        $m1e=strtotime(date('Y-m-d H:i:s',strtotime("$end_time1+1month")));
        //下个月完成单数
        $next=Db::name('alimport')
            ->whereTime('before_time','between',["$m1s","$m1e"])
            ->where('sales_executive','like',"%/".$city."%")
            ->where('new_type',"续签")
            ->where('product_category',"诚信通服务")
            ->count();
        //M+1月任务
        $nextover=Db::name('achievements')
            ->where('a_time',$m1s)
            ->where('branch_id',$bran)
            ->where('offic_id',3)
            ->value('task_id');
        if ($nextover==""){
            $data['xm1']=0;
        }else{
            $data['xm1']=round($next/$nextover,2)*100   ;
        }
        //续签M+2月续费率
        $start_time2=date('Y-m-d H:i:s',$start_time);
        $end_time2=date('Y-m-d H:i:s',$end_time);
        $m2s=strtotime(date('Y-m-d H:i:s',strtotime("$start_time2+2month")));
        $m2e=strtotime(date('Y-m-d H:i:s',strtotime("$end_time2+2month")));
        //续签M+2的续费单数
        $next2=Db::name('alimport')
            ->whereTime('before_time','between',["$m2s","$m2e"])
            ->where('sales_executive','like',"%/".$city."%")
            ->where('new_type',"续签")
            ->where('product_category',"诚信通服务")
            ->count();
        //M+2月任务
        $next2over=Db::name('achievements')
            ->where('a_time',strtotime($m2s))
            ->where('branch_id',$bran)
            ->where('offic_id',3)
            ->value('task_id');
        if ($next2over==""){
            $data['xm2']=0;
        }else{
            $data['xm2']=round($next2/$next2over,2)*100   ;
        }
        //续签M+3月续费率
        $start_time3=date('Y-m-d H:i:s',$start_time);
        $end_time3=date('Y-m-d H:i:s',$end_time);
        $m3s=strtotime(date('Y-m-d H:i:s',strtotime("$start_time3+3month")));
        $m3e=strtotime(date('Y-m-d H:i:s',strtotime("$end_time3+3month")));
        //续签M+3的续费单数
        $next3=Db::name('alimport')
            ->whereTime('before_time','between',["$m3s","$m3e"])
            ->where('sales_executive','like',"%/".$city."%")
            ->where('new_type',"续签")
            ->where('product_category',"诚信通服务")
            ->count();
        //M+3月任务
        $next3over=Db::name('achievements')
            ->where('a_time',strtotime($m3s))
            ->where('branch_id',$bran)
            ->where('offic_id',3)
            ->value('task_id');
        if ($next3over==""){
            $data['xm3']=0;
        }else{
            $data['xm3']=round($next3/$next3over,2)*100   ;
        }
        //大数据统计分析
        //全部新签本月累计销售业绩
        $data['allnew']=Db::name('alimport')
            ->whereTime('end_time','between',["$start_time","$end_time"])
            ->where('new_type',"新签")
            ->where('product_category',"诚信通服务")
            ->count();
        //print_r($data['allnew']);die;
        ////全部代运营本月累计销售业绩
        $data['alldai']=Db::name('per_order')
            ->whereTime('per_addtime','between',["$start_time","$end_time"])
            ->where('per_type','=',4)
            ->where('per_status','=',3)
            ->sum('per_amounted');
        $data['alldai']=round($data['alldai']/10000,2);
        $data['allnewp4p']=Db::name('alimport')
            ->whereTime('single_time','between',["$start_time","$end_time"])
            ->where('new_type',"新签")
            ->where('product_category',"网销宝充值包")
            ->sum('account_money');
        $data['allnewp4p']=round($data['allnewp4p']/10000,2);
        //代运营本月销售业绩
        $data['dai']=Db::name('per_order')
            ->where('per_bid',$bran)
            ->whereTime('per_addtime','between',["$start_time","$end_time"])
            ->where('per_type','=',4)
            ->where('per_status','=',3)
            ->sum('per_amounted');
        $data['dai']=round($data['dai']/10000,2);
        //代运营本月销售业绩目标
        $data['daito']=Db::name('achievements')
            ->where('branch_id',$bran)
            ->where('offic_id',8)
            ->whereTime('a_time','between',["$start_time","$end_time"])
            ->value('task_id');
        if ($data['daito']==""){
            $data['daito']=0;
        }else{
            $data['daito']=$data['daito'];
        }
        //代运营本季度销售业绩
        $data['daiji']=Db::name('per_order')
            ->where('per_bid',$bran)
            ->whereTime('per_addtime','between',["$mstart","$mend"])
            ->where('per_type','=',4)
            ->where('per_status','=',3)
            ->sum('per_amounted');
        $data['daiji']=round($data['daiji']/10000,2);
        //代运营本季度目标
        $data['daijito']=Db::name('achievementss')
            ->where('branch_id',$bran)
            ->where('offic_id',8)
            ->where('s_time',$s)
            ->value('task_id');
        if ($data['daijito']==""){
            $data['daijito']=0;
        }else{
            $data['daijito']=$data['daijito'];
        }
        //集团所有分公司
        //续签本月到单
        $data['xumonth1']=Db::name('alimport')
            ->whereTime('before_time','between',["$start_time","$end_time"])
            ->where('new_type',"续签")
            ->where('product_category',"诚信通服务")
            ->count();
        //续签本月目标
        $data['xumonthto1']=Db::name('achievements')
            ->where('a_time',strtotime($ntime))
            ->where('offic_id',3)
            ->sum('task_id');
        if ($data['xumonthto1']==""){
            $data['xumonthto1']=0;
        }else{
            $data['xumonthto1']=$data['xumonthto1'];
        }
        //续签M月续费率
        if ($data['xumonthto1']==0){
            $data['zxm']=0;
        }else{
            $data['zxm']=round($data['xumonth1']/$data['xumonthto1'],2)*100    ;
        }
        unset($data['xumonth1']);
        unset($data['xumonthto1']);
        //下个月完成单数
        $next1=Db::name('alimport')
            ->whereTime('before_time','between',["$m1s","$m1e"])
            ->where('new_type',"续签")
            ->where('product_category',"诚信通服务")
            ->count();
        //M+1月任务
        $nextover1=Db::name('achievements')
            ->where('a_time',$m1s)
            ->where('offic_id',3)
            ->sum('task_id');
        if ($nextover1==""){
            $data['zxm1']=0;
        }else{
            $data['zxm1']=round($next1/$nextover1,2)*100   ;
        }
        //续签M+2的续费单数
        $next3=Db::name('alimport')
            ->whereTime('before_time','between',["$m2s","$m2e"])
            ->where('sales_executive','like',"%/".$city."%")
            ->where('new_type',"续签")
            ->where('product_category',"诚信通服务")
            ->count();
        //M+2月任务
        $next3over=Db::name('achievements')
            ->where('a_time',$m2s)
            ->where('branch_id',$bran)
            ->where('offic_id',3)
            ->value('task_id');
        if ($next3over==""){
            $data['zxm2']=0;
        }else{
            $data['zxm2']=round($next3/$next3over,2)*100   ;
        }
        //分公司网销宝本月到单
        $data['mp4p']=Db::name('alimport')
            ->whereTime('end_time','between',["$start_time","$end_time"])
            ->where('sales_executive','like',"%/".$city."%")
            ->where('new_type',"新签")
            ->where('product_category',"网销宝充值包")
            ->count();
        //分公司网销宝本月目标
        $data['mp4pto']=Db::name('achievements')
            ->where('branch_id',$bran)
            ->where('offic_id',4)
            ->whereTime('a_time','between',["$start_time","$end_time"])
            ->value('task_id');
        if ($data['mp4pto']==""){
            $data['mp4pto']=0;
        }else{
            $data['mp4pto']=$data['mp4pto'];
        }
        //分公司网销宝本季到单
        $data['sp4p']=Db::name('alimport')
            ->whereTime('end_time','between',["$mstart","$mend"])
            ->where('sales_executive','like',"%/".$city."%")
            ->where('new_type',"新签")
            ->where('product_category',"网销宝充值包")
            ->count();
        //分公司网销宝本季目标
        $data['sp4pto']=Db::name('achievementss')
            ->where('s_time',$s)
            ->where('branch_id',$bran)
            ->where('offic_id',4)
            ->value('task_id');
        if ($data['sp4pto']==""){
            $data['sp4pto']=0;
        }else{
            $data['sp4pto']=$data['sp4pto'];
        }
        //分公司网销宝今日引新
        $data['dnp4p']=Db::name('alimport')
            ->whereTime('end_time','between',["$time","$time1"])
            ->where('sales_executive','like',"%/".$city."%")
            ->where('new_type',"新签")
            ->where('product_category',"网销宝充值包")
            ->count();
        //分公司网销宝今日消耗
        $data['dxp4p']=Db::name('alimport')
            ->whereTime('end_time','between',["$time","$time1"])
            ->where('sales_executive','like',"%/".$city."%")
            ->where('new_type',"续签")
            ->where('product_category',"网销宝充值包")
            ->count();
        //分公司网销宝本月消耗
        $data['mxp4p']=Db::name('alimport')
            ->whereTime('end_time','between',["$start_time","$end_time"])
            ->where('sales_executive','like',"%/".$city."%")
            ->where('new_type',"续签")
            ->where('product_category',"网销宝充值包")
            ->count();
        //新签排行榜
        $data['newsort']=$this->newtasksort();
        //续签排行榜
        $data['xusort']=$this->xutasksort();
        //网销宝排行榜
        $data['wangtasksort']=$this->wangtasksort();
        //代运营排行榜
        $data['daitasksort']=$this->daitasksort();
        //当前月份
        $data['yy']=date("m");
        //代运营柱状图
        for ($i = 0; $i < 6; $i++){
            $daimoth[][] = date("Y-m", strtotime("-$i months"));
        }
        foreach ($daimoth as $k1=>$v1){
            $y=substr($v1[0],0,4);
            $m=substr($v1[0],-2,2);
            $daimoth[$k1]=$this->getdate($y,$m);
            $data['daiz'][]=Db::name('per_order')
                ->where('per_bid',1)
                ->whereTime('per_addtime','between',[$daimoth[$k1]['firstday'],$daimoth[$k1]['lastday']])
                ->where('per_type','=',4)
                ->where('per_status','=',3)
                ->sum('per_amounted');
        }
        $data['ntime']=Db::name('import_log')
            ->order('time desc')
            ->field('time')
            ->find();
        $data['city']=$city;
        $this->assign('data',$data);
        return $this->fetch('sjz');

//        return $this->fetch('task');
        //return $data;
    }
    //时间
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
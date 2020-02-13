<?php


namespace app\index\model;
use think\Model;
use think\db;
use think\Session;

class  Nets extends Model
{
    public function loads(){
        $data=[];
        $result=[];
        $time = strtotime(date('Ymd'));
        $res=Db::name('newcustomer')
            ->where('w_status','>=','1')
            ->where('w_5pay','=',$time)
            ->select();
        if ($res){
            foreach ($res as $k=>$v){
                $data[$k]['aliname']=$res[$k]['c_aliname'];
                $data[$k]['username']=$res[$k]['c_username'];
                $data[$k]['moblie']=$res[$k]['c_tel'];
                $data[$k]['position']=$res[$k]['c_position'];
                $data[$k]['telephone']=$res[$k]['c_tele'];
                $data[$k]['corporate_name']=$res[$k]['c_company'];
                $data[$k]['company_address']=$res[$k]['c_address'];
                $data[$k]['sign_date']=Db::name('neworderform')->where('cid',$res[$k]['c_id'])->value('totime');
                $data[$k]['expires']=Db::name('neworderform')->where('cid',$res[$k]['c_id'])->value('endtime');
                $data[$k]['bid']=$res[$k]['bid'];
                $data[$k]['w_sta']=$res[$k]['w_status'];
            }
            foreach ($data as $key=>$value){
                $result[$key]['oid']=$value['bid'];
                unset($value['bid']);
                $result[$key]['addtime']=$value['sign_date'];
                $result[$key]['maturitys']=$value['expires'];
                $result[$key]['cid']=Db::name('customer')
                    ->insertGetId($value);
            }

            $re=Db::name('relation_company')
                ->insertAll($result);
            if (!empty($re)){
                $newdata=Db::name('customer')
                    ->limit($re)
                    ->order('id desc')
                    ->field('id')
                    ->select();
                foreach ($newdata as $k=>$v){
                    $newdata[$k]['newtime']=strtotime(date('Ymd',time()));
                    $newdata[$k]['cid']= $newdata[$k]['id'];
                    unset($newdata[$k]['id']);
                }
                $r=Db::name('worder_time')->insertAll($newdata);
            }
        }

            $th=strtotime(date('Ymd',time()))-30*24*3600;
            $da=Db::name('worder_time')
                ->where('newtime','=',$th)
                ->field('cid')
                ->select();
            if ($da){
                foreach ($da as $k=>$v){
                    Db::name('customer')->where('id',$v['cid'])->update(['w_sta'=>5]);
                    $resu=Db::name('worder_time')->where('cid',$v['cid'])->update(['newtime'=>strtotime(date('Ymd',time()))]);
                }
            }
            $thi=strtotime(date('Ymd',time()))-10*30*24*3600;
            $das=Db::name('worder_time')
            ->where('newtime','=',$thi)
            ->field('cid')
            ->select();
            if ($das){
                foreach ($das as $k=>$v){
                    Db::name('customer')->where('id',$v['cid'])->update(['w_sta'=>1]);
                    $resu=Db::name('worder_time')->where('cid',$v['cid'])->update(['newtime'=>strtotime(date('Ymd',time()))]);
                }
            }
    }

}
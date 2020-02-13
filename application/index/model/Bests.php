<?php


namespace app\index\model;
use think\Model;
use think\Db;
use think\Session;
use think\Request;

class Bests extends Model
{
    public function lists($search){
        $data['data']=Db::name('customer')
            ->where('w_sta',5)
            ->where('corporate_name|aliname','like','%'.$search.'%')
            ->paginate(30,false,['query' => request()->param()]);
        $data['page'] = $data['data']->render();
        return $data;
    }
    public function worder($id){
        $customer=Db::name('customer')
            ->where('id',$id)
            ->field('id,username,aliname,moblie,corporate_name,company_address')
            ->find();
        return $customer;
    }

    public function worderadd($data){
        $data['totime']=strtotime($data['totime']);
        $data['uid']=Session::get('user.id');
        $data['bid']=Session::get('user.branch_id');
        $res=Db::name('worder')
            ->insert($data);
        $up['w_best']=1;
        Db::name('customer')->where('id',$data['cid'])->update($up);
        Db::name('worder_time')->where('cid',$data['cid'])->delete();
        if ($res){
            return true;
        }else{
            return false;
        }

    }

    public function worderlist($search){
        $data['data']=Db::name('worder')
            ->where('company','like','%'.$search.'%')
            ->paginate(30,false,['query' => request()->param()]);
        $data['page']=$data['data']->render();
        return $data;
    }

    public function changeStatus1($id){
        $update['w_best']=2;
        $cid=Db::name('worder')->where('id',$id)->value('cid');
        $up['status']=2;
        $res=Db::name('worder')->where('id',$id)->update($up);
        $result=Db::name('customer')->where('id',$cid)->update($update);
        if ($res){
            return true;
        }else{
            return false;
        }


    }


}
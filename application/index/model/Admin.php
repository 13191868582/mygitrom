<?php
namespace app\index\model;
use think\Model;
use think\db;
use think\Session;
use think\NodeTree;
class Admin extends Model{

    //修改密码
    public function editNode($data){
        $da['password'] = md5($data['password']);
        if(db("admin")->where("id",$data['id'])->update($da)){
            $ret['ok']=true;
            $ret['msg']='成功';
            return $ret;
        }else{
            $ret['ok']=false;
            $ret['msg']='失败';
            return $ret;
        }
    }
}
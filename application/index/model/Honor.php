<?php
/*
 * 内容：荣誉系统
 * 时间：2019年11月2日08:47:16
 * 代码编写者：段文明
 */

namespace app\index\model;
use think\Model;
use think\db;
use think\Session;

class Honor extends Model
{
    //分公司奥斯卡
    public function index($data){
        unset($data['file']);
        if ($data['h_time']=="月度"){
            $data['h_month']=strtotime($data['h_month']);
            unset($data['h_season']);
            unset($data['h_halfyear']);
            unset($data['h_year']);
        }elseif($data['h_time']=="季度"){
            $data['h_season']=date("Y",time())."-".$data['h_season'];
            unset($data['h_month']);
            unset($data['h_halfyear']);
            unset($data['h_year']);
        }elseif($data['h_time']=="半年度"){
            $data['h_halfyear']=date("Y",time())."-".$data['h_halfyear'];
            unset($data['h_month']);
            unset($data['h_season']);
            unset($data['h_year']);
        }elseif($data['h_time']=="年度"){
            $data['h_year']=date("Y",time());
            unset($data['h_month']);
            unset($data['h_season']);
            unset($data['h_halfyear']);
        }
        unset($data['h_time']);
        $res=Db::name('honor')->insert($data);
        if ($res){
            return true;
        }else{
            return false;
        }
    }
    //员工
    public function best($data){
        unset($data['file']);
        $data['people_time']=strtotime($data['people_time']);
        $res=Db::name('fame')->insert($data);
        if ($res){
            return true;
        }else{
            return false;
        }
    }
//优秀经理
    public function wbest($data){
        unset($data['file']);
        $data['w_time']=strtotime($data['w_time']);
        $res=Db::name('wbest')->insert($data);
        if ($res){
            return true;
        }else{
            return false;
        }
    }

    //王牌vs王牌
    public function trump($data){
        unset($data['file']);
        if ($data['h_time']=="月度"){
            $data['h_month']=strtotime($data['h_month']);
            unset($data['h_season']);
            unset($data['h_halfyear']);
            unset($data['h_year']);
        }elseif($data['h_time']=="季度"){
            $data['h_season']=date("Y",time())."-".$data['h_season'];
            unset($data['h_month']);
            unset($data['h_halfyear']);
            unset($data['h_year']);
        }elseif($data['h_time']=="半年度"){
            $data['h_halfyear']=date("Y",time())."-".$data['h_halfyear'];
            unset($data['h_month']);
            unset($data['h_season']);
            unset($data['h_year']);
        }elseif($data['h_time']=="年度"){
            $data['h_year']=date("Y",time());
            unset($data['h_month']);
            unset($data['h_season']);
            unset($data['h_halfyear']);
        }
        unset($data['h_time']);
        $data['people_time']=strtotime($data['people_time']);
        $res=Db::name('trump')->insert($data);
        if ($res){
            return true;
        }else{
            return false;
        }

    }

    //分公司奥斯卡列表
    public function hlist(){
        $result['data']=Db::name('honor')->
            paginate(100,false,['query' => request()->param()]);
        $result['page'] = $result['data']->render();
        return $result;
    }

    //员工列表
    public function fame(){
        $result['data']=Db::name('fame')->
        paginate(100,false,['query' => request()->param()]);
        $result['page'] = $result['data']->render();
        return $result;
    }

    //经理
    public function  jlists(){
        $result['data']=Db::name('wbest')->
        paginate(100,false,['query' => request()->param()]);
        $result['page'] = $result['data']->render();
        return $result;
    }

    public function  trumps(){
        $result['data']=Db::name('trump')->
        paginate(100,false,['query' => request()->param()]);
        $result['page'] = $result['data']->render();
        return $result;
    }

    public function editlistsone($id){
        $data=Db::name('honor')->where('id',$id)->find();
        return $data;
    }
    public  function editlistsdo($data){
        $id=$data['id'];
        unset($data['id']);
        unset($data['file']);
        $data['h_month']=strtotime($data['h_month']);
        $res=Db::name('honor')->where('id',$id)->update($data);
        return $res;
    }

    public function  deletelists($id){
        $res=Db::name('honor')->where('id',$id)->delete();
        return $res;
    }

    public function editwlists($id){
        $data=Db::name('fame')->where('id',$id)->find();
        return $data;
    }

    public function editwistsdo($data){
        $id=$data['id'];
        unset($data['id']);
        unset($data['file']);
        $data['people_time']=strtotime($data['people_time']);
        $res=Db::name('fame')->where('id',$id)->update($data);
        return $res;
    }
    public function deletewlists($id){
        $res=Db::name('fame')->where('id',$id)->delete();
        return $res;
    }

    public function editjlists($id){
        $data=Db::name('wbest')->where('id',$id)->find();
        return $data;
    }
    public  function editjlistsdo($data){
        $id=$data['id'];
        unset($data['id']);
        unset($data['file']);
        $data['w_time']=strtotime($data['w_time']);
        $res=Db::name('wbest')->where('id',$id)->update($data);
        return $res;
    }

    public function deletejlists($id){
        $res=Db::name('wbest')->where('id',$id)->delete();
        return $res;
    }

    public function editvlists($id){
        $data=Db::name('trump')->where('id',$id)->find();
        return $data;
    }

    public function editvlistsdo($data){
        if (isset($data['h_month'])){
            unset($data['h_time']);
            $data['h_month']=strtotime($data['h_month']);
        }
        if ($data['h_season']!=""){
            unset($data['h_time']);
            $data['h_season']=date('Y',time())."-".$data['h_season'];
        }
        if ($data['h_halfyear']!=""){
            unset($data['h_time']);
            $data['h_halfyear']=date('Y',time())."-".$data['h_halfyear'];
        }
        if (isset($data['h_time'])=="年度"){
            $data['h_year']=date('Y',$data['h_year']);
        }
        $id=$data['id'];
        unset($data['id']);
        unset($data['file']);
        $data['people_time']=strtotime($data['people_time']);
        $res=Db::name('trump')->where('id',$id)->update($data);
        return $res;
    }

    public function deletevlists($id){
        $res=Db::name('trump')->where('id',$id)->delete();
        return $res;
    }

}

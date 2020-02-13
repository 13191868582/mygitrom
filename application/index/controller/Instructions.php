<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Request;

class Instructions extends Common
{
    public function index()
    {
        $res = Db::name('instructions')->where('type',0)->find();

        return $this->fetch("index",['res'=>$res]);
    }

    public function add()
    {
        $res = Db::name('instructions')->where('type',0)->find();

        return $this->fetch("add",['res'=>$res]);
    }

    public function doAdd()
    {
        $request=Request::instance();
        $data=$request->param();

        $res = Db::name('instructions')->where('type',0)->find();



        if (!empty($data['id'])){
            
            $db = db('instructions');
            $res = $db -> where('id',$data['id']) -> update(['comment' => $data['data']]);
            if ($res){
                return 1;
            }else{
                return 2;
            }
        }else{
            $db = db('instructions');
            $res  = $db -> insert(['comment' => $data['data'],'type' => 0]);
            if ($res) {
                return 3;

            }else{
                return 4;
            }

        }


    }

    /**
     * 销售锦囊管理
     * @return [type] [description]
     */
    public function salespackage()
    {

        $res = Db::name('instructions')->where('type',1)->find();

        return $this->fetch("salespackage",['res'=>$res]);
    }

    public function addsales()
    {
        
        $request=Request::instance();
        $data=$request->param();

        $res = Db::name('instructions')->where('type',1)->find();



        if (!empty($data['id'])){
            
            $db = db('instructions');
            $res = $db -> where('id',$data['id']) -> update(['comment' => $data['data']]);
            if ($res){
                return 1;
            }else{
                return 2;
            }
        }else{
            $db = db('instructions');
            $res  = $db -> insert(['comment' => $data['data'],'type' => 1]);
            if ($res) {
                return 3;

            }else{
                return 4;
            }

        }
    }
}




<?php
namespace app\index\model;

use think\Model;
use think\Session;

class Resumes extends Model
{
    //查询所有简历
    public function index($wordKey,$name)
    {
        $admin = Session::get('admin');
        $user = Session::get('user');
        if ($admin || $user['branch_id'] == 15){
            if ($wordKey){
                $where['r.branch_id'] = $wordKey;
            }
            if ($name){
                $where['r.username'] = $name;
            }
        }elseif ($user['department'] == 7 && $user['branch_id'] != 15){
            $res['id'] = $user['branch_id'];
            $andWhere['r.branch_id'] = $user['branch_id'];
            if ($wordKey){
                $where['r.branch_id'] = $wordKey;
            }
            if ($name){
                $where['r.username'] = $name;
            }
        }
        if (empty($res)){
            $result['branch'] = db('branch_office')->select();
        }else{
            $result['branch'] = db('branch_office')->where($res)->select();
        }
        if (!empty($andWhere)){
            if (!empty($where)){
                $result['resume'] = db('resume')
                    ->alias('r')
                    ->join('silver_branch_office b','b.id = r.branch_id')
                    ->field('r.* , b.name')
                    ->where($where)
                    ->paginate(30,false,['query' => request()->param()]);



            }else{
                $result['resume'] = db('resume')
                    ->alias('r')
                    ->join('silver_branch_office b','b.id = r.branch_id')
                    ->field('r.* , b.name')
                    ->where($andWhere)
                    ->paginate(30,false,['query' => request()->param()]);
            }

        }else{
            if (!empty($where)){
                $result['resume'] = db('resume')
                    ->alias('r')
                    ->join('silver_branch_office b','b.id = r.branch_id')
                    ->field('r.* , b.name')
                    ->where($where)
                    ->paginate(30,false,['query' => request()->param()]);
            }else{
                $result['resume'] = db('resume')
                    ->alias('r')
                    ->join('silver_branch_office b','b.id = r.branch_id')
                    ->field('r.* , b.name')
                    ->paginate(30,false,['query' => request()->param()]);
            }
        }



        return $result;
    }

    public function add()
    {
        $admin = Session::get('admin');
        $user = Session::get('user');

        if ($admin || $user['branch_id'] == 15){
            $result = db('branch_office')->select();
        }elseif ($user['department'] == 7 && $user['branch_id'] != 15){
            $result = db('branch_office')->where('id',$user['branch_id'])->select();
        }

        return $result;

    }

    public function doadd($result)
    {
        $user = Session::get('user');
        $time = strtotime($result['time']);
        unset($result['time']);
        $result['time'] = $time;
        $result['addtime'] = time();
        if ($user) {
            $result['user_id'] = $user['id'];
        }


        $res = db('resume')->insert($result);
        if ($res)
        {
            $id = db('resume')->getLastInsID();
            $arr['resume_id'] = $id;
            $arr['statuc'] = $result['statuc'];
            $arr['type'] = 0;
            $arr['addtime'] = time();
            $arr['user_id'] = $user['id'];
            $res1 = db('resume_type')->insert($arr);
            return $res1;
        }else{
            return 5;
        }




    }
}
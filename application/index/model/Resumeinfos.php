<?php
namespace app\index\model;

use think\Model;
use think\Session;

class Resumeinfos extends Model
{
    public function index($branch,$start,$end)
    {
        $admin = Session::get('admin');
        $user = Session::get('user');
        if ($admin || $user['branch_id'] == 15)
        {
            if ($branch){
                $where['branch_id'] = $branch;
            }
            if ($start){
                $reswhere['addtime']  =  array('egt',strtotime($start));
                $rwhere['addtime']  =  array('elt',strtotime($end));
            }

            $type = 1;
        }elseif ($user['department'] == 7 && $user['branch_id'] != 15){
            $where['id'] = $user['id'];
            if ($start){
                $reswhere['addtime']  =  array('egt',strtotime($start));
                $rwhere['addtime']  =  array('elt',strtotime($end));
            }

            $type = 2;
        }






        if (!empty($where))
        {
            $user_id = db('user')
                ->field('id,username')
                ->where('department',7)
                ->where('status',1)
                ->where($where)
                ->select();


        }else{
            $user_id = db('user')
                ->field('id,username')
                ->where('department',7)
                ->where('status',1)
                ->select();
        }


        if (empty($user_id))
        {
            $arr['result'] = [];

        }else{

            foreach ($user_id as $key=>$value)
            {
                if (!empty($reswhere)){
                    $phone = db('resume')->where('user_id',$value['id'])->where($reswhere)->where($rwhere)->count();

                    $resumre = db('resume')
                        ->Where('user_id',$value['id'])
                        ->where($reswhere)
                        ->where($rwhere)
                        ->where(function($query) {
                            $query->where('statuc',2)
                                ->whereOr('statuc',3)
                                ->whereOr('statuc',4)
                                ->whereOr('statuc',5);
                        })
                        ->count();
                    $answer = db('resume')
                        ->Where('user_id',$value['id'])
                        ->where($reswhere)
                        ->where($rwhere)
                        ->where(function($query) {
                            $query->where('statuc',1)
                                ->whereOr('statuc',2)
                                ->whereOr('statuc',3)
                                ->whereOr('statuc',4)
                                ->whereOr('statuc',5);
                        })
                        ->count();
                    $entry = db('resume')
                        ->Where('user_id',$value['id'])
                        ->where($reswhere)
                        ->where($rwhere)
                        ->where(function($query) {
                            $query->where('statuc',4)
                                ->whereOr('statuc',5);
                        })
                        ->count();
                    $ruzhi = db('resume_type')
                        ->Where('user_id',$value['id'])
                        ->where('statuc',4)
                        ->where($reswhere)
                        ->where($rwhere)
                        ->count();
                    $lizhi = db('resume_type')
                        ->Where('user_id',$value['id'])
                        ->where($reswhere)
                        ->where($rwhere)
                        ->where('statuc',5)
                        ->count();

                }else{
                    $phone = db('resume')->where('user_id',$value['id'])->count();

                    $resumre = db('resume')
                        ->Where('user_id',$value['id'])
                        ->where(function($query) {
                            $query->where('statuc',2)
                                ->whereOr('statuc',3)
                                ->whereOr('statuc',4)
                                ->whereOr('statuc',5);
                        })
                        ->count();
                    $answer = db('resume')
                        ->Where('user_id',$value['id'])
                        ->where(function($query) {
                            $query->where('statuc',1)
                                ->whereOr('statuc',2)
                                ->whereOr('statuc',3)
                                ->whereOr('statuc',4)
                                ->whereOr('statuc',5);
                        })
                        ->count();
                    $entry = db('resume')
                        ->Where('user_id',$value['id'])
                        ->where(function($query) {
                            $query->where('statuc',4)
                                ->whereOr('statuc',5);
                        })
                        ->count();
                    $ruzhi = db('resume_type')
                        ->Where('user_id',$value['id'])
                        ->where('statuc',4)
                        ->count();
                    $lizhi = db('resume_type')
                        ->Where('user_id',$value['id'])
                        ->where('statuc',5)
                        ->count();
                }

                $result[$key]['phone'] = $phone;
                $result[$key]['resumre'] = $resumre;
                $result[$key]['answer'] = $answer;
                $result[$key]['entry'] = $entry;
                $result[$key]['ruzhi'] = $ruzhi;
                $result[$key]['lizhi'] = $lizhi;
                $result[$key]['username'] = $value['username'];
                $result[$key]['user_id'] = $value['id'];
            }
            $arr['result'] = $result;
        }




       $arr['type'] = $type;
        return $arr;
    }
}
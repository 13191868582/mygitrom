<?php

namespace app\index\controller;

use app\index\model\Resumeinfos;
use think\Request;

class Resumeinfo extends Common
{
    public function index()
    {
        $result = Request::instance()->param('branch');
        $start = Request::instance()->param('start');
        $end = Request::instance()->param('end');

        $branch = db('branch_office')->select();
        $resumeinfo = new Resumeinfos();
        $res = $resumeinfo->index($result,$start,$end);

        $phonenum = array_sum(array_column($res['result'], 'phone'));
        $resumrenum = array_sum(array_column($res['result'], 'resumre'));
        $answernum = array_sum(array_column($res['result'], 'answer'));
        $entrynum = array_sum(array_column($res['result'], 'entry'));
        $ruzhinum = array_sum(array_column($res['result'], 'ruzhi'));
        $lizhinum = array_sum(array_column($res['result'], 'lizhi'));
        return $this->fetch('',['res'=>$res['result'],'type'=>$res['type'],'branch'=>$branch,'result'=>$result,'and'=>$start,'end'=>$end,'phonenum' => $phonenum,'resumrenum' => $resumrenum,'answernum' => $answernum,'entrynum' => $entrynum,'ruzhinum' => $ruzhinum,'lizhinum' => $lizhinum]);

    }

    public function test()
    {
        $result = db('resume')->select();
        foreach ($result as $key=>$value)
        {
            db('resume')->where('resume_id',$value['id'])->update(['user_id'=>$value['user_id']]);
        }
    }


}
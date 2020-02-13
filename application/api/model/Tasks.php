<?php


namespace app\api\model;
use app\api\controller\Notice;
use think\Db;
use think\Model;
use app\api\model\Notices;
class Tasks extends  Model
{
    //接收人
    public function receiver(){
        $username=Db::name('user')
            ->where('dd_postion',"总经理")
            ->column('username');
        $username['10']="郝娜";
        $username['11']="郭东阳";
        $username['12']="耿浩";
        $username['13']="王运鹏";
        return $username;
    }
    //下达任务
    public function index($data){
        $task=array();
        $data['task_people']=explode(',',$data['task_people']);
        foreach ($data['task_people'] as $k=>$v){
            $task[$k]['task_title']=$data['task_title'];
            $task[$k]['task_people']=$v;
            $task[$k]['task_time']=time();
            $task[$k]['task_img']="/uploads/xcx/".trim($data['task_img'],'"');
            $task[$k]['task_content']=$data['task_content'];
            $task[$k]['task_status']=0;
        }
        $res=Db::name('task')
            ->insertAll($task);
        if($res){
            foreach ($data['task_people'] as $key=>$value){
                $userid=Db::name('dingding')->where('name',$value)->value('userid');
                $num=Db::name('task')->where('task_people',$value)->order('id desc')->value('id');
                $notice=new Notice();
                $msg = [
                    'msgtype' => "text",
                    "text" => [
                        'content' => "董事长给您指派了一条任务,请您到钉钉应用豆宝内查看任务详情".$num."。"
                    ],
                ];
                $msg=json_encode($msg);
                $notice->ding( $userid,$msg);
            }
            return 1;
        }else{
            return 2;
        }
    }

    //待确认列表
    public function confirmedlist($task_people){
        $where=array();
        if ($task_people=="戴金秋"){
            $data=Db::name('task')
                ->where('task_status',"in",0,2)
                ->field("from_unixtime(task_time,'%Y-%m-%d') as task_time,id,task_title,task_content,task_img,task_people,task_status")
                ->order('task_time','desc')
                ->select();
            return $data;
        }else{
            $where['task_people']=$task_people;
            $data=Db::name('task')
                ->where($where)
                ->where('task_status',0)
                ->field("from_unixtime(task_time,'%Y-%m-%d') as task_time,id,task_title,task_content,task_img,task_people,task_status")
                ->order('task_time','desc')
                ->select();
            return $data;
        }
    }
    //查看详情待确认任务
    public function confirmed($id){
        $data=Db::name('task')
            ->where('id',$id)
            ->field("from_unixtime(task_time,'%Y-%m-%d') as task_time,id,task_title,task_content,task_img,task_people,task_status")
            ->find();
        return $data;
    }

    //去执行
    public function confirmed_to($id){
        $res=Db::name('task')
            ->where('id',$id)
            ->update(['task_status'=>1,'task_qtime'=>time()]);
        if ($res){
            $task_people=Db::name('task')->where('id',$id)->value('task_people');
            $notice=new Notice();
            $msg = [
                'msgtype' => "text",
                "text" => [
                    'content' => $task_people."查看了您指派的任务，并确认执行".$id."。"
                ],
            ];
            $msg=json_encode($msg);
            $notice->ding("3002445925347918",$msg);
            return $res;
        }

    }

    //查看执行中任务列表
    public function confirmed_dolist($task_people){
        $where=array();
        if ($task_people=="戴金秋"){
            $where=array();
        }else{
            $where['task_people']=$task_people;
        }
        $data=Db::name('task')
            ->where($where)
            ->where('task_status',1)
            ->order('task_time','desc')
            ->select();
        return $data;
    }
    //去提交任务
    public function confirmed_do($id){
        $res=Db::name('task')
            ->where('id',$id)
            ->update(['task_status'=>2,'task_overtime'=>time()]);
        if ($res){
            $task_people=Db::name('task')->where('id',$id)->value('task_people');
            $notice=new Notice();
            $msg = [
                'msgtype' => "text",
                "text" => [
                    'content' => $task_people."完成了您指派的任务，请您对他的工作进行评分".$id."。"
                ],
            ];
            $msg=json_encode($msg);
            $notice->ding("3002445925347918",$msg);
            return $res;
        }
    }
    //查看待评价任务列表
    public function confirmed_evaluate($task_people){
        $where=array();
        if ($task_people=="戴金秋"){
            $where=array();
        }else{
            $where['task_people']=$task_people;
        }
        $data=Db::name('task')
            ->where($where)
            ->where('task_status',2)
            ->order('task_time','desc')
            ->select();
        return $data;
    }

    //去评价任务
    public function confirmed_evaluatedo($data){
        Db::startTrans();
        try{
            Db::name('task')
                ->where('id',$data['t_id'])
                ->update(['task_status'=>3,'task_etime'=>time()]);
            $data['e_time']=time();
            Db::name('taskevaluate')
                ->insert($data);
            // 提交事务
            Db::commit();
            return 1;
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return 2;
        }
    }
    //查看评价任务
    public function confirmed_evaluatelist($id){
        $data=Db::name('taskevaluate')
            ->where('t_id',$id)
            ->find();
        return $data;
    }


}
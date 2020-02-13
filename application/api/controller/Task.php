<?php
namespace app\api\controller;
use app\api\model\Tasks;
use think\Controller;
use think\Db;
use think\Request;

class Task  extends  Controller
{
    //接收人
    public  function receiver(){
        $obj= new Tasks();
        $username=$obj->receiver();
        return json_encode($username);
    }
    //图片上传
    public function upload(){
        if($_FILES){
            $file = $_FILES['file'];
            $file_name = $file['name'];
            $tmp_file = $file['tmp_name'];
            $error = $file['error'];
            if($error == 0){
                move_uploaded_file($tmp_file,ROOT_PATH.'public'. DS.'uploads/xcx/'.$file_name);
                return json_encode($file_name);
            }
        }

    }
    //添加下达任务
    public function  index(){
        $data=input('');
        $obj=new Tasks();
        $res=$obj->index($data);
        return json_encode($res);

    }
    //待确认列表
    public function confirmedlist(){
        $task_people=input('task_people');
        $obj=new Tasks();
        $data=$obj->confirmedlist($task_people);
        return json_encode($data);
    }
    //查看详情待确认任务
    public function confirmed(){
        $id=input('id');
        $obj=new Tasks();
        $data=$obj->confirmed($id);
        return json_encode($data);
    }
    //去执行
    public function confirmed_to(){
        $id=input('id');
        $obj=new Tasks();
        $res=$obj->confirmed_to($id);
        return json_encode($res);
    }
    //查看执行中任务列表
    public function confirmed_dolist(){
        $task_people=input('task_people');
        $obj=new Tasks();
        $data=$obj->confirmed_dolist($task_people);
        return json_encode($data);
    }

    //去提交任务
    public function confirmed_do(){
        $id=input('id');
        $obj=new Tasks();
        $res=$obj->confirmed_do($id);
        return json_encode($res);
    }

    //查看待评价任务列表
    public function confirmed_evaluate(){
        $task_people=input('task_people');
        $obj=new Tasks();
        $data=$obj->confirmed_evaluate($task_people);
        return json_encode($data);
    }

    //去评价任务
    public function confirmed_evaluatedo(){
        $data=input('');
        $obj=new Tasks();
        $res=$obj->confirmed_evaluatedo($data);
        return json_encode($res);
    }
    //查看评价任务
    public function confirmed_evaluatelist(){
        $id=input('id');
        $obj=new Tasks();
        $res=$obj->confirmed_evaluatelist($id);
        return json_encode($res);
    }
    
}
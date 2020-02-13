<?php
namespace app\api\controller;

use app\api\model\Notices;
use think\Controller;
use think\Session;
use app\index\model\Productcontrols;
use think\Db;

class Notice  extends Controller
{
    public $AppKey = 'dinghqvqfbn5lwqdnkrh';
    public $AppSecret = 'Zge_SbEtQACIGpADV4T8U6hu_0dO8UrK5pN7k3Q4X0JaJfE8nr91zboJxw4kVKax';

    //获取token
    public function getToken()
    {
        $url = "https://oapi.dingtalk.com/gettoken?appkey=".$this->AppKey."&appsecret=".$this->AppSecret;

        $curl = new Notices();

        $result = $curl->curl_get($url);

        $arr = json_decode($result,true);
        return $arr;

    }

    public function ding($user_id,$msg)
    {
        $arr = $this->getToken();

        $url = "https://oapi.dingtalk.com/topapi/message/corpconversation/asyncsend_v2?access_token=".$arr['access_token'];

        $data = [
            'agent_id' => '296884119',
            'userid_list' => $user_id,
            'msg' => $msg,
        ];

        $curl = new Notices();
        $result = $curl->curl_post($url,$data);

    }

    public function text()
    {
        $andtime = strtotime(date('2019-11-01 00:00:00'));

        $user = db('user')
        ->alias('u')
        ->join('silver_branch_office b','u.branch_id = b.id')
        ->where('u.department',2)
        ->field('u.id,u.username,u.branch_id,b.name')
        ->where('u.status',1)
        ->select();

        foreach ($user as $key=>$value)
        {
            $result = db('alimport')
                ->where('sales',$value['username'])
                ->where('single_time','>=',$andtime)
                ->count();
            if ($result == 0)
            {
                $arr[] = $value;
            }
        }

        $branch = db('branch_office')->select();

        foreach ($branch as $key=>$value)
        {
            foreach ($arr as $k => $v)
            {
                if ($value['id'] == $v['branch_id'])
                {
                    $res[$value['id']][] = $v['username'];
                }
            }
        }

        foreach ($res as $key=>$value)
        {
            $username[$key] = db('user')
                ->alias('u')
                ->join('silver_dingding d' ,'d.name = u.username')
                ->field('d.userid')
                ->where('u.branch_id',$key)
                ->where('u.department',2)
                ->where('u.rank',11)
                ->find();
            if ($username[$key] == "")
            {
                $username[$key] = db('user')
                    ->alias('u')
                    ->join('silver_dingding d' ,'d.name = u.username')
                    ->field('d.userid')
                    ->where('u.branch_id',$key)
                    ->where('u.rank',1)
                    ->find();
            }

            $username[$key]['username'] = $value;


        }



        foreach ($username as $key => $value) {
            $name = db('branch_office')->field('name')->where('id',$key)->find();

                $str = implode(',', $value['username']);
                $msg = [
                    'msgtype' => "text",
                    "text" => [
                        'content' => $name['name']."本月未破蛋员工:" . $str,
                    ],
                ];

                $this->ding('016803062736312388', json_encode($msg));


        }

    }

   

    public function user()
    {
        $this_month_start = strtotime(date('Y-m-d H:i:s', strtotime("first day of this month 00:00:00")));
        $user = db('user')
            ->alias('u')
            ->join('silver_branch_office b','u.branch_id = b.id')
            ->where('u.department',2)
            ->field('u.id,u.username,u.branch_id,b.name')
            ->where('u.status',1)
            ->select();

        foreach ($user as $key=>$value)
        {
            $result = db('alimport')
                ->where('sales',$value['username'])
                ->where('single_time','>=',$this_month_start)
                ->count();
            if ($result == 0)
            {
                $arr[] = $value;
            }
        }

        $branch = db('branch_office')->select();

        foreach ($branch as $key=>$value)
        {
            foreach ($arr as $k => $v)
            {
                if ($value['id'] == $v['branch_id'])
                {
                    $res[$value['id']][] = $v['username'];
                }
            }
        }

        foreach ($res as $key=>$value)
        {
            $username[$key] = db('user')
                ->alias('u')
                ->join('silver_dingding d' ,'d.name = u.username')
                ->field('d.userid')
                ->where('u.branch_id',$key)
                ->where('u.department',2)
                ->where('u.rank',11)
                ->find();
            if ($username[$key] == "")
            {
                $username[$key] = db('user')
                    ->alias('u')
                    ->join('silver_dingding d' ,'d.name = u.username')
                    ->field('d.userid')
                    ->where('u.branch_id',$key)
                    ->where('u.rank',1)
                    ->find();
            }

            $username[$key]['username'] = $value;


        }


        foreach ($username as $key => $value)
        {
            if (!empty($value['username'])){
                $username = implode(',',$value['username']);

                db('azero')->insert(['branch_id'=>$key,'username'=>$username,'time'=>time()]);
                if (!empty($value['userid']))
                {
                    $str =  implode(',',$value['username']);
                    $msg = [
                        'msgtype' => "text",
                        "text" => [
                            'content'=>"本月未破蛋员工:".$str,
                        ],
                    ];

                    $this->ding($value['userid'],json_encode($msg));

                }
            }

        }



    }

    public function AppletsDing($name)
    {
        $arr = explode(',', $name);
        foreach ($arr as $key => $value) {
           $res = db('dingding')->where('name',$value)->find();
            $msg = [
                        'msgtype' => "text",
                        "text" => [
                            'content'=>"工作安排通知：您有一条新的任务安排，请在豆宝-->任务下达内查收。",
                        ],
                    ];

            $result = $this->ding($res['userid'],json_encode($msg));
        }
        

        
    }

    public function timingDing()
    {
        $task = db('task')->where('task_status',0)->select();

        foreach ($task as $key => $value) {
            $res = db('dingding')->where('name',$value['task_people'])->find();
            $msg = [
                        'msgtype' => "text",
                        "text" => [
                            'content'=>"工作安排通知：您有一条新的任务安排，请在豆宝-->任务下达内查收。",
                        ],
                    ];

            $result = $this->ding($res['userid'],json_encode($msg));
        }
    }



    //自动执行方法
    public function timing()
    {
        $pro = new productcontrols();
        $pro->association();
        $arr = ['18631826973','03180001'];
        foreach ($arr as $key=>$value){
            $res = db('productcontrol')->where('static',0)->where('job_num',$value)->count();
            $user = db('user')->where('job_num',$value)->find();
            $dingding = db('dingding')->where('mobile',$user['mobile'])->find();
            $msg = [
                'msgtype' => "text",
                "text" => [
                    'content'=>"工作通知:今日核查任务数量:".$res,
                ],
            ];

            $this->ding($dingding['userid'],json_encode($msg));
        }
        $task_people=Db::name('task')->where('task_status',0)->field('task_people')->column('task_people');
        foreach ($task_people as $k=>$v){
            $userid=Db::name('dingding')->where('name',$v)->value('userid');

            $msg = [
                'msgtype' => "text",
                "text" => [
                    'content' => "董事长给您指派了一条任务，请您到钉钉应用豆宝内查看任务详情。"
                ],
            ];
            $msg=json_encode($msg);
            $this->ding( $userid,$msg);
        }

    }


}
<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\User;
use think\Session;
use think\Request;
class Login extends Controller
{
    public function index()
    {
    	return $this->fetch('login');
    }

    public function ydd()
    {
        return $this->fetch('ydd');
    }

    public function login()
    {
        
        
    	$data=$_POST;
    	$user=new User();
    	$res=$user->dologin($data);
    	if($res=='0'){
            $ret['ok']=false;
            $ret['msg']='您的账号不存在!';
            echo json_encode($ret);
    	}elseif($res=='1'){
            $ret['ok']=false;
            $ret['msg']='您的密码不正确!';
            echo json_encode($ret);
    	}elseif($res=='2'){
            $ret['ok']=false;
            $ret['msg']='您的账号被禁用!';
            echo json_encode($ret);
    	}else{
            
    		$ret['ok']=true;
    		$ret['msg']='您的登录成功!';
    		echo json_encode($ret);
    		
    	}
    }

    //登出
    public function logout()
    {
        $user=new User();
        $res=$user->exit();
        session(null);
        $this->redirect("login/index");
    }
    //扫码登陆
    public function logined()
   {
        $AppKey='dinghqvqfbn5lwqdnkrh';
        $AppSecret='Zge_SbEtQACIGpADV4T8U6hu_0dO8UrK5pN7k3Q4X0JaJfE8nr91zboJxw4kVKax';
        $AppId='dingoa7k6t5fbeuiwy3sas';
        $AppMi='7y3CGAtdTDZgFVvR8__X9K0RFHDpHf8aF2lrIeCF2Ce2BhyBXDB9wFhxrAwv5zVv';
        $request=Request::instance();
        $code=$request->param('code');
        //获取access_token
        $url='https://oapi.dingtalk.com/gettoken?appkey='.$AppKey.'&appsecret='.$AppSecret;
        $access_token=json_decode(file_get_contents($url),true);
        $access_token=$access_token['access_token'];
        //获取永久授权码
        $time=getMillisecond();
        $sign=dosign($time,$AppMi);
        $tkurl="https://oapi.dingtalk.com/sns/getuserinfo_bycode?signature=".$sign."&timestamp=".$time."&accessKey=".$AppId;
        $post_data=json_encode(array('tmp_auth_code'=>$code));
        //请求
        $data=json_decode(curl_post($tkurl,$post_data),true);
        $unionurl="https://oapi.dingtalk.com/user/getUseridByUnionid?access_token=".$access_token."&unionid=".$data['user_info']['unionid'];
        $userid=json_decode(file_get_contents($unionurl),true);
        $userurl="https://oapi.dingtalk.com/user/get?access_token=".$access_token."&userid=".$userid['userid'];
        $userdetail=json_decode(file_get_contents($userurl),true);
        $moblie=$userdetail['mobile'];
        $user=db('user')->where('mobile',$moblie)->find();
        if(empty($user)){
            // $user=db('user')->where('job_num','03110010')->find();
            echo "<script>alert('您提交的手机号与公司钉钉注册手机号不一致，请联系分公司人力部修改或添加！');history.go(-1);</script>";
            exit;
        }else{
            $update['dd_number']=$userdetail['department'][0];
            $update['dd_postion']=$userdetail['position'];
            db('user')->where('mobile',$moblie)->update($update);
        }
        $usered=new User();
        $res=$usered->dologined($user);
        if($res=='3'){
            header("Location:/");
        }
    }


}

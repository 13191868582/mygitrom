<?php
namespace app\api\controller;
use think\Controller;
use think\Request;
use think\Db;
use think\Session;
class Ding extends controller
{
	//获取access_token
	public function gettoken()
	{
		$AppKey='dinghqvqfbn5lwqdnkrh';
		$AppSecret='Zge_SbEtQACIGpADV4T8U6hu_0dO8UrK5pN7k3Q4X0JaJfE8nr91zboJxw4kVKax';
		$request=Request::instance();
		$code=$request->param('code');
		//获取access_token
		$url='https://oapi.dingtalk.com/gettoken?appkey='.$AppKey.'&appsecret='.$AppSecret;
		$access_token=json_decode(file_get_contents($url),true);
		$access_token=$access_token['access_token'];
		//获取userid
		$murl='https://oapi.dingtalk.com/user/getuserinfo?access_token='.$access_token.'&code='.$code;
		$res=json_decode(file_get_contents($murl),true);
		$userid=$res['userid'];
		$uurl='https://oapi.dingtalk.com/user/get?access_token='.$access_token.'&userid='.$userid;
		$userlist=json_decode(file_get_contents($uurl),true);
		// $ceshi='https://oapi.dingtalk.com/department/list?access_token='.$access_token.'&id=137385971';
		// $userlist=json_decode(file_get_contents($ceshi),true);
		// var_dump($userlist);
		// die;
		// if(is_array($userlist['roles'])){
		// 	$userlist['roles']=json_encode($userlist['roles']);
		// }
		$department=$userlist['department'][0];
		
		if(is_array($userlist['department'])){
			$userlist['department']=json_encode($userlist['department']);
		}
		unset($userlist['errcode']);
		unset($userlist['errmsg']);
		unset($userlist['tags']);
		$res=Db::name('dingding')->where('userid',$userid)->find();
		if(!$res){
			Db::name('dingding')->insert($userlist);
		}else{
			unset($userlist['roles']);
			Db::name('dingding')->where('mobile',$userlist['mobile'])->update($userlist);
		}
		
		$moblie=$userlist['mobile'];
		$user=Db::name('user')->where('mobile',$moblie)->find();
		if(!$user){
			$ulist=Db::name('user')->where('job_num','03110010')->find();
			Session::set('a_user',$ulist);
		}else{
			$update['dd_number']=$department;
			$update['dd_postion']=$userlist['position'];
			Db::name('user')->where('mobile',$moblie)->update($update);
			$ulist=Db::name('user')->where('mobile',$moblie)->find();
			Session::set('a_user',$ulist);
		}
		return json_encode($user);
	}	
}
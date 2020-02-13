<?php
namespace app\index\controller;
use think\controller;
use app\index\model\Userlog;
use \think\Request;
use think\Session;
/**
 * 员工登陆日志
 */
class Userlogs extends Common
{
	
	public function loglist()
	{
		$log=new Userlog();
		$data=$log->loglist();
		return $this->fetch('',['data'=>$data]);
	}
}
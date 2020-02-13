<?php
namespace app\index\model;
use think\Model;
use think\db;
use think\Session;
use think\NodeTree;
use app\index\model\Service;
/**
 * 员工登陆日志
 */
class Userlog extends Model
{
	public function loglist()
	{
		$res=Db::name('record')->order('id desc')->paginate(30);
		return $res;
	}
}
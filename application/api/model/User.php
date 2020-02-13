<?php 
namespace app\api\model;

use think\Model;
use think\Session;
use think\db;

class User extends Model
{
	public function login($data)
	{
		$username = $data['username'];
		$pwd = $data['password'];
		$user = db('user')->where('job_num|mobile',$username)->find();
		$admin = db('admin')->where('username',$username)->find();

		if ($user == "" && $admin == "") {
			return 0;
		}else{
			if ($user != "") {
				if ($user['pawd'] == $pwd) {
					Session::set('a_user',$user);
					return 1;
				}else{
					
					return 2;
				}
			}
			if ($admin != "") {
				if ($admin['password'] == $pwd) {
					Session::set('a_admin',$admin);
					return 1;
				}else{
					
					return 2;
				}
			}
		}
	}
}
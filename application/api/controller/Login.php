<?php 
namespace app\api\controller;

use think\Controller;
use think\Request;
use app\api\model\User;

class Login extends Controller
{
	public function login()
	{
		$request=Request::instance();
        $data=$request->param();
		$user = new User();
		$res = $user->login($data);
		
		if ($res == 0) {
			$ret = [
				'code' => 0,
				'msg' => '账户不存在！'
			]; 
		}else{
			if ($res == 1) {
				$ret = [
					'code' => 1,
					'msg' => '登录成功！'
				];
			}else{
				$ret = [
					'code' => 2,
					'msg' => '密码错误！'
				];
			}

		}

		return json_encode($ret);

	}

	public function logout()
	{
		session(null);
	}
}
<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Role;
use \think\Request;
use \think\SelectTree; 
/**
* 角色分配类
*/
class Roles extends Common
{
	//角色列表
	public function roleList()
	{
		$res=Role::roleList();
		return $this->fetch('role_list',['res'=>$res]);
	}
	//添加角色
	public function doAdd()
	{
		$request=Request::instance();
		$data=$request->param();
		$res=Role::doAdd($data);
		if($res){
			$arr = array('code' =>200,'msg'=>'添加成功！');
		}else{
			$arr = array('code' =>404,'msg'=>'添加失败！');
		}
		return returnmsg($arr);
	}
	//编辑角色
	public function editRole()
	{
		$request=Request::instance();
		$data=$request->param();
		$res=Role::editRole($data);
		return $this->fetch("edit_role",['res'=>$res]);
	}
	//编辑保存
	public function doEdit()
	{
		$request=Request::instance();
		$data=$request->param();
		$res=Role::doEdit($data);
		if($res){
			$arr = array('code' =>200,'msg'=>'修改成功！');
		}else{
			$arr = array('code' =>404,'msg'=>'修改失败！');
		}
		return returnmsg($arr);
	}
	//授权页面
	public function grantRole()
	{
		if($_POST){
			$gid = input('gid', 0);
            $rules = input('rules', '');

            if(Role::updateAuthGroup($gid, ['auths'=>$rules])) {
                $arr = array('code' =>200,'msg'=>'修改成功！');
            } else {
               $arr = array('code' =>404,'msg'=>'修改失败！');
            }
            return returnmsg($arr);
		}else{
		$request=Request::instance();
		$gid=input('id', 0);
		$res=Role::grantRole($gid);
		foreach ($res as $key => $value) {
	            $res[$key]['open'] = true;
	        }
        $this->assign('gid', $gid);
        $this->assign('rules', json_encode($res));

        return $this->fetch('grant_auth');
		}
	
	}
}
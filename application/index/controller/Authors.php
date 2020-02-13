<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Author;
use \think\Request;
use \think\SelectTree; 
/**
* 权限控制器
*/
class Authors extends Common
{
	//权限列表
	public function authList()
	{
		$author=new Author();
		$res=$author->authList();
		$option = [
                'parent_key'    => 'pid',
                'menu_name'     => 'name',
            ];
            $tree = new SelectTree($res, $option);
            $html_tree = $tree->getArray(0,0, ' ');
		return $this->fetch("author_list",['list'=>$html_tree]);
	}
	//添加菜单
	public function addAuthor()
	{
		$author=new Author();
		$request=Request::instance();
		$pid=$request->get('pid');
		if($pid){
			$rule_list = $author->authList();
            $option = [
                'parent_key'    => 'pid',
                'menu_name'     => 'name',
            ];
            $tree = new SelectTree($rule_list, $option);
            $html_tree = $tree->getArray(0,0, ' ');
            $this->assign('tree', $html_tree);
		}
		 
        $this->assign('pid', $pid);
		return $this->fetch("add_author");
	}
	//执行添加
	public function doAddauthor()
	{
		$request=Request::instance();
		$data=$request->param();
		if(empty($data['pid'])){
			$data['pid']=0;
		}
		$author=new Author();
		$res=$author->doAddauthor($data);
		if($res){
			$arr = array('code' =>200,'msg'=>'添加成功！');
		}else{
			$arr = array('code' =>404,'msg'=>'添加失败！');
		}

		return returnmsg($arr);
	}
	//修改菜单
	public function editAuthor()
	{
		$request=Request::instance();
		$data=$request->get('id');
		$author=new Author();
		$res=$author->editAuthor($data);
		return $this->fetch("edit_author",["res"=>$res]);
	}
	//执行修改
	public function doEditauthor()
	{
		$request=Request::instance();
		$data=$request->param();
		$author=new Author();
		$res=$author->doEditauthor($data);
		if($res){
			$arr = array('code' =>200,'msg'=>'修改成功！');
		}else{
			$arr = array('code' =>404,'msg'=>'修改失败！');
		}
		return returnmsg($arr);
	}
}
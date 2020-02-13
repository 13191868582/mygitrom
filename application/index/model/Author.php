<?php
namespace app\index\model;
use think\Model;
use think\db;
use think\Session;
/**
* 
*/
class Author extends Model
{
	public function authList()
	{
		$res=Db::name('author')->select();
		return $res;
	}
	//执行添加
	public function doAddauthor($data)
	{
		$res=Db::name("author")->insertGetId($data);
		if($data['pid']==0){
			$add['path']='0,'.$res;
			$result=Db::name('author')->where('id',$res)->update($add);
		}else{
			$path=Db::name("author")->where('id',$data['pid'])->field('path')->find();
			$add['path']=$path['path'].','.$res;
			$result=Db::name('author')->where('id',$res)->update($add);
		}
		return $result;
	}
	//修改
	public function editAuthor($id)
	{
		$data=Db::name('author')->where('id',$id)->find();
		return $data;
	}
	//执行修改
	public function doEditauthor($data)
	{
		$id=$data['id'];
		$update['name']=$data['name'];
		$update['title']=$data['title'];
		$update['status']=$data['status'];
		$res=Db::name('author')->where('id',$id)->update($update);
		return $res ? true : false;
	}
}

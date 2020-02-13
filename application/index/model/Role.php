<?php
namespace app\index\model;
use think\Model;
use think\db;
use think\Session;
/**
* 角色分类model类
*/
class Role extends Model
{
	public static function roleList()
	{
		$res=db('role')->select();
		return $res;
	}
	//添加角色
	public static function doAdd($data)
	{
		$res=db('role')->insert($data);
		return $res;
	}
	//编辑角色
	public static function editRole($data)
	{
		$res=db('role')->where('id',$data['id'])->find();
		return $res;
	}
	//编辑保存
	public static function doEdit($data)
	{
		$id=$data['did'];
		$update['title']=$data['title'];
		$update['status']=$data['status'];
		$res=db('role')->where('id',$id)->update($update);
		return $res;
	}
	//获取权限
	public static function grantRole($id)
	{
		  $allRule = self::field('id,pId,name')
            ->table('silver_author')
            ->order('id', 'asc')
            ->select();
        // 角色拥有的节点
        $res = self::field('auths')
            ->where('id', $id)
            ->find();
        $groupRule = explode(',', $res['auths']);

        foreach ($allRule as $key => $rule) {
            if(in_array($rule['id'],$groupRule)) {
                $allRule[$key]['checked'] = true;
            }
        }

        return $allRule;
	}
	//更新用户信息
	 public static function updateAuthGroup($gid, $data=[]) {
        $res = db('role')->where('id', $gid)->update($data);
        return $res ? true : false;
    }
}
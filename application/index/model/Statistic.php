<?php
namespace app\index\model;
use think\Model;
use think\db;
use think\Session;
/**
* 
*/
class Statistic extends Model
{
	
	public static function companyList()
	{
		$res=db('branch_office')->select();
		return $res;
	}

	public function rate($branch_id='',$job_num='',$start='',$end='')
	{
		// if($branch_id){
		// 	$res=Db::query("select m.*,sum(c.p_num) as p_num,sum(c.o_num) as o_num,sum(c.p_nums) as p_nums,sum(c.o_nums) as o_nums from silver_service_month as m left join silver_service_completion c on m.month=c.service_month left join silver_user as u on c.uid=u.id where u.branch_id=".$branch_id." and c.addtime between ".$start." and ".$end." group by m.month");
		// }elseif($job_num){
		// 	$res=Db::query("select m.*,sum(c.p_num) as p_num,sum(c.o_num) as o_num,sum(c.p_nums) as p_nums,sum(c.o_nums) as o_nums from silver_service_month as m left join silver_service_completion c on m.month=c.service_month left join silver_user as u on c.uid=u.id where u.job_num=".$job_num." and c.addtime between ".$start." and ".$end." group by m.month");
		// }else{
		// 	$res=Db::query("select m.*,sum(c.p_num) as p_num,sum(c.o_num) as o_num,sum(c.p_nums) as p_nums,sum(c.o_nums) as o_nums from silver_service_month as m left join silver_service_completion c on m.month=c.service_month where c.addtime between ".$start." and ".$end." group by m.month");
		// }
		if($branch_id){
			$res[]=Db::query("select sum(s.p_num) as p_num,sum(s.o_num) as o_num,sum(s.p_nums) as p_nums,sum(s.o_nums) as o_nums,b.name from silver_service_completion as s left join  silver_user as u on s.uid=u.id join  silver_branch_office b on u.branch_id=b.id where u.branch_id=".$branch_id." and s.addtime between ".$start." and ".$end);
		}else{
			$res[]=Db::query("select sum(p_num) as p_num,sum(o_num) as o_num,sum(p_nums) as p_nums,sum(o_nums) as o_nums from silver_service_completion  where addtime between ".$start." and ".$end);
			$branch = db('branch_office')->field('id')->select();
			foreach ($branch as $key => $value) {
				$arr=Db::query("select sum(s.p_num) as p_num,sum(s.o_num) as o_num,sum(s.p_nums) as p_nums,sum(s.o_nums) as o_nums,b.name from silver_service_completion as s left join  silver_user as u on s.uid=u.id join  silver_branch_office b on u.branch_id=b.id where u.branch_id=".$value['id']." and s.addtime between ".$start." and ".$end);
				// $arr = Db::name('service_completion')->alias('s')->field(['SUM(s.p_num) as p_num','SUM(s.o_num) as o_num','SUM(s.p_nums) as p_nums','SUM(s.o_nums) as o_nums','u.job_num','u.username','u.branch_id','b.name'])->join('silver_user u','s.uid=u.id','LEFT')->join('silver_branch_office b','b.id = u.branch_id')->where('s.addtime','between time',[$start,$end])->where('u.branch_id',$value['id'])->select();

				array_push($res,$arr);
			}
		}
		return $res;
		
	}

	public function prerate($branch_id='',$job_num='',$start='',$end='')
	{
		if($job_num){

			$res=Db::name('service_completion')->alias('s')->field(['SUM(s.p_num) as p_num','SUM(s.o_num) as o_num','SUM(s.p_nums) as p_nums','SUM(s.o_nums) as o_nums','u.job_num','u.username','u.branch_id'])->join('silver_user u','s.uid=u.id','LEFT')->where('s.addtime','between time',[$start,$end])->where('u.id',$job_num)->paginate(30);
//			 var_dump($res);
		}elseif($branch_id){
            $user = db('user')->where('department',3)->where('branch_id',$branch_id)->select();

			 // $res=Db::name('user')->alias('u')->field(['SUM(s.p_num) as p_num','SUM(s.o_num) as o_num','SUM(s.p_nums) as p_nums','SUM(s.o_nums) as o_nums','u.job_num','u.username','u.branch_id'])->join('silver_service_completion s','u.id=s.uid','LEFT')->where('silver_service_completion.addtime','between time',[$start,$end])->where('u.branch_id',$branch_id)->order('s.addtime desc')->group('u.id')->paginate(5);
            $data=Db::name('service_completion')
                ->alias('s')
                ->field(['SUM(s.p_num) as p_num','SUM(s.o_num) as o_num','SUM(s.p_nums) as p_nums','SUM(s.o_nums) as o_nums','u.job_num','u.username','u.branch_id'])->join('silver_user u','s.uid=u.id','LEFT')
                ->where('s.addtime','between time',[$start,$end])
                ->where('u.branch_id',$branch_id)
                ->group('s.uid')
                ->paginate(30);

            foreach($user as $key=>$value){
                foreach ($data as $k=>$v){
                    if ($value['username'] == $v['username']){
                        $str = $v;
                        unset($v);
                        break;
                    }else{
                        $str['p_num'] = 0;
                        $str['o_num'] = 0;
                        $str['p_nums'] = 0;
                        $str['o_nums'] = 0;
                        $str['job_num'] = $value['job_num'];
                        $str['username'] = $value['username'];
                        $str['branch_id'] = $value['branch_id'];
                    }



                }
                $res[] = $str;
            }
		}else{
		    $user = db('user')->where('department',3)->select();
            $data=Db::name('user')
                ->alias('u')
                ->join('silver_service_completion','u.id=silver_service_completion.uid',"LEFT")
                ->field(['SUM(silver_service_completion.p_num) as p_num','SUM(silver_service_completion.o_num) as o_num','SUM(silver_service_completion.p_nums) as p_nums','SUM(silver_service_completion.o_nums) as o_nums','u.job_num','u.username','u.branch_id'])
                ->where('silver_service_completion.addtime','between time',[$start,$end])
                ->group('silver_service_completion.uid')
                ->select();
		    foreach($user as $key=>$value){
                foreach ($data as $k=>$v){
                    if ($value['username'] == $v['username']){
                        $str = $v;
                        unset($v);
                        break;
                    }else{
                        $str['p_num'] = 0;
                        $str['o_num'] = 0;
                        $str['p_nums'] = 0;
                        $str['o_nums'] = 0;
                        $str['job_num'] = $value['job_num'];
                        $str['username'] = $value['username'];
                        $str['branch_id'] = $value['branch_id'];
                    }



                }
                $res[] = $str;
            }



		}
		return $res;
	}

	public function userLists($id)
	{
		$res=Db::name('user')->field('id,username')->where('department',3)->where('branch_id',$id)->select();
		return $res;
	}

	public function export($branch_id='',$job_num='',$start='',$end='')
	{
		if($job_num){
			$res=Db::name('service_completion')->alias('s')->field(['SUM(s.p_num) as p_num','SUM(s.o_num) as o_num','SUM(s.p_nums) as p_nums','SUM(s.o_nums) as o_nums','u.job_num','u.username','b.name'])->join('silver_user u','s.uid=u.id','LEFT')->join('silver_branch_office b','b.id = u.branch_id')->where('s.addtime','between time',[$start,$end])->where('u.id',$job_num)->select();
			// var_dump($res);
		}elseif($branch_id){

            $user = db('user')->where('department',3)->where('branch_id',$branch_id)->select();

            // $res=Db::name('user')->alias('u')->field(['SUM(s.p_num) as p_num','SUM(s.o_num) as o_num','SUM(s.p_nums) as p_nums','SUM(s.o_nums) as o_nums','u.job_num','u.username','u.branch_id'])->join('silver_service_completion s','u.id=s.uid','LEFT')->where('silver_service_completion.addtime','between time',[$start,$end])->where('u.branch_id',$branch_id)->order('s.addtime desc')->group('u.id')->paginate(5);
            $data=Db::name('service_completion')
                ->alias('s')
                ->field(['SUM(s.p_num) as p_num','SUM(s.o_num) as o_num','SUM(s.p_nums) as p_nums','SUM(s.o_nums) as o_nums','u.job_num','u.username','u.branch_id'])->join('silver_user u','s.uid=u.id','LEFT')
                ->where('s.addtime','between time',[$start,$end])
                ->where('u.branch_id',$branch_id)
                ->group('s.uid')
                ->paginate(30);

            foreach($user as $key=>$value){
                foreach ($data as $k=>$v){
                    if ($value['username'] == $v['username']){
                        $str = $v;
                        unset($v);
                        break;
                    }else{
                        $str['p_num'] = 0;
                        $str['o_num'] = 0;
                        $str['p_nums'] = 0;
                        $str['o_nums'] = 0;
                        $str['job_num'] = $value['job_num'];
                        $str['username'] = $value['username'];
                        $str['branch_id'] = $value['branch_id'];
                    }



                }
                $res[] = $str;
            }
		}else{
            $user = db('user')->where('department',3)->select();
            $data=Db::name('user')
                ->alias('u')
                ->join('silver_service_completion','u.id=silver_service_completion.uid',"LEFT")
                ->field(['SUM(silver_service_completion.p_num) as p_num','SUM(silver_service_completion.o_num) as o_num','SUM(silver_service_completion.p_nums) as p_nums','SUM(silver_service_completion.o_nums) as o_nums','u.job_num','u.username','u.branch_id'])
                ->where('silver_service_completion.addtime','between time',[$start,$end])
                ->group('silver_service_completion.uid')
                ->select();
            foreach($user as $key=>$value){
                foreach ($data as $k=>$v){
                    if ($value['username'] == $v['username']){
                        $str = $v;
                        unset($v);
                        break;
                    }else{
                        $str['p_num'] = 0;
                        $str['o_num'] = 0;
                        $str['p_nums'] = 0;
                        $str['o_nums'] = 0;
                        $str['job_num'] = $value['job_num'];
                        $str['username'] = $value['username'];
                        $str['branch_id'] = $value['branch_id'];
                    }



                }
                $res[] = $str;
            }
		}
		return $res;
	}
}

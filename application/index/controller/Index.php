<?php
namespace app\index\controller;
use app\index\model\Admin;
use think\Controller;
use app\index\model\User;
use think\Session;
use think\Request;
/**
* 
*/
class Index extends Common
{
	public function index()
	{
	    $q=input('q');
	    $m=input('m');
	    $y=input('y');
	    $w=input('w');
	    $b=input('b');
	    $c=input('c');
	    $v=input('v');
	    $n=input('n');
		$user=new User();
		$pic=$user->pic();
		$arr=$user->pkList();
		$pkCompany=$user->pkCompany();
		$ratio=$user->serviceFirst();
		$ratio1=$user->serCompany();
		//分公司奥斯卡月度
        $month=$user->month();
        if ($q!=""){
            $month=$user->season($q);
            return $this->fetch('q1',['month'=>$month]);
        }
        //名人堂TOP123
        $bestnew=$user->bestnew1();
        if ($m!=""){
            $bestnew=$user->bestnew($m);
            return $this->fetch('m1',['bestnew'=>$bestnew]);
        }
        if ($y!=""){
            $bestnew=$user->wbest($y);
            return $this->fetch('y1',['bestnew'=>$bestnew]);
        }
        $trump=$user->trump1($w);
        if ($w!=""){
            $trump=$user->trump($w);
            return $this->fetch('w1',['trump'=>$trump]);
        }
        if ($b!=""&&$c!=""){
            $seasontrump=$user->seasontrump($b,$c);
            return $this->fetch('p1',['seasontrump'=>$seasontrump]);
        }
        if ($v!=""&&$n!=""){
            $yeartrump=$user->yeartrump($v,$n);
            return $this->fetch('p1',['seasontrump'=>$yeartrump]);
        }

		//是否已做答
		$admin=Session::get('admin');
		if($admin){
			$prp=1;
		}else{
			$prp=$user->prp();
		}
		return $this->fetch('',[
		    'pic'=>$pic,'arr'=>$arr,
            'pkCompany'=>$pkCompany,
            'ratio'=>$ratio,
            'ratio1'=>$ratio1,
            'prp'=>$prp,
            'month'=>$month,
            'q'=>$q,
            'bestnew'=>$bestnew,
            'trump'=>$trump,
        ]);
	}

//员工列表
	public function user_list()
	{
        
		if($_POST){
			$data=$_POST;
		}else{
			$data='';
		}
		
		$user=new User();
		$page=$user->user_list($data);
        // echo "<pre>";
        // print_r($page);
        // exit();
		$this->assign('page',$page);
		return $this->fetch('list');
	}
//添加员工
	public function add_user()
	{
		$user=new User();
		$role=$user->role();
        $department = db('department')->select();
        $branch = db('branch_office')->select();
		return $this->fetch("",['role'=>$role,'department'=>$department,'branch'=>$branch]);
	}
	//执行添加员工操作
	public function doadd()
	{
		$data=$_POST;
		$user=new user();
		$res=$user->add_user($data);
		if($res=="1"){
			$arr=array('code'=>200,'msg'=>'添加成功！');
			return returnmsg($arr);
		}
		if($res=="2"){
			$arr=array('code'=>404,'msg'=>'添加失败！');
			return returnmsg($arr);
		}
		if($res=="3"){
			$arr=array('code'=>404,'msg'=>'工号已存在，请重新输入！');
			return returnmsg($arr);
		}
		if($res=="4"){
			$arr=array('code'=>404,'msg'=>'身份证号已存在，请重新输入！');
			return returnmsg($arr);
		}
	}
	//上传身份证
	public function upload()
	{
	    // 获取表单上传文件 例如上传了001.jpg
	    $file = request()->file('file');
	    // 移动到框架应用根目录/public/uploads/ 目录下
	    if($file){
	        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
	        if($info){
	        	$getSaveName=str_replace("\\","/",$info->getSaveName());
	            // 成功上传后 获取上传信息
	            // var_dump($getSaveName);
	            // die;
	            // 输出 jpg
	            return json(['code' => 0, 'msg' => '上传成功!', 'url' => '/uploads/' . $getSaveName]);
	           
	        }else{
	            // 上传失败获取错误信息
	            echo $file->getError();
	        }
	    }
	}

	//多图片上传
    public function uploads(Request $request){
        //接收上传的文件
        $file = $this->request->file('file');
        if(!empty($file)){
            //图片存的路径
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            // 移动到框架应用根目录/public/uploads/ 目录下
            if($info){
                $getSaveName=str_replace("\\","/",$info->getSaveName());
                return json(['code' => 0, 'msg' => '上传成功!', 'url' => '/uploads/' . $getSaveName]);
            }else{
                // 上传失败获取错误信息
                $file->getError();
            }
        }
    }
	//加载修改页面

	public function edit()
	{
		$id=$_GET['id'];
		$user=new user();
		$res=$user->edit($id);
		$role=$user->role();
        $department = db('department')->select();
        $branch = db('branch_office')->select();
		if($res=="1"){
			echo "<script>alert('参数有误！请重试');</script>";
		}else{
			return $this->fetch("edit_user",['data'=>$res,'role'=>$role,'department'=>$department,'branch'=>$branch]);
		}
	}
	//执行修改操作
	public function doedit()
	{
		$data=$_POST;
		$user=new user();
		$res=$user->doedit($data);
		if($res=="1"){
			$arr=array('code'=>200,'msg'=>'更新数据成功！');
			return returnmsg($arr);
		}else{
			$arr=array('code'=>404,'msg'=>'没有任何数据修改');
			return returnmsg($arr);
		}
		
	}

	    //修改密码
    public function password(){
        // $id = input("get.id");
        $request=Request::instance();
        $id=$request->get('id');
        $data = User::get($id);
        $this->assign("data",$data->getData());
        $role=Session::get('role');
        $this->assign('rolecommon',$role);
        return $this->fetch();
    }
    //执行修改
    public function doEdd(){
            $data = input("post.");
            if($data['pass']==''){
                echo '错误';
            }else{
                if($data['password']==''){
                    echo '错误';
                }else{
                    if($data['password']!=$data['pass']){
                        echo '错误';
                    }else{
                        $User = new User();
                        $res = $User->editNode($data);
                        if($res['ok']==true){
                            echo json_encode($res);
                        }else{
                            echo json_encode($res);
                        }
                    }
                }

            }
	}
    //修改密码
    public function password1(){
        // $id = input("get.id");
        $request=Request::instance();
        $id=$request->get('id');
        $data = Admin::get($id);
        $this->assign("data",$data->getData());
        $role=Session::get('role');
        $this->assign('rolecommon',$role);
        return $this->fetch();
    }
    //执行修改
    public function doEdd1(){
        $data = input("post.");
        if($data['pass']==''){
            echo '错误';
        }else{
            if($data['password']==''){
                echo '错误';
            }else{
                if($data['password']!=$data['pass']){
                    echo '错误';
                }else{
                    $admin = new Admin();
                    $res = $admin->editNode($data);
                    if($res['ok']==true){
                        echo json_encode($res);
                    }else{
                        echo json_encode($res);
                    }
                }
            }

        }
    }

    //答题
    public function problem()
    {
    	$user=new User();
    	$res=$user->problem();
        $this->assign('data',$res);
        // var_dump($res);
		return $this->fetch();
    }
    //答题记录
    public function problemed()
    {
        $problem3="";
    	$request=Request::instance();
    	$data=$request->post();
    	if ($request->post('problem1')!=NULL && $request->post('problem2')!=NULL) {
            for ($i=0; $i <6 ; $i++) {
            $str='problem3'.$i; 
            if($request->post($str)!=NULL){
                $problem3.=$data[$str].',';
                unset($data[$str]);
            }
        }
            $data['problem3']=rtrim($problem3,',');
    		$user=new User();
    		$res=$user->problemed($data);
    		if($res['num1']==0){
    			$str1="1、";
    		}else{
    			$str1="";
    		}
    		if($res['num2']==0){
    			$str2="2、";
    		}else{
    			$str2="";
    		}
    		// if($res['num3']==0){
    		// 	$str3="3、";
    		// }else{
    		// 	$str3="";
    		// }
    		$str="";
    		$str=$str1.$str2;
    		if($str){
    			$arr=array('code'=>300,'msg'=>'第'.$str.'题错误！');
    		}else{
    			$arr=array('code'=>200,'msg'=>'全部做对!');
    		}
    	}else{
    		$arr=array('code'=>404,'msg'=>'请全部作答！');
    	}

    	return $arr;
    }
    //我的豆币
    public function beanlist()
    {
    	$request=Request::instance();
    	$id=$request->get('id');
    	$user=new User();
    	$beanlist=$user->beanlist($id);
    	return $this->fetch('',['bean'=>$beanlist]);
    }
}
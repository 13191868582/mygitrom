<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

//部门
use think\Db;
function branchname($name){

    $branch_id=Db::name('user')->where('username',$name)->value('branch_id');
    $branch_name=branch($branch_id);
    return $branch_name;
}
function department($id){
  if($id==1){
    return "财务";
  }
  if($id==2){
    return "新签销售";
  }
  if($id==3){
    return "续签客服";
  }
  if($id==4){
    return "网销宝客服";
  }
  if($id==5){
    return "回签部";
  }
  if($id==6){
        return "行政";
    }
  if($id==7){
        return "人力";
    }
  if($id==8){
        return "代运营";
    }
  if($id==9){
        return "分公司总经理";
    }
  if($id==10){
        return "董事长";
    }
  if($id==11){
        return "集团副总";
    }

}

//分公司

function branch_name(){
    $branch =\think\Db::name('branch_office')->select();
    return $branch;
}

//部门
function office(){
    $office=\think\Db::name('department')->select();
    return $office;
}
//状态
function u_status($rank)
{
  if($rank==1){
    return "在职";
  }
  if($rank==2){
    return "离职";
  }
  if($rank==3){
    return "休假";
  }
  if($rank==4){
    return "禁用";
  }
}


//分公司
function branch($id)
{
    if($id==0){
        return "集团";
    }
  if($id==1){
    return "石家庄分公司";
  }
  if($id==2){
    return "邯郸分公司";
  }
  if($id==3){
    return "衡水分公司";
  }
  if($id==4){
    return "沧州分公司";
  }
  if($id==5){
    return "廊坊分公司";
  }
  if($id==6){
    return "保定分公司";
  }
  if($id==7){
    return "白沟分公司";
  }
  if($id==8){
    return "邢台分公司";
  }
  if($id==9){
    return "西安分公司";
  }
  if($id==14){
      return "河北燕青";
  }

}

function br(){
    $branch=array(
        array("id"=>1,"name"=>"石家庄分公司"),
        array("id"=>2,"name"=>"邯郸分公司"),
        array("id"=>3,"name"=>"衡水分公司"),
        array("id"=>4,"name"=>"沧州分公司"),
        array("id"=>5,"name"=>"廊坊分公司"),
        array("id"=>6,"name"=>"保定分公司"),
        array("id"=>7,"name"=>"白沟分公司"),
        array("id"=>8,"name"=>"邢台分公司"),
        array("id"=>9,"name"=>"西安分公司"),
        array("id"=>14,"name"=>"燕青"),
    );
    return $branch;
}
function returnmsg($data)
{
  $res=json_encode($data);
  echo $res;
}

function position($id)
{
  if($id==1){
    return "总经理";
  }
  if($id==2){
    return "部门负责人";
  }
  if($id==3){
    return "电商负责人";
  }
}


function us($data)
{
$res=isset($data) ?$data : '';
return $res;
}
//本月开始和结束时间
function mFristAndLast($y="",$m=""){
 if($y=="") $y=date("Y");
 if($m=="") $m=date("m");
 $m=sprintf("%02d",intval($m));
 $y=str_pad(intval($y),4,"0",STR_PAD_RIGHT);
 $m>12||$m<1?$m=1:$m=$m;
 $firstday=strtotime($y.$m."01000000");
 $firstdaystr=date("Y-m-01",$firstday);
 $lastday = strtotime(date('Y-m-d 23:59:59', strtotime("$firstdaystr +1 month -1 day")));
 return array("firstday"=>$firstday,"lastday"=>$lastday);
}
//上个月第一天时间和最后一天时间
function lastTime()
{
  $start=strtotime(date('Ym01000000', strtotime('-1 month')));
    $end=strtotime(date('Ymt000000', strtotime('-1 month')));
    return array('start'=>$start,'end'=>$end);
}
function getAgeByID($id){ 
        
//过了这年的生日才算多了1周岁 
        if(empty($id)) return ''; 
        $date=strtotime(substr($id,6,8));
//获得出生年月日的时间戳 
        $today=strtotime('today');
//获得今日的时间戳 
        $diff=floor(($today-$date)/86400/365);
//得到两个日期相差的大体年数 
        
//strtotime加上这个年数后得到那日的时间戳后与今日的时间戳相比 
        $age=strtotime(substr($id,6,8).' +'.$diff.'years')>$today?($diff+1):$diff; 
  
        return $age; 
    } 
function arraySort($arr,$key,$type='asc'){
        $keyArr = []; // 初始化存放数组将要排序的字段值
        foreach ($arr as $k=>$v){
            $keyArr[$k] = $v[$key]; // 循环获取到将要排序的字段值
        }
        if($type == 'asc'){
            asort($keyArr); // 排序方式,将一维数组进行相应排序
        }else{
            arsort($keyArr);
        }
        foreach ($keyArr as $k=>$v){
            $newArray[$k] = $arr[$k]; // 循环将配置的值放入响应的下标下
        }
        $newArray = array_merge($newArray); // 重置下标
        return $newArray; // 数据返回
    }

    function getClientIp() {
      $ip = 'unknown';
      $unknown = 'unknown';
      if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown)) {
          // 使用透明代理、欺骗性代理的情况
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];

     } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], $unknow)) {
    // 没有代理、使用普通匿名代理和高匿代理的情况
         $ip = $_SERVER['REMOTE_ADDR'];
     }
    // 处理多层代理的情况
    if (strpos($ip, ',') !== false) {
        // 输出第一个IP
         $ip = reset(explode(',', $ip));
     }
    return $ip;
}

function getCity($ip)
{
   $url="http://ip.taobao.com/service/getIpInfo.php?ip=".$ip;
   $ipinfo=json_decode(file_get_contents($url)); 
   if($ipinfo->code=='1'){
       return false;
   }
   $city = $ipinfo->data->region.$ipinfo->data->city;
   return $city; 
}

function curl_post($url,$post_data)
{
    //初始化
    $curl = curl_init();
    //设置抓取的url
    curl_setopt($curl, CURLOPT_URL, $url);
    //设置头文件的信息作为数据流输出
    curl_setopt($curl, CURLOPT_HEADER, 1);
    //设置获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    //设置post方式提交
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
   //执行命令
    $data = curl_exec($curl);
    //关闭URL请求
    curl_close($curl);
     //显示获得的数据
     return $data;
   }
 function dosign($time,$appse){
  $s = hash_hmac('sha256', $time,$appse, true);
    $signature = base64_encode($s);
    $urlencode_signature = urlencode($signature);
    return $urlencode_signature;
 }
 //获取毫秒
 function getMillisecond() {
  list($s1, $s2) = explode(' ', microtime());
  return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
}

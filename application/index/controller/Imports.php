<?php
/*
 * execl导入阿里数据
 * 2019年10月30日10:09:47
 * 段文明
 */

namespace app\index\controller;
use think\Db;
use think\Request;
use app\index\model\Import;

class Imports extends Common
{

    public function  index(){
        return $this->fetch('');
    }
    public function upload_excel() {
        $insertsuccess=0;
        $updatesuccess=0;
        $noupdatesuccess=0;
        $upoverp4p=0;
        $request = \think\Request::instance();
        $file = $request->file('file');
        $save_path = ROOT_PATH . 'uploads/';
        $info = $file->move($save_path);
        $filename=$save_path . DIRECTORY_SEPARATOR . $info->getSaveName();
        if(file_exists($filename)) {//如果文件存在
            import('phpexcel.PHPExcel', EXTEND_PATH);
            if( strstr($filename,'.xlsx')){
                $PHPReader = new \PHPExcel_Reader_Excel2007();
            }else{
                $PHPReader = new \PHPExcel_Reader_Excel5();
            }
            //载入excel文件
            $PHPExcel = $PHPReader->load($filename);
            $sheet = $PHPExcel->getActiveSheet(0);//获得sheet
            $highestRow = $sheet->getHighestRow(); // 取得共有数据数
            $data=$sheet->toArray();
            for($i=1;$i<$highestRow-1;$i++){
                $import['memberid']=$data[$i][0];
                $import['company']=$data[$i][1];
                $import['sales']=$data[$i][2];
                $import['sales_executive']=$data[$i][3];
                $import['centennial_number']=$data[$i][4];
                $import['order_number']=$data[$i][5];
                $import['new_type']=$data[$i][6];
                $import['contact_times']=$data[$i][7];
                $import['single_time']=strtotime($data[$i][8]);
                $import['product_category']=$data[$i][9];
                $import['end_time']=strtotime($data[$i][10]);
                $import['send_time']=strtotime($data[$i][11]);
                $import['sendover_time']=strtotime($data[$i][12]);
                $import['now_sales']=$data[$i][13];
                $import['newsales_executive']=$data[$i][14];
                $import['single_money']=str_replace(',', '',$data[$i][15]);
                $import['account_money']=str_replace(',', '',$data[$i][16]);
                $import['over_time']=strtotime($data[$i][17]);
                $import['before_time']=strtotime($data[$i][18]);
                $import['bottom_number']=$data[$i][19];
                if ($import['product_category']=="网销宝充值包"){
                    $upp4p=Db::name('customer')
                        ->where('aliname',$import['memberid'])
                        ->find();
                    if ($upp4p){
                        $updop4p=Db::name('customer')
                            ->where('aliname',$import['memberid'])
                            ->update(['p4p'=>1]);
                        Db::name('customer')
                            ->where('aliname',$import['memberid'])
                            ->update(['w_sta'=>2]);
                        if ($updop4p){
                            $upoverp4p++;
                        }
                    }
                    if(strripos($import['sales_executive'],'石家庄')){
                        $oid=1;
                    }elseif(strripos($import['sales_executive'],'邯郸')){
                        $oid=2;
                    }elseif(strripos($import['sales_executive'],'衡水')){
                        $oid=3;
                    }elseif(strripos($import['sales_executive'],'沧州')){
                        $oid=4;
                    }elseif(strripos($import['sales_executive'],'廊坊')){
                        $oid=5;
                    }elseif(strripos($import['sales_executive'],'保定')){
                        $oid=6;
                    }elseif(strripos($import['sales_executive'],'白沟')){
                        $oid=7;
                    }elseif(strripos($import['sales_executive'],'邢台')){
                        $oid=8;
                    }elseif(strripos($import['sales_executive'],'西安')){
                        $oid=9;
                    }else{
                        $oid=0;
                    }
                    $ach['aliorder']=$import['order_number'];
                    $ach['aliname']=$import['memberid'];
                    $ach['company']=$import['company'];
                    $ach['type']=$import['new_type'];
                    $ach['ptype']=$import['product_category'];
                    $ach['totime']=$import['end_time'];
                    $ach['fintime']=$import['sendover_time'];
                    $ach['username']=$import['sales'];
                    $ach['endtime']=$import['over_time'];
                    $ach['money']=$import['account_money'];
                    $ach['oid']=$oid;
                    $ach['permonth']=strtotime(date("Y-m",$import['end_time']));
                    $ach['status']=0;
//                    $findones=Db::name('achievement')
//                        ->where('aliname', $ach['aliname'])
//                        ->where('type',$ach['type'])
//                        ->where('aliorder',$ach['aliorder'])
//                        ->field('id')
//                        ->find();
//
//                    if ($findones){
//                        db('achievement')->where('id',$findones['id'])->update($ach);
//                    }else{
//                        db('achievement')->insert($ach);
//                    }
                }
                $findone=Db::name('alimport')
                        ->where('memberid',$import['memberid'])
                        ->where('new_type',$import['new_type'])
                        ->where('order_number',$import['order_number'])
                        ->find();
                if (!empty($findone)){
                    unset($import['memberid']);
                    unset($import['new_type']);
                    unset($import['order_number']);
                    $up=Db::name('alimport')->where('id',$findone['id'])->update($import);
                    if ($up){
                        $updatesuccess++;
                    }else{
                        $noupdatesuccess++;
                    }
                }else{
                    $add=Db::name('alimport')->insert($import);
                    if ($add){
                        $insertsuccess++;
                    }else{
                        echo "添加失败";
                    }
                }
            }
            if($highestRow){
                $highestRow=$highestRow-2;
                $content['content']="总上传数据:".$highestRow.","."添加成功数据:".$insertsuccess.","."更新数据:".$updatesuccess.","."更新失败数据:".$noupdatesuccess.","."网销宝充值包更新:".$upoverp4p;
                $content['type']="到单";
                $content['time']=time();
                Db::name('import_log')
                    ->insert($content);
                $arr = array('code' =>200,'msg'=>"总上传数据:".$highestRow.","."添加成功数据:".$insertsuccess.","."更新数据:".$updatesuccess.","."更新失败数据:".$noupdatesuccess.","."网销宝充值包更新:".$upoverp4p);
            }else{
                $arr = array('code' =>404,'msg'=>'上传数据为空');
            }
            return returnmsg($arr);
        }else{
            return array("resultcode" => -5, "resultmsg" => "文件不存在", "data" => null);
        }

    }


    public function  logs(){
        $obj=new Import();
        $data=$obj->logs();
        return $this->fetch('',['data'=>$data]);
    }
//    public  function pr_log($log_content) {
//        $log_filename = ROOT_PATH .'logs'.DS ."log".DS.date("Ym").DS;
//        !is_dir($log_filename) && mkdir($log_filename, 0777, true) &&	chmod($log_filename, 0777);;
//
//        if(is_array($log_content)){
//            $log_content = JSONReturn($log_content);
//        }
//        file_put_contents($log_filename.date("d").'.log', '['.date("Y-m-d H:i:s").']' .PHP_EOL . $log_content . PHP_EOL."------------------------ --------------------------".PHP_EOL, FILE_APPEND);
//    }

}
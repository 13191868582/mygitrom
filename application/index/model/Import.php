<?php
/*
 * execl导入阿里数据
 * 2019年10月30日10:09:47
 * 段文明
 */

namespace app\index\model;
use think\Model;
use think\Db;
use think\Session;
class Import extends Model
{
    public function logs(){
        $data['data']=Db::name('import_log')
            ->paginate(100,false,['query' => request()->param()]);
        $data['page']=$data['data']->render();
        return $data;
    }
}
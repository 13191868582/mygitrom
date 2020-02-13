<?php
namespace app\api\model;

use think\Model;


class Notices extends Model
{
    function curl_post($url,$data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $output = curl_exec($ch);
        //关闭curl
        curl_close($ch);
        //返回数据
        return $output;
    }

    public function curl_get($url)
    {

        $info = curl_init();
        curl_setopt($info,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($info,CURLOPT_HEADER,0);
        // curl_setopt($info, CURLOPT_POST, 1);
        curl_setopt($info,CURLOPT_NOBODY,0);
        curl_setopt($info,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($info,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($info,CURLOPT_SSL_VERIFYHOST,false);
        curl_setopt($info,CURLOPT_URL,$url);
        $output = curl_exec($info);
        curl_close($info);
        return $output;
    }
}
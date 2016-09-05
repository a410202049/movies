<?php
class Http{
   // public function __construct() {
   //      header("Content-type: text/html; charset=utf-8");
   // }
   public function httpRequest($url,$method,$params=array()){
        if(trim($url)==''||!in_array($method,array('get','post'))||!is_array($params)){
            return false;
        }
        $result = "";
        $curl=curl_init();
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION ,1); //加入重定向处理
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($curl,CURLOPT_HEADER,0 ) ;
        curl_setopt($curl, CURLOPT_ENCODING, "gzip");
        switch($method){
            case 'get':
                $str='?';
                foreach($params as $k=>$v){
                    $str.=$k.'='.$v.'&';
                }
                $str=substr($str,0,-1);
                $url.=$str;
                curl_setopt($curl,CURLOPT_URL,$url);
                $result = curl_exec($curl);
            break;
            case 'post':
                curl_setopt($curl,CURLOPT_URL,$url);
                curl_setopt($curl,CURLOPT_POST,1 );
                curl_setopt($curl,CURLOPT_POSTFIELDS,$params);
                $result = curl_exec($curl);
            break;
            default:
                $result='';
            break;
        }
        if(isset($result)){
            $result=curl_exec($curl);
        }
        curl_close($curl);
        return $result;
    }
}

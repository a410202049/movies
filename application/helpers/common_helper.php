<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function recurse_copy($src,$dst) {  
	// 原目录，复制到的目录
    $dir = opendir($src);
    @mkdir($dst);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src . '/' . $file) ) {
                recurse_copy($src . '/' . $file,$dst . '/' . $file);
            }
            else {
                copy($src . '/' . $file,$dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

function deldir($dir) {
	//先删除目录下的文件：
	$dh=opendir($dir);
	while ($file=readdir($dh)) {
		if($file!="." && $file!="..") {
		  $fullpath=$dir."/".$file;
			  if(!is_dir($fullpath)) {
			      unlink($fullpath);
			  } else {
			      deldir($fullpath);
			  }
		}
	}
	closedir($dh);
	//删除当前文件夹：
	if(rmdir($dir)) {
		return true;
	} else {
		return false;
	}
}


/**
* [tree 递归分类]
*/
function tree($table,$p_id='0') {
    $tree = array();
    foreach($table as $row){
        if($row['pid']==$p_id){
            $tmp = tree($table,$row['id']);
            if($tmp){
                $row['child']=$tmp;
            }
            $tree[]=$row;                
        }
    }
    return $tree;    
}


/**
 * [array_sort 二维数组字段排序]
 * @param  array  $array [description]
 * @param  [type] $field [description]
 * @param  string $type  [description]
 * @return [type]        [description]
 */
function array_sort($array = array(),$field = "",$type = 'desc'){
    $flag=array();
    foreach($array as $key => $arr){
		$flag[$key]=$arr[$field];
    }
    $type == 'desc' ? array_multisort($flag, SORT_DESC, $array) : array_multisort($flag, SORT_ASC, $array);
    return $array;
}

/**
 * [curl_multi_fetch 多线程CURL]
 */
function curl_multi_fetch($urls) {
    $queue = curl_multi_init();
    $map = array();
    foreach ($urls as $key => $url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");

        // curl_setopt($ch, CURLOPT_POSTFIELDS, $post[$key]);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_NOSIGNAL, true);
        curl_multi_add_handle($queue, $ch);
        $map[(string) $ch] = $url;
    }
    $i = 0;
    $responses = array();
    do {
        while (($code = curl_multi_exec($queue, $active)) == CURLM_CALL_MULTI_PERFORM) ;
        if ($code != CURLM_OK) { break; }
        while ($done = curl_multi_info_read($queue)) {
            $error = curl_error($done['handle']);
            $results = curl_multi_getcontent($done['handle']);
            $responses[$i] = compact('error', 'results');
            curl_multi_remove_handle($queue, $done['handle']);
            curl_close($done['handle']);
            $i++;
        }
        if ($active > 0) {
            curl_multi_select($queue, 30);
        }
    } while ($active);
    curl_multi_close($queue);
    return $responses;
}

function dump($arr){
	echo "<pre>";
	print_r( $arr);
	echo "</pre>";
}

/*
*功能：php完美实现下载远程图片保存到本地
*参数：文件url,保存文件目录,保存文件名称，使用的下载方式
*当保存文件名称为空时则使用远程文件原来的名称
*/
function getImage($url,$save_dir='',$filename='',$type=0){
    if(trim($url)==''){
        return array('file_name'=>'','save_path'=>'','error'=>1);
    }
    if(trim($save_dir)==''){
        $save_dir='./';
    }
    if(trim($filename)==''){//保存文件名
        $ext=strrchr($url,'.');
        if($ext!='.gif'&&$ext!='.jpg'){
            return array('file_name'=>'','save_path'=>'','error'=>3);
        }
        $filename=time().$ext;
    }
    if(0!==strrpos($save_dir,'/')){
        $save_dir.='/';
    }
    //创建保存目录
    if(!file_exists($save_dir)&&!mkdir($save_dir,0777,true)){
        return array('file_name'=>'','save_path'=>'','error'=>5);
    }
    //获取远程文件所采用的方法 
    if($type){
        $ch=curl_init();
        $timeout=5;
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
        $img=curl_exec($ch);
        curl_close($ch);
    }else{
        ob_start(); 
        readfile($url);
        $img=ob_get_contents(); 
        ob_end_clean(); 
    }
    //$size=strlen($img);
    //文件大小 
    $fp2=@fopen($save_dir.$filename,'a');
    fwrite($fp2,$img);
    fclose($fp2);
    unset($img,$url);
    return array('file_name'=>$filename,'save_path'=>$save_dir.$filename,'error'=>0);
} 

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


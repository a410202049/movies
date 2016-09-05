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
function curl_multi_fetch($urlarr = array()) {
    $result = $res = $ch = array();
    $nch = 0;
    $mh = curl_multi_init();
    foreach($urlarr as $nk =>$url) {
        $timeout = 2;
        $ch[$nch] = curl_init();
        curl_setopt_array($ch[$nch], array(CURLOPT_URL =>$url, CURLOPT_HEADER =>false, CURLOPT_RETURNTRANSFER =>true, CURLOPT_TIMEOUT =>$timeout, ));
        curl_multi_add_handle($mh, $ch[$nch]); ++$nch;
    }
    /* wait for performing request */
    do {
        $mrc = curl_multi_exec($mh, $running);
    } while ( CURLM_CALL_MULTI_PERFORM == $mrc );
    while ($running && $mrc == CURLM_OK) {
        // wait for network           
        if (curl_multi_select($mh, 0.5) > -1) {
            // pull in new data;               
            do {
                $mrc = curl_multi_exec($mh, $running);
            } while ( CURLM_CALL_MULTI_PERFORM == $mrc );
        }
    }
    if ($mrc != CURLM_OK) {
        error_log("CURL Data Error");
    }
    /* get data */
    $nch = 0;
    foreach($urlarr as $moudle =>$node) {
        if (($err = curl_error($ch[$nch])) == '') {
            $res[$nch] = curl_multi_getcontent($ch[$nch]);
            $result[$moudle] = $res[$nch];
        } else {
            error_log("curl error");
        }
        curl_multi_remove_handle($mh, $ch[$nch]);
        curl_close($ch[$nch]); ++$nch;
    }
    curl_multi_close($mh);
    return $result;
}

function dump($arr){
	echo "<pre>";
	print_r( $arr);
	echo "</pre>";
}


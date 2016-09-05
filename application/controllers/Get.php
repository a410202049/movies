<?php
 set_time_limit(0);
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'third_party/QueryList/vendor/autoload.php';
use QL\QueryList;
class Get extends CI_Controller {
	public $baseUrl = 'http://www.seye6.com';
	public function __construct(){
		parent::__construct();
		$this->load->library('http');
	}

	public function index()
	{
		$page = $this->input->get('page');
		$pageNum = $page ? $page : '0';
		if($pageNum < '3700'){
			$pageNum++;
			$data = $this->gePage($pageNum);
			foreach ($data as $key => $value) {
				$this->db->insert('movie',array('id'=>$value['id'],'get_url'=>$value['url']));
			}
			echo "<script>location.href='".base_url('Get/index')."/?page=".$pageNum."'</script>";
		}
		// for($i = 1;$i<=3700;$i++){
		// 	$data = $this->gePage($i);
		// 	foreach ($data as $key => $value) {
		// 		$this->db->insert('movie',array('id'=>$value['id'],'get_url'=>$value['url']));
		// 	}
		// }
	}

	public function getDetail($url){
		$html = $this->http->httpRequest($url,'get');
		$reg = array(
		    'title'=> array('.mbox>.list>.rtl>a ','text'),
		    'update_time'=>array('.mbox>.list>.info>span ','text','',function($e){
		    	$datatime = str_replace("/","-",$e)." 00:00:00";
		    	return $datatime;
		    }),
		    'cover_img'=>array('.mbox>.list>.img>img ','src'),
		    'description'=>array('.pbox >p ','html'),
		    'play_url'=>array('#playlist>a','href','',function($e){
		    	$ci = &get_instance();
		    	$pUrl = $ci->baseUrl.$e;
		    	$ht = $ci->http->httpRequest($pUrl,'get');
		    	preg_match('/var nurl = \"(.*)\";/', $ht, $payurl);
		    	return $payurl[1];
		    })
		);

		$data = QueryList::Query($html,$reg);
		$ret = $data->getData();
		$images = QueryList::Query($html,array('link' => array('.pbox>.img>img','src')))->data;
		$retData = $ret[0];
		$retData['images'] = array_column($images, 'link');
		return $retData;
	}

	public function gePage($pageNum){
		$movies = array();
		$page = $this->baseUrl."/page".$pageNum."/";
		$html = $this->http->httpRequest($page,'get');
		$data = QueryList::Query($html,array(
		    'url'=> array('.list>h4>a ','href')
		))->data;
		foreach ($data as $key => $value) {
			$url = $this->baseUrl.$value['url'];
			preg_match('/\/(\d+)\.html/', $value['url'], $matches);
			$movies[$key]['id'] = $matches[1];
			$movies[$key]['url'] = $url;
			// $movies[$key]['detail'] = $this->getDetail($url);
		}
		return $movies;
	}
}

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
		if($pageNum <= '1031'){
			$pageNum++;
			$this->detailDb($pageNum);
			echo "<script>location.href='".base_url('Get/index')."/?page=".$pageNum."'</script>";
		}
	}

	/**
	 * [detailDb 详情入库]
	 * @return [type] [description]
	 */
	public function detailDb($nowindex){
		$this->db->limit(10,10 * ($nowindex - 1));
		$this->db->order_by('id');
		$ret = $this->db->select('*')->where(array('paly_url'=>NULL))->or_where(array('paly_url'=>''))->get('movie')->result_array();
		$urls = array_column($ret, 'get_url');
		$data = $this->getDetail($urls);
		foreach ($data as $key => $value) {
			if(!$this->db->get_where('movie',array('id'=>$value['id'],'paly_url'=>$value['paly_url']))->row_array()){
				$this->db->update('movie',array('name'=>$value['title'],'update_time'=>$value['update_time'],'cover_img'=>$value['cover_img'],'description'=>$value['description'],'paly_url'=>$value['paly_url']),array('id'=>$value['id']));
			}
			foreach ($value['images'] as $k => $v) {
				$this->db->insert('movie_images',array('movie_id'=>$value['id'],'url'=>$v));
			}
		}

	}

	/**
	 * [pageDb 列表入库]
	 * @return [type] [description]
	 */
	public function pageDb(){
		$page = $this->input->get('page');
		$pageNum = $page ? $page : '0';
		if($pageNum < '5'){
			$pageNum++;
			$data = $this->gePage($pageNum);
			foreach ($data as $key => $value) {
				// $this->db->insert('movie',array('id'=>$value['id'],'get_url'=>$value['url']));
				if(!$this->db->get_where('movie',array('id'=>$value['id']))->row_array()){
					$this->db->query('insert into `ed_movie` (`id`,`get_url`) values("'.$value['id'].'","'.$value['url'].'") ');
				}
			}
			echo "<script>location.href='".base_url('Get/index')."/?page=".$pageNum."'</script>";
		}
	}

	public function getDetail($urls){
		$htmls =curl_multi_fetch($urls);
		$reg = array(
		    'title'=> array('.mbox>.list>.rtl>a ','text'),
		    'update_time'=>array('.mbox>.list>.info>span ','text','',function($e){
		    	$datatime = str_replace("/","-",$e)." 00:00:00";
		    	return $datatime;
		    }),
		    'cover_img'=>array('.mbox>.list>.img>img ','src'),
		    'description'=>array('.pbox >p ','html'),
		    'paly_url'=>array('#playlist>a','href','',function($e){
		    	$ci = &get_instance();
		    	$pUrl = $ci->baseUrl.$e;
		    	$ht = curl_multi_fetch(array($pUrl));
		    	// $ht = $ci->http->httpRequest($pUrl,'get');
		    	preg_match('/var nurl = \"(.*)\";/', $ht[0]['results'], $payurl);
		    	return $payurl ? $payurl[1] : '';
		    }),
		    'id'=>array('.fav>a','onclick','',function($e){
		    	preg_match('/\/(\d+)\.html/', $e, $id);
		    	return $id[1];
		    })
		    // <span class="fav"><a href="javascript:void(0)" onclick='getFav("/xuesheng/1547.html","")'>
		);
		$results = array();
		foreach ($htmls as $key => $html) {
			$html = $html['results'];
			$data = QueryList::Query($html,$reg);
			$ret = $data->getData();
			$images = QueryList::Query($html,array('link' => array('.pbox>.img>img','src')))->data;
			$retData = $ret[0];
			$retData['images'] = array_column($images, 'link');
			$results[$key] = $retData;
		}
		return $results;
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
		}
		return $movies;
	}
}

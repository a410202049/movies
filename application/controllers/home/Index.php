<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends CI_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function index()
	{
		set_time_limit(0) ;
		$data = $this->db->limit(1000)->get_where('movie',array('cover_image'=>NULL))->result_array();
		// echo $this->db->last_query();
		// print_r($data);exit;
		foreach ($data as $key => $value) {
			$ret = getImage($value['cover_img'],'uploads/'.$value['id']);
			$this->db->update('movie',array('cover_image'=>$ret['save_path']),array('id'=>$value['id']));
			$da = $this->db->get_where('movie_images',array('movie_id'=>$value['id']))->result_array();
			foreach ($da as $k => $v) {
				$r = getImage($v['url'],'uploads/'.$value['id']);
				$this->db->update('movie_images',array('image_url'=>$r['save_path']),array('id'=>$v['id']));
			}
		}
	}
}

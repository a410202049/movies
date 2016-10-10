<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends Home_Base_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function index()
	{	

		$array['url'] = 'page';
        $array['tableName'] = 'movie';
        $array['seg'] = '2';
        $array['pre_page']='上一页';
        $array['next_page']='下一页';
        $retData = $this->page($array);
		$arr['retData'] = $retData;
		$arr['pager'] = $this->mypage_class;
		$this->twig->render('Index/index',$arr);
	}


	public function category($cid)
	{	
		$url = $this->uri->segment(1);
		$array['url'] = $url;
        $array['tableName'] = 'movie';
        $array['seg'] = '2';
        $array['pre_page']='上一页';
        $array['next_page']='下一页';
        $array['where'] = array('cid'=>$cid);
        $retData = $this->page($array);
		$arr['retData'] = $retData;
		$arr['pager'] = $this->mypage_class;
		$this->twig->render('Index/index',$arr);
	}

	public function detail($id){
		$movie = $this->db->get_where('movie',array('id'=>$id))->row_array();
		$movie_images = $this->db->get_where('ed_movie_images',array('movie_id'=>$id))->result_array();
		$rand_movie = $this->db->query('SELECT * FROM ed_movie ORDER BY RAND()  LIMIT 4')->result_array();
		$arr['rand_movie'] = $rand_movie;
		$arr['movie'] = $movie;
		$arr['movie_images'] = $movie_images;
		$this->twig->render('Index/detail',$arr);
	}
}

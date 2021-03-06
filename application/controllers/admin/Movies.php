<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Movies extends Admin_Auth_Base_Controller {
	public function __construct(){
		parent::__construct();
	}

	public function category(){
			$retData = 	$this->db->get('movie_category')->result_array();
			foreach ($retData as $key => $data) {
					$retData[$key]['name'] = $data['title'];
					$retData[$key]['parentid']= $data['pid'];
			}


			$this->load->library('tree');
			$this->tree->icon = array('&nbsp;&nbsp;&nbsp;','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
			$this->tree->nbsp = '&nbsp;&nbsp;&nbsp;';
			$tdStr = "<tr>
									<td>\$id</td>
									<td>\$spacer\$name</td>
									<td>\$keywords</td>
									<td>\$description</td>
									<td><a href='javascript:void(0)' class='btn btn-sm btn-primary edit-btn' data-id='\$id' title='编辑'><i class='fa fa-edit'></i></a> <a href='javascript:void(0)' class='btn btn-sm btn-danger delete-btn' data-id='\$id' title='删除'><i class='fa fa-trash'></td>
							</tr>";
			$this->tree->init($retData);
			$tr = $this->tree->get_tree(0, $tdStr);

			$this->twig->render(
				'Movies/category',array('tr'=>$tr)
			);
	}

	public function renderCategory(){
		$nid = $this->input->get('nid');
		$pid = 'false';
		$where = "";
		$navData = array();
		 if($nid){
            $navData = $this->db->get_where('movie_category',array('id'=>$nid))->row_array();
            $pid = $navData['pid'];
            $where = " where id != ".$nid;
        }
		$arr['navigation'] = $navData;
        $arr['nid'] = $nid;
        $arr['pid'] = $pid;
        $arr['btn_type'] = $nid ? 'edit' : 'add';
        $sql = "select * from ed_movie_category".$where;
        $arr['categorys'] = $this->db->query($sql)->result_array();
				$this->twig->render(
					'Movies/renderCategory',$arr
				);
	}

	public function editNavigation(){
        $arr = $this->input->post();
        $keywords = $arr['keywords'];
        $description = $arr['description'];
        $pid = $arr['pid'] ? $arr['pid'] : '0';

        if($arr['type'] == 'add'){
            $data_arr = array(
                'description'=>$description,
                'keywords'=>$keywords,
                'pid'=>$pid,
                'title'=>$arr['title']
            );
            $this->db->insert('movie_category',$data_arr);
            $this->response_data('1','导航添加成功');
        }else if($arr['type'] == 'edit'){
            $nid = $arr['nid'];
            $data_arr = array(
				'description'=>$description,
				'keywords'=>$keywords,
				'pid'=>$pid,
				'title'=>$arr['title']
            );
            $this->db->update('movie_category',$data_arr,array('id'=>$nid));
            $this->response_data('1','编辑成功');
        }
	}

	public function delCategory(){
		$arr = $this->input->post();
		$nid = $arr['nid'];
		$this->db->delete('movie_category',array('id'=>$nid));
		$this->response_data('1','删除成功');
	}

	public function movieList(){
		$page['url'] = 'admin/Movies/movieList';
        $array['query'] = 'select m.*,c.title from ed_movie as m left join ed_movie_category as c on m.cid = c.id';
        
        $retData = $this->page($array);
		$arr['retData'] = $retData;
		$arr['pager'] = $this->mypage_class;
		$this->twig->render(
			'Movies/movieList',$arr
		);
	}

	public function renderMovie(){
		$mid = $this->input->get('mid');
		$arr = array();
        $sql = "select * from ed_movie_category";
        $arr['categorys'] = $this->db->query($sql)->result_array();
        $arr['btn_type'] = $mid ? 'edit' : 'add';
        if($mid){
            $movie = $this->db->get_where('movie',array('id'=>$mid))->row_array();
            // print_r($movie);exit;
            $arr['movie'] = $movie;
            $arr['mid'] = $mid;
        }
		$this->twig->render(
			'Movies/renderMovie',$arr
		);
	}


	public function editMovie(){
        $arr = $this->input->post();
        $director = $arr['director'];
        $description = $arr['description'];
        $cid = $arr['cid'];
        $cover_img = $arr['cover_img'];
        $name = $arr['name'];
        $paly_url = $arr['paly_url'];
        $release_time = $arr['release_time'];
        if($arr['type'] == 'add'){
            $data_arr = array(
                'director'=>$director,
                'description'=>$description,
                'cid'=>$cid,
                'name'=>$name,
                'cover_img'=>$cover_img,
                'paly_url'=>$paly_url,
                'update_time' =>date('Y-m-d H:i:s', time()),
                'release_time' =>$release_time

            );
            $this->db->insert('movie',$data_arr);
            $this->response_data('1','添加成功');
        }else if($arr['type'] == 'edit'){
            $mid = $arr['mid'];
            $data_arr = array(
                'director'=>$director,
                'description'=>$description,
                'cid'=>$cid,
                'name'=>$name,
                'cover_img'=>$cover_img,
                'paly_url'=>$paly_url,
                'release_time' =>$release_time,
                'update_time' =>date('Y-m-d H:i:s', time())

            );
            $this->db->update('movie',$data_arr,array('id'=>$mid));
            $this->response_data('1','编辑成功');
        }
	}

}

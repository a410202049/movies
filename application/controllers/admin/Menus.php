<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Menus extends Admin_Auth_Base_Controller {
	public function __construct(){
		parent::__construct();
	}

	public function index(){
			$retData = 	$this->db->get('menus')->result_array();
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
									<td>\$sort</td>
									<td>\$target</td>
									<td><a href='javascript:void(0)' class='btn btn-sm btn-primary edit-btn' data-id='\$id' title='编辑'><i class='fa fa-edit'></i></a> <a href='javascript:void(0)' class='btn btn-sm btn-danger delete-btn' data-id='\$id' title='删除'><i class='fa fa-trash'></td>
							</tr>";
			$this->tree->init($retData);
			$tr = $this->tree->get_tree(0, $tdStr);

			$this->twig->render(
				'Menus/index',array('tr'=>$tr)
			);
	}

	public function renderNavigation(){
		$nid = $this->input->get('nid');
		$pid = 'false';
		$where = "";
		$navData = array();
		 if($nid){
            $navData = $this->db->get_where('menus',array('id'=>$nid))->row_array();
            $pid = $navData['pid'];
            $where = " where id != ".$nid;
        }
				$arr['navigation'] = $navData;
        $arr['nid'] = $nid;
        $arr['pid'] = $pid;
        $arr['btn_type'] = $nid ? 'edit' : 'add';
        $sql = "select * from ed_menus".$where;
        $arr['categorys'] = $this->db->query($sql)->result_array();
				$this->twig->render(
					'Menus/renderNavigation',$arr
				);
	}

	public function editNavigation(){
        $arr = $this->input->post();
        $target = $arr['target'];
        $sort = $arr['sort'];
        $pid = $arr['pid'] ? $arr['pid'] : '0';

        if(!$arr['url']){
            $this->response_data('0','URL不能为空');
        }

        if($arr['type'] == 'add'){
            $data_arr = array(
                'create_time'=>date('Y-m-d H:i:s', time()),
                'sort'=>$sort,
                'target'=>$target,
                'pid'=>$pid,
                'url'=>$arr['url'],
                'title'=>$arr['title']
            );
            $this->db->insert('menus',$data_arr);
            $this->response_data('1','导航添加成功');
        }else if($arr['type'] == 'edit'){
            $nid = $arr['nid'];
            $data_arr = array(
							'sort'=>$sort,
							'target'=>$target,
							'pid'=>$pid,
							'url'=>$arr['url'],
							'title'=>$arr['title']
            );
            $this->db->update('menus',$data_arr,array('id'=>$nid));
            $this->response_data('1','导航编辑成功');
        }
	}

	public function delNavigation(){
		$arr = $this->input->post();
		$nid = $arr['nid'];
		$this->db->delete('menus',array('id'=>$nid));
		$this->response_data('1','删除成功');
	}

}

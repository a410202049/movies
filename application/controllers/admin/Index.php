<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Index extends Admin_Auth_Base_Controller {
	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$this->twig->render(
			'Index/index'
		);
	}


	/**
	 * [sysHome 系统首页]
	 * @return [type] [description]
	 */
	public function sysHome(){
			$sys_info['ip'] = $_SERVER['SERVER_ADDR'];
			$sys_info['web_server'] = $_SERVER['SERVER_SOFTWARE'];
			$sys_info['gd'] = extension_loaded("gd") ? 'yes' : 'no';
			$sys_info['max_filesize'] = ini_get('upload_max_filesize');
			$sys_info['PHP_VERSION'] = PHP_VERSION;
			$info = $this->db->query("select VERSION() as mysql_version")->row_array();
			$sys_info['mysql_get_server_info'] = $info['mysql_version'];
			$sys_info['os'] = PHP_OS;
			// $sys_info['logs'] = $this->db->get('admin_log',5,0)->result_array();
			$this->twig->render('Index/sysHome',$sys_info);
	}

	/**
	 * [siteSeting 站点设置]
	 */
	public function siteSeting(){
				$data = $this->db->get('site_seting')->row_array();
				$this->twig->render('Index/siteSeting',$data);
	}

	public function saveSeting(){
		$arr = $this->input->post();
				$dataArr = array(
						'sitename'=>$arr['sitename'],
						'description'=>$arr['description'],
						'email' =>$arr['email'],
						'phone' =>$arr['phone'],
						'address' =>$arr['address'],
						'keywords'=>$arr['keywords'],
						'statistical_code'=>$arr['statistical_code'],
						'record_number'=>$arr['record_number']
				);
				$this->db->update('site_seting',$dataArr);
				$this->response_data('1','保存成功');
	}

}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends Admin_Base_Controller {

    public function __construct()
    {
        parent::__construct();
    }

	public function index()
	{
    if($this->isLogin()){
      redirect(base_url('admin/Index'));
    }else{
      $this->twig->render(
        'Login/index',
        array('error_flashdata'=>$this->session->flashdata('error'))
      );
    }
	}


	/**
	 * 登陆
	 */
	public function do_login(){
		if(!isset($_SESSION)){
			session_start();
		}
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$userData = $this->db->get_where('admin',array('username'=>$username))->row_array();
		if($userData['password'] != md5($password)) {
			$this->session->set_flashdata('error','用户名或密码错误');
		}else if($userData && !$userData['status']){
			$this->session->set_flashdata('error','该用户已经被禁用，请联系管理员');
		}
		if($this->session->flashdata('error')){
			redirect(base_url('admin/Login'));
		}
		$this->session->set_userdata('uid',$userData['id']);
		$this->db->update('admin', array('last_login_time'=>date('Y-m-d H:i:s', time()),'last_login_ip'=>$this->input->ip_address()), array('id'=>$userData['id']));
		redirect(base_url('admin/Index'));

	}

	/**
	 * 验证码
	 */
	public function code(){
		$config = array(
			'width'	=>	80,
			'height'=>	35,
			'codeLen'=>	4,
			'fontSize'=>16
			);
		$this->load->library('code', $config);
		$this->code->show();
	}

	/**
	 * 退出登陆
	 */
	public function logout(){
		$this->session->sess_destroy();
		redirect(base_url('admin/Login'));
	}


}

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


}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common extends Base_Controller {
	
	/**
	 * 上传图片
	 */
	public function doUpload()
	{	
		$filepath = $this->input->get('filepath');
		if(!empty($filepath)){
			$config['upload_path']  = './uploads/'.$filepath;

	        if(!is_dir($config['upload_path'])){
	        	mkdir($config['upload_path'],0777,true);
	        }
	        $filepath = $filepath.'/';
		}else{
	    	$config['upload_path']      = './uploads/';
	    	$filepath = '';
		}
	    $config['allowed_types']    = 'gif|jpg|png';
	    $config['file_name']  = time(); //文件名不使用原始名
	    $this->load->library('upload', $config);
	    if ( ! $this->upload->do_upload('file'))
	    {	
	        $this->response_data('error', strip_tags($this->upload->display_errors()));
	    }
	    else
	    {
	        $data =$this->upload->data();
	        $arr['file_name'] = '/uploads/'.$filepath.$data['file_name'];

	        $is_cover = $this->input->get('is_cover');
	        if($is_cover){
		       	$this->load->library('image');
	            $this->image->load('.'.$arr['file_name'])->quality(0)->size(250, 160)->fixed_given_size(true)->save('.'.$arr['file_name']);
	        }

	        $this->response_data('1','上传成功',$arr);
	    }
	}

}

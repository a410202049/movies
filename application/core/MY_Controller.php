<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Base_Controller extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 判断管理员是否登录
     */
    public function isLogin()
    {
        return $this->session->userdata('uid');
    }

    /**
     * [getUid 根据uid获取管理员信息]
     */
    public function getUid($uid){
        $data = $this->db->get_where('admin', array('id'=>$uid))->row_array();
        return $data;
    }

    /**
     * @name 错误跳转公共方法
     * @param string $message 错误提示信息
     * @param number $time 跳转时间
     * @param string $url 跳转的URL
     */
    public function error($message,$url = FALSE,$time = 3)
    {
        if( !$url ) {
            $data['url'] = 'javascript:history.back(-1);';
        } else {
            $data['url'] = $url;
        }
        $data['message'] = $message;
        $data['time'] = $time;
        die($this->load->view('public/error',$data,true));
    }

    /**
     * @name 成功跳转公共方法
     * @param string $message 成功提示信息
     * @param number $time 跳转时间
     * @param string $url 跳转的URL
     */
    public function success($message,$url = FALSE,$time = 3)
    {
        if( !$url ) {
            $data['url'] = 'javascript:history.back(-1);';
        } else {
            $data['url'] = $url;
        }
        $data['message'] = $message;
        $data['time'] = $time;
        die($this->load->view('public/success',$data,true));
    }


    /**
     * ajax返回
     */
    function response_data($status,$message = "",$data = array()){
        $this->output->set_header('Content-Type: application/json; charset=utf-8');
        $array= array(
            'status' =>$status,
            'message' => $message,
            'data' =>$data
        );
        echo json_encode($array);
        exit;
    }

    /**
     * 分页函数
     */
    function page($array){
        $perpage = isset($array['perpage']) ? $array['perpage'] : '15';
        $part = isset($array['part']) ? $array['part'] : '2';
        $url = isset($array['url']) ? $array['url'] : '';
        $seg = isset($array['seg']) ? $array['seg'] : '4';
        $tableName = isset($array['tableName']) ? $array['tableName'] : '';
        $where = isset($array['where']) ? $array['where'] : '1=1';
        $page_config['perpage']=$perpage;   //每页条数
        $page_config['part']=$part;//当前页前后链接数量
        $page_config['url']=$url;//url
        $page_config['pre_page']='<span aria-hidden="true">«</span>';
        $page_config['next_page']='<span aria-hidden="true">»</span>';//url
        $page_config['seg']=$seg;//参数取 index.php之后的段数，默认为3，即index.php/control/function/18 这种形式
        $page_config['nowindex']=$this->uri->segment($page_config['seg']) ? $this->uri->segment($page_config['seg']):1;//当前页
        $this->load->library('mypage_class');
        if(isset($array['query'])){
            $query = $this->db->query($array['query']);
            $page_config['total'] = count($query->result_array());
        }else{
            $page_config['total'] = $this->db->where($where)->count_all_results($tableName);
        }
        $this->mypage_class->initialize($page_config);
        $this->db->limit($page_config['perpage'],$page_config['perpage'] * ($page_config['nowindex'] - 1));
        if(isset($array['query'])){
            $data = $this->db->query($array['query'].' LIMIT '.$page_config['perpage'] * ($page_config['nowindex'] - 1).','.$page_config['perpage'])->result_array();
        }else{
            $data = $this->db->order_by('id','desc')->get_where($tableName,$where)->result_array();
        }
        return $data;
    }

}

class Admin_Base_Controller extends Base_Controller{
    public function __construct()
    {
        parent::__construct();
        $this->config->load('twig');
        $twig_config = $this->config->item('twig');//后台模板引擎设置
        $this->load->library('Twig',$twig_config);
    }

}

class Admin_Auth_Base_Controller extends Admin_Base_Controller{
    public function __construct()
    {
        parent::__construct();
        $this->uid = $this->isLogin();
        if(!$this->uid){
          redirect(base_url('admin/Login'));
        }
        $this->load->library('auth');
        $group = $this->auth->getGroup($this->uid);
        $this->groupid = $group['group_id'];
        $where = array('pid'=>'0','isshow'=>'1');
        $data = $this->db->order_by('sort', 'asc')->get_where('auth_rule',$where)->result_array();
        foreach ($data as $k=>$v){
            if(!$this->auth->check($v['method'], $this->uid) && $this->uid != 1 && $this->groupid !=1 ){
                unset($data[$k]);
            }else{
                $data[$k]['sub'] = $this->db->from('auth_rule')->where(array('pid'=>$v['id'],'isshow'=>'1'))->order_by('sort', 'asc')->get()->result_array();
                foreach ($data[$k]['sub'] as $k2 => $v2){
                    if(!$this->auth->check($v2['method'], $this->uid) && $this->uid != 1 && $this->groupid !=1 ){
                        unset($data[$k]['sub'][$k2]);
                    }
                }
            }
        }
        $objResult = $this->db->select('admin.*,auth_group.id as gid,auth_group.title')->from('admin')->join('auth_group_access', 'auth_group_access.uid = admin.id')->join('auth_group', 'auth_group_access.group_id = auth_group.id')->where(array('admin.id'=>$this->uid))->get()->row_array();
        $this->twig->assign('navs',$data);
        $this->twig->assign('userinfo',$objResult);
        // $not_check = array('SiteManage/index','BlockManage/renderEditItem','BlockManage/renderEditBlock','ArticleManage/renderEditArticle');
        // //当前操作的请求                 模块名/方法名
        // if(in_array($this->router->fetch_class().'/'.$this->router->fetch_method(), $not_check)){
        //     return true;
        // }
        if(!$this->auth->check($this->router->fetch_class().'/'.$this->router->fetch_method(),$this->uid) && $this->uid !=1 && $this->groupid !=1){
            if(IS_AJAX){
                $this->response_data('error','没有权限');
            }else{
                $this->error('没有权限');
            }
        }
    }

}

class Home_Base_Controller extends Base_Controller{
    public function __construct()
    {
        parent::__construct();
        $navs = $this->db->get_where('menus')->result_array();
        $this->config->load('twig');
        $twig_config = $this->config->item('twig_home');//后台模板引擎设置
        $this->load->library('Twig',$twig_config);
        $this->twig->assign('navs',$navs);
    }

}

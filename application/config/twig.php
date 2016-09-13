<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// // 默认扩展名
// $config['extension'] = ".twig";

// // 默认模版路劲
// $config['template_dir'] = APPPATH . "views/";

// // 缓存目录
// $config['cache_dir'] = APPPATH . "cache/twig/";

// // 是否开启调试模式
// $config['debug'] = false;

// // 自动刷新
// $config['auto_reload'] = true;

$config['twig'] = array(
	'extension'=> '.twig',
	'template_dir'=>"./template/admin/",
	'cache_dir'=> './template/admin/cache/',
	'debug'=>true,
	'auto_reload'=>true
);

$config['twig_home'] = array(
	'extension'=> '.twig',
	'template_dir'=>"./template/home/",
	'cache_dir'=> './template/home/cache/',
	'debug'=>true,
	'auto_reload'=>true
);

/* End of file twig.php */
/* Location: ./application/config/twig.php */
<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['email'] = array(
    // 'protocol' => 'smtp',
    // 'smtp_host' => 'smtp.exmail.qq.com',
    // 'smtp_user' => 'noreply@eptonic.com',
    // 'smtp_pass' => 'Eptonic2016',
    // 'smtp_port' => '465',
    // 'charset' => 'utf-8',
    // 'wordwrap' => true,
    // 'mailtype' => 'html',
    // 'smtp_crypto' => 'ssl',
    // 'newline'  => "\r\n",
    // 'crlf' => "\r\n"
    // 配置邮件发送服务器
    'MAIL_HOST' =>'smtp.exmail.qq.com',//smtp服务器的名称
    'MAIL_SMTPAUTH' =>TRUE, //启用smtp认证
    'MAIL_USERNAME' =>'noreply@eptonic.com',//你的邮箱名
    'MAIL_FROM' =>'noreply@eptonic.com',//发件人地址
    'MAIL_FROMNAME'=>'Eptonic',//发件人姓名
    'MAIL_PASSWORD' =>'Eptonic2016',//邮箱密码
    'MAIL_CHARSET' =>'utf-8',//设置邮件编码
    'MAIL_ISHTML' =>TRUE, // 是否HTML格式邮件
);



/* End of file twig.php */
/* Location: ./application/config/twig.php */

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="refresh" content="3; URL=login.php" />
<title>DouPHP 管理中心 -  </title>
<meta name="Copyright" content="Douco Design." />
<link href="<?php echo base_url('public/admin/css/public.css');?>" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo base_url('public/admin/js/jquery.min.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('public/admin/js/global.js');?>"></script>
</head>
<body>
<div id="outMsg">
 <h2><?php echo $message;?></h2>
 <dl>
  <dt>如果您不做出选择，将在 <?php echo $time;?> 秒后跳转到上一个链接地址。</dt>
  <dd><a href="<?php echo $url;?>">返回上一页</a></dd>
 </dl>
</div>
<script language="javascript">
    setTimeout("location.href='<?php echo $url;?>';",<?php echo $time*1000;?>);
</script>
</body>
</html>
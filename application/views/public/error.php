<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <title>Eptonic</title>
    <meta name="keywords" content="eptonic,easexport">
    <meta name="description" content="后台管理中心">
	<style>
		body{
			width: 100%;
			overflow: hidden;
			font-family: "verdana","Microsoft Yahei";
			color: #545454;
		}
		*{
			padding: 0;
			margin: 0;
		}
		#outMsg{
			width: 80%;
			margin: 0 auto;
			padding: 2em;
			margin-top: 2em;
			clear: both;
			overflow: hidden;
			text-align: center;
		}
		.err-img{
			height: 3em;
			margin-bottom: 1em;
/* 			border-bottom: 1px solid red; */
		}
		.err-img img{
			max-height: 90%;
		}
		#outMsg article{
			line-height:3em;
			margin: 0 1em;
			border: 1px solid #eee;
			border-top: 2px solid #45a1e2;
			padding: 1em;
		}
	</style>
</head>
<body>
<div id="outMsg">
	<div class="err-img">
		<a href="http://zh.eptonic.com" title="Watch Something Cool?">
			<img src="<?php echo base_url('public/admin/img/logo.png');?>" alt="">
		</a>
	</div>
	<article>
		<h3><?php echo $message;?></h3>
	</article>
</div>
</body>
</html>
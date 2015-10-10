<?php
$head   =	'';

$head  .= 	'<link rel="stylesheet" type="text/css" href="Public/css/css.css">'."\n\t";
$head  .= 	'<link rel="stylesheet" type="text/css" href="Public/bootstrap/css/bootstrap.min.css">'."\n\t";
$head  .= 	'<link rel="stylesheet" type="text/css" href="Public/bootstrap/css/bootstrap-responsive.min.css">'."\n\t";
$head  .= 	'<link rel="stylesheet" type="text/css" href="Public/easyui/themes/bootstrap/easyui.css">'."\n\t";
$head  .= 	'<link rel="stylesheet" type="text/css" href="Public/easyui/themes/icon.css">'."\n\t";


$head  .= 	'<script type="text/javascript" src="Public/js/jquery-1.7.2.min.js"></script>'."\n\t";
$head  .= 	'<script type="text/javascript" src="Public/bootstrap/js/bootstrap.js"></script>'."\n\t";
$head  .= 	'<script type="text/javascript" src="Public/easyui/jquery.easyui.min.js"></script>'."\n\t";
$head  .= 	'<script type="text/javascript" src="Public/js/js.js"></script>'."\n\t";

$head  .=	"\n";

$data = array(
				'base' 					=> 	base_url () . 'application/views/',
				'logout' 				=>	base_url () . 'labs/logout',
				'head'					=>	$head	 			
			);
$ipForbidden = '10.50.';
$message = array(
		'无法用此用户名登录：系统中已有用户以此用户名登录，请联系系统管理员！',
		'欢迎您使用前程无忧的Resume QA系统',
		'登陆失败，用户名或密码错误！',		
		'您已经120天未修改密码了，请先修改密码！'
);
$opr = array(
		array('徐峰','erasexu'),
		array('姚健骏','yaoyao'),
		array('孙夏寅','s.jacky'),
		array('周琳','tommy'),
		array('陈昌','chaney'),
		array('王宏斌','hongbin'),
		array('金宏昀','hongyun'),
		array('郑传强','cq.zheng'),
		array('张达','martin.z'),
		array('李龙','lilong'),
		array('刘超','liuchao')	,
		array('杨涛','tao.yang'),
		array('王炜','wangwei'),
		array('肖艺','yi.xiao'),
		array('王发靖','wangfajing'),
		array('王阳','wang.yang'),
		array('朱轩','zhu.xuan'),
		array('胡蒙','hu.meng'),
		array('刘顺宇','liu.shunyu'),
		array('陈龙','chen.long'),
		array('周宇蒙','z.yumeng')
);
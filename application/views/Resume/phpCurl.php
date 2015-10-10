<?php 
@header("Content-Type:text/html;charset=UTF-8");
if ( !empty($title))
	echo "<title>".$title."</title>";
if ( isset($jquery) && $jquery)
	echo '<script type="text/javascript" src="../application/views/public/js/jquery-1.7.2.min.js"></script>';
if ( isset($jsfile) && $jsfile)
	echo '<script type="text/javascript" src="../application/views/public/js/js.js"></script>';
if ( !empty($tool))
	echo $tool;
if ( !empty($html))
	echo $html;
if ( !empty($js))
	echo $js;
?>



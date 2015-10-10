<?php
if ( !empty($header))
@header("Content-Type:text/html;charset=UTF-8");
if ( isset($html))
	echo $html;
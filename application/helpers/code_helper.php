<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * 默认辅助器
 * @package Helper
 */
 

 

// XML-RPC  提交加密认证数据
if (!function_exists('xmlRpcEncode')) {
	function xmlRpcEncode($str) {
		return base64_encode(gzcompress(serialize($str)));
	}

}

// XML-RPC 接受数据解密
if (!function_exists('xmlRpcDecode')) {
	function xmlRpcDecode($str) {
		return unserialize(gzuncompress(base64_decode($str)));
	}

}

//

/* End of file  code_helper.php */
/* Location: application/helpers/code_helper.php */

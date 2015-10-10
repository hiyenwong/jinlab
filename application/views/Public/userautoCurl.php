<?php 

@header("Content-Type:text/html;charset=UTF-8");
set_time_limit(3600);

$ch = curl_init ( 'http://10.100.1.191/woims/index.php/user/userAjax' );
curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, TRUE );
curl_setopt ( $ch, CURLOPT_TIMEOUT, 60 );
curl_setopt ( $ch, CURLOPT_POST, true);
curl_setopt ( $ch, CURLOPT_POSTFIELDS, 'q='.$_POST['q']);
curl_setopt ( $ch, CURLOPT_PROXY, "");
$html = curl_exec ( $ch );
curl_close($ch);
if($html == NULL){
	echo "none";
}
else{
	$html = preg_replace('/[\n]+/is', '|', $html); //替换换行符
	$as = explode("|", $html);
	$re = "[";
	for($i=0;$i<count($as)/4-1;$i++){
		$re.='"'.$as[4*$i]."[".$as[4*$i+1]."]".'"'.",";	}
	$re.='"'.$as[count($as)-4]."[".$as[count($as)-3]."]".'"'."]";
	echo $re;
}
?>
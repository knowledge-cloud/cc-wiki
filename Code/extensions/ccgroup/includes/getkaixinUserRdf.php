<?php
include '../conf.php';
$access_token=$_REQUEST['access_token'];
//$access_token="174952|6.6c056548ee32e72066adc5ca3f3813db.2592000.1336201200-258943266";
$url="http://".$ccHost.":".$ccPort."/".$ccWiki."/includes/getkaixinUser.php?access_token=".$access_token;
$ch = curl_init($url);//打开
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
$response  = curl_exec($ch);
curl_close($ch);//关闭
$all=array();
$input = new DOMDocument ();
$input->loadXML($response);
$datas=$input->getElementsByTagName("user");
foreach ($datas as $data){
	$sns_id=$data->getElementsByTagName('id');
	$sns_id=$sns_id->item(0)->nodeValue;
	$all["sns_id"]=$sns_id;
	
	$name=$data->getElementsByTagName('name');
	$name=$name->item(0)->nodeValue;
	$all["name"]=$name;
	
	$avatar=$data->getElementsByTagName('url');
	$avatar=$avatar->item(0)->nodeValue;
	$all["avatar"]=$avatar;
	
	$source=$data->getElementsByTagName('cate');
	$source=$source->item(0)->nodeValue;
	$all["source"]=$source;
}
$result=json_encode($all);
//$result=urlencode($result);
echo $result;

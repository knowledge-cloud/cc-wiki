<?php
include '../conf.php';

$title=$_REQUEST["title"];
$source=$_REQUEST["source"];
$weibourl="http://".$ccHost.":".$ccPort."/".$ccWiki."/includes/weiboMashup.php?title=".$title."&source=".$source;
$ch = curl_init($weibourl);//打开
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
$weiboResponse  = curl_exec($ch);
curl_close($ch);//关闭


$all=array();
$weibo = new DOMDocument ();
$weibo->loadXML($weiboResponse);

$weibodatas=$weibo->getElementsByTagName("status");
$weibo_count=1;
foreach ($weibodatas as $weibodata){
	$weiboarray=array();
	$mid=$weibodata->getElementsByTagName('mid');
	$mid=$mid->item(0)->nodeValue;
	$weiboarray["mid"]=$mid;
	
	$status=$weibodata->getElementsByTagName('text');
	$status=$status->item(0)->nodeValue;
	$weiboarray["status"]=$status;
	
	$published_time=$weibodata->getElementsByTagName('time');
	$published_time=$published_time->item(0)->nodeValue;
	$weiboarray["published_time"]=$published_time;
	
	$source=$weibodata->getElementsByTagName('cate');
	$source=$source->item(0)->nodeValue;
	$weiboarray["source"]=$source;
	
	$person=array();
	
	$sns_id=$weibodata->getElementsByTagName('id');
	$sns_id=$sns_id->item(0)->nodeValue;
	$person["sns_id"]=$sns_id;
	
	$name=$weibodata->getElementsByTagName('name');
	$name=$name->item(0)->nodeValue;
	$person["name"]=$name;
	
	$avatar=$weibodata->getElementsByTagName('image');
	$avatar=$avatar->item(0)->nodeValue;
	$person["avatar"]=$avatar;
	
	$source=$weibodata->getElementsByTagName('cate');
	$source=$source->item(0)->nodeValue;
	$person["source"]=$source;
	
	$weiboarray["person"]=$person;
	
    $all["weibo_".$weibo_count]=$weiboarray;  
    $weibo_count++;
}
$result=json_encode($all);
//$result=urlencode($result);
echo $result;
?>

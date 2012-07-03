<?php
include '../conf.php';

$text=$_REQUEST["text"];
$photo_url="http://".$ccHost.":".$ccPort."/".$ccWiki."/includes/photo.php?text=".$text;
$ch = curl_init($photo_url);//打开
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
$response  = curl_exec($ch);
curl_close($ch);//关闭


$all=array();
$photo = new DOMDocument ();
$photo->loadXML($response);

$photodatas=$photo->getElementsByTagName("photo");
$photo_count=1;
foreach ($photodatas as $photodata){
	$photoarray=array();
	$photo_id=$photodata->getElementsByTagName('photo_id');
	$photo_id=$photo_id->item(0)->nodeValue;
	$photoarray["photo_id"]=$photo_id;
	
	$title=$photodata->getElementsByTagName('title');
	$title=$title->item(0)->nodeValue;
	$photoarray["title"]=$title;
	
	$description=$photodata->getElementsByTagName('description');
	$description=$description->item(0)->nodeValue;
	$photoarray["description"]=$description;
	
	$picture=$photodata->getElementsByTagName('picture');
	$picture=$picture->item(0)->nodeValue;
	$photoarray["picture"]=$picture;
	
    $all["photo_".$photo_count]=$photoarray;  
    $photo_count++;
}
$result=json_encode($all);
//$result=urlencode($result);
echo $result;
?>
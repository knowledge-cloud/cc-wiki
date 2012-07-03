<?php

function geturl($url){
	$ch = curl_init($url);//打开
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
	$response  = curl_exec($ch);
	curl_close($ch);//关闭
     return $response;	
}
$text=$_REQUEST["text"];
$searchurl="http://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=599026ae5650269bc315791db7971f70&format=json&nojsoncallback=1&text=".$text;
$response=geturl($searchurl);
$photos=json_decode($response,true);
$doc=new DOMDocument('1.0', 'UTF-8');
$doc->formatOutput=true;
$root=$doc->createElement("photos");
$doc->appendChild($root);
$count=0;
foreach ($photos['photos']['photo'] as $photo){
	if($count<10){
        $photoInfo=$doc->createElement("photo");
	$photo_id=$doc->createElement("photo_id");
	$photo_title=$doc->createElement("title");
	$photo_description=$doc->createElement("description");
	$photo_picture=$doc->createElement("picture");	
	
	$id=$photo['id'];
	$secret=$photo['secret'];
	$server=$photo['server'];
	$farm=$photo['farm'];
	$info_url="http://api.flickr.com/services/rest/?method=flickr.photos.getInfo&api_key=599026ae5650269bc315791db7971f70&format=json&nojsoncallback=1&photo_id=".$id;
	$response=geturl($info_url);
	$info=json_decode($response,true);
	$title=$info['photo']['title']["_content"];
	$description=$info['photo']['description']['_content'];
	$picture="http://farm".$farm.".static.flickr.com/".$server."/".$id."_".$secret.".jpg";
	
	$photo_id->appendChild($doc->createTextNode($id));
	$photo_title->appendChild($doc->createTextNode($title));
	$photo_description->appendChild($doc->createTextNode($description));
	$photo_picture->appendChild($doc->createTextNode($picture));
	$photoInfo->appendChild($photo_id);
	$photoInfo->appendChild($photo_title);
	$photoInfo->appendChild($photo_description);
	$photoInfo->appendChild($photo_picture);
	$root->appendChild($photoInfo);
        $count++;
}
}
echo $doc->saveXML();

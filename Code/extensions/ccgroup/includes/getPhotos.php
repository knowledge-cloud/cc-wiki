<?php
function geturl($url){
	$ch = curl_init($url);//打开
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
	$response  = curl_exec($ch);
	curl_close($ch);//关闭
     	return $response;	
}
function getPhotos($text)
{
	$searchurl="http://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=599026ae5650269bc315791db7971f70&format=json&nojsoncallback=1&text=".$text;
	$response=geturl($searchurl);
	$photos=json_decode($response,true);
	$count=0;
	$output = array();
	foreach ($photos['photos']['photo'] as $photo){
		if($count>=10){
			break;
		}
		$item = array();
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
		$item['photo_id']=$id;
		$item['photo_title']=$title;	
		$item['description']=$description;	
		$item['picture']=$picture;	
        	$count++;
		$output[]=$item;
	}
	return $output;
}
?>

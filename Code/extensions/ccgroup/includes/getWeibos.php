<?php
include_once (dirname(__FILE__).'/../conf.php');
function weiboMashup($title)
{
	global $ccHost,$ccPort,$ccWiki;
	$titles=explode(",", $title);
	$urls=array("http://".$ccHost.":".$ccPort."/".$ccWiki."/includes/qqweiboTrend.php?httext=","http://".$ccHost.":".$ccPort."/".$ccWiki."/includes/sinaweiboTrend.php?trend=");
	$doc=new DOMDocument('1.0','UTF-8');
	$doc->formatOutput=true;
	$root=$doc->createElement("statuses");
	$doc->appendChild($root);
	foreach ($urls as $url){
		foreach ($titles as $title){
			$ch = curl_init($url.$title);//打开
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
			$response  = curl_exec($ch);
			curl_close($ch);//关闭
			$input=new DOMDocument();
			$input->loadXML($response);
			$statuses=$input->getElementsByTagName("status");
			foreach ($statuses as $s){
				
				$status=$doc->createElement("status");
				$mid=$doc->createElement("mid");
				$text=$doc->createElement("text");
				$time=$doc->createElement("time");
				$name=$doc->createElement("name");
				$id=$doc->createElement("id");
				$image=$doc->createElement("image");
				$cate=$doc->createElement("cate");
				$page=$doc->createElement("page");
				$midValue=$s->getElementsByTagName("mid");
				$textValue=$s->getElementsByTagName("text");
				$timeValue=$s->getElementsByTagName("time");
				$nameValue=$s->getElementsByTagName("name");
				$idValue=$s->getElementsByTagName("id");
				$imageValue=$s->getElementsByTagName("image");
				$cateValue=$s->getElementsByTagName("cate");
				$pageValue=$s->getElementsByTagName("page");

				$mid->appendChild($doc->createTextNode($midValue->item(0)->nodeValue));
				$text->appendChild($doc->createTextNode($textValue->item(0)->nodeValue));
				$time->appendChild($doc->createTextNode($timeValue->item(0)->nodeValue));
				$name->appendChild($doc->createTextNode($nameValue->item(0)->nodeValue));
				$id->appendChild($doc->createTextNode($idValue->item(0)->nodeValue));
				$image->appendChild($doc->createTextNode($imageValue->item(0)->nodeValue));
				$cate->appendChild($doc->createTextNode($cateValue->item(0)->nodeValue));
				$page->appendChild($doc->createTextNode($pageValue->item(0)->nodeValue));
				$status->appendChild($mid);
				$status->appendChild($text);
				$status->appendChild($time);
				$status->appendChild($name);
				$status->appendChild($id);
				$status->appendChild($image);
				$status->appendChild($cate);
				$status->appendChild($page);
				$root->appendChild($status);
			}
		}
	}
	
	$gbdata=$doc->saveXML();
	$input=new DOMDocument();
	$input->loadXML($gbdata);
	$statuses=$input->getElementsByTagName("status");
	$length=$statuses->length;
	for($i=0;$i<$length;$i++){
		$time1=$statuses->item($i)->getElementsByTagName("time");
		$timeValue1=$time1->item(0)->nodeValue;
		for($j=$i+1;$j<$length;$j++){
			$time2=$statuses->item($j)->getElementsByTagName("time");
			$timeValue2=$time2->item(0)->nodeValue;
			if($timeValue1 < $timeValue2){
				$statuses->item(0)->parentNode->insertBefore($statuses->item($j),$statuses->item($i));
			}
		}
	}
	$response=$input->saveXML();

	$input=new DOMDocument();
	$input->loadXML($response);
	$statuses=$input->getElementsByTagName("status");
	$i=1;
	foreach ($statuses as $status){
		$page=$status->getElementsByTagName("page");
		$page->item(0)->nodeValue=$i;
		$i++;
	}
	return $input->saveXML();
}



function getWeibos($title)
{
	global $ccHost,$ccPort,$ccWiki;
	$weiboResponse  = weiboMashup($title);
//	echo 'weiboResponse: '.$weiboResponse.'<br/>';
	$all=array();
	$weibo = new DOMDocument ();
	$weibo->loadXML($weiboResponse);

	$weibodatas=$weibo->getElementsByTagName("status");
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
	
    		$all[]=$weiboarray;  
	}
	echo $all;
}
?>

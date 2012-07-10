<?php
include '../gbconfig.php';
include '../conf.php';
//fliterXML
//$gb=$_REQUEST['gb'];
//$gb="lashou,meituan,wowo,nuomi,manzuo";
$gb='lashou';
$gb=explode(',', $gb);
//$language=$_REQUEST['language'];
$language="杭州,hangzhou";
$language=explode(',', $language);
//$keyword=$_REQUEST['keyword'];
$keyword="";
$keywords=explode(',', $keyword);
//$endTime=$_REQUEST['endTime'];
$endTime="2011-12-30";
$city_weather="";
$input=new DOMDocument();
$doc=new DOMDocument('1.0','UTF-8');
$doc->formatOutput=true;
$root=$doc->createElement("deals");
$doc->appendChild($root);
$i=1;
$url="http://".$ccHost.":".$ccPort."/".$ccWiki."/includes/meituanCity.php";
$ch = curl_init($url);//打开
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
$response  = curl_exec($ch);
curl_close($ch);//关闭
$citys=json_decode($response,true);
$count=1;
foreach ($citys as $city_name){
	
foreach ($gb as $gbcompany){
if($gbcompany=="meituan"){
	$gburl="http://www.meituan.com/api/v2/".$city_name['ecity']."/deals";
	$rurl=$meituan;
}
elseif ($gbcompany=="lashou"){
	$gburl="http://open.client.lashou.com/api/detail/city/".$city_name['ccity'];
	$rurl=$lashou;
}
elseif($gbcompany=="wowo"){
	$gburl="http://www.55tuan.com/openAPI.do?city=".$city_name['ecity'];
	$rurl=$wowo;
}
elseif($gbcompany=="nuomi"){
	$gburl="http://www.nuomi.com/api/dailydeal?version=1&city=".$city_name['ecity'];
	$rurl=$nuomi;
}
elseif($gbcompany=="ftuan"){
	$gburl="http://newapi.ftuan.com/api/v2.aspx?city=".$city_name['ecity'];
	$rurl=$ftuan;
}
elseif($gbcompany=="manzuo"){
	$gburl="http://api.manzuo.com/common_".$city_name['ecity'].".xml";
	$rurl=$manzuo;
}
$contents=json_decode($rurl,true);

$input->load($gburl);
$input_datas=$input->getElementsByTagName($contents['data']);
//$count=1;
foreach ($input_datas as $input_data){
	if($count<=20){
	$title_value=$input_data->getElementsByTagName($contents['product']['title']);
	$title_value=$title_value->item(0)->nodeValue;
	$detail_value=$input_data->getElementsByTagName($contents['product']['description']);
	$detail_value=$detail_value->item(0)->nodeValue;
	$end_time_value=$input_data->getElementsByTagName($contents['product']['validThrough']);
	$end_time_value=$end_time_value->item(0)->nodeValue;
	$end_time_value=date('c',$end_time_value);
	/*$flag=0;
	foreach ($keywords as $keyword)
	{
		if(stristr($title_value, $keyword)||stristr($detail_value, $keyword)){
			$flag=1;
		}
	}
	if($flag==0)
		continue;
	*/
	//if($end_time_value>=$endTime){
	//foreach ($keywords as $keyword)
		
	$data=$doc->createElement("data");
	$page=$doc->createElement("page");
	$page->appendChild($doc->createTextNode($i));
	$data->appendChild($page);
	$i++;
	$id=$doc->createElement("id");
	$input_id=$input_data->getElementsByTagName($contents['product']['id']);
	$id->appendChild($doc->createTextNode($input_id->item(0)->nodeValue));
	$data->appendChild($id);
	
	$title=$doc->createElement("title");
	$input_title=$input_data->getElementsByTagName($contents['product']['title']);
	$title->appendChild($doc->createTextNode($input_title->item(0)->nodeValue));
	$data->appendChild($title);
	
	$city=$doc->createElement("city");
	$input_city=$input_data->getElementsByTagName($contents['product']['city']);
	$city->appendChild($doc->createTextNode($input_city->item(0)->nodeValue));
	$city_weather=$input_city->item(0)->nodeValue;
	$data->appendChild($city);
	
	$category=$doc->createElement("category");
	$input_category=$input_data->getElementsByTagName($contents['product']['category']);
	$category_value=$input_category->item(0)->nodeValue;
	if($contents['cate'][$category_value]!=''){
		$category_value=$contents['cate'][$category_value];
	}
	else {
		$category_value='其他';
	}
	$category->appendChild($doc->createTextNode($category_value));
	$data->appendChild($category);
	
	$value=$doc->createElement("value");
	$input_value=$input_data->getElementsByTagName($contents['discount']['original_price']);
	$value->appendChild($doc->createTextNode($input_value->item(0)->nodeValue));
	$data->appendChild($value);
	
	$price=$doc->createElement("price");
	$input_price=$input_data->getElementsByTagName($contents['discount']['present_price']);
	$price->appendChild($doc->createTextNode($input_price->item(0)->nodeValue));
	$data->appendChild($price);
	
	$purchase_count=$doc->createElement("purchase_count");
	$input_purchase_count=$input_data->getElementsByTagName($contents['product']['purchase_count']);
	$purchase_count->appendChild($doc->createTextNode($input_purchase_count->item(0)->nodeValue));
	$data->appendChild($purchase_count);
	
	$detail=$doc->createElement("detail");
	$input_detail=$input_data->getElementsByTagName($contents['product']['description']);
	$detail->appendChild($doc->createTextNode($input_detail->item(0)->nodeValue));
	$data->appendChild($detail);
	
	$url=$doc->createElement("url");
	$input_url=$input_data->getElementsByTagName($contents['product']['name']);
	$url->appendChild($doc->createTextNode($input_url->item(0)->nodeValue));
	$data->appendChild($url);
	
	$image=$doc->createElement("image");
	$input_image=$input_data->getElementsByTagName($contents['img']['name']);
	$image->appendChild($doc->createTextNode($input_image->item(0)->nodeValue));
	$data->appendChild($image);
	
	$start_time=$doc->createElement("start_time");
	$input_start_time=$input_data->getElementsByTagName($contents['product']['validFrom']);
	$time=$input_start_time->item(0)->nodeValue;
	$time_value=date('c',$time);
	$start_time->appendChild($doc->createTextNode($time_value));
	$data->appendChild($start_time);
	
	$end_time=$doc->createElement("end_time");
	$input_end_time=$input_data->getElementsByTagName($contents['product']['validThrough']);
	$time=$input_end_time->item(0)->nodeValue;
	$time_value=date('c',$time);
	$end_time->appendChild($doc->createTextNode($time_value));
	$data->appendChild($end_time);
	
	$website=$doc->createElement("website");
	$website->appendChild($doc->createTextNode($contents['website']));
	$data->appendChild($website);
	/*
	$time=$doc->createElement("time");
	
	$time->appendChild($doc->createTextNode(date('Y-m-d H:i:s',time())));
	$data->appendChild($time);
	
	*/
	$shops=$doc->createElement("shops");
	$input_shops=$input_data->getElementsByTagName($contents['shop']);
	foreach ($input_shops as $input_shop){
		$shop=$doc->createElement("shop");
		$address=$doc->createElement("address");
		$input_address=$input_shop->getElementsByTagName($contents['location']['address']);
		$address->appendChild($doc->createTextNode($input_address->item(0)->nodeValue));
		$shop->appendChild($address);
		
		$latitude=$doc->createElement("latitude");
		$input_latitude=$input_shop->getElementsByTagName($contents['location']['latitude']);
		$latitude->appendChild($doc->createTextNode($input_latitude->item(0)->nodeValue));
		$shop->appendChild($latitude);
		
		$longitude=$doc->createElement("longitude");
		$input_longitude=$input_shop->getElementsByTagName($contents['location']['longitude']);
		$longitude->appendChild($doc->createTextNode($input_longitude->item(0)->nodeValue));
		$shop->appendChild($longitude);
		
		$city=$doc->createElement("city");
		$city->appendChild($doc->createTextNode($city_weather));
		$shop->appendChild($city);
		
		$url="http://".$ccHost.":".$ccPort."/".$ccWiki."/includes/weather.php?city=".$city_weather;
		$ch = curl_init($url);//打开
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
		$response  = curl_exec($ch);
		curl_close($ch);//关闭	
		$weather_data=json_decode($response,true);
		
		$weather=$doc->createElement("weather");
		
		$today_condition=$doc->createElement("today_condition");
		$today_condition->appendChild($doc->createTextNode($weather_data['today_condition']['@attributes']['data']));
		
		$today_low_temperature=$doc->createElement("today_low_temperature");
		$today_low_temperature->appendChild($doc->createTextNode($weather_data['today_low_temperature']['@attributes']['data']));
		
		$today_high_temperature=$doc->createElement("today_high_temperature");
		$today_high_temperature->appendChild($doc->createTextNode($weather_data['today_high_temperature']['@attributes']['data']));
		
		$tomorrow_condition=$doc->createElement("tomorrow_condition");
		$tomorrow_condition->appendChild($doc->createTextNode($weather_data['tomorrow_condition']['@attributes']['data']));
		
		$tomorrow_low_temperature=$doc->createElement("tomorrow_low_temperature");
		$tomorrow_low_temperature->appendChild($doc->createTextNode($weather_data['tomorrow_low_temperature']['@attributes']['data']));
		
		$tomorrow_high_temperature=$doc->createElement("tomorrow_high_temperature");
		$tomorrow_high_temperature->appendChild($doc->createTextNode($weather_data['tomorrow_high_temperature']['@attributes']['data']));
		
		$theDayAfterTomorrow_condition=$doc->createElement("theDayAfterTomorrow_condition");
		$theDayAfterTomorrow_condition->appendChild($doc->createTextNode($weather_data['theDayAfterTomorrow_condition']['@attributes']['data']));
		
		$theDayAfterTomorrow_low_temperature=$doc->createElement("theDayAfterTomorrow_low_temperature");
		$theDayAfterTomorrow_low_temperature->appendChild($doc->createTextNode($weather_data['theDayAfterTomorrow_low_temperature']['@attributes']['data']));
		
		$theDayAfterTomorrow_high_temperature=$doc->createElement("theDayAfterTomorrow_high_temperature");
		$theDayAfterTomorrow_high_temperature->appendChild($doc->createTextNode($weather_data['theDayAfterTomorrow_high_temperature']['@attributes']['data']));

		$weather->appendChild($today_condition);
		$weather->appendChild($today_low_temperature);
		$weather->appendChild($today_high_temperature);
		$weather->appendChild($tomorrow_condition);
		$weather->appendChild($tomorrow_low_temperature);
		$weather->appendChild($tomorrow_high_temperature);
		$weather->appendChild($theDayAfterTomorrow_condition);
		$weather->appendChild($theDayAfterTomorrow_low_temperature);
		$weather->appendChild($theDayAfterTomorrow_high_temperature);
		
		$shop->appendChild($weather);
		$shops->appendChild($shop);
	}
	$data->appendChild($shops);
    $root->appendChild($data);
    $count++;
}
}
}
}
echo $doc->saveXML();


?>

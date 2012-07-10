<?php
include '../conf.php';
$keyword=$_REQUEST["keyword"];
$gb=$_REQUEST["gb"];
$time=$_REQUEST["time"];
$city=$_REQUEST["city"];
/*
$keyword="自助";
$gb="meituan,lashou,wowo,nuomi";
$time="2011-12-30";
$city="北京,beijing";
*/
$gburl="http://".$ccHost.":".$ccPort."/".$ccWiki."/includes/groupBuy.php?keyword=".$keyword."&language=".$city."&endTime=".$time."&gb=".$gb;
//$gburl="http://open.client.lashou.com/api/detail/city/2419/p/1/r/10";
$ch = curl_init($gburl);//打开
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
$response  = curl_exec($ch);
curl_close($ch);//关闭
//echo $response;
$product_count=1;
$location_count=1;
$img_count=1;
$discount_count=1;
$all=array();
$doc = new DOMDocument ();
$doc->loadXML($response);
$datas=$doc->getElementsByTagName("data");
foreach ($datas as $data){
	$productInfo=array();
	$id=$data->getElementsByTagName('id');
	$id=$id->item(0)->nodeValue;
	$productInfo["goodrelation:id"]=$id;
	
	$title=$data->getElementsByTagName('title');
	$title=$title->item(0)->nodeValue;
	$productInfo["goodrelation:title"]=$title;
	
	$category=$data->getElementsByTagName('category');
	$category=$category->item(0)->nodeValue;
	$productInfo["goodrelation:category"]=$category;
	
	
	$description=$data->getElementsByTagName('detail');
	$description=$description->item(0)->nodeValue;
	$productInfo["goodrelation:description"]=$description;
	
	$name=$data->getElementsByTagName('url');
	$name=$name->item(0)->nodeValue;
	$productInfo["goodrelation:url"]=$name;
	
	$purchase_count=$data->getElementsByTagName('purchase_count');
	$purchase_count=$purchase_count->item(0)->nodeValue;
	$productInfo["goodrelation:purchase_count"]=$purchase_count;
	
	$image=$data->getElementsByTagName('image');
	$image=$image->item(0)->nodeValue;
	$productInfo["goodrelation:picture"]=$image;
	
	$website=$data->getElementsByTagName('website');
	$website=$website->item(0)->nodeValue;
	$productInfo["goodrelation:website"]=$website;
	
	$time=$data->getElementsByTagName('time');
	$time=$time->item(0)->nodeValue;
	$productInfo["goodrelation:time"]=$time;
	
	$validTime=array();
	$validFrom=$data->getElementsByTagName('start_time');
	$validFrom=$validFrom->item(0)->nodeValue;
	$validTime["goodrelation:validFrom"]=$validFrom;
	
	$validThrough=$data->getElementsByTagName('end_time');
	$validThrough=$validThrough->item(0)->nodeValue;
	$validTime["goodrelation:validThrough"]=$validThrough;
	
	$discount=array();
	$original_price=$data->getElementsByTagName('value');
	$original_price=$original_price->item(0)->nodeValue;
	$discount["goodrelation:original_price"]=$original_price;
	
	$present_price=$data->getElementsByTagName('price');
	$present_price=$present_price->item(0)->nodeValue;
	$discount["goodrelation:present_price"]=$present_price;
	
	$shops=$data->getElementsByTagName('shop');
	$location_count=1;
	$location=array();
	foreach ($shops as $shop){
		$locationInfo=array();
		
		$city=$data->getElementsByTagName('city');
		$city=$city->item(0)->nodeValue;
		$locationInfo["goodrelation:city"]=$city;
		
		$address=$shop->getElementsByTagName('address');
		$address=$address->item(0)->nodeValue;
	    $locationInfo["goodrelation:address"]=$address;
		
	    $latitude=$shop->getElementsByTagName('latitude');
	    $latitude=$latitude->item(0)->nodeValue;
	    $locationInfo["goodrelation:latitude"]=$latitude;
	    
	    $longitude=$shop->getElementsByTagName('longitude');
	    $longitude=$longitude->item(0)->nodeValue;
	    $locationInfo["goodrelation:longitude"]=$longitude;
	    
	    $weather_datas=$shop->getElementsByTagName('weather');
	    $weather=array();
	    foreach ($weather_datas as $weather_data){
    
	    $today_condition=$weather_data->getElementsByTagName("today_condition");
	    $today_condition=$today_condition->item(0)->nodeValue;
	    $weather["goodrelation:today_condition"]=$today_condition;
	    
	    $today_low_temperature=$weather_data->getElementsByTagName("today_low_temperature");
	    $today_low_temperature=$today_low_temperature->item(0)->nodeValue;
	    $weather["goodrelation:today_low_temperature"]=$today_low_temperature;
	    
	    $today_high_temperature=$weather_data->getElementsByTagName("today_high_temperature");
	    $today_high_temperature=$today_high_temperature->item(0)->nodeValue;
	    $weather["goodrelation:today_high_temperature"]=$today_high_temperature;
	    
	    $tomorrow_condition=$weather_data->getElementsByTagName("tomorrow_condition");
	    $tomorrow_condition=$tomorrow_condition->item(0)->nodeValue;
	    $weather["goodrelation:tomorrow_condition"]=$tomorrow_condition;
	    
	    $tomorrow_low_temperature=$weather_data->getElementsByTagName("tomorrow_low_temperature");
	    $tomorrow_low_temperature=$tomorrow_low_temperature->item(0)->nodeValue;
	    $weather["goodrelation:tomorrow_low_temperature"]=$tomorrow_low_temperature;
	    
	    $tomorrow_high_temperature=$weather_data->getElementsByTagName("tomorrow_high_temperature");
	    $tomorrow_high_temperature=$tomorrow_high_temperature->item(0)->nodeValue;
	    $weather["goodrelation:tomorrow_high_temperature"]=$tomorrow_high_temperature;
	    
	    $theDayAfterTomorrow_condition=$weather_data->getElementsByTagName("theDayAfterTomorrow_condition");
	    $theDayAfterTomorrow_condition=$theDayAfterTomorrow_condition->item(0)->nodeValue;
	    $weather["goodrelation:theDayAfterTomorrow_condition"]=$theDayAfterTomorrow_condition;
	    
	    $theDayAfterTomorrow_low_temperature=$weather_data->getElementsByTagName("theDayAfterTomorrow_low_temperature");
	    $theDayAfterTomorrow_low_temperature=$theDayAfterTomorrow_low_temperature->item(0)->nodeValue;
	    $weather["goodrelation:theDayAfterTomorrow_low_temperature"]=$theDayAfterTomorrow_low_temperature;
	    
	    $theDayAfterTomorrow_high_temperature=$weather_data->getElementsByTagName("theDayAfterTomorrow_high_temperature");
	    $theDayAfterTomorrow_high_temperature= $theDayAfterTomorrow_high_temperature->item(0)->nodeValue;
	    $weather["goodrelation:theDayAfterTomorrow_high_temperature"]=$theDayAfterTomorrow_high_temperature;
	    
	    }
	    $locationInfo["goodrelation:weather"]=$weather;
	    $location["location".$location_count]=$locationInfo;
	    $location_count++;
	}
	
	$productInfo["goodrelation:availableAtOrFrom"]=$location;
	$productInfo["goodrelation:hasDiscount"]=$discount;
	$productInfo["goodrelation:validTime"]=$validTime;
	$all["product".$product_count]=$productInfo;
	$product_count++;
}
$result=json_encode($all);
//$result=urlencode($result);
echo $result;
?>

<?php
$city=$_REQUEST['city'];
//$city="beijing";
$url="http://www.google.com/ig/api?hl=zh-cn&weather=".$city;
$ch = curl_init($url);//打开
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
$response  = curl_exec($ch);
curl_close($ch);//关闭
$response=mb_convert_encoding($response, 'UTF-8','GB2312');
$xml=simplexml_load_string($response);
$forecast_count=1;
$weather=array();
foreach ($xml->weather->forecast_conditions as $forecast){
	if($forecast_count==1){
		$weather['today_condition']=$forecast->condition->attributes();
		$weather['today_low_temperature']=$forecast->low->attributes();
		$weather['today_high_temperature']=$forecast->high->attributes();
	}
	elseif ($forecast_count==2){
		$weather['tomorrow_condition']=$forecast->condition->attributes();
		$weather['tomorrow_low_temperature']=$forecast->low->attributes();
		$weather['tomorrow_high_temperature']=$forecast->high->attributes();
	}
	else{
		$weather['theDayAfterTomorrow_condition']=$forecast->condition->attributes();
		$weather['theDayAfterTomorrow_low_temperature']=$forecast->low->attributes();
		$weather['theDayAfterTomorrow_high_temperature']=$forecast->high->attributes();
	}
	$forecast_count++;
}
$weather=json_encode($weather);
echo $weather;
?>
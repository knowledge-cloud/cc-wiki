<?php
function lat_lon_tran($value)
{
	$values=explode('.',$value);
	if(count($values)==2){
		for($i=6-strlen($values[1]);$i>0;$i=$i-1)
        		$values[1]=$values[1].'0';
		$value = $values[0].$values[1];
	}else{
        	$value=$value.'000000';
	}
	return $value;
}

function weather($lat,$lon)
{
	$url="http://www.google.com/ig/api?hl=zh-cn&weather=,,,".lat_lon_tran($lat).",".lat_lon_tran($lon);
	$ch = curl_init($url);//打开
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
	$response  = curl_exec($ch);
	curl_close($ch);//关闭
	$response=mb_convert_encoding($response, 'UTF-8','GB2312');
	$xml=simplexml_load_string($response);
	$forecast=$xml->weather->forecast_conditions;
	if(sizeof($forecast)==0)
	{
		echo 'get weather info failed';
		return null;
	}
	$weather=array();
	$weather['today_condition']=$forecast[0]->condition->attributes();
	$weather['today_low_temperature']=$forecast[0]->low->attributes();
	$weather['today_high_temperature']=$forecast[0]->high->attributes();
	$weather['tomorrow_condition']=$forecast[1]->condition->attributes();
	$weather['tomorrow_low_temperature']=$forecast[1]->low->attributes();
	$weather['tomorrow_high_temperature']=$forecast[1]->high->attributes();
	$weather['thirdday_condition']=$forecast[2]->condition->attributes();
	$weather['thirdday_low_temperature']=$forecast[2]->low->attributes();
	$weather['thirdday_high_temperature']=$forecast[2]->high->attributes();
	return $weather;
}

function getWeather($lat,$lon,$city_ch)
{
	$doc=new DOMDocument('1.0','UTF-8');
	$doc->formatOutput=true;                //格式：缩进和extra space
	$root=$doc->createElement("weathers");         //Create new element node
	$doc->appendChild($root);
	$weather=$doc->createElement("weather");
	$weather_name = $doc->createElement("weather_name");
	$name = 'Weather_'.$city_ch.'_'.date('Ymd',time());
	$weather_name->appendChild($doc->createTextNode($name));
	$weather->appendChild($weather_name);
		
	$out = weather($lat,$lon);
	if($out==null)
		continue;

	$today_condition=$doc->createElement('today_condition');
	$today_condition->appendChild($doc->createTextNode($out['today_condition']));
	$weather->appendChild($today_condition);

	$today_low_temperature=$doc->createElement('today_low_temperature');
	$today_low_temperature->appendChild($doc->createTextNode($out['today_low_temperature']));
	$weather->appendChild($today_low_temperature);
	
	$today_high_temperature=$doc->createElement('today_high_temperature');
	$today_high_temperature->appendChild($doc->createTextNode($out['today_high_temperature']));
	$weather->appendChild($today_high_temperature);

	$tomorrow_condition=$doc->createElement('tomorrow_condition');
	$tomorrow_condition->appendChild($doc->createTextNode($out['tomorrow_condition']));
	$weather->appendChild($tomorrow_condition);

	$tomorrow_low_temperature=$doc->createElement('tomorrow_low_temperature');
	$tomorrow_low_temperature->appendChild($doc->createTextNode($out['tomorrow_low_temperature']));
	$weather->appendChild($tomorrow_low_temperature);

	$tomorrow_high_temperature=$doc->createElement('tomorrow_high_temperature');
	$tomorrow_high_temperature->appendChild($doc->createTextNode($out['tomorrow_high_temperature']));
	$weather->appendChild($tomorrow_high_temperature);


	$thirdday_condition=$doc->createElement('thirdday_condition');
	$thirdday_condition->appendChild($doc->createTextNode($out['thirdday_condition']));
	$weather->appendChild($thirdday_condition);

	$thirdday_low_temperature=$doc->createElement('thirdday_low_temperature');
	$thirdday_low_temperature->appendChild($doc->createTextNode($out['thirdday_low_temperature']));
	$weather->appendChild($thirdday_low_temperature);

	$thirdday_high_temperature=$doc->createElement('thirdday_high_temperature');
	$thirdday_high_temperature->appendChild($doc->createTextNode($out['thirdday_high_temperature']));
	$weather->appendChild($thirdday_high_temperature);

	$root->appendChild($weather);

	return $doc;
}

?>

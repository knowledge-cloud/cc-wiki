<?php
include_once (dirname(__FILE__).'/../../includes/import.php');
//translate the XML file into array: deals
function getWeather($file)
{

	$doc=new DOMDocument('1.0','UTF-8');
	$doc->load($file);
	$weathers = $doc->getElementsbyTagName('weather');
	$out = array();
	foreach($weathers as $weather)
	{
		$item = array();
        	$weather_name = $weather->getElementsbyTagName('weather_name')->item(0)->nodeValue;
		$item['weather_name']=$weather_name;

        	$today_condition = $weather->getElementsbyTagName('today_condition')->item(0)->nodeValue;
		$item['today_condition']=$today_condition;
        	$today_low_temperature = $weather->getElementsbyTagName('today_low_temperature')->item(0)->nodeValue;
		$item['today_low_temperature']=$today_low_temperature;
        	$today_high_temperature = $weather->getElementsbyTagName('today_high_temperature')->item(0)->nodeValue;
		$item['today_high_temperature']=$today_high_temperature;

        	$tomorrow_condition = $weather->getElementsbyTagName('tomorrow_condition')->item(0)->nodeValue;
		$item['tomorrow_condition']=$tomorrow_condition;
        	$tomorrow_low_temperature = $weather->getElementsbyTagName('tomorrow_low_temperature')->item(0)->nodeValue;
		$item['tomorrow_low_temperature']=$tomorrow_low_temperature;
        	$tomorrow_high_temperature = $weather->getElementsbyTagName('tomorrow_high_temperature')->item(0)->nodeValue;
		$item['tomorrow_high_temperature']=$tomorrow_high_temperature;

        	$thirdday_condition = $weather->getElementsbyTagName('thirdday_condition')->item(0)->nodeValue;
		$item['thirdday_condition']=$thirdday_condition;
        	$thirdday_low_temperature = $weather->getElementsbyTagName('thirdday_low_temperature')->item(0)->nodeValue;
		$item['thirdday_low_temperature']=$thirdday_low_temperature;
        	$thirdday_high_temperature = $weather->getElementsbyTagName('thirdday_high_temperature')->item(0)->nodeValue;
		$item['thirdday_high_temperature']=$thirdday_high_temperature;
		$out[]=$item;
	}
	return $out;
}

//import data page of Deal
function importWeather($file)
{
	$weathers = getWeather($file);
	$count = 0;
	foreach($weathers as $weather){
		$result="{{ #ask: [[{{PAGENAME}}]]
                            | ?Ontology/Today_condition
                            | ?Ontology/Today_high_temperature
                            | ?Ontology/Today_low_temperature
                            | ?Ontology/Tomorrow_condition
                            | ?Ontology/Tomorrow_high_temperature
                            | ?Ontology/Tomorrow_low_temperature
                            | ?Ontology/Thirdday_condition
                            | ?Ontology/Thirdday_high_temperature
                            | ?Ontology/Thirdday_low_temperature
                            | format=template
                            | template=ShowWeather
                         }}";

		$result=$result."[[Ontology/Today_condition::".$weather['today_condition']."|".$weather['today_condition']." ]]";
		$result=$result."[[Ontology/Today_high_temperature::".$weather['today_high_temperature']."|".$weather['today_high_temperature']." ]]";
		$result=$result."[[Ontology/Today_low_temperature::".$weather['today_low_temperature']."|".$weather['today_low_temperature']." ]]";
		$result=$result."[[Ontology/Tomorrow_condition::".$weather['tomorrow_condition']."|".$weather['tomorrow_condition']." ]]";
		$result=$result."[[Ontology/Tomorrow_low_temperature::".$weather['tomorrow_low_temperature']."|".$weather['tomorrow_low_temperature']." ]]";
		$result=$result."[[Ontology/Tomorrow_high_temperature::".$weather['tomorrow_high_temperature']."|".$weather['tomorrow_high_temperature']." ]]";
		$result=$result."[[Ontology/Thirdday_condition::".$weather['thirdday_condition']."|".$weather['thirdday_condition']." ]]";
		$result=$result."[[Ontology/Thirdday_low_temperature::".$weather['thirdday_low_temperature']."|".$weather['thirdday_low_temperature']." ]]";
		$result=$result."[[Ontology/Thirdday_high_temperature::".$weather['thirdday_high_temperature']."|".$weather['thirdday_high_temperature']." ]]";
	
	
		$timestamp = date('Y-m-d H:i:s',time());
		$result=$result."[[Ontology/Timestamp::".$timestamp."|".$timestamp." ]]";
		$result = $result."[[Category:Ontology/Weather]]";

		if(!exist($weather['weather_name'])){
	echo 'save '.$weather['weather_name'].'
';
			savePage($weather['weather_name'],$result);
			$count = $count + 1;
		}
	}
	return $count;
}

?>

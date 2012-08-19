<?php
include_once (dirname(__FILE__).'/../../includes/import.php');


//translate the XML file into array: deals
function getLocation($file)
{
	$doc=new DOMDocument('1.0','UTF-8');
	$doc->load($file);
	$locations = $doc->getElementsbyTagName('location');
	$out = array();
	foreach($locations as $location)
	{
		$item = array();	
		
        	$deal_id = $location->getElementsbyTagName('deal_id')->item(0)->nodeValue;
        	$shop_id = $location->getElementsbyTagName('shop_id')->item(0)->nodeValue;
        	$latitude = $location->getElementsbyTagName('latitude')->item(0)->nodeValue;
        	$longitude= $location->getElementsbyTagName('longitude')->item(0)->nodeValue;
        	$city = $location->getElementsbyTagName('city')->item(0)->nodeValue;
        	$campus = $location->getElementsbyTagName('campus')->item(0)->nodeValue;
        	$address = $location->getElementsbyTagName('address')->item(0)->nodeValue;
		if($shop_id==''){
			$item['location_name'] = 'Location_Deal_'.$deal_id.'_'.$latitude.'_'.$longitude;
		}else{
			$item['location_name'] = 'Location_Shop_'.$shop_id;
		}
		$cities = explode(',',$city);
		$item['weather_name']='Weather_杭州_'.date('Ymd',time());
		$item['latitude']=$latitude;
		$item['longitude']=$longitude;
		$item['city']=$city;
		$item['campus']=$campus;
		$item['address']=$address;
		$out[]=$item;
	}
	return $out;
}


//import data page of Deal
function importLocation($file)
{
	$locations = getLocation($file);
	$count = 0;
	foreach($locations as $location){
		$result="{{#display_point: ".$location['latitude'].",".$location['longitude']."|label=".$location['city'].",".$location['address']."}}";
		$result=$result."[[Ontology/Address::".$location['address']."|".$location['address']." ]]";
		$result=$result."[[Ontology/Campus::".$location['campus']."|".$location['campus']." ]]";
		$result=$result."[[Ontology/City::".$location['city']."|".$location['city']." ]]";
		$result=$result."[[Ontology/Latitude::".$location['latitude']."|".$location['latitude']." ]]";
		$result=$result."[[Ontology/Longitude::".$location['longitude']."|".$location['longitude']." ]]";
	
//		$weather = $location['weather'];	
		$weather_name = $location['weather_name'];	
		$result=$result."[[Ontology/HasWeather::".$weather_name."|".$weather_name." ]]";
		$result=$result."{{:".$weather_name."}}";
	
		$result = $result."[[Category:Ontology/Location]]";
		$timestamp=date('Y-m-d H:i:s',time());
		$result=$result."[[Ontology/Timestamp::".$timestamp."|".$timestamp." ]]";

		if(!exist($location['location_name'])){
			echo 'save '.$location['location_name'].'
';
			savePage($location['location_name'],$result);
			$count = $count + 1;
		}
	}
	return $count;
}

?>

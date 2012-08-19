<?php
function calculateCampus($lat,$lon)
{
	$yq_lat=30.263523;
	$yq_lon=120.120822;
	$xx_lat=30.277140;
	$xx_lon=120.135994;
	$zjg_lat=30.308348;
	$zjg_lon=120.086405;
	$hjc_lat=30.273059;
	$hjc_lon=120.186685;
	$zj_lat=30.196668;
	$zj_lon=120.124545;
	if($lat=='' || $lon=='')
		return '未知';
	$dis=array();
	$yq_dis = pow(($yq_lat-$lat),2)+pow(($yq_lon-$lon),2);
	$dis[]=$yq_dis;
	$xx_dis = pow(($xx_lat-$lat),2)+pow(($xx_lon-$lon),2);
	$dis[]=$xx_dis;
	$zjg_dis = pow(($zjg_lat-$lat),2)+pow(($zjg_lon-$lon),2);
	$dis[]=$zjg_dis;
	$hjc_dis = pow(($hjc_lat-$lat),2)+pow(($hjc_lon-$lon),2);
	$dis[]=$hjc_dis;
	$zj_dis = pow(($zj_lat-$lat),2)+pow(($zj_lon-$lon),2);
	$dis[]=$zj_dis;
	rsort($dis);
	switch($dis[sizeof($dis)-1]){
		case $yq_dis:
			return '玉泉';
			break;
		case $xx_dis:
			return '西溪';
			break;
		case $zjg_dis:
			return '紫金港';
			break;
		case $hjc_dis:
			return '华家池';
			break;
		case $zj_dis:
			return '之江';
			break;
	}
}
function gbLocation($file)
{
	$doc=new DOMDocument('1.0','UTF-8');
	$doc->formatOutput=true;                //格式：缩进和extra space
	$root=$doc->createElement("locations");         //Create new element node
	$doc->appendChild($root);
	$input = new DOMDocument();	
	$input->load($file); 
	$input_datas=$input->getElementsByTagName('data');
	foreach($input_datas as $input_data)
	{
		$input_shops=$input_data->getElementsByTagName('shop');
		foreach ($input_shops as $input_shop)
		{
			$location=$doc->createElement("location");

			$deal_id=$doc->createElement("deal_id");
			$input_id=$input_data->getElementsByTagName('id');  
			$input_id=$input_id->item(0)->nodeValue;
			$deal_id->appendChild($doc->createTextNode($input_id));
			$location->appendChild($deal_id);

			$shop_id=$doc->createElement("shop_id");
			$shop_id->appendChild($doc->createTextNode(""));
			$location->appendChild($shop_id);
			
			$address=$doc->createElement("address");
      			$input_address = $input_shop->getElementsByTagName('address');
			if(!empty($input_address->item(0)->nodeValue))
				$address->appendChild($doc->createTextNode($input_address->item(0)->nodeValue));
			else
				$address->appendChild($doc->createTextNode(''));
			$location->appendChild($address);

			$latitude=$doc->createElement("latitude");
			$input_latitude=$input_shop->getElementsByTagName('latitude');
			if(!empty($input_latitude->item(0)->nodeValue))
          			$latitude->appendChild($doc->createTextNode($input_latitude->item(0)->nodeValue));
			else
				$latitude->appendChild($doc->createTextNode(''));
			$location->appendChild($latitude);

			$longitude=$doc->createElement("longitude");
			$input_longitude=$input_shop->getElementsByTagName('longitude');
			if(!empty($input_longitude->item(0)->nodeValue))
          			$longitude->appendChild($doc->createTextNode($input_longitude->item(0)->nodeValue));
			else
				$longitude->appendChild($doc->createTextNode(''));
			$location->appendChild($longitude);

			if($input_latitude->item(0)->nodeValue=='' || $input_longitude->item(0)->nodeValue=='' || $input_address->item(0)->nodeValue=='' || $input_latitude->item(0)->nodeValue=='0.0' || $input_longitude->item(0)->nodeValue=='')
				continue;

			$campus=$doc->createElement("campus");
			$campus_value = calculateCampus($input_latitude->item(0)->nodeValue,$input_longitude->item(0)->nodeValue);
			$campus->appendChild($doc->createTextNode($campus_value));
			$location->appendChild($campus);

			$city=$doc->createElement("city");
			$input_city=$input_shop->getElementsByTagName('city');
			if(!empty($input_city->item(0)->nodeValue))
          			$city->appendChild($doc->createTextNode($input_city->item(0)->nodeValue));
			else
				$city->appendChild($doc->createTextNode(''));
			$location->appendChild($city);
			

//			echo 'add address '.$input_address->item(0)->nodeValue.'...
//';
			$root->appendChild($location);
		}
	}
	return $doc;
	
}
function shopLocation($file)
{
	$doc=new DOMDocument('1.0','UTF-8');
	$doc->formatOutput=true;                //格式：缩进和extra space
	$root=$doc->createElement("locations");         //Create new element node
	$doc->appendChild($root);
	$input = new DOMDocument();	
	$input->load($file); 
	$input_datas=$input->getElementsByTagName('shop');
	foreach($input_datas as $input_data)
	{
		$location=$doc->createElement("location");

		$deal_id=$doc->createElement("deal_id");
		$deal_id->appendChild($doc->createTextNode(""));
		$location->appendChild($deal_id);

		$shop_id=$doc->createElement("shop_id");
		$input_id = $input_data->getElementsByTagName("shop_id");
		$shop_id->appendChild($doc->createTextNode($input_id->item(0)->nodeValue));
		$location->appendChild($shop_id);
			
		$address=$doc->createElement("address");
      		$input_address = $input_data->getElementsByTagName('address');
		if(!empty($input_address->item(0)->nodeValue))
			$address->appendChild($doc->createTextNode($input_address->item(0)->nodeValue));
		else
			$address->appendChild($doc->createTextNode(''));
		$location->appendChild($address);

		$latitude=$doc->createElement("latitude");
		$input_latitude=$input_data->getElementsByTagName('latitude');
		if(!empty($input_latitude->item(0)->nodeValue))
          		$latitude->appendChild($doc->createTextNode($input_latitude->item(0)->nodeValue));
		else
			$latitude->appendChild($doc->createTextNode(''));
		$location->appendChild($latitude);

		$longitude=$doc->createElement("longitude");
		$input_longitude=$input_data->getElementsByTagName('longitude');
		if(!empty($input_longitude->item(0)->nodeValue))
          		$longitude->appendChild($doc->createTextNode($input_longitude->item(0)->nodeValue));
		else
			$longitude->appendChild($doc->createTextNode(''));
		$location->appendChild($longitude);

		if($input_latitude->item(0)->nodeValue=='' || $input_longitude->item(0)->nodeValue=='' || $input_address->item(0)->nodeValue=='' || $input_latitude->item(0)->nodeValue=='0.0' || $input_longitude->item(0)->nodeValue=='')
			continue;

		$campus=$doc->createElement("campus");
		$campus_value = calculateCampus($input_latitude->item(0)->nodeValue,$input_longitude->item(0)->nodeValue);
		$campus->appendChild($doc->createTextNode($campus_value));
		$location->appendChild($campus);

		$city=$doc->createElement("city");
		$input_city=$input_data->getElementsByTagName('city');
		if(!empty($input_city->item(0)->nodeValue))
          		$city->appendChild($doc->createTextNode($input_city->item(0)->nodeValue));
		else
			$city->appendChild($doc->createTextNode(''));
		$location->appendChild($city);
			

//		echo 'add address '.$input_address->item(0)->nodeValue.'...
//';
		$root->appendChild($location);
	}
	return $doc;
}
?>

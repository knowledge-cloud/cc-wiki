<?php
include_once (dirname(__FILE__)."/data/gbData.php");
include_once (dirname(__FILE__)."/data/locationData.php");
include_once (dirname(__FILE__)."/data/weatherData.php");
include_once (dirname(__FILE__)."/data/JiepangLocationSearch.php");
$time = date('Y-m-d'); 
$d_dir = dirname(__FILE__).'/data/xml/'.$time;
if(!file_exists($d_dir))
{
	mkdir($d_dir);
} 
chdir($d_dir);

$arr = array('meituan','lashou','wowo','nuomi','ftuan','manzuo');
$arr_shop = array('meituan','lashou','nuomi');
$city_en='hangzhou';
$city_ch='杭州';
$lat='30.15';
$lon='120.10';
/**
loading	groupbuy data
**/
foreach ($arr as $item){
	if(!file_exists($item.'.xml')){
		echo 'get '.$item.'.xml...
';
		$doc=getGroupbuyData($city_en,$city_ch,$item);
		$doc->save($item.'.xml');
	}else{
		echo $item.'.xml exists.
';
	}
}

/**
generate location data from groupbuy
**/
foreach ($arr as $item){
	if(!file_exists('location_'.$item.'.xml')){
		echo 'generate location_'.$item.'.xml...
';
		$doc= gbLocation($item.'.xml');
		$doc->save('location_'.$item.'.xml');
	}else{
		echo 'location_'.$item.'.xml exists.
';
	}
}


/**
generate shop data from groupbuy 
**/
foreach ($arr_shop as $item){
	if(!file_exists('shop_'.$item.'.xml')){
		echo 'generate shop_'.$item.'.xml...
';
		$shop = getJiepang($item.'.xml');
		$shop->save('shop_'.$item.'.xml');
	}else{
		echo 'shop_'.$item.'.xml exists.
';
	}
}

/**
generate location data from shop 
**/
foreach ($arr_shop as $item){
	if(!file_exists('location_shop_'.$item.'.xml')){
		echo 'generate location_shop_'.$item.'.xml...
';
		$doc= shopLocation('shop_'.$item.'.xml');
		$doc->save('location_shop_'.$item.'.xml');
	}else{
		echo 'location_shop_'.$item.'.xml exists.
';
	}
}

/*
generate weather data
*/
if(!file_exists('weather.xml')){
	echo 'generate weather.xml...
';
	$doc= getWeather($lat,$lon,$city_ch);
	$doc->save('weather.xml');
}else{
	echo 'weather.xml exists.
';
}

?>

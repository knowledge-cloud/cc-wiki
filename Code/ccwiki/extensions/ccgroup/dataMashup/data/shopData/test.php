<?php

require_once 'jiepang.api.php';

set_time_limit(0);
session_start();
$jiepang = new JiepangApi();

$dir = dirname(__FILE__);

//$web_array = array("nuomi1");
    chdir($dir);
    $doc = new DOMDocument('1.0','UTF-8');   
    $shop_info = new DOMDocument('1.0','UTF-8');
    $shop_info->formatOutput=true;
	  $para_city='杭州';
	  $para_lat='30.276777';
	  $para_lon='120.198312';
	  $para_q='杭州市江干区艮山西路184号';
            $locations_p = $jiepang->api('locations/search', array
            (
              'lat' => $para_lat,                
              'lon' => $para_lon,  
              'city' => $para_city,
              'q' => $para_q,
            ));
	var_dump($locations_p);
?>

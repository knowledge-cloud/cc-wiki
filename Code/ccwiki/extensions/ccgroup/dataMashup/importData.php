<?php
include_once (dirname(__FILE__).'/import/importDeal.php');
include_once (dirname(__FILE__).'/import/importLocation.php');
include_once (dirname(__FILE__).'/import/importWeather.php');
include_once (dirname(__FILE__).'/import/importPhoto.php');
include_once (dirname(__FILE__).'/import/importShop.php');
$time = date('Y-m-d'); 
$d_dir = dirname(__FILE__).'/data/xml/'.$time;
if(!file_exists($d_dir))
{
	mkdir($d_dir);
} 
chdir($d_dir);

$arr = array('meituan','lashou','wowo','nuomi','ftuan','manzuo');
$arr_shop = array('meituan','lashou','nuomi');
echo 'import weather...
';
$num_weather = importWeather('weather.xml');
echo 'totally '.$num_weather.' weather pages imported
';
echo 'import deal location...
';
$num_dl = 0;
foreach ($arr as $item){
	$tmp = importLocation('location_'.$item.'.xml');	
	$num_dl = $num_dl + $tmp;
}
echo 'totally '.$num_dl.' deal location pages imported
';

echo 'import shop location...
';
$num_sl = 0;
foreach ($arr_shop as $item){
	$tmp = importLocation('location_shop_'.$item.'.xml');	
	$num_sl = $num_sl + $tmp;
}
echo 'totally '.$num_sl.' shop location pages imported
';


echo 'import Deal...
';
$num_deal = 0;
foreach ($arr as $item){
	$tmp = importDeal($item.'.xml');	
	$num_deal = $num_deal + $tmp;
}
echo 'totally '.$num_deal.' deal pages imported
';

echo 'import Photo...
';
$num_photo = 0;
foreach ($arr_shop as $item){
	$tmp = importPhoto('shop_'.$item.'.xml');	
	$num_photo = $num_photo + $tmp;
}
echo 'totally '.$num_photo.' photo pages imported
';

echo 'import Shop...
';
$num_shop = 0;
foreach ($arr_shop as $item){
	$tmp = importShop('shop_'.$item.'.xml');	
	$num_shop = $num_shop + $tmp;
}
echo 'totally '.$num_shop.' shop pages imported
';
?>

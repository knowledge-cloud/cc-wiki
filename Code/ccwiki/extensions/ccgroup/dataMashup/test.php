<?php
include_once (dirname(__FILE__).'/data/JiepangLocationSearch.php');
$d_dir = dirname(__FILE__).'/test';
chdir($d_dir);
$arr = array('meituan','lashou','nuomi');
//$arr = array('wowo');
foreach ($arr as $item){
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
?>

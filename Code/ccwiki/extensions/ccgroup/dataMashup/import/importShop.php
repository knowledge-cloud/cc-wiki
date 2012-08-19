<?php
include_once (dirname(__FILE__).'/../../includes/import.php');
//translate the XML file into array: shops
function getShop($file)
{
    $doc = new DOMDocument('1.0','UTF-8');  
    $doc->load($file);
    $shops = $doc->getElementsByTagName('shop');
    $re_shops = array();
    foreach($shops as $key => $shop)
    {
        $shop_arr = array();
        $shop_arr['id'] = $shop->getElementsByTagName('shop_id')->item(0)->nodeValue;
        $shop_arr['shop_name'] = $shop->getElementsByTagName('shop_name')->item(0)->nodeValue;
        
        $photos = $shop->getElementsByTagName('photo');
        $photo_arr = array();
        foreach($photos as $num => $photo)
        {
            $sub_photo_arr = array();
            $pic_id = $photo->getElementsByTagName('pic_id');
            if(!empty($pic_id->item(0)->nodeValue))
            {
                $sub_photo_arr['id'] = $photo->getElementsByTagName('pic_id')->item(0)->nodeValue;
            }
            $photo_arr[$num] = $sub_photo_arr;
        }
        $shop_arr['photos'] = $photo_arr;

        $deal_id = $shop->getElementsByTagName('deal_id')->item(0)->nodeValue;
        $shop_arr['shopRelatedDeal'] = 'Deal_'.$deal_id ;    
        $re_shops[$key] = $shop_arr;
    }
    return $re_shops;
}

//import data page of Shop
function importShop($file)
{
	$shops = getShop($file);
	$count = 0;
	foreach($shops as $shop){
		$shop_page_name = 'Shop_'.$shop['id'];	
		if(!exist($shop_page_name)){
			$result="{{ #ask: [[{{PAGENAME}}]]
				| ?Ontology/ShopId
				| ?Ontology/ShopName
				| ?Ontology/Timestamp
				| format=template
				| template=ShowShop
			}}";
			$result=$result."[[Ontology/ShopId::".$shop['id']."|".$shop['id']." ]]";
			$result=$result."[[Ontology/ShopName::".$shop['shop_name']."|".$shop['shop_name']." ]]";

			foreach($shop['photos'] as $photo){
				$photo_name = 'Photo_'.$shop_page_name.'_'.$photo['id'];	
				$result=$result."[[Ontology/HasPhoto::".$photo_name."|".$photo_name." ]]";
				$result=$result."{{:".$photo_name."}}";
			}
      
			$location_name = 'Location_'.$shop_page_name;	
			$result=$result."[[Ontology/HasLocation::".$location_name."|".$location_name." ]]";
			$result=$result."{{:".$location_name."}}";

			$result=$result."[[Category:Ontology/Shop]]";
			$timestamp = date('Y-m-d H:i:s',time());
			$result=$result."[[Ontology/Timestamp::".$timestamp."|".$timestamp." ]]";
		
			$result = $result."[[Ontology/ShopRelatedDeal::".$shop['shopRelatedDeal']."|".$shop['shopRelatedDeal']." ]]";
			echo 'save '.$shop_page_name.'
';
			savePage($shop_page_name,$result);
			$count = $count + 1;
		}else{
			echo $shop_page_name.' add ShopRelatedDeal '.$shop['shopRelatedDeal'].'
';
			$result = '[[Ontology/ShopRelatedDeal::'.$shop['shopRelatedDeal'].']]';
			savePage($shop_page_name,$result);
		}
	}
	return $count;
}

?>

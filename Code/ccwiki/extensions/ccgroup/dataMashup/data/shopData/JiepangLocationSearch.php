<?php

require_once 'jiepang.api.php';

set_time_limit(0);
session_start();
$jiepang = new JiepangApi();

$dir = dirname(__FILE__);

$web_array = array("meituan");
//$web_array = array("nuomi1");
foreach($web_array as $webName)
{
    chdir($dir);
    $doc = new DOMDocument('1.0','UTF-8');   
    $shop_info = new DOMDocument('1.0','UTF-8');
    $shop_info->formatOutput=true;
    
    $doc->load($webName.'.xml');

    $datas = $doc->getElementsbyTagName('data');
    
    $shops=$shop_info->createElement('shops');         //Create new element node "shops"
	  $shop_info->appendChild($shops);
        
    foreach($datas as $data)
    {
        $deal_id_para = $data->getElementsbyTagName('id');
        $deal_id_para = $deal_id_para->item(0)->nodeValue;                //get deal_id
        $shops_data = $data->getElementsbyTagName('shop');
        
        foreach($shops_data as $shop_data)
        {       
            
            $para_city = $shop_data->getElementsbyTagName('city');  
            $para_city = $para_city->item(0)->nodeValue;
            
            $para_lat = $shop_data->getElementsbyTagName('latitude');
            $para_lat = $para_lat->item(0)->nodeValue;
            
            $para_lon = $shop_data->getElementsbyTagName('longitude');
            $para_lon = $para_lon->item(0)->nodeValue;
            
            $para_q = $shop_data->getElementsbyTagName('address');
            $para_q = $para_q->item(0)->nodeValue;

            $locations_p = array();   //存放地点信息
            $photos_p = array();      //存放图片信息
            $address = array();     //存放地址信息
	    echo 'address: '.$para_q.'
';
            $locations_p = $jiepang->api('locations/search', array
            (
              'lat' => $para_lat,                
              'lon' => $para_lon,  
              'city' => $para_city,
              'q' => $para_q,
            ));
            if(!empty($locations_p['items']))
            {
                $location_p = $locations_p['items'][0];
                
                $shop = $shop_info->createElement('shop');               //Create new element node "shop"
                $shops -> appendChild($shop);
                    
                $deal_id = $shop_info->createElement('deal_id');         //Create new element node "deal_id"
                $deal_id ->appendChild($shop_info->createTextNode($deal_id_para));
                $shop -> appendChild($deal_id);
                    
                $shop_name_para = $location_p['name'];                         //get shop_name                    
                $shop_name = $shop_info->createElement('shop_name');          //Create new element node "shop_name"
                $shop_name ->appendChild($shop_info->createTextNode($shop_name_para));
                $shop -> appendChild($shop_name);
                    
                $shop_id_para = $location_p['guid'];                             //get shop_id
                $shop_id = $shop_info->createElement('shop_id');          //Create new element node "shop_id"
                $shop_id ->appendChild($shop_info->createTextNode($shop_id_para));
                $shop -> appendChild($shop_id);
            
                $address = $jiepang->api('locations/show', array        //查看地址信息，并保存地址信息
                (           
                    'guid' => $location_p['guid'],   
                ));
            
                $latitude_para = $address['lat'];
                $longitude_para = $address['lon'];
                $address_para = $address['addr'];                            //get address
                $city_para = $address['city']; 
            
                $location = $shop_info->createElement('location');          //Create new element node "location"  
                $shop -> appendChild($location);
                        
                $latitude = $shop_info->createElement('latitude');          //Create new element node "latitude"
                $latitude ->appendChild($shop_info->createTextNode($latitude_para));
                $location -> appendChild($latitude);
                        
                $longitude = $shop_info->createElement('longitude');          //Create new element node "longitude"
                $longitude ->appendChild($shop_info->createTextNode($longitude_para));
                $location -> appendChild($longitude);
                        
                $address = $shop_info->createElement('address');          //Create new element node "address"
                $address ->appendChild($shop_info->createTextNode($address_para));
                $location -> appendChild($address);
                    
                $city = $shop_info->createElement('city');          //Create new element node "city"
                $city ->appendChild($shop_info->createTextNode($city_para));
                $location -> appendChild($city);
            
                $photos = $shop_info->createElement('photos');          //Create new element node "photos"    
                $shop -> appendChild($photos);
            
                $photos_p = $jiepang->api('locations/photos', array
                (           
                    'guid' => $location_p['guid'],  
                    'count' => '10',
                ));
                if(!empty($photos_p['items']))
                {
                    foreach($photos_p['items'] as $photo_p)                   //查看图片信息，并保存图片信息                
                    {
                        $pic_discription_para = $photo_p['body'];
                        $pic_url_para = $photo_p['photo']['url'];
                        $pic_id_para = $photo_p['id'];
                    
                        $photo = $shop_info->createElement("photo");          //Create new element node "photo"
                        $photos -> appendChild($photo);
         
                        $pic_id = $shop_info->createElement('pic_id');          //Create new element node "pic_id"
                        $pic_id ->appendChild($shop_info->createTextNode($pic_id_para));
                        $photo -> appendChild($pic_id);
                    
                        $pic_url = $shop_info->createElement('pic_url');          //Create new element node "pic_url"
                        $pic_url ->appendChild($shop_info->createTextNode($pic_url_para));
                        $photo -> appendChild($pic_url);
                        
                        $pic_discription = $shop_info->createElement('pic_discription');          //Create new element node "pic_discription"
                        $pic_discription ->appendChild($shop_info->createTextNode($pic_discription_para));
                        $photo -> appendChild($pic_discription);
     
                    }
                }
            }
        }    
    }
    $time = date('Y-m-d'); 
    chdir('data');
    if(!file_exists($time))
    {
        mkdir($time);
    } 
    chdir($time);  
    $shop_info->save('shop_'.$webName.'.xml');
}
?>

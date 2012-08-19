<?php
function getDeal($file)
{	
    $doc=new DOMDocument('1.0','UTF-8');
    $doc->load($file);
    
    $datas = $doc->getElementsbyTagName('data');
    
    $deals = array();
    foreach($datas as $data_key => $data)
    { 
        $deal = array();
        
        $id = $data->getElementsbyTagName('id');
        $deal['id'] = $id->item(0)->nodeValue;
        
        $title = $data->getElementsbyTagName('title');
        $deal['title'] = $title->item(0)->nodeValue;
        
        $category = $data->getElementsbyTagName('category');
        $deal['category'] = $category->item(0)->nodeValue;
        
        $description = $data->getElementsbyTagName('description');
        $deal['description'] = $description->item(0)->nodeValue;
       
        $validThrough = $data->getElementsbyTagName('validThrough');
        $deal['validThrough'] = $validThrough->item(0)->nodeValue;
        
        $validfrom = $data->getElementsbyTagName('validfrom');
        if(!empty($validfrom->item(0)->nodeValue))
        {
          $deal['validfrom'] = $validfrom->item(0)->nodeValue;
        }
        else
        {
          $deal['validfrom'] = '';
        }
               
        $original_price = $data->getElementsbyTagName('original_price');
        $deal['original_price'] = $original_price->item(0)->nodeValue;
        
        $present_price = $data->getElementsbyTagName('present_price');
        $deal['present_price'] = $present_price->item(0)->nodeValue;
        
        $discount_value = $data->getElementsbyTagName('discount_value');
        $deal['discount_value'] = $discount_value->item(0)->nodeValue;
        
        $url = $data->getElementsbyTagName('url');
        $deal['url'] = $url->item(0)->nodeValue;
        
        $picture = $data->getElementsbyTagName('picture');
        $deal['picture'] = $picture->item(0)->nodeValue;
        
        $source = $data->getElementsbyTagName('source');
        $deal['source'] = $source->item(0)->nodeValue;
        
        $gbcity = $data->getElementsbyTagName('gbcity');
        $deal['gbcity'] = $gbcity->item(0)->nodeValue;
        
        $shops = $data->getElementsbyTagName('shop');
        $shops_arr=array();
        foreach($shops as $shop_key => $shop_arr)
        {          
          $shop = array();
          
          $latitude = $shop_arr->getElementsbyTagName('latitude');
          $shop['latitude'] = $latitude->item(0)->nodeValue;
          
          $longitude = $shop_arr->getElementsbyTagName('longitude');
          $shop['longitude'] = $longitude->item(0)->nodeValue;

          $shops_arr[$shop_key] = $shop;
        }
        $deal['shops']=$shops_arr;
        $deals[$data_key] = $deal;
    }   
    return $deals;
  
}
?>

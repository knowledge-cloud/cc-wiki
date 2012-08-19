<?php
include_once (dirname(__FILE__).'/../../includes/import.php');
//translate the XML file into array: deals
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
          $deal['validFrom'] = $validfrom->item(0)->nodeValue;
        }
        else
        {
          $deal['validFrom'] = '';
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

//import data page of Deal
function importDeal($file)
{
	$deals = getDeal($file);
	$count = 0;
	foreach($deals as $deal){
		$result="{{ #ask: [[{{PAGENAME}}]]
			| ?Ontology/Id
			| ?Ontology/Title
			| ?Ontology/Description
			| ?Ontology/Source
			| ?Ontology/Picture
			| ?Ontology/Original_price
			| ?Ontology/Present_price
			| ?Ontology/Discount_value
			| ?Ontology/ValidFrom
			| ?Ontology/ValidThrough
			| ?Ontology/Gbcity
			| ?Ontology/Url
			| ?Ontology/Category
			| ?Ontology/Timestamp
			| format=template
			| template=ShowDeal
		}}";
		$result=$result."[[Ontology/Id::".$deal['id']."|".$deal['id']." ]]";
		$result=$result."[[Ontology/Category::".$deal['category']."|".$deal['category']." ]]";
//		$title=substr($deal['title'],0,100);
		$title=$deal['title'];
		$result=$result."[[Ontology/Title::".$title."|".$title." ]]";
		$result=$result."[[Ontology/Description::".$deal['description']."|".$deal['description']." ]]";
		$result=$result."[[Ontology/Url::".$deal['url']."|".$deal['url'] ." ]]";
		$result=$result."[[Ontology/Picture::".$deal['picture']."|".$deal['picture']." ]]";
		$result=$result."[[Ontology/Source::".$deal['source']."|".$deal['source']." ]]";
		$result=$result."[[Ontology/Original_price::".$deal['original_price']."|".$deal['original_price']." ]]";
		$result=$result."[[Ontology/Present_price::".$deal['present_price']."|".$deal['present_price']." ]]";
		$result=$result."[[Ontology/Discount_value::".$deal['discount_value']."|".$deal['discount_value']." ]]";
		$result=$result."[[Ontology/Gbcity::".$deal['gbcity']."|".$deal['gbcity']." ]]";
		$result=$result."[[Ontology/ValidFrom::".$deal['validFrom']."|".$deal['validFrom']." ]]";
		$result=$result."[[Ontology/ValidThrough::".$deal['validThrough']."|".$deal['validThrough']." ]]";
		
		
		foreach($deal['shops'] as $shop){
			$location_name = 'Location_Deal_'.$deal['id'].'_'.$shop['latitude'].'_'.$shop['longitude'];	
			$result=$result."[[Ontology/HasLocation::".$location_name."|".$location_name." ]]";
			$result=$result."{{:".$location_name."}}";
		}
		$result=$result."[[Category:Ontology/Deal]]";
		$timestamp=date('Y-m-d H:i:s',time());
		$result=$result."[[Ontology/Timestamp::".$timestamp."|".$timestamp." ]]";
		if(!exist('Deal_'.$deal['id'])){
			echo 'save '.'Deal_'.$deal['id'].'
';
			savePage('Deal_'.$deal['id'],$result);
			$count = $count + 1;
		}
	}
	return $count;
}

?>

<?php
include_once (dirname(__FILE__).'/gbmapping.php');

function getGroupbuyData($city_en,$city_cn,$source)
{
  $name = $source;
	
  global $meituan,$lashou,$wowo,$nuomi,$ftuan,$manzuo;
	$input=new DOMDocument();
	$doc=new DOMDocument('1.0','UTF-8');
	$doc->formatOutput=true;                //格式：缩进和extra space
	$root=$doc->createElement("deals");         //Create new element node
	$doc->appendChild($root);
	switch($source)
  {
		case "meituan":
			$gburl="http://www.meituan.com/api/v2/".$city_en."/deals";
			$contents=json_decode($meituan,true);
			break;
		case "lashou":
			$gburl="http://open.client.lashou.com/api/detail/city/".$city_cn;
			$contents=json_decode($lashou,true);
			break;
		case "wowo":
			$gburl="http://www.55tuan.com/openAPI.do?city=".$city_en;
			$contents=json_decode($wowo,true);
			break;
		case "nuomi":
			$gburl="http://www.nuomi.com/api/dailydeal?version=1&city=".$city_en;
			$contents=json_decode($nuomi,true);
			break;
		case "ftuan":
			$gburl="http://newapi.ftuan.com/api/v2.aspx?city=".$city_en;
			$contents=json_decode($ftuan,true);
			break;
		case "manzuo":
			$gburl="http://api.manzuo.com/common_".$city_en.".xml";
			$contents=json_decode($manzuo,true);
			break;
	}
	
	$input->load($gburl); 
	$input_datas=$input->getElementsByTagName($contents['data']);
	foreach ($input_datas as $input_data)
  {
		$data=$doc->createElement("data");

		$id=$doc->createElement("id");
		$input_id=$input_data->getElementsByTagName($contents['id']);  
		$input_id=$input_id->item(0)->nodeValue;
   
		if($source=="nuomi" || $source=="ftuan")
    {                                                                                  //&&??
			$tmp1 = explode("/",$input_id);
			$tmp2 = $tmp1[sizeof($tmp1)-1];
			$tmp2 = explode(".",$tmp2);                   //把字符串分割为数组（在.处）
			$input_id = $tmp2[0];
		}
    else if($source=="manzuo")
    {
			$tmp1 = explode("/",$input_id);
			$tmp2 = $tmp1[sizeof($tmp1)-1];
			$tmp2 = explode("?",$tmp2);
			$input_id = $tmp2[0];
		}
    else
    {
		}
		$id->appendChild($doc->createTextNode($input_id));
		$data->appendChild($id);

		$title=$doc->createElement("title");
		$input_title=$input_data->getElementsByTagName($contents['title']);
		$title->appendChild($doc->createTextNode($input_title->item(0)->nodeValue));
		$data->appendChild($title);

		$category=$doc->createElement("category");
		$input_category=$input_data->getElementsByTagName($contents['category']);
    if(!empty($input_category->item(0)->nodeValue))
    {
        $category_value=$input_category->item(0)->nodeValue;
    }
    else
    {
        $category_value='';
    }
		$cates=$contents['cate'];
		if(in_array($category_value,array_keys($cates)))
    {	
			$category_value=$cates[$category_value];
		}
		else 
    {
			$category_value='未知';
		}
		$category->appendChild($doc->createTextNode($category_value));
		$data->appendChild($category);

		$description=$doc->createElement("description");
		$input_description=$input_data->getElementsByTagName($contents['description']);
		if(!empty($input_description->item(0)->nodeValue))
    {
        $input_description=$input_description->item(0)->nodeValue;
    }
    else
    {
        $input_description='';
    }
		$description->appendChild($doc->createTextNode($input_description));
		$data->appendChild($description);

		$validThrough=$doc->createElement("validThrough");
		$input_validThrough=$input_data->getElementsByTagName($contents['validThrough']);
		$input_validThrough=$input_validThrough->item(0)->nodeValue;
		$input_validThrough=date('c',$input_validThrough);
		$validThrough->appendChild($doc->createTextNode($input_validThrough));
		$data->appendChild($validThrough);

		$validFrom=$doc->createElement("validFrom");
		$input_validFrom=$input_data->getElementsByTagName($contents['validFrom']);
		$input_validFrom=$input_validFrom->item(0)->nodeValue;
		$input_validFrom=date('c',$input_validFrom);
		$validFrom->appendChild($doc->createTextNode($input_validFrom));
		$data->appendChild($validFrom);
		
		$original_price=$doc->createElement("original_price");
		$input_original_price=$input_data->getElementsByTagName($contents['original_price']);
		$original_price->appendChild($doc->createTextNode($input_original_price->item(0)->nodeValue));
    $Ori_price = $input_original_price->item(0)->nodeValue;
		$data->appendChild($original_price);

		$present_price=$doc->createElement("present_price");
		$input_present_price=$input_data->getElementsByTagName($contents['present_price']);
		$present_price->appendChild($doc->createTextNode($input_present_price->item(0)->nodeValue));
    $Pre_price = $input_present_price->item(0)->nodeValue;
		$data->appendChild($present_price);
    
    
    $discount_value = $doc->createElement("discount_value");
    if($Ori_price != 0){
        $value = round((($Pre_price*10)/$Ori_price),2);
    }
    else
    {
        $value = '';
    }
    $discount_value -> appendChild($doc->createTextNode($value));
    $data -> appendChild($discount_value);
	
		$url=$doc->createElement("url");
		$input_url=$input_data->getElementsByTagName($contents['url']);
		$url->appendChild($doc->createTextNode($input_url->item(0)->nodeValue));
		$data->appendChild($url);

		$picture=$doc->createElement("picture");
		$input_picture=$input_data->getElementsByTagName($contents['picture']);
		$picture->appendChild($doc->createTextNode($input_picture->item(0)->nodeValue));
		$data->appendChild($picture);

		$gbsource=$doc->createElement("source");
		$gbsource->appendChild($doc->createTextNode($contents['source']));
		$data->appendChild($gbsource);

		$gbcity=$doc->createElement("gbcity");
		$input_city=$input_data->getElementsByTagName($contents['city']);
		$gbcity->appendChild($doc->createTextNode($input_city->item(0)->nodeValue));
		$data->appendChild($gbcity);
	
		$timestamp=$doc->createElement("timestamp");
		$timestamp->appendChild($doc->createTextNode(date('Y-m-d H:i:s',time())));
		$data->appendChild($timestamp);
	
		$shops=$doc->createElement("shops");
		$input_shops=$input_data->getElementsByTagName($contents['shop']);
		foreach ($input_shops as $input_shop)
    {
			$shop=$doc->createElement("shop");
			
			$city=$doc->createElement("city");
			$input_city=$input_data->getElementsByTagName($contents['city']);
			$city->appendChild($doc->createTextNode($input_city->item(0)->nodeValue));
			$shop->appendChild($city);
			
			$shop_name=$doc->createElement("shop_name");
			$input_shop_name=$input_data->getElementsByTagName($contents['shop_name']);
			if(!empty($input_shop_name->item(0)->nodeValue))
      {
          $shop_name->appendChild($doc->createTextNode($input_shop_name->item(0)->nodeValue));
      }
      else
      {
          $shop_name->appendChild($doc->createTextNode(''));
      }
			$shop->appendChild($city);

			$address=$doc->createElement("address");
      $err = $input_shop->getElementsByTagName($contents['address']);
      if(!empty($err))
      {
          $input_address=$err;
      }
      else
      {
         $input_address='';
      }
			
			if(!empty($input_address->item(0)->nodeValue))
      {
          $address->appendChild($doc->createTextNode($input_address->item(0)->nodeValue));
      }
      else
      {
          $address->appendChild($doc->createTextNode(''));
      }     
			$shop->appendChild($address);
		
			$latitude=$doc->createElement("latitude");
			$input_latitude=$input_shop->getElementsByTagName($contents['latitude']);
			if(!empty($input_latitude->item(0)->nodeValue))
      {
          $latitude->appendChild($doc->createTextNode($input_latitude->item(0)->nodeValue));
      }
      else
      {
          $latitude->appendChild($doc->createTextNode(''));
      } 
			$shop->appendChild($latitude);
		
			$longitude=$doc->createElement("longitude");
			$input_longitude=$input_shop->getElementsByTagName($contents['longitude']);
			if(!empty($input_longitude->item(0)->nodeValue))
      {
          $longitude->appendChild($doc->createTextNode($input_longitude->item(0)->nodeValue));
      }
      else
      {
          $longitude->appendChild($doc->createTextNode(''));
      }
			$shop->appendChild($longitude);
			
			$shops->appendChild($shop);
		}	
		$data->appendChild($shops);
    		$root->appendChild($data);
	} 
  
  return $doc;
}
?>

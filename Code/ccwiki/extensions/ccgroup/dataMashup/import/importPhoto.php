<?php
include_once (dirname(__FILE__).'/../../includes/import.php');
//translate the XML file into array: photos
function getPhoto($file)
{
    $doc = new DOMDocument('1.0','UTF-8');  
    $doc->load($file);
    $shops = $doc->getElementsByTagName('shop');
    $re_photos = array();
    foreach($shops as $shop)
    {
        $shop_arr['id'] = $shop->getElementsByTagName('shop_id')->item(0)->nodeValue;
        $photos = $shop->getElementsByTagName('photo');
        foreach($photos as $photo)
        {
            $photo_arr = array();
            
            $pic_id = $photo->getElementsByTagName('pic_id');
            if(!empty($pic_id->item(0)->nodeValue))
            {
                $photo_arr['id'] = $photo->getElementsByTagName('pic_id')->item(0)->nodeValue;
            }
            
            $pic_url = $photo->getElementsByTagName('pic_url');
            if(!empty($pic_url->item(0)->nodeValue))
            {
                $photo_arr['picture'] = $photo->getElementsByTagName('pic_url')->item(0)->nodeValue;
            }
            
            $pic_discription = $photo->getElementsByTagName('pic_discription');
            if(!empty($pic_discription->item(0)->nodeValue))
            {
                $photo_arr['description'] = $photo->getElementsByTagName('pic_discription')->item(0)->nodeValue;
            }
            else
            {
                $photo_arr['description'] = '';
            }
            
            $photo_arr['shop_page_name'] = 'Shop_'.$shop_arr['id'];   
               
            $re_photos[] = $photo_arr;
        }  
    }
    return $re_photos;
}


//import data page of Photo 
function importPhoto($file)
{
	$photos = getPhoto($file);
	$count = 0;
	foreach($photos as $photo){
		$result="{{ #ask: [[{{PAGENAME}}]]
                            | ?Ontology/PhotoId
                            | ?Ontology/PhotoDescription
                            | ?Ontology/PhotoUrl
                            | ?Ontology/Timestamp
                            | format=template
                            | template=ShowPhoto
                         }}";

		$result=$result."[[Ontology/PhotoId::".$photo['id']."|".$photo['id']." ]]";
		$result=$result."[[Ontology/PhotoDescription::".$photo['description']."|".$photo['description']." ]]";
		$result=$result."[[Ontology/PhotoUrl::".$photo['picture']."|".$photo['picture']." ]]";
	
	
		$result = $result."[[Category:Ontology/Photo]]";
		$timestamp = date('Y-m-d H:i:s',time());
		$result=$result."[[Ontology/Timestamp::".$timestamp."|".$timestamp." ]]";
		
		$photo_name='Photo_'.$photo['shop_page_name'].'_'.$photo['id'];

		if(!exist($photo_name)){
			echo 'save '.$photo_name.'
';
			savePage($photo_name,$result);
			$count = $count + 1;
		}
	}
	return $count;
}

?>

<?php
include_once (dirname(__FILE__).'/../conf.php');
include_once (dirname(__FILE__).'/import.php');
include_once (dirname(__FILE__).'/getPhotos.php');
function importPhotos($text,$userpage)
{
	$photodatas=getPhotos($text);
	var_dump($photodatas);
	foreach ($photodatas as $photodata ){
		$resultPhoto="{{ #ask: [[{{PAGENAME}}]]
                   | ?Ontology 0/photo_id
                   | ?Ontology 0/title
                   | ?Ontology 0/description
                   | ?Ontology 0/picture
                   | format=template
                   | template=ShowFlickr
                   }}";
		$resultPhoto=$resultPhoto."[[Ontology 0/photo_id::".$photodata['photo_id']."| ]]";
		$resultPhoto=$resultPhoto."[[Ontology 0/title::".$photodata['title']."| ]]";
		$resultPhoto=$resultPhoto."[[Ontology 0/description::".$photodata['description']."| ]]";
		$resultPhoto=$resultPhoto."[[Ontology 0/picture::".$photodata['picture']."| ]]";
		$resultPhoto=$resultPhoto."[[Ontology 0/userpage::".$userpage."| ]]";
		$resultPhoto=$resultPhoto."[[Ontology 0/timestamp::".date('Y-m-d H:i:s',time())."| ]]";
		$resultPhoto=$resultPhoto."[[Category:Ontology 0/Photo]]";
	
		if(!exist("Photo_".$photodata['photo_id']))	
			savePage("Photo_".$photodata['photo_id'],$resultPhoto);
	}
}
?>

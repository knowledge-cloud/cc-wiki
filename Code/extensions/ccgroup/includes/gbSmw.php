<?php
include '../conf.php';
global $ccHost;
global $ccPort;
global $ccSite;
global $ccWiki;
global $ccDB;
global $ccDBUsername;
global $ccDBPassword;
global $ccDBName;
global $cc_conf_gb;
global $cc_page;
function import($pagename,$content){
	global $ccHost;
	global $ccPort;
	global $ccSite;
	global $ccWiki;
	$url="http://".$ccHost.":".$ccPort."/".$ccWiki."/includes/import.php";
	$post_data="pageTitle=".$pagename."&pageContent=".$content."&section=new";
	$ch = curl_init();//打开
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);
$response  = curl_exec($ch);
curl_close($ch);//关闭
}
function exist($page){
	global $ccHost;
	global $ccPort;
	global $ccSite;
	global $ccWiki;
	global $ccDB;
	global $ccDBUsername;
	global $ccDBPassword;
	global $ccDBName;
	global $cc_conf_gb;
	global $cc_page;
	$link=mysql_connect($ccDB,$ccDBUsername,$ccDBPassword);
	if(!$link) echo "failed";
	mysql_select_db($ccDBName,$link);
	$sql="select * from page where page_title='$page'";
	$result=mysql_query($sql,$link);
	$firstline=mysql_fetch_array($result);
	if(empty($firstline))
	{
		return false;
	}
	else
		return true;
}
function delete($pagename){
	if(file_exists('../data/'.$pagename)){
		unlink('../data/'.$pagename);
	}
}

$result="";
/*
$link=mysql_connect($ccDB,$ccDBUsername,$ccDBPassword);
if(!$link) echo "failed";
mysql_select_db($ccDBName,$link);
$sql="select * from ".$cc_conf_gb." where page_name='$arg'";
$res=mysql_query($sql,$link);
$firstline=mysql_fetch_array($res);
$gb=$firstline['web'];
$city=$firstline['city'];
$time=$firstline['time'];
$sql="select keyword from ".$cc_page." where page_name='$arg'";
$res=mysql_query($sql,$link);
$firstline=mysql_fetch_array($res);
$keyword=$firstline['keyword'];
*/
$url="http://".$ccHost.":".$ccPort."/".$ccWiki."/includes/gbRdf.php";
$ch = curl_init($url);//打开
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
$response  = curl_exec($ch);
curl_close($ch);//关闭
$products=json_decode($response,true);
foreach ($products as $product ){
	$result="";
	$resultDiscount="";
	$resultTime="";
	$count=0;
	if(exist("Deal_".$product['goodrelation:id']))
		continue;
	foreach ($product["goodrelation:availableAtOrFrom"] as $location){
		$count++;
		$resultLocation="{{#display_point: ".$location['goodrelation:latitude'].",".$location['goodrelation:longitude']."|label=".$location['goodrelation:city'].",".$location['goodrelation:address']."}}";
	   
           	$resultLocation=$resultLocation."[[Ontology 0/address::".$location['goodrelation:address']."| ]]";
	          if($location['goodrelation:latitude']==''||$location['goodrelation:longitude']==''){
                 $location['goodrelation:latitude']=39.97687;
                $location['goodrelation:longitude']=116.49229;
                   }
                      
          	$resultLocation=$resultLocation."[[Ontology 0/latitude::".$location['goodrelation:latitude']."| ]]";
		$resultLocation=$resultLocation."[[Ontology 0/longitude::".$location['goodrelation:longitude']."| ]]";
		$resultLocation=$resultLocation."[[Ontology 0/city::".$location['goodrelation:city']."| ]]";
		$resultLocation=$resultLocation."[[Ontology 0/localWeather::".$product['goodrelation:id'].'_location'.$count.'_weather'."| ]]";
		$resultLocation=$resultLocation."[[Ontology 0/timestamp::".date('Y-m-d H:i:s',time())."| ]]";
		$resultLocation=$resultLocation."{{:".$product['goodrelation:id'].'_location'.$count.'_weather'."}}";
		$resultLocation=$resultLocation."[[Category:Ontology 0/Location]]";
		
		import($product['goodrelation:id'].'_location'.$count,$resultLocation);
	   
	    
	    
	    $resultWeather="{{ #ask: [[{{PAGENAME}}]]
                            | ?Ontology 0/today_condition
                            | ?Ontology 0/today_high_temperature
                            | ?Ontology 0/today_low_temperature
                            | ?Ontology 0/tomorrow_condition
                            | ?Ontology 0/tomorrow_high_temperature
                            | ?Ontology 0/tomorrow_low_temperature
                            | ?Ontology 0/theDayAfterTomorrow_condition
                            | ?Ontology 0/theDayAfterTomorrow_high_temperature
                            | ?Ontology 0/theDayAfterTomorrow_low_temperature
                            | format=template
                            | template=ShowWeather
                         }}";
	    $resultWeather=$resultWeather."[[Ontology 0/today_condition::".$location["goodrelation:weather"]["goodrelation:today_condition"]."| ]]";
	    $resultWeather=$resultWeather."[[Ontology 0/today_low_temperature::".$location["goodrelation:weather"]["goodrelation:today_low_temperature"]."| ]]";
	    $resultWeather=$resultWeather."[[Ontology 0/today_high_temperature::".$location["goodrelation:weather"]["goodrelation:today_high_temperature"]."| ]]";
	    $resultWeather=$resultWeather."[[Ontology 0/tomorrow_condition::".$location["goodrelation:weather"]["goodrelation:tomorrow_condition"]."| ]]";
	    $resultWeather=$resultWeather."[[Ontology 0/tomorrow_low_temperature::".$location["goodrelation:weather"]["goodrelation:tomorrow_low_temperature"]."| ]]";
	    $resultWeather=$resultWeather."[[Ontology 0/tomorrow_high_temperature::".$location["goodrelation:weather"]["goodrelation:tomorrow_high_temperature"]."| ]]";
	    $resultWeather=$resultWeather."[[Ontology 0/theDayAfterTomorrow_condition::".$location["goodrelation:weather"]["goodrelation:theDayAfterTomorrow_condition"]."| ]]";
	    $resultWeather=$resultWeather."[[Ontology 0/theDayAfterTomorrow_low_temperature::".$location["goodrelation:weather"]["goodrelation:theDayAfterTomorrow_low_temperature"]."| ]]";
	    $resultWeather=$resultWeather."[[Ontology 0/theDayAfterTomorrow_high_temperature::".$location["goodrelation:weather"]["goodrelation:theDayAfterTomorrow_high_temperature"]."| ]]";
	    $resultWeather=$resultWeather."[[Ontology 0/timestamp::".date('Y-m-d H:i:s',time())."| ]]";
	    $resultWeather=$resultWeather."[[Category:Ontology 0/Weather]]";
	    import($product['goodrelation:id'].'_location'.$count."_weather",$resultWeather);
	  
	    
	}
	$resultDiscount="{{ #ask: [[{{PAGENAME}}]]
                       | ?Ontology 0/original_price=original_price
                       | ?Ontology 0/present_price=present_price
                      }}";
	$resultDiscount=$resultDiscount."[[Ontology 0/original_price::".$product['goodrelation:hasDiscount']['goodrelation:original_price']."| ]]";
	$resultDiscount=$resultDiscount."[[Ontology 0/present_price::".$product['goodrelation:hasDiscount']['goodrelation:present_price']."| ]]";
	$resultDiscount=$resultDiscount."[[Ontology 0/timestamp::".date('Y-m-d H:i:s',time())."| ]]";
	$resultDiscount=$resultDiscount."[[Category:Ontology 0/Discount]]";
	import($product['goodrelation:id'].'_discount',$resultDiscount);

	
	$resultTime="{{#ask: [[{{PAGENAME}}]]
                  | ?Ontology 0/validFrom
                  | ?Ontology 0/validThrough
                  | format=timeline
                  | timelinebands=WEEK,MONTH,YEAR
                  | timelineposition=end
                  | limit=150
                }}";
	$resultTime=$resultTime."[[Ontology 0/validFrom::".$product['goodrelation:validTime']['goodrelation:validFrom']."| ]]";
	$resultTime=$resultTime."[[Ontology 0/validThrough::".$product['goodrelation:validTime']['goodrelation:validThrough']."| ]]";
	$resultTime=$resultTime."[[Ontology 0/timestamp::".date('Y-m-d H:i:s',time())."| ]]";
	$resultTime=$resultTime."[[Category:Ontology 0/Time]]";	
	import($product['goodrelation:id'].'_time',$resultTime);
	
	$result="{{ #ask: [[{{PAGENAME}}]]
               | ?Ontology 0/id
               | ?Ontology 0/title
               | ?Ontology 0/source
               | ?Ontology 0/picture
               | ?Ontology 0/original price
               | ?Ontology 0/present price
               | ?Ontology 0/validFrom
               | ?Ontology 0/description
               | ?Ontology 0/availableAtOrFrom
               | ?Ontology 0/url
               | ?Ontology 0/validThrough
               | format=template
               | template=ShowDeal
             }}";
	$result=$result."[[Ontology 0/id::".$product['goodrelation:id']."| ]]";
	$result=$result."[[Ontology 0/title::".substr($product['goodrelation:title'],0,100)."| ]]";
	$result=$result."[[Ontology 0/description::".$product['goodrelation:description']."| ]]";
	$result=$result."[[Ontology 0/url::".$product['goodrelation:url']."| ]]";
	$result=$result."[[Ontology 0/picture::".$product['goodrelation:picture']."| ]]";
	$result=$result."[[Ontology 0/source::".$product['goodrelation:website']."| ]]";
	$result=$result."[[Ontology 0/timestamp::".date('Y-m-d H:i:s',time())."| ]]";
	
	for($count;$count>=1;$count--){
	$result=$result."[[Ontology 0/availableAtOrFrom::".$product['goodrelation:id'].'_location'.$count."| ]]";
	$result=$result."{{:".$product['goodrelation:id'].'_location'.$count."}}";
	}
	$result=$result."[[Ontology 0/hasDiscount::".$product['goodrelation:id'].'_discount'."| ]]";
	$result=$result."{{:".$product['goodrelation:id'].'_discount'."}}";
	$result=$result."[[Ontology 0/validTime::".$product['goodrelation:id'].'_time'."| ]]";
	$result=$result."{{:".$product['goodrelation:id'].'_time'."}}";
	$result=$result."[[Category:Ontology 0/Deal]]";
	import("deal_".$product['goodrelation:id'],$result);
}

?>

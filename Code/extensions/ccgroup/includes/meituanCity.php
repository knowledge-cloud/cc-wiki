<?php
include '../conf.php';
$city_url="http://www.meituan.com/api/v1/divisions";
$input=new DOMDocument();
$input->load($city_url);
$input_datas=$input->getElementsByTagName("division");
$output=array();
$count=1;
foreach ($input_datas as $input_data){
	$city=array();
	$ccity_value=$input_data->getElementsByTagName("id");
	$ccity_value=$ccity_value->item(0)->nodeValue;
	$city['ecity']=$ccity_value;
	$ecity_value=$input_data->getElementsByTagName("name");
	$ecity_value=$ecity_value->item(0)->nodeValue;
	$city['ccity']=$ecity_value;
	$output[$ccity_value]=$city;
	$count++;
	if($count>=10)
		break;
}
$result=json_encode($output);
//$result=urlencode($result);
echo $result;
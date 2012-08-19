<?php
include_once (dirname(__FILE__).'/lib/HttpClient.class.php');
include_once (dirname(__FILE__).'/../conf.php');
$httext=$_REQUEST['httext'];
//$httext='杭州美食';
global $qqweibo_key,$ccHost;
$access_token='c2f71fae5c9a565d599d7cd0986198c7';
$openid='8D64F49D4F4D34B84BDF7165C05045E6';
$url="http://open.t.qq.com/api/statuses/ht_timeline_ext?";
$url.="oauth_consumer_key=".$qqweibo_key."&access_token=".$access_token."&openid=".$openid."&clientip=".$ccHost."&oauth_version=2.a&scope=all";	
$param="&format=json&httext=".$httext."&pageflag=1&pageinfo=''&reqnum=100";//startindex表示起始页 0是第一页 1是第二页
$url.=$param;

//$response = HttpClient::quickGet($url);
$ch = curl_init($url);//打开
if($ccProxy!="")
	curl_setopt($ch, CURLOPT_PROXY, $ccProxy);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response= curl_exec($ch);
//var_dump($response);
curl_close($ch);//关闭

$contents=json_decode($response,true);
$doc=new DOMDocument('1.0', 'UTF-8');
$doc->formatOutput=true;
$root=$doc->createElement("statuses");
$doc->appendChild($root);
$i=1;
$count=1;
if($contents['data']['info']==NULL)
{
	echo $doc->saveXML();
}
foreach ($contents['data']['info'] as $content){
	if($count<=10 and strlen($content['text'])>50){
        $status=$doc->createElement("status");
	$mid=$doc->createElement("mid");
	$text=$doc->createElement("text");
	$time=$doc->createElement("time");
	$name=$doc->createElement("name");
	$id=$doc->createElement("id");
	$image=$doc->createElement("image");
	$cate=$doc->createElement("cate");
	$page=$doc->createElement("page");
	$mid->appendChild($doc->createTextNode($content['id']));
	$text->appendChild($doc->createTextNode($content['text']));
	$timestamp=date('c',$content['timestamp']);
	$time->appendChild($doc->createTextNode($timestamp));
	$name->appendChild($doc->createTextNode($content['nick']));
	$id->appendChild($doc->createTextNode($content['name']));	
	if($content['head']==""){
		$image->appendChild($doc->createTextNode("http://img.kaixin001.com.cn/i/50_0_0.gif"));
	}
	else{
	$image->appendChild($doc->createTextNode($content['head']."/100.jpg"));
	}
	$cate->appendChild($doc->createTextNode('tencent'));
	$page->appendChild($doc->createTextNode($i));
	$i++;
	$status->appendChild($mid);
	$status->appendChild($text);
	$status->appendChild($time);
	$status->appendChild($name);
	$status->appendChild($id);
	$status->appendChild($image);
	$status->appendChild($cate);
	$status->appendChild($page);
	$root->appendChild($status);
        $count++;
}
}
echo $doc->saveXML();
?>

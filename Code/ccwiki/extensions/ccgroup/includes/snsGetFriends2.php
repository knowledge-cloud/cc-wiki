<?php
include_once (dirname(__FILE__).'/../conf.php');
//include_once (dirname(__FILE__).'/lib/HttpClient.class.php');
//2ad35211d244666aa091575e0e8c59fd;9C38229B891E22FB8BDB8F7FC041C83F
//$out=getQqweiboFriends("2ad35211d244666aa091575e0e8c59fd","9C38229B891E22FB8BDB8F7FC041C83F");
//var_dump($out);
//echo count($out);
function getRenrenFriends($access_token){
	global $renren_secret,$ccProxy;
	$method="friends.getFriends";
	$v="1.0";
	$format="XML";
	$content="access_token=".$access_token."format=".$format."method=".$method."v=".$v.$renren_secret;
	$sig=md5($content);
	$post_data="access_token=".$access_token."&format=".$format."&method=".$method."&v=".$v."&sig=".$sig;
	$url="http://api.renren.com/restserver.do";
	$response=false;
	$try=0;
	while($response==false&&$try<=10) //尝试十次
	{
		$ch = curl_init();
		if($ccProxy!="")
			curl_setopt($ch, CURLOPT_PROXY, $ccProxy);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);
		$response  = curl_exec($ch);
		curl_close($ch);
		$try=$try+1;
	}
	$output = array();
	$input = new DOMDocument();
	$input->loadXML($response);
	$friends=$input->getElementsByTagName("friend");
	if(!empty($friends))
	{
		foreach ($friends as $friend)
			{
			$item = array();
			$id=$friend->getElementsByTagName("id")->item(0)->nodeValue;
			$name=$friend->getElementsByTagName("name")->item(0)->nodeValue;
			$photo=$friend->getElementsByTagName("tinyurl")->item(0)->nodeValue;
			$item['sns_id']=$id;
			$item['name']=$name;
			$item['avatar']=$photo;
			$item['source']="renren";
			$output[]=$item;
		   }
	}
	//if(count($output)==0)
	  // var_dump($response);
	return $output;
}

function getKaixinFriends($access_token){
	global $ccProxy;
	$fields="";
	$url="https://api.kaixin001.com/friends/me.json?access_token=".$access_token."&fields=".$fields;
	$response=false;
	$try=0;
	while($response==false&&$try<=10) //尝试十次
	{
		$ch = curl_init($url);//打开
		if($ccProxy!="")
			curl_setopt($ch, CURLOPT_PROXY, $ccProxy);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
		$response  = curl_exec($ch);
		curl_close($ch);//关闭
		$try=$try+1;
	}
	$contents=json_decode($response,true);
	$output = array();
	if(!empty($contents['users']))
	{
		foreach($contents['users'] as $content){
			$item = array();
			$item['sns_id'] = $content['uid'];
			$item['name'] = $content['name'];
			$item['avatar'] = $content['logo50'];
			$item['source']="kaixin";
			$output[]=$item;
		}
	}
	//if(count($output)==0)
	 //var_dump($response);
	return $output;
}

function getQqweiboFriends($access_token,$openid){
	global $qqweibo_key,$ccHost,$ccProxy;
	$url="http://open.t.qq.com/api/friends/fanslist?";
	$url.="oauth_consumer_key=".$qqweibo_key."&access_token=".$access_token."&openid=".$openid."&clientip=".$ccHost."&oauth_version=2.a&scope=all";
	$index=0;
	$out = array();
	do
    {
		$param="&format=json&reqnum=30&startindex=".$index;//startindex表示起始页 0是第一页 1是第二页
		$url.=$param;
		//echo $url."<br/>";//test	
		$response=false;
		//$try=0;
		//while($response==false&&$try<=5) //尝试5次
		//{
			//$response = HttpClient::quickGet($url);// send http get request
			$ch = curl_init($url);//打开
			if($ccProxy!="")
			curl_setopt($ch, CURLOPT_PROXY, $ccProxy);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$response= curl_exec($ch);
			//var_dump($response);
			curl_close($ch);//关闭

			//$try=$try+1;
		//}
	   //echo "response:"; /////////////////test
	   //var_dump($response);///////////////////test
		$contents=json_decode($response,true);
		if(!empty($contents['data']['info']))
		{
			foreach($contents['data']['info'] as $content)
				{
					$item = array();
					$item['sns_id'] = $content['name'];//用name作id好了
					$item['name'] = $content['nick'];
					//$item['name'] = $content['name'];
					$item['avatar'] = ($content['head']=="")?"http://img.kaixin001.com.cn/i/50_0_0.gif":$content['head']."/50.jpg";
					$item['source'] = "tencent";
					$out[$item['sns_id']]=$item;
				}
		}
	   $index++;
	}while(!empty($contents['data']['info']));
	
	//if(count($out)==0)
	//var_dump($response);
	return $out;	
}

?>

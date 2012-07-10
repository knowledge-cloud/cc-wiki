<?php
include_once (dirname(__FILE__).'/../conf.php');
    ///保存一个wiki文件

function exist($page){
        global $ccHost,$ccPort,$ccSite,$ccWiki,$ccDB,$ccDBUsername,$ccDBPassword,$ccDBName,$cc_conf_gb,$cc_page;
        $link=mysql_connect($ccDB,$ccDBUsername,$ccDBPassword);
        if(!$link) echo "failed";
        mysql_select_db($ccDBName,$link);
        $sql="select * from page where page_title='$page'";
        $result=mysql_query($sql,$link);
        $firstline=mysql_fetch_array($result);
        if(empty($firstline))
                return false;
        else
                return true;
}


function  deletePage($pageTitle){
try {
	global $ccHost,$ccPort,$ccSite;
        $token = login();
        login($token);
	$xml=httpRequest("http://".$ccHost.":".$ccPort."/".$ccSite."/api.php?action=query&prop=info|revisions&intoken=edit&titles=Main%20Page","");
 	$token=substr($xml,stripos($xml,"token")+12,34);
				
        $fields['title'] = $pageTitle;
        $fields['action'] = 'delete';
        $fields['token'] = $token;	
        $xml1=httpRequest("http://".$ccHost.":".$ccPort."/".$ccSite."/api.php",$fields);
				
} catch (Exception $e) {
       die("FAILED in deletePage: " . $e->getMessage());
}
}
    ///发送wikiApi请求并返回结果
    function httpRequest($url, $post="") {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.9) Gecko/20071025 Firefox/2.0.0.9');
            curl_setopt($ch, CURLOPT_URL, ($url));
            curl_setopt($ch, CURLOPT_ENCODING, "UTF-8" );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_COOKIEFILE, "cookies.tmp");
            curl_setopt($ch, CURLOPT_COOKIEJAR, "cookies.tmp");
            if (!empty($post)) 
		curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
            $xml = curl_exec($ch);
            if (!$xml) {
                    throw new Exception("Error getting data from server ($url): " . curl_error($ch));
            }
            curl_close($ch);
            return $xml;
    }
    ///登录wiki api 
    function login ($token='') {
	    global $ccHost,$ccPort,$ccSite,$ccUsername,$ccPassword;
            $url = "http://".$ccHost.":".$ccPort."/".$ccSite."/api.php?action=login&format=xml";

            $params = "action=login&lgname=".$ccUsername."&lgpassword=".$ccPassword;
            if (!empty($token)) {
                    $params .= "&lgtoken=".$token;
            }
            $data = httpRequest($url, $params);
            if (empty($data)) {
                    throw new Exception("No data received from server. Check that API is enabled.");
            }
            $xml = simplexml_load_string($data);
            if (!empty($token)) {
                    //Check for successful login
                    $expr = "/api/login[@result='Success']";
                    $result = $xml->xpath($expr);
                    if(!count($result)) {
                            throw new Exception("Second Login Failed");
                    }
            } else {
                    $expr = "/api/login[@token]";
                    $result = $xml->xpath($expr);
                    if(!count($result)) {
                            throw new Exception("First Login Failed");
                    }
            }
            return $result[0]->attributes()->token;
    }
?>

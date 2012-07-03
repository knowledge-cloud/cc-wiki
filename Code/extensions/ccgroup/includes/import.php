<?php
include '../conf.php';
    ///保存一个wiki文件
    function  savePage($pageTitle, $pageContent){
	try {
                global $settings;
                $token = login($settings['user'], $settings['pass']);

		echo '<br />'.$token;
                login($settings['user'], $settings['pass'], $token);
		$xml=httpRequest($settings['wikiroot']."/api.php?action=query&prop=info|revisions&intoken=edit&titles=Main%20Page","");
		echo '<br />'.$token.'<br />';
 		$token=substr($xml,stripos($xml,"token")+12,34);
		echo $token;
//                $fieldsdelete['title'] = $settings['filename']; 
//                $fieldsdelete['action'] = 'delete';
//                $fieldsdelete['token'] = $token;
//	        $xml1=httpRequest($settings['wikiroot']."/api.php",$fieldsdelete);	
		      	
				
                $fields['title'] = $pageTitle;
		if($settings['section']!=null)
			$fields['section'] = $settings['section'];
		if($settings['sectiontitle']!=null)
			$fields['sectiontitle'] = $settings['sectiontitle'];
		$fields['text'] = $pageContent;
		if($settings['sectiontitle']!=null)
			$fields['summary'] = $settings['summary'];
                $fields['action'] = 'edit';
                $fields['token'] = $token;	
                $xml1=httpRequest($settings['wikiroot']."/api.php",$fields);
	        echo $xml1;
				
        } catch (Exception $e) {
                die("FAILED in savePage: " . $e->getMessage());
        }
    }
    ///获取一个页面的代码
    function  getPageResouceCode($pageTitle){
     
        return "";
    }
    ///发送wikiApi请求并返回结果
    function httpRequest($url, $post="") {
	    global $settings;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.9) Gecko/20071025 Firefox/2.0.0.9');
            curl_setopt($ch, CURLOPT_URL, ($url));
            curl_setopt($ch, CURLOPT_ENCODING, "UTF-8" );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $settings['cookiefile']);
            curl_setopt($ch, CURLOPT_COOKIEJAR, $settings['cookiefile']);
            if (!empty($post)) curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
            $xml = curl_exec($ch);
            if (!$xml) {
                    throw new Exception("Error getting data from server ($url): " . curl_error($ch));
            }
            curl_close($ch);
            return $xml;
    }
    ///登录wiki api 
    function login ($user, $pass, $token='') {
	    global $settings;
            $url = $settings['wikiroot'] . "/api.php?action=login&format=xml";

            $params = "action=login&lgname=".$user."&lgpassword=".$pass;
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
echo count($result);
                    if(!count($result)) {
                            throw new Exception("Login failed");
                    }
            } else {
                    $expr = "/api/login[@token]";
                    $result = $xml->xpath($expr);
                    if(!count($result)) {
                            throw new Exception("Login token not found in XML");
                    }
            }

            return $result[0]->attributes()->token;
    }
//$settings['wikiroot'] = "http://10.214.0.147/ccwiki_data";
$settings['wikiroot'] = "http://".$ccHost.":".$ccPort."/".$ccSite;
$pageTitle=$_REQUEST['pageTitle'];
$pageContent=$_REQUEST['pageContent'];
echo $pageTitle;
echo $pageContent;
$settings['section']=$_REQUEST['section'];
$settings['sectiontitle']=$_REQUEST['sectiontitle'];
$settings['summary']=$_REQUEST['summary'];

//$settings['user'] = "Root";
//$settings['pass'] = "ccntgrid";

$settings['user'] = $ccUsername;
$settings['pass'] = $ccPassword;
$settings['cookiefile'] = "cookies.tmp";

savePage($pageTitle, $pageContent);
?>

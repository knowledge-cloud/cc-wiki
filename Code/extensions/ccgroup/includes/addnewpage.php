<?php
$settings['wikiroot'] = "http://localhost/mediawiki-1.16.5";
$settings['user'] = "Admin";
$settings['pass'] = "123456";
$settings['cookiefile'] = "cookies.tmp";

function httpRequest($url, $post="") {
        global $settings;

        $ch = curl_init();
        //Change the user agent below suitably
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.9) Gecko/20071025 Firefox/2.0.0.9');
        curl_setopt($ch, CURLOPT_URL, ($url));
        curl_setopt( $ch, CURLOPT_ENCODING, "UTF-8" );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($ch, CURLOPT_COOKIEFILE, $settings['cookiefile']);
        curl_setopt ($ch, CURLOPT_COOKIEJAR, $settings['cookiefile']);
        if (!empty($post)) curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
        //UNCOMMENT TO DEBUG TO output.tmp
        //curl_setopt($ch, CURLOPT_VERBOSE, true); // Display communication with server
        //$fp = fopen("output.tmp", "w");
        //curl_setopt($ch, CURLOPT_STDERR, $fp); // Display communication with server
        
        $xml = curl_exec($ch);
        
        if (!$xml) {
                throw new Exception("Error getting data from server ($url): " . curl_error($ch));
        }

        curl_close($ch);
        
        return $xml;
}


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


     try {
                global $settings;
                $token = login($settings['user'], $settings['pass']);
                login($settings['user'], $settings['pass'], $token);
                //echo ("SUCCESS");
				
				
				$xml=httpRequest("http://localhost/mediawiki-1.16.5/api.php?action=query&prop=info&intoken=import&titles=Main%20Page","");
				$token=substr($xml,stripos($xml,"token")+12,34);
                //echo $xml;
				//echo $token;
		//	action=import?&?xml=dumpcontentgoeshere?&?token=58b54e0bab4a1d3fd3f7653af38e75cb%2B\	
		
$fields['xml'] = '@'.'./769950.xml'; //文件绝对路径
$fields['action'] = 'import';
$fields['token'] = $token;
//echo $fields['token'];
		
		
		
		$xml1=httpRequest("http://localhost/mediawiki-1.16.5/api.php",$fields);
		
		
		  echo $xml1;
		
		
		
		
		
		
		
		
		
	
						
				
					
				
				
				
				
				
        } catch (Exception $e) {
                die("FAILED: " . $e->getMessage());
        }


?>

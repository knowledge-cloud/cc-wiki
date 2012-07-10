<?php
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
?>

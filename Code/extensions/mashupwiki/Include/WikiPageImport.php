<?php
/**
 * Description of WikiPageImport
 *
 * @author masling
 */
class WikiPageImport {
    protected   $settings = Array();
    ///刷新页面
    public function purgePage($pageTitle){
        if(is_array($pageTitle))
            $pages=join("|",$pageTitle);
        else
            $pages=$pageTitle;
        $pages=rawurlencode($pages);
        $this->httpRequest($this->settings['wikiroot']."/api.php?action=purge&titles=".$pages);	
    }
       ///保存一个wiki文件
    public function savaPage($pageTitle,$pageContent){
       try {
                $token = $this->login($this->settings['user'], $this->settings['pass']);
                $this->login($this->settings['user'], $this->settings['pass'], $token);
		$xml=$this->httpRequest($this->settings['wikiroot']."/api.php?action=query&prop=info|revisions&intoken=edit&titles=Main%20Page","");
 		$token=substr($xml,stripos($xml,"token")+12,34);	      			
                $fields['title'] = $pageTitle;
		$fields['text'] = $pageContent;
                $fields['action'] = 'edit';
                $fields['token'] = $token;
                $xml1=$this->httpRequest($this->settings['wikiroot']."/api.php?format=xml",$fields);
                $xml = simplexml_load_string($xml1);
                $expr = "/api/edit[@result='Success']";
                $result = $xml->xpath($expr);                
                if(!count($result)) {
                        throw new Exception("编辑页面失败");
                }
	        return true;
				
        } catch (Exception $e) {
              return false;
        }
    }
    ///获取一个页面的代码
    public  function getPageResouceCode($pageTitle){
        $xml=$this->httpRequest($this->settings['wikiroot']."/api.php?action=query&prop=revisions&rvprop=content&format=xml&titles=".rawurlencode($pageTitle));
        $xmldoc = simplexml_load_string($xml);
        $expr = "/api/query/pages/page/revisions/rev";
        $result = $xmldoc->xpath($expr);
        if(count($result)>0)
            return $result[0][0];
        else 
            return false;
    }
    ///检查页面是否存在
    public function checkPage($pageTitle){
        $xml=$this->httpRequest($this->settings['wikiroot']."/api.php?action=query&format=xml&titles=".rawurlencode($pageTitle));
        $xmldoc = simplexml_load_string($xml);
        $expr = "/api/query/pages/page/@pageid";
        $result = $xmldoc->xpath($expr);
        //var_dump($result);
        if(count($result)>0)
            return $result[0];
        else return -1;
    }
    ///发送wikiApi请求并返回结果
    function httpRequest($url, $post="") {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.9) Gecko/20071025 Firefox/2.0.0.9');
            curl_setopt($ch, CURLOPT_URL, ($url));
            curl_setopt($ch, CURLOPT_ENCODING, "UTF-8" );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $this->settings['cookiefile']);
            curl_setopt($ch, CURLOPT_COOKIEJAR, $this->settings['cookiefile']);
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
            $url = $this->settings['wikiroot'] . "/api.php?action=login&format=xml";

            $params = "lgname=".$user."&lgpassword=".$pass;
            if (!empty($token)) {
                    $params .= "&lgtoken=".$token;
            }

            $data = $this->httpRequest($url, $params);

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
   ///设置默认值
    function __construct() {
        $this->settings['wikiroot'] = "http://10.214.0.147/ccwiki";
        $this->settings['user'] = "Root";
        $this->settings['pass'] = "ccntgrid";
        $this->settings['cookiefile'] = "cookies.tmp";
    }
}
?>

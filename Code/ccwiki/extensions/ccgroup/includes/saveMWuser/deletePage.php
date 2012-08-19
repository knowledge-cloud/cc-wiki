<?php
function deletePage($pageTitle){
	echo 'delete page '.$pageTitle.'<br />';
	try {
                global $settings;
                $token = login($settings['user'], $settings['pass']);

                echo '<br />'.$token;
                login($settings['user'], $settings['pass'], $token);
                $xml=httpRequest($settings['wikiroot']."/api.php?action=query&prop=info&intoken=delete&titles=Main%20Page","");
                echo '<br />'.$token.'<br />';
                $token=substr($xml,stripos($xml,"token")+12,34);
                echo $token;

		$fieldsdelete['title'] = $pageTitle;
                $fieldsdelete['action'] = 'delete';
                $fieldsdelete['token'] = $token;
                $xml1=httpRequest($settings['wikiroot']."/api.php",$fieldsdelete);
                echo $xml1;

        } catch (Exception $e) {
                die("FAILED in deletePage: " . $e->getMessage());
        }
}

?>

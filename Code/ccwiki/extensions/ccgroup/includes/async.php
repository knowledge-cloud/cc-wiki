<?php
include_once (dirname(__FILE__).'/../conf.php');
function async_call($path)
{
	global $ccHost,$ccPort;
    $fp=fsockopen($ccHost,$ccPort,$errno,$errstr,5);
    if(!$fp){
        echo"$errstr ($errno)<br />\n";
    }
    else
    {
    	echo 'in async'.'<br/>';
    	$out = "GET $path /HTTP/1.1\r\n";
    	$out .= "Host: $ccHost";
    	$out .= "Connection: Close\r\n\r\n";
    	fputs($fp,$out);
    	fclose($fp);
    }
}

?>

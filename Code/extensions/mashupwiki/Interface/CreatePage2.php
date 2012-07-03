<?php
session_start();
$ltype=$_REQUEST["ltype"];
$str="";
switch($ltype){
    case "part":
         $_SESSION["deal"]=$_REQUEST["deal"];
         $_SESSION["mode"]="part";
         $id= explode(" ", $_SESSION["deal"]);
         $_SESSION["url"]=$_REQUEST["url"];
         $str="'/extensions/ccgroup/includes/'+type+'participateLogin.php?deal='+encodeURI('".$id[1]."');";
         $title="参与者身份选择";
         break;
    case "inter":
         $_SESSION["deal"]=$_REQUEST["deal"];
         $id=explode(" ", $_SESSION["deal"]);
         $_SESSION["mode"]="inter";
         $_SESSION["url"]=$_REQUEST["url"];
         $str="'/extensions/ccgroup/includes/'+type+'interestLogin.php?deal='+encodeURI('".$id[1]."');";
         $title="感兴趣身份选择";
        break;
    case "create":
         $_SESSION["c_pagename"]=$_REQUEST["pagename"];
         $_SESSION["c_cateory"]=$_REQUEST["cateory"];
         $_SESSION["c_keywords"]= $_REQUEST["keywords"];
         $str="'/extensions/ccgroup/includes/'+type+'createLogin.php?userPage='+encodeURI('".$_REQUEST["pagename"]."');";
         $title="创建者身份选择";
         break;

    case "login":
	 $str="Special:UserLogin";
	 $title="登陆选择"; 
	 break;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>新建页面</title>
<link href="css/common.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="css/style.css" type="text/css" />
<script type="text/javascript">
    var api = "",W;
    if(frameElement){
       api= frameElement.api;
       W= api.opener;
    }
    function gologin(type){
	if(type=='cc')
        {
	 	window.open(W.mediaWiki.config.values.wgServer+W.mediaWiki.config.values.wgScriptPath+'/index.php/Special:UserLogin');	
        }else{
        var path=<?php echo $str?>;
        window.open(W.mediaWiki.config.values.wgServer+W.mediaWiki.config.values.wgScriptPath+path);
	api.close();
       }
    }
</script>
</head>
<body>
<table width="480" height="350" border="0" cellpadding="0" cellspacing="0" class="table1">
  <tr>
    <td height="35" colspan="2" align="center"><strong><?php echo $title?></strong></td>
  </tr>
  <tr>
    <td width="50%" height="60" align="center"><img src="images/logo_rr.png" alt="人人" onclick="gologin('renren');" width="147" height="47" /></td>
    <td width="50%" height="60" align="center"><img src="images/logo_tx.png" alt="腾讯" onclick="gologin('qqweibo');" width="147" height="47" /></td>
  </tr>
  <tr>
    <td width="50%" height="60" align="center"><img src="images/logo_kx.png" alt="开心" onclick="gologin('kaixin');" width="147" height="47" /></td>
    <td width="50%" height="60" align="center"><img src="images/logo_cc.png" alt="CCWiki" onclick="gologin('cc');" width="147" height="47" /></td>
  </tr>
</table>
</body>
</html>

<?php
//set_include_path(dirname(__FILE__));
include_once ( dirname(__FILE__).'/../conf.php');
include_once (dirname(__FILE__).'/../includes/snsMapping2.php');

/*
 * 这个SpecialPage用于将用户账号和一个sns的accessToken绑定；
 * 用户注册账号之后，跳转到该页面，然后由用户选择一个sns账号绑定
 */

class SpecialMapping extends SpecialPage {

        function __construct() 
        {
                parent::__construct( 'Mapping' );
        }

        function execute( $par )     //这个是该类的主函数
        {
	        global $wgOut, $wgRequest, $ccHost, $ccPort, $ccWiki;
			$this->opreateCookies();
	        $this->setHeaders();
	        $this->checkID();
	        $wgOut->addHTML( $this->makeForm() );

            /////当从SpecialUserInfo跳转过来时 弹出对话框
			$page=$wgRequest->getText( 'page' );
			$msg=$wgRequest->getText( 'msg' );
			if($page=='UserInfo' || $page=='interface.php')
			{
               $script='<SCRIPT Language="javaScript">window.alert("'.$msg.'"); </SCRIPT>';
				$wgOut->addHTML($script );
			}
            //////////////////////////

			$state=0;
			if(isset($_COOKIE['currentState']))
				$currentState=$_COOKIE['currentState'];
			else
				$currentState=0;
			$state=$this->setState($currentState);   //根据请求本页面时所传参数来设置本页面状态
			setcookie('currentState',$state);

			if($state==2||$state==3)//默认绑定账号返回或其它绑定账号返回
			{
				$mw=$_COOKIE['ccwikiUserName'];
				$sns=$wgRequest->getText( 'sns' );
				$access_token=$wgRequest->getText( 'access_token' );
				$openid='';
				if($sns=="qqweibo")
				$openid=$wgRequest->getText( 'openid' );

				$dbw = wfGetDB( DB_MASTER );
				$msg="";
				if($state==2)//默认绑定，需要更新数据库相关属性
				{
					 setDefaultSNS($mw,$sns);
					 $msg="为默认账号";
				} 
                //
				//弹出对话框，提示绑定成功
				 if($sns=='renren')
					 $msg2='人人账号';
				 elseif($sns=='kaixin')
					 $msg2='开心账号';
				 else
					 $msg2='腾讯微博账号';
				 $message=$msg2."绑定".$msg."成功！";

				 $script='<SCRIPT Language="javaScript">window.alert("'.$message.'"); </SCRIPT>';
				 $wgOut->addHTML($script );

				//更新span显示为，继续绑定或跳到主页
				 $script2="<SCRIPT Language='javaScript'>document.getElementById('prompt').innerText = '继续绑定多个账号或';</SCRIPT>";
				 $wgOut->addHTML($script2);
				 $script3="<SCRIPT Language='javaScript'>document.getElementById('indexUrl').innerText = '返回主页：'; </SCRIPT>";
				 $wgOut->addHTML($script3);
			}

	    }
        

        private function makeForm() //生成界面，界面上三个图标的按钮链接到授权页面
        {
	         global $ccHost, $ccPort, $ccWiki,$ccSite;
	         $title = self::getTitleFor( 'Mapping' );
	         $form = '<fieldset><legend>' . wfMsgHtml( 'Mapping' ) . '</legend>';
			 $url="http://".$ccHost.":".$ccPort."/".$ccSite."/index.php"; 
			//login Renren
	         $form .= '<span id="prompt">请首先选择与ccwiki默认绑定的账号:</span><a id="indexUrl" href="'.$url.'" ></a><br/>-----------------------------------------------<br /><a href="http://' .$ccHost. ':' .$ccPort. '/' .$ccWiki. '/includes/snsInterface.php?type=mapping&sns=renren"><input type="image" height="60" width="140" src="http://'.$ccHost.':'.$ccPort.'/'.$ccWiki.'/special/images/renren_logo.jpg" /></a> &nbsp &nbsp';
		    //login Kaixin
		        $form .= '<a href="http://' .$ccHost. ':' .$ccPort. '/' .$ccWiki. '/includes/snsInterface.php?type=mapping&sns=kaixin"><input type="image" height="50" width="140" src="http://'.$ccHost.':'.$ccPort.'/'.$ccWiki.'/special/images/kaixin_logo.jpg" /></a> &nbsp &nbsp';
		    //login Tencen
	      	    $form .= '<a href="http://' .$ccHost. ':' .$ccPort. '/' .$ccWiki. '/includes/snsInterface.php?type=mapping&sns=qqweibo"><input type="image" height="50" width="140" src="http://'.$ccHost.':'.$ccPort.'/'.$ccWiki.'/special/images/qqweibo_logo.gif" /></a> &nbsp &nbsp';
                $form .= '</fieldset>';
                return $form;
        }
        
        private function checkID() //检测用户是否已经注册并登陆
        {
			 global $ccHost, $ccPort, $ccSite;
        	 if(!isset($_COOKIE['ccwikiUserID']))
	   			header("Location:http://".$ccHost.":".$ccPort."/".$ccSite."/index.php/Special:UserLogin");
        }

		private function setState($currentState)
		{
           global  $wgRequest;
		   $out='0';
		   if($wgRequest->getText( 'page' )=='snsLogin'&&$wgRequest->getText( 'sns' )!=''&&$wgRequest->getText( 'access_token' )!='')
		   {
			   if($currentState==1)
			   $out='2';
			   else
			   $out='3';
		   }
		   else
		   {
			   $out='1';
		   }
		   return $out;
		}

}
?>

<?php
set_include_path(dirname(__FILE__));
include_once ( dirname(__FILE__).'/../conf.php');
include_once (dirname(__FILE__).'/../includes/snsMapping2.php');
include_once (dirname(__FILE__).'/../includes/snsGetUser.php');
include_once (dirname(__FILE__).'/../includes/snsCheckToken.php');

/*
 * 这个SpecialPage用于显示某个用户创建的、参与的、支持的、反对的页面
 */

class SpecialUserInfo extends SpecialPage {
      function __construct() 
        {
                parent::__construct( 'UserInfo' );
        }
	
 
    
       function execute( $par )     //这个是该类的主函数
        {
		  global $wgOut, $wgRequest, $ccHost, $ccPort, $ccSite,$srfgScriptPath;
		   $this->setHeaders();
           $wgOut->addStyle($srfgScriptPath."/CSS/userInfo.css");//add CSS
		   $Tokens=$this->getTokens();
		   $LArray=$this->checkTokens($Tokens);
		  /* if($msg!="")
		   {
			$src='Location:http://' .$ccHost. ':' .$ccPort. '/' .$ccSite. '/index.php/Special:mapping?page=UserInfo&msg='.$msg;
            header($src);
		   }*/ 
		   $user=$this->getSnsAccount($Tokens,$LArray);
		   $html=$this->setSnsInfo($user,$LArray);
		   $tmp=$this->setUnMapping($user,$LArray);
		   $html=$html.$tmp;
		   $wgOut->addHTML($html);
		   $wgOut->addHTML($this->showMsg($LArray));	   
		}
	    private function showMsg($LArray)
		{
			$script="";
			$msg="";
			if($LArray['renren']=='0')
				$msg.="人人网";
			if($LArray['kaixin']=='0')
			{
				if($msg!="")
					$msg.='、';
				$msg.="开心网";
			}
			if($LArray['qqweibo']=='0')
			{
				if($msg!="")
					$msg.='、';
				$msg.="腾讯微博";
			}
			if($msg!="")
			{
				$msg.="的TOKNE已失效，相关sns账户的页面信息将不能显示，如需显示，请重新绑定相关账号！";
				$script='<SCRIPT Language="javaScript">window.alert("'.$msg.'"); </SCRIPT>';
			}
			return $script;
			
		}
        public function getTokens()
		{
			global $wgOut, $wgRequest, $ccHost, $ccPort, $ccSite,$ccWiki;
			if(!isset($_COOKIE['ccwikiUserID']))
				header("Location:http://".$ccHost.":".$ccPort."/".$ccSite."/index.php/Special:UserLogin");

            $mw=$_COOKIE['ccwikiUserName'];
			$Tokens=array();
			$Users=array();
			$Tokens['renren']=getToken($mw,'renren');
			$Tokens['kaixin']=getToken($mw,'kaixin');
			$Tokens['qqweibo']=getToken($mw,'qqweibo');
			return $Tokens;
		}

		private function checkTokens($Tokens)
		{
			global $wgOut, $wgRequest, $ccHost, $ccPort, $ccSite,$ccWiki;	
			$IsLegal=array("renren"=>'1',"kaixin"=>'1',"qqweibo"=>'1');
			
			$html="";
			$state=0;
			if(!empty($Tokens['renren']))
			{
				$sns="renren";
				$access_token=$Tokens['renren'];
				$openid="";
				if(IsTokenExpired($sns,$access_token,$openid)=='1')
				{
					$state=1;
					$IsLegal['renren']='0';
				}
					
			}

			if(!empty($Tokens['kaixin']))
			{
				$sns="kaixin";
				$access_token=$Tokens['kaixin'];
				$openid="";
				if(IsTokenExpired($sns,$access_token,$openid)=='1')
				{
					$IsLegal['kaixin']='0';
					$state=1;
				}
					
			}

			if(!empty($Tokens['qqweibo']))
			{
				$data=explode(";",$Tokens['qqweibo']);
				$access_token=$data[0];
				$openid=$data[1];
				$sns="qqweibo";
				if(IsTokenExpired($sns,$access_token,$openid)=='1')
				{
					$IsLegal['qqweibo']='0';
					$state=1;
				}					
			}
			return $IsLegal;	
		}

		public function getSnsAccount($Tokens,$LArray)
		{	
			$Users=array();
			if(!empty($Tokens['renren'])&&$LArray['renren']=='1')
			{
				$Users['renren']=getRenrenUser($Tokens['renren']);
			}

			if(!empty($Tokens['kaixin'])&&$LArray['kaixin']=='1')
			{
				$Users['kaixin']=getKaixinUser($Tokens['kaixin']);
			}

			if(!empty($Tokens['qqweibo'])&&$LArray['qqweibo']=='1')
			{
				$data=explode(";",$Tokens['qqweibo']);
				$access_token=$data[0];
				$openid=$data[1];
				$Users['qqweibo']=getQqweiboUser($access_token,$openid);
			}
			
			return $Users;
		}

		private function setSnsInfo($user,$LArray)
		{
		      global $wgOut, $wgRequest, $ccHost, $ccPort, $ccSite;
			  $src='http://' .$ccHost. ':' .$ccPort. '/' .$ccSite.'/index.php/';
			  $form='<div class="frtitle">
					<h2>欢迎您，'.$_COOKIE['ccwikiUserName'].'，请点击图标查看对应账户信息：</h2><hr width=100% /></br>
					</div>';
			  $form.='<div class="searchbox">
					 <div class="searchbox2">
					 <div class="userinfo">
					 <table>
					 ';
			 if(!empty($user['renren'])&&$LArray['renren']=='1')
			  {
				  $page=$src.'Person_'.$user['renren']['sns_id'];
				  $form.='
						<td>
						<div class="fl"><a href="'.$page.'"><img src="'.$user['renren']['avatar'].'" width="80" height="80" class="img_b2" /></a></div>
						</td>

						<td>
						<div class="fr">
						<ul>
							<li>名称：'.$user['renren']['name'].'</li>
							<li>ID：'.$user['renren']['sns_id'].'</li>
							<li>来源：人人网</li>
						</ul>
						</div>
						</td>';
			   }
				if(!empty($user['kaixin'])&&$LArray['kaixin']=='1')
			  {
				  $page=$src.'Person_'.$user['kaixin']['sns_id'];
				  $form.='
						<td>
						<div class="fl"><a href="'.$page.'"><img src="'.$user['kaixin']['avatar'].'" width="80" height="80" class="img_b2" /></a></div>
						</td>

						<td>
						<div class="fr">
						<ul>
							<li>名称：'.$user['kaixin']['name'].'</li>
							<li>ID：'.$user['kaixin']['sns_id'].'</li>
							<li>来源：开心网</li>
						</ul>
						</div>
						</td>';
			   }
			   if(!empty($user['qqweibo'])&&$LArray['qqweibo']=='1')
			  {
				   $page=$src.'Person_'.$user['qqweibo']['sns_id'];
				  $form.='
						<td>
						<div class="fl"><a href="'.$page.'"><img src="'.$user['qqweibo']['avatar'].'" width="80" height="80" class="img_b2" /></a></div>
						</td>

						<td>
						<div class="fr">
						<ul>
							<li>名称：'.$user['qqweibo']['name'].'</li>
							<li>ID：'.$user['qqweibo']['sns_id'].'</li>
							<li>来源：腾讯微博</li>
						</ul>
						</div>
						</td>';
			   }
			   $form.=' </table>
					  </div >
					  </div >
					  </div >
					 ';
			 return $form;
		}
		private function setUnMapping($user,$LArray){
		      	global $wgOut, $wgRequest, $ccHost, $ccPort, $ccSite,$ccWiki;
			$flag=false;
			$form = '<fieldset>';
			$url="http://".$ccHost.":".$ccPort."/".$ccSite."/index.php";
                 	$form .= '<span id="prompt">点击图标可解除帐户绑定:</span><a id="indexUrl" href="'.$url.'" ></a><br/>-----------------------------------------------<br />';
			if(!empty($user['renren'])&&$LArray['renren']=='1'){
				$form.='<a href="http://' .$ccHost. ':' .$ccPort. '/' .$ccWiki. '/includes/snsInterface.php?type=unmapping&sns=renren"><input type="image" height="60" width="140" src="http://'.$ccHost.':'.$ccPort.'/'.$ccWiki.'/special/images/renren_logo.jpg" /></a> &nbsp &nbsp';
				$flag=true;
			}
			if(!empty($user['kaixin'])&&$LArray['kaixin']=='1')
			 {
                        	$form .= '<a href="http://' .$ccHost. ':' .$ccPort. '/' .$ccWiki. '/includes/snsInterface.php?type=unmapping&sns=kaixin"><input type="image" height="50" width="140" src="http://'.$ccHost.':'.$ccPort.'/'.$ccWiki.'/special/images/kaixin_logo.jpg" /></a> &nbsp &nbsp';
				$flag=true;
			}
                    //login Tencen
			 if(!empty($user['qqweibo'])&&$LArray['qqweibo']=='1'){
                    		$form .= '<a href="http://' .$ccHost. ':' .$ccPort. '/' .$ccWiki. '/includes/snsInterface.php?type=unmapping&sns=qqweibo"><input type="image" height="50" width="140" src="http://'.$ccHost.':'.$ccPort.'/'.$ccWiki.'/special/images/qqweibo_logo.gif" /></a> &nbsp &nbsp';
				$flag=true;
			}
        	        $form .= '</fieldset>';
			if($flag==true)
				return $form;	
			else
				return '';
		}	
        	 
}
?>

<?php
set_include_path(dirname(__FILE__));
include_once ( dirname(__FILE__).'/../conf.php');
//include_once ( dirname(__FILE__).'/../includes/snsGetFriends.php');
//include_once ( dirname(__FILE__).'/../includes/snsEditPage.php');
/*
 * 这个SpecialPage用于将用户账号和一个sns的accessToken绑定；
 * 用户注册账号之后，跳转到该页面，然后由用户选择一个sns账号绑定
 */

class SpecialCreatePage extends SpecialPage {

        function __construct() 
        {
                parent::__construct( 'CreatePage' );
        }

        function execute( $par )     //这个是该类的主函数
        {
	        global $wgOut, $wgRequest, $ccHost, $ccPort, $ccWiki;
		//	$wgOut->addHTML('<script type="text/javascript" src="http://code.jquery.com/jquery-1.4.2.min.js"></script>');
		//	$wgOut->addHTML(' <script type="text/javascript"			  src="http://ajax.microsoft.com/ajax/jquery.validate/1.7/jquery.validate.min.js"></script>');
	    //    $this->setHeaders();
	        $wgOut->addHTML( $this->makeForm() );
			$wgOut->addHTML( $this->addJS() );

			
	      
	    }
        

        private function makeForm() 
        {
	         global $ccHost, $ccPort, $ccWiki;
	         $title = self::getTitleFor( 'CreatePage' );
			 $form = '<fieldset id="field"><legend>' . wfMsgHtml( 'createpage' ) . '</legend>';

			 $form .= '<form id="formcreate"  methed="post" action="http://'.$ccHost.':'.$ccPort.'/ccwiki/extensions/mashupwiki/Interface/CreatePage2.php">
					<table width="500" border="0" cellpadding="0" cellspacing="0" class="table1">
				  <tr>
					<td width="30%" height="35" align="right">页面分类：</td>
					<td height="35"><label >
					  <select name="category" id="category" class="required" >
						<option></option> 
						<option value="Shopping">购物</option> 
						<option value="Restaurant">餐饮</option>
						<option value="Travelling">旅游</option>
						<option value="Entertainment">娱乐</option>
        					<option value="Cosmetology">美容</option>
						<option value="HealthCare">健康</option>
						<option value="LifeService">生活</option>
						<option value="Others">美容</option>
					  </select>
					</label></td>
				  </tr>
				  <tr>
					<td width="30%" height="35" align="right">页面名称：</td>
					<td height="35"><input type="text" name="pagename" id="pagename" class="required" /></td>
				  </tr>
				  <tr>
					<td height="35" align="right">关 键 字：</td>
					<td height="35"><input type="text" name="keywords" id="keywords" class="required" /></td>
				  </tr>
				  <tr >
					<td height="35" align="right">&nbsp;</td>
					<td height="35" style="margin-top: 10px; padding-top: 5px"><label>
					  <input type="image" name="submit" id="submit" class="submit" src="http://'.$ccHost.':'.$ccPort.'/'.$ccWiki.'/special/images/btn_submit.png" />
					&nbsp;
					<input type="image" name="reset" id="reset" src="http://'.$ccHost.':'.$ccPort.'/'.$ccWiki.'/special/images/btn_reset.png" />
					</label>
					<input type="hidden" id="ltype" name="ltype" value="create" /></td>
				  </tr>
						</table>
				</form>';			
				//  $form .= '</body></html></fieldset>';
				$form .= '</fieldset>';
				 return $form;
		 }

		 private function addJS()
		 {
		  global $wgOut, $wgRequest, $ccHost, $ccPort, $ccWiki, $ccSite;
		  $url1="http://".$ccHost.":".$ccPort."/".$ccWiki."/scripts/jquery.js";
		  $url2="http://".$ccHost.":".$ccPort."/".$ccWiki."/scripts/jquery.validate.js";
		  $url3="http://".$ccHost.":".$ccPort."/".$ccSite."/extensions/mashupwiki/Interface/MashupPageInterface.php?a=checkpage&pagename=";//验证页面名称
//		  $url3="../MashupPageInterface.php?a=checkpage&pagename=";//验证页面名称
		  $form= '<script Language="JavaScript" Src="'.$url1.'"></script>';
		  $form.= '<script Language="JavaScript" Src="'.$url2.'"></script>';
		  $form.='<style>
					select.error{
				    border: 1px dotted red;
					color:black
					}
					div.error { display: none; }
					input:focus { border: 1px dotted black;color:black}
					input.error { border: 1px dotted red;color:black }
					td{word-wrap: break-word; word-break: break-all;}
					span.formtips.onError{color:red; margin-left:5px}
					span.formtips.onSuccess{color:black; margin-left:5px}
				 </style>

					<script>
						var api = "",W;
						if(frameElement){
						   api= frameElement.api;
						   W= api.opener;
						}		
					   var C2d;
					   $(document).ready(function() {

						
						  //文本框失去焦点后
						 $(\'form :input\').blur(function(){
							    var $parent = $(this).parent();
								$parent.find(".formtips").remove();
							  if( $(this).is(\'#pagename\') )
							  {
								   if( this.value=="" )
								   {
									   var errorMsg = \'请输入页面名称 中英文都可以\';
									   $parent.append(\'<span class="formtips onError">\'+errorMsg+\'</span>\');
									   $(this).addClass("error");
									}else
									{
									 var pagename = $(this).val(); 
									 var changeUrl ="'.$url3.'"+pagename; 
									 var str =""; 
									 $.post(changeUrl,function(str)
									 { 
												if(str == \'false\')
												{ 
												   var errorMsg = \'您输入的页面名称已存在，请重新输入\';
												   $parent.append(\'<span class="formtips onError" >\'+errorMsg+\'</span>\');
												   $(this).addClass("error");
												}

												else
												{ 
												 //  var okMsg = \'输入正确\';
												 //  $parent.append(\'<span class="formtips onSuccess">\'+okMsg+\'</span>\');
												   $(this).removeClass("error");
												} 		   

											 })

									//  var okMsg = \'输入正确\';
                                    //  $parent.append(\'<span class="formtips onSuccess">\'+okMsg+\'</span>\');
	                                  $(this).removeClass("error");


									}
							  }


							 if( $(this).is(\'#category\') ){
							   if( this.value=="" ){
								   var errorMsg = \'请选择页面分类\';
								   $parent.append(\'<span class="formtips onError">\'+errorMsg+\'</span>\');
						    	    $(this).addClass("error");
							   }
							   else
							   {
									$(this).removeClass("error");
							   }
							   }
					
							  if( $(this).is(\'#keywords\')){
							   if( this.value==""&&this.value.length < 2 ){
								   var errorMsg = \'页面关键字不少于2个字\';
								   $parent.append(\'<span class="formtips onError">\'+errorMsg+\'</span>\');
								   $(this).addClass("error");
							   }

								else{
								 //  var okMsg = \'输入正确\';
								 // $parent.append(\'<span class="formtips onSuccess">\'+okMsg+\'</span>\');
								   $(this).removeClass("error");
							   }

							  }




						  })
						    //提交，最终验证。
							$(\'#submit\').click(function(){
								   $("form :input.required").trigger(\'blur\');
								   var numError = $(\'form .error\').length;
								   if(numError){
									   return false;
								   }
							})
							 $(\'#reset\').click(function(){
							  $("span.formtips.onError").remove();
							  $("span.formtips.onSuccess").remove();
							  $("#category").removeClass("error");
							  $("#keywords").removeClass("error");
							  $("#pagename").removeClass("error");
							  $("#category").attr("value","");
							  $("#keywords").attr("value","");
							  $("#pagename").attr("value","");
							  return false;
							  });
						});			
					</script>';
					return $form;
		 }
}
?>

<?php
set_include_path(dirname(__FILE__));
include_once ( dirname(__FILE__).'/../conf.php');
//include_once ( dirname(__FILE__).'/../includes/snsGetFriends.php');
//include_once ( dirname(__FILE__).'/../includes/snsEditPage.php');
/*
 * 这个SpecialPage用于将用户账号和一个sns的accessToken绑定；
 * 用户注册账号之后，跳转到该页面，然后由用户选择一个sns账号绑定
 */

class SpecialTestPage extends SpecialPage {

        function __construct() 
        {
                parent::__construct( 'TestPage' );
        }

        function execute( $par )     //这个是该类的主函数
        {
	        global $wgOut, $wgRequest, $ccHost, $ccPort, $ccWiki;
		//	$wgOut->addHTML('<script type="text/javascript" src="http://code.jquery.com/jquery-1.4.2.min.js"></script>');
		//	$wgOut->addHTML(' <script type="text/javascript"       src="http://ajax.microsoft.com/ajax/jquery.validate/1.7/jquery.validate.min.js"></script>');
			$wgOut->addHTML( $this->addJS() );
	    //    $this->setHeaders();
	        $wgOut->addHTML( $this->makeForm() );

			
	      
	    }
        

        private function makeForm() 
        {
	         global $ccHost, $ccPort, $ccWiki;
	         $title = self::getTitleFor( 'CreatePage' );
			 $form = '<fieldset><legend>' . wfMsgHtml( 'createpage' ) . '</legend>';

			 $form .= '<form id="formcreate"  methed="post" action="CreatePage2.php">
					<table width="500" border="0" cellpadding="0" cellspacing="0" class="table1">
				  <tr>
					<td width="30%" height="35" align="right">页面分类：</td>
					<td height="35"><label>
					  <select name="category" id="category">
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
					<td height="35" align="right">页面名称：</td>
					<td height="35"><input type="text" name="pagename" id="pagename" class="input300" /><br/></td>
				  </tr>
				  <tr>
					<td height="35" align="right">关 键 字：</td>
					<td height="35"><input type="text" name="keywords" id="keywords" class="input300" /><br/></td>
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
		  global $wgOut, $wgRequest, $ccHost, $ccPort, $ccWiki;
		  $url1="http://".$ccHost.":".$ccPort."/".$ccWiki."/scripts/jquery.js";
		  $url2="http://".$ccHost.":".$ccPort."/".$ccWiki."/scripts/jquery.validate.js";	
		  $form= '<script Language="JavaScript" Src="'.$url1.'"></script>';
		   $form.= '<script Language="JavaScript" Src="'.$url2.'"></script>';
		   $form.='<style>
					label.error, label.error {
				    color: red;
					font-style: italic
					}
					div.error { display: none; }
					input:focus { border: 1px dotted black; }
					input.error { border: 1px dotted red; }
					td{word-wrap: break-word; word-break: break-all;}
				 </style>

					<script>
						var api = "",W;
						if(frameElement){
						   api= frameElement.api;
						   W= api.opener;
						}
					
					   var C2d;
						$().ready(function() {
						 $("#formcreate").validate({
							rules: {
								category: "required",
								pagename: {
												required: true,
												remote: {
													url: "MashupPageInterface.php?a=checkpage",
													type: "post"
												}
								},
								keywords: {
									required: true,
									minlength: 2
								}
							},
								messages: {
								category: "请选择页面分类",
								pagename: {
									required: "请输入页面名称 中英文都可以",
									remote: "该页面已存在，请重新输入"
								},
								keywords: {
									required: "请输入页面",
									minlength: "页面关键字不少于2个字"
								}
		    				}
							
						});
						  
						});
							
					</script>';
					return $form;
		 }
}
?>

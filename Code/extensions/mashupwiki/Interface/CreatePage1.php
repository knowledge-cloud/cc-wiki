<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>新建页面</title>
<script src="../js/jquery.js"></script>
<script src="../js/jquery.validate.js"></script>
<style>
label.error, label.error {
	/* remove the next line when you have trouble in IE6 with labels in list */
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
			cateory: "required",
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
			cateory: "请选择页面分类",
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
       /* $("#submit").bind("click", function() {
            if($("#formcreate").valid()){
               C2d=W.jQuery17.dialog({
                        id: 'CreatePage2',
                        title:"登录窗口",
                        content: 'url:'+W.mediaWiki.config.values.wgServer+W.mediaWiki.config.values.wgScriptPath+'/extensions/mashupwiki/Interface/CreatePage2.php',
                        lock:true,
                        min:false,
                        max:false,
                        padding:'2px 1px 25px 10x',
                        background:'#ccc',
                        parent:api,
                        close:function(){
                            if($("#createdatapage").val()!=""){
                                setTimeout(function(){
                                   $.post("MashupPageInterface.php",$("#formcreate").serialize(),function(response){
                                       var valid = response === true;
                                       if(valid){
                                           W.jQuery17.dialog.confirm('创建页面成功，转到新页面按【是】,留在本页面按【否】', function(){
                                                    W.location.href=W.mediaWiki.config.values.wgServer+W.mediaWiki.config.values.wgScriptPath+"/index.php?title="+encodeURI($("#pagename"))
                                                }, function(){
                                                     W.created.close();
                                                });
                                       }},"json");
                                }, 100);
                            }
                        }
                    });
            }
        });*/
    });
</script>
</head>
<body>

<form id="formcreate"  methed="post" action="CreatePage2.php">
    <table width="500" border="0" cellpadding="0" cellspacing="0" class="table1">
  <tr>
    <td width="30%" height="35" align="right">页面分类：</td>
    <td height="35"><label>
      <select name="cateory" id="cateory">
        <option></option> 
        <option value="0">购物</option> 
        <option value="1">餐饮</option>
        <option value="2">旅游</option>
        <option value="3">娱乐</option>
        <option value="4">电影</option>
        <option value="5">健康</option>
        <option value="6">生活</option>
        <option value="7">美容</option>
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
      <input type="image" name="submit" id="submit" class="submit" src="images/btn_submit.png" />
    &nbsp;
    <img name="reset" id="reset" src="images/btn_reset.png" />
    </label>
    <input type="hidden" id="ltype" name="ltype" value="create" /></td>
  </tr>
        </table>
</form>

    <div style="hight:10px"> &nbsp; </div>
</body>
</html>

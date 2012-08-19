function DateToFullDateTimeString(date)
{
    var year = date.getFullYear();
    var month = date.getMonth();
    var day = date.getDate();
    var hour = date.getHours();
    var minute = date.getMinutes();
    var second = date.getSeconds();

    var datestr;

    if (month <9)
    {
        month = '0' + (month + 1);
    }
    if (day < 10)
    {
        day = '0' + day;
    }
    if (hour < 10)
    {
        hour = '0' + hour;
    }
    if (minute < 10)
    {
        minute = '0' + minute;
    }
    if (second < 10)
    {
        second = '0' + second;
    }

    datestr = year  + month + day  + hour+ "00" + "00";
    return datestr;
}
			
  function xiaoqu_click(selected){
      document.getElementById('xiaoquselect').innerHTML="校区:"+selected.value;
	  document.getElementById('xiaoquselect').value=selected.value;
      document.getElementById('content').style.display="";
      document.getElementById('xiaoquselect').style.display="";
      var mgr = FacetedSearch.singleton.FacetedSearchInstance.getAjaxSolrManager();
	  mgr.store.removeByValue("fq","smwh_Ontology/Campus_xsdvalue_s:紫金港");
	  mgr.store.removeByValue("fq","smwh_Ontology/Campus_xsdvalue_s:西溪");
	  mgr.store.removeByValue("fq","smwh_Ontology/Campus_xsdvalue_s:华家池");
      mgr.store.removeByValue("fq","smwh_Ontology/Campus_xsdvalue_s:之江");
	  mgr.store.removeByValue("fq","smwh_Ontology/Campus_xsdvalue_s:玉泉");
	  var tmp="smwh_Ontology/Campus_xsdvalue_s:"+selected.value;
	  mgr.store.addByValue("fq", "smwh_attributes:smwh_Ontology/Campus_xsdvalue_t");
      mgr.store.addByValue("fq", tmp);
	//  alert(tmp));
	  mgr.doRequest(0);
	 
  }
  function validdate_click(validdate){
  document.getElementById('validdate').value=validdate.value;
      document.getElementById('validdate').innerHTML="有效期:"+validdate.value;
      document.getElementById('content').style.display="";
      document.getElementById('validdate').style.display="";
	  var mgr = FacetedSearch.singleton.FacetedSearchInstance.getAjaxSolrManager();
	  //当前日期加数字
	  var d = new Date();
      var now=DateToFullDateTimeString(d);
      var now1=parseInt(now);
			  var now2=now1+1000000; //1
	  var now21=now2+10000000000;
	  var now3=now1+3000000; //2
	  var now31=now3+10000000000;
	  var now4=now1+7000000;//3
	  var now41=now4+10000000000;
	  var now5=now1+100000000;//4
	  var now51=now5+10000000000;
	
	 var from1="smwh_Ontology/ValidThrough_datevalue_l:["+now2+" TO "+now21+"]";
	 var from2="smwh_Ontology/ValidThrough_datevalue_l:["+now3+" TO "+now31+"]";
	 var from3="smwh_Ontology/ValidThrough_datevalue_l:["+now4+" TO "+now41+"]";
	 var from4="smwh_Ontology/ValidThrough_datevalue_l:["+now5+" TO "+now51+"]";
	  mgr.store.removeByValue("fq",from1);
      mgr.store.removeByValue("fq",from2);
	  mgr.store.removeByValue("fq",from3);
	  mgr.store.removeByValue("fq",from4);
	 
	
	  mgr.store.addByValue("fq", "smwh_attributes:smwh_Ontology/ValidThrough_xsdvalue_dt");
    
	  
	  if(validdate.value=="一天以上"){
	
	 //mgr.store.addByValue("fq","smwh_Ontology/ValidThrough_datevalue_l:[20120801000000 TO 20120801000000");
      mgr.store.addByValue("fq",from1);
	 
	  }
	      if(validdate.value=="三天以上"){
		
	  mgr.store.addByValue("fq",from2);
	  }
	      if(validdate.value=="一周以上"){
		
	 mgr.store.addByValue("fq",from3);
	  }   if(validdate.value=="一月以上"){
		
	 mgr.store.addByValue("fq",from4);
	  }
	  
	    mgr.doRequest(0);
	  
	  
	  
  }
  //折扣
  function shopcredit_click(shopcredit){
      document.getElementById('shopcredit').innerHTML="折扣选择:"+shopcredit.value;
      document.getElementById('content').style.display="";
	    document.getElementById('shopcredit').value=shopcredit.value;
      document.getElementById('shopcredit').style.display="";
	     var mgr = FacetedSearch.singleton.FacetedSearchInstance.getAjaxSolrManager();
	
	   mgr.store.removeByValue("fq","smwh_Ontology/Discount_value_xsdvalue_d:[0 TO 3]");
	  mgr.store.removeByValue("fq","smwh_Ontology/Discount_value_xsdvalue_d:[4 TO 5]");
	    mgr.store.removeByValue("fq","smwh_Ontology/Discount_value_xsdvalue_d:[6 TO 9]");

			  mgr.store.addByValue("fq", "smwh_attributes:smwh_Ontology/Discount_value_xsdvalue_d");
	
	  if(shopcredit.value=="0-3折"){
	  
		
	   mgr.store.addByValue("fq", "smwh_Ontology/Discount_value_xsdvalue_d:[0 TO 3]");
	 
	  }
	      if(shopcredit.value=="4-5折"){
		
	   mgr.store.addByValue("fq", "smwh_Ontology/Discount_value_xsdvalue_d:[4 TO 5]");
	 
	  }
	      if(shopcredit.value=="6-9折"){
		
	   mgr.store.addByValue("fq", "smwh_Ontology/Discount_value_xsdvalue_d:[6 TO 9]");
	 
	  }
	    


	  mgr.doRequest(0);
	  
	  
	  
  }
  function search_enter(searchcontent){
      if (event.keyCode==13)
      {
          document.getElementById("searchcontent").innerHTML="搜索内容:"+searchcontent.value;
          document.getElementById('searchcontent').style.display="";
      }

  }


function cancel_xiaoqu(xiaoqu){
	document.getElementById('xiaoquselect').style.display="none";
	var obj=document.getElementsByName('xiaoqu');
	var mgr = FacetedSearch.singleton.FacetedSearchInstance.getAjaxSolrManager();
	var tmp="smwh_Ontology/Campus_xsdvalue_s:"+document.getElementById('xiaoquselect').value;

	mgr.store.removeByValue("fq",tmp);
 mgr.store.removeByValue("fq", "smwh_attributes:smwh_Ontology/Campus_xsdvalue_t");
	mgr.doRequest(0);

	for(var i=0;i<obj.length;i++){
		if(obj[i].checked)
		   obj[i].checked=false;
		}
	
	}
	
function cancel_date(date){
      document.getElementById('validdate').style.display="none";
	 var obj1=document.getElementsByName('date');
	for(var i=0;i<obj1.length;i++){
		if(obj1[i].checked)
		   obj1[i].checked=false;
		}
	
			  var d = new Date();
      var now=DateToFullDateTimeString(d);
       var now1=parseInt(now);
	  var now2=now1+1000000; //1
	  var now21=now2+10000000000;
	  var now3=now1+3000000; //2
	  var now31=now3+10000000000;
	  var now4=now1+7000000;//3
	  var now41=now4+10000000000;
	  var now5=now1+100000000;//4
	  var now51=now5+10000000000;
		var mgr = FacetedSearch.singleton.FacetedSearchInstance.getAjaxSolrManager();
	 var from1="smwh_Ontology/ValidThrough_datevalue_l:["+now2+" TO "+now21+"]";
	 var from2="smwh_Ontology/ValidThrough_datevalue_l:["+now3+" TO "+now31+"]";
	 var from3="smwh_Ontology/ValidThrough_datevalue_l:["+now4+" TO "+now41+"]";
	 var from4="smwh_Ontology/ValidThrough_datevalue_l:["+now5+" TO "+now51+"]";
	  	var tmp=document.getElementById('validdate').value;
		
		/*var fq = mgr.store.values('fq');

		for (var i = 0, l = fq.length; i < l; i++) {
			alert(fq[i]);
			
		}
		*/
	
	 	  if(tmp=="一天以上"){
	
      mgr.store.removeByValue("fq",from1);
	 
	  }
	      if(tmp=="三天以上"){
		
	  mgr.store.removeByValue("fq",from2);
	  }
	      if(tmp=="一周以上"){
		
	 mgr.store.removeByValue("fq",from3);
	  }   if(tmp=="一月以上"){
		
	 mgr.store.removeByValue("fq",from4);
	  }
	  
	  	  mgr.doRequest(0);
		}
		
function cancel_credit(credit){




    var mgr = FacetedSearch.singleton.FacetedSearchInstance.getAjaxSolrManager();
	var tmp=document.getElementById('shopcredit').value;
	
	  if(tmp=="0-3折"){
	  
		
	   mgr.store.removeByValue("fq", "smwh_Ontology/Discount_value_xsdvalue_d:[0 TO 3]");
	 
	  }
	      if(tmp=="4-5折"){
		
	   mgr.store.removeByValue("fq", "smwh_Ontology/Discount_value_xsdvalue_d:[4 TO 5]");
	 
	  }
	      if(tmp=="6-9折"){
		
	   mgr.store.removeByValue("fq", "smwh_Ontology/Discount_value_xsdvalue_d:[6 TO 9]");
	 
	  }
			  mgr.doRequest(0);
		
		
		
		
		
		
	      document.getElementById('shopcredit').style.display="none";
		  
		  var obj2=document.getElementsByName('credit');
	for(var i=0;i<obj2.length;i++){
		if(obj2[i].checked)
		   obj2[i].checked=false;
		} }

function navigate(id,info){
var tmp;
if(info==null||info=="(+*)"){
tmp="smwh_search_field:(+*)";
$("#query").attr("value","");
   document.getElementById("searchcontent").innerHTML="搜索内容: ";
          document.getElementById('searchcontent').style.display="";

}else 
{
tmp="smwh_search_field:(+"+info+"*)";
$("#query").attr("value",info);
 document.getElementById("searchcontent").innerHTML="搜索内容: <a id='"+info+"'href='javascript:cancel_choosen(\""+info+"\")'>"+info+"</a>";
          document.getElementById('searchcontent').style.display="";

}




  if(id==0){
 //餐饮美食
  	  	var mgr = FacetedSearch.singleton.FacetedSearchInstance.getAjaxSolrManager();
		mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:餐饮美食");
		mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:商品零售");
			mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:休闲娱乐");
			mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:生活服务");
				mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:酒店旅游");
					mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:健康美容");
					mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:未知");
	 mgr.store.addByValue("fq","smwh_attributes:smwh_Ontology/Category_xsdvalue_t");
		mgr.store.addByValue("fq","smwh_Ontology/Category_xsdvalue_s:餐饮美食");
	 		 //$("#query").attr("value","");
 mgr.store.addByValue('q',tmp);
			mgr.doRequest(0);
			
       document.getElementById('topid').innerHTML="<span class='px14 orange'><strong>热门分类：</strong><a href='javascript:gototop(\"火锅\")' class='orange'>火锅</a>&nbsp&nbsp&nbsp&nbsp<a href='javascript:gototop(\"自助\")' class='orange'>自助&nbsp&nbsp&nbsp&nbsp</a><a href='javascript:gototop(\"西餐\")' class='orange'>西餐&nbsp&nbsp&nbsp&nbsp</a><a href='javascript:gototop(\"海鲜\")' class='orange'>海鲜&nbsp&nbsp&nbsp&nbsp</a><a href='javascript:gototop(\"咖啡茶馆\")' class='orange'>咖啡茶馆</a></span>";
	   document.getElementById('topid').style.display="";
	  ////    document.getElementById('categoryselect').innerHTML="餐饮";
	//  document.getElementById('categoryselect').value=selected.value;
	    //
		 //  document.getElementById('categoryselect').style.display="";
  }
   if(id==1){//餐饮
 
  	  	var mgr = FacetedSearch.singleton.FacetedSearchInstance.getAjaxSolrManager();
		mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:餐饮美食");
		mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:商品零售");
		mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:休闲娱乐");
		mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:生活服务");
	    mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:酒店旅游");
		mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:健康美容");
		mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:未知");
	 mgr.store.addByValue("fq","smwh_attributes:smwh_Ontology/Category_xsdvalue_t");
		mgr.store.addByValue("fq","smwh_Ontology/Category_xsdvalue_s:商品零售");
	 		// $("#query").attr("value","");
 mgr.store.addByValue('q',tmp);
			mgr.doRequest(0);
	   document.getElementById('topid').innerHTML="<span class='px14 orange'><strong>热门分类：</strong><a href='javascript:gototop(\"家居\")' class='orange'>家居&nbsp&nbsp&nbsp&nbsp</a><a href='javascript:gototop(\"数码\")' class='orange'>数码&nbsp&nbsp&nbsp&nbsp</a><a href='javascript:gototop(\"配饰\")'class='orange'>配饰&nbsp&nbsp&nbsp&nbsp</a><a href='javascript:gototop(\"母婴用品\")' class='orange'>母婴用品&nbsp&nbsp&nbsp&nbsp</a><a href='javascript:gototop(\"运动用品\")' class='orange'>运动用品</a>";
	   document.getElementById('topid').style.display="";
  
  }
    if(id==2){//餐饮
 
  	  	var mgr = FacetedSearch.singleton.FacetedSearchInstance.getAjaxSolrManager();
			mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:餐饮美食");
		mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:商品零售");
		mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:休闲娱乐");
		mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:生活服务");
	    mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:酒店旅游");
		mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:健康美容");
		mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:未知");
	 mgr.store.addByValue("fq","smwh_attributes:smwh_Ontology/Category_xsdvalue_t");
		mgr.store.addByValue("fq","smwh_Ontology/Category_xsdvalue_s:休闲娱乐");
				// $("#query").attr("value","");
 mgr.store.addByValue('q',tmp);
			mgr.doRequest(0);
			   document.getElementById('topid').innerHTML="<span class='px14 orange'><strong>热门分类：</strong><a href='javascript:gototop(\"电影\")' class='orange'>电影&nbsp&nbsp&nbsp&nbsp</a><a href='javascript:gototop(\"景点乐园\")' class='orange'>景点乐园&nbsp&nbsp&nbsp&nbsp</a><a href='javascript:gototop(\"KTV\")'class='orange'>KTV</a><a href='javascript:gototop(\"摄影写真\")' class='orange'>摄影写真&nbsp&nbsp&nbsp&nbsp</a><a href='javascript:gototop(\"足疗按摩\")' class='orange'>足疗按摩&nbsp&nbsp&nbsp&nbsp</a><a href='javascript:gototop(\"温泉\")' class='orange'>温泉</a>";
	   document.getElementById('topid').style.display="";
  
  }
      if(id==3){//餐饮
 
  	  	var mgr = FacetedSearch.singleton.FacetedSearchInstance.getAjaxSolrManager();
			mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:餐饮美食");
		mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:商品零售");
		mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:休闲娱乐");
		mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:生活服务");
	    mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:酒店旅游");
		mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:健康美容");
		mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:未知");
	 mgr.store.addByValue("fq","smwh_attributes:smwh_Ontology/Category_xsdvalue_t");
		mgr.store.addByValue("fq","smwh_Ontology/Category_xsdvalue_s:生活服务");
				// $("#query").attr("value","");
 mgr.store.addByValue('q',tmp);
			mgr.doRequest(0);
			     document.getElementById('topid').innerHTML="<span class='px14 orange'><strong>热门分类：</strong><a href='javascript:gototop(\"配镜\")'class='orange'>配镜&nbsp&nbsp&nbsp&nbsp</a><a href='javascript:gototop(\"汽车保养\")'class='orange'>汽车保养&nbsp&nbsp&nbsp&nbsp</a><a href='javascript:gototop(\"体检保健\")'class='orange'>体检保健&nbsp&nbsp&nbsp&nbsp</a><a href='javascript:gototop(\"艺术舞蹈\")'class='orange'>艺术舞蹈</a>";
	   document.getElementById('topid').style.display="";
  
  }
  
      if(id==4){//餐饮
 
  	  	var mgr = FacetedSearch.singleton.FacetedSearchInstance.getAjaxSolrManager();
			mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:餐饮美食");
		mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:商品零售");
		mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:休闲娱乐");
		mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:生活服务");
	    mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:酒店旅游");
		mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:健康美容");
		mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:未知");
	 mgr.store.addByValue("fq","smwh_attributes:smwh_Ontology/Category_xsdvalue_t");
		mgr.store.addByValue("fq","smwh_Ontology/Category_xsdvalue_s:酒店旅游");
				// $("#query").attr("value","");
 mgr.store.addByValue('q',tmp);
			mgr.doRequest(0);
				   document.getElementById('topid').innerHTML="<span class='px14 orange'><strong>热门分类：</strong><a href='javascript:gototop(\"豪华酒店\")' class='orange'>豪华酒店&nbsp&nbsp&nbsp&nbsp</a><a href='javascript:gototop(\"特色酒店\")'class='orange'>特色酒店&nbsp&nbsp&nbsp&nbsp</a><a href='javascript:gototop(\"经济型酒店\")'class='orange'>经济型酒店&nbsp&nbsp&nbsp&nbsp</a><a href='javascript:gototop(\"国内游\")'class='orange'>国内游</a>";
	   document.getElementById('topid').style.display="";
  
  }
      if(id==5){//餐饮
 
  	  	var mgr = FacetedSearch.singleton.FacetedSearchInstance.getAjaxSolrManager();
				mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:餐饮美食");
		mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:商品零售");
		mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:休闲娱乐");
		mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:生活服务");
	    mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:酒店旅游");
		mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:健康美容");
		mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:未知");
	 mgr.store.addByValue("fq","smwh_attributes:smwh_Ontology/Category_xsdvalue_t");
		mgr.store.addByValue("fq","smwh_Ontology/Category_xsdvalue_s:健康美容");
				// $("#query").attr("value","");
 mgr.store.addByValue('q',tmp);
			mgr.doRequest(0);
    document.getElementById('topid').innerHTML="<span class='px14 orange'><strong>热门分类：</strong><a href='javascript:gototop(\"美发\")'class='orange'>美发&nbsp&nbsp&nbsp&nbsp</a><a href='javascript:gototop(\"美甲\")'class='orange'>美甲&nbsp&nbsp&nbsp&nbsp</a><a href='javascript:gototop(\"美体\")'class='orange'>美体&nbsp&nbsp&nbsp&nbsp</a><a href='javascript:gototop(\"游泳\")'class='orange'>游泳</a>";
	   document.getElementById('topid').style.display="";
  }    if(id==6){//餐饮
 
  	  	var mgr = FacetedSearch.singleton.FacetedSearchInstance.getAjaxSolrManager();
			mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:餐饮美食");
		mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:商品零售");
		mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:休闲娱乐");
		mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:生活服务");
	    mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:酒店旅游");
		mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:健康美容");
		mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:未知");
	 mgr.store.addByValue("fq","smwh_attributes:smwh_Ontology/Category_xsdvalue_t");
		mgr.store.addByValue("fq","smwh_Ontology/Category_xsdvalue_s:未知");
				// $("#query").attr("value","");
  mgr.store.addByValue('q',tmp);
			mgr.doRequest(0);
  
  }    if(id==7){//所有


  	  	var mgr = FacetedSearch.singleton.FacetedSearchInstance.getAjaxSolrManager();
				mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:餐饮美食");
		mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:商品零售");
		mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:休闲娱乐");
		mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:生活服务");
	    mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:酒店旅游");
		mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:健康美容");
		mgr.store.removeByValue("fq","smwh_Ontology/Category_xsdvalue_s:未知");
		// $("#query").attr("value","");
 mgr.store.addByValue('q',tmp);
			mgr.doRequest(0);
  
  }
    
  }

 function gototop(id){
 if(id=="(+*)"){
   // document.getElementById("searchcontent").innerHTML="搜索内容:";
    //document.getElementById('searchcontent').style.display="";
  return;
 
 }
   var querystring="smwh_search_field:(+";
var querystring1=$("#query").attr("value");
querystring1=querystring1.replace(/\s/g,"");
 	var mgr = FacetedSearch.singleton.FacetedSearchInstance.getAjaxSolrManager();
//alert(querystring1);

if(querystring1==""){

$("#query").attr("value",id);
   document.getElementById("searchcontent").innerHTML="搜索内容: <a id='"+id+"'href='javascript:cancel_choosen(\""+id+"\")'>"+id+"</a>";
          document.getElementById('searchcontent').style.display="";
	var mSearch =id;

		// trim the search term
		mSearch = mSearch.replace(/^\s*(.*?)\s*$/,'$1');
		
		var qs = mSearch;

		// If the query is enclosed in braces it is treated as expert query.
		// Expert queries may contain logical operators. Text is not converted
		// to lowercase.
		mExpertQuery = qs.charAt(0) === '(' 
					   && qs.charAt(mSearch.length-1) === ')';
		if (!mExpertQuery) {
			qs = prepareQueryString(qs);
		} else {
			// A colon in the search term must be escaped otherwise SOLR will throw
			// a parser exception
			qs = qs.replace(/(:)/g,"\\$1");
		}
		//alert(qs);
		mgr.store.addByValue('q','smwh_search_field:'+qs);
		mgr.doRequest(0);

}else

{
querystring1=querystring1.replace(/\s/g,"");
var tmpstring=querystring1.split("or");
tmpstring.push(id);
	var mgr = FacetedSearch.singleton.FacetedSearchInstance.getAjaxSolrManager();
var inputtext="";
$('#searchcontent').html("");
$('#searchcontent').append("搜索内容:");

for(var i = 0; i < tmpstring.length; i ++)
{  
  
	inputtext=" or "+tmpstring[i]+inputtext;

	   $('#searchcontent').append("<a id='"+tmpstring[i]+"'href='javascript:cancel_choosen(\""+tmpstring[i]+"\")'>"+tmpstring[i]+"</a>"+"&nbsp;&nbsp;&nbsp;&nbsp;");
       
       querystring=querystring+tmpstring[i]+"*) OR smwh_search_field:(+";
	
}

querystring=querystring.substring(0,querystring.length-24);
//alert(querystring);
inputtext=inputtext.substring(3);
$("#query").attr("value",inputtext);

mgr.store.addByValue('q',querystring);
	mgr.doRequest(0);	
	}
		
 
 }
  function cancel_choosen(id){
    var querystring="smwh_search_field:(+";
  var querystring1=$("#query").attr("value");
querystring1=querystring1.replace(/\s/g,"");
var tmpstring=querystring1.split("or");
//tmpstring.push(id);

   if(tmpstring.search("/"+id+"/")!=-1){

tmpstring.remove(tmpstring.getIndexByValue(id));
  
var inputtext="";
$('#searchcontent').html("");
$('#searchcontent').append("搜索内容:");

for(var i = 0; i < tmpstring.length; i ++)
{  
   //alert(tmpstring[i]);
	inputtext=" or "+tmpstring[i]+inputtext;

	   $('#searchcontent').append("<a id='"+tmpstring[i]+"'href='javascript:cancel_choosen(\""+tmpstring[i]+"\")'>"+tmpstring[i]+"</a>"+"&nbsp;&nbsp;&nbsp;&nbsp;");
       
       querystring=querystring+tmpstring[i]+"*) OR smwh_search_field:(+";
	
}

querystring=querystring.substring(0,querystring.length-24);
//alert(querystring);
inputtext=inputtext.substring(3);
$("#query").attr("value",inputtext);
//alert(querystring);
if(querystring=="")
{querystring="smwh_search_field:(+*)";
}
//alert(querystring);
mgr.store.addByValue('q',querystring);
	mgr.doRequest(0);


 //  alert("删掉");
  }
  else {
  
   // GolbalArray.push(textid.value);
	 //alert("新的");
	}



 //document.getElementById(id).style.display="none"; 
  
  
  }
  function paixu(id){
  var mgr = FacetedSearch.singleton.FacetedSearchInstance.getAjaxSolrManager();
  if(id==1){
  

  	mgr.store.addByValue('sort',"smwh_Modification_date_xsdvalue_dt asc, score desc");
		mgr.doRequest(0);
  
  
  }
   if(id==2){

  	mgr.store.addByValue('sort',"smwh_Ontology/Present_price_xsdvalue_d asc, score desc");
		mgr.doRequest(0);
  
  
  }
  
  
  
  }
	function prepareQueryString(queryString) {
		// Extract all phrases
		var phrases = queryString.match(/".*?"/g);
		var endWithPhrase = queryString.charAt(queryString.length-1) === '"';
		
		// Remove phrases from the query string and trim it
		queryString = queryString.replace(/(".*?")/g, '')
								 .replace(/^\s*(.*?)\s*$/,'$1')
								 .replace(/\s\s*/g, ' ');
		
		// Split the query string at spaces in words
		var words = queryString.split(' ');
		
		var result = "";
		
		// Convert words to lower case and escape the special characters:
		// + - && || ! ( ) { } [ ] ^ " ~ * ? : \			
		for (var i = 0, numWords = words.length; i < numWords; ++i) {
			var w = words[i].toLowerCase()
			                   .replace(/([\+\-!\(\)\{\}\[\]\^"~\*\?\\:])/g, '\\$1')
					           .replace(/(&&|\|\|)/g,'\\$1');
			// Add a * to the last word if the query string does not end with a phrase
			if (!endWithPhrase && i == numWords-1) {
				w += '*';
			}
							   
			result += "+" + w + " ";
		}
		
		// Escape special characters in phrases
		if (phrases) {
			for (i = 0; i < phrases.length; ++i) {
				var p = phrases[i].substring(1,phrases[i].length-1);
				var p = '+"' + p.replace(/([\+\-!\(\)\{\}\[\]\^"~\*\?\\:])/g, '\\$1')
								.replace(/(&&|\|\|)/g,'\\$1') +
						'" ';
				result += p;
			}
		}
		
		if (result.length > 0) {
			result = '(' + result + ')';
		}
		
		return result;		
	}
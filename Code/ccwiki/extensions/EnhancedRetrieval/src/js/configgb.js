  function request(paras)
    { 
        var url = location.href; 
        var paraString = url.substring(url.indexOf("?")+1,url.length).split("&"); 
        var paraObj = {} ;
        for (i=0; j=paraString[i]; i++){ 
        paraObj[j.substring(0,j.indexOf("=")).toLowerCase()] = j.substring(j.indexOf("=")+1,j.length); 
        } 
        var returnValue = paraObj[paras.toLowerCase()]; 
        if(typeof(returnValue)=="undefined"){ 
        return ""; 
        }else{ 
        return returnValue; 
        } 
    }

$(document).ready(function(){
tmpfrom="";
tmpto="";
 tmpfrom=request("from1");
 tmpto=request("to1");
if(tmpfrom!=""&&tmpto!=""){
var showfrom=tmpfrom.substring(4,6)+"/"+tmpfrom.substring(6,8)+"/"+tmpfrom.substring(0,4)
var showto=tmpto.substring(4,6)+"/"+tmpto.substring(6,8)+"/"+tmpto.substring(0,4)

$("#f_rangeEnd").val(showto);

$("#f_rangeStart").val(showfrom);

}
GolbalState=1;
var pagename=request("pagename");
var city1=request("city1");
var source1=request("source1");
if(city1=="")
{
$("#cityname option:selected").text("==select city==");
}
else{
$("#cityname option:selected").text(decodeURI(city1));

}

source1=decodeURI(source1);
if(source1=="美团"){
$("#meituan").attr("checked",true);


}
if(source1=="拉手"){
$("#lashou").attr("checked",true);


}
if(source1=="窝窝团"){
$("#wowotuan").attr("checked",true);


}
if(source1=="满座"){
$("#manzuo").attr("checked",true);


}

if(source1=="糯米"){
$("#nuomi").attr("checked",true);


}

$("#pagename").val(pagename);

//alert($("xfsResultTitle").text());

$("xfsResultTitle").each(function(){

//alert($(this).html());
/*
var s=$(this).children("td").eq(0).text();
if(s!=""){
var tmp="<input id=\"abc\" type=\"checkbox\"  name=\"checkbox\" value=\""+s+"\"/>选择";
//alert(tmp);

$(this).append(tmp);


} */

}); 




 
	
	
$(".flip").click(function(){
    $(".panel").slideToggle("slow");
  });
  
  
     $("#addtuangou").click(function(){

		// alert("aa");

var abc=""; 

 

 var t = $(":checkbox:checked").each(function(){
 //alert($(this).val());
      abc = abc+"OR [["+$(this).val()+"]] ";
      
 });
//$("#query").val(abc);
abc=abc.substring(3);
//alert(abc);
if(abc==""){
alert("您什么都没选");

return;
}

//alert($("#pagename").val());
  //alert("其设置成功");
	  //alert("您什么都没选");
	  website="../index.php/Special:ConfigureGB?state=1&DealAskWhere="+abc+"&pagename="+$("#pagename").val()+"";
	  location=href=website;
   
	 
    })
	
	
	

  
  
  
     $("#btn1").click(function(){
	//alert($("xfsResultTitle").text());
    // alert("$("#docs").html());
  // $("#pagename").val($("#docs").html());
  // alert($("#field_categories").html());
    $("[name=\'checkbox\']").attr("checked",\'true\');//全选
  
    })
	   $("#btn2").click(function(){
     
    $("[name=\'checkbox\']").removeAttr("checked");//取消全选
  
    })
	
	
	 $("#meituan").click(function(){
        $("#lashou").attr("checked",false);
       $("#wowotuan").attr("checked",false);
	   $("#manzuo").attr("checked",false);
		

         $("#nuomi").attr("checked",false);
		//$("#meituan").attr("checked",false);
   
  changeall();
  
    })
	
		 $("#manzuo").click(function(){
		 $("#lashou").attr("checked",false);
       $("#wowotuan").attr("checked",false);
	  // $("#manzuo").attr("checked",false);
		

         $("#nuomi").attr("checked",false);
		$("#meituan").attr("checked",false);
		   changeall();
	
		 
		 
		 
		 })
		 		 $("#wowotuan").click(function(){
		 $("#lashou").attr("checked",false);
      // $("#wowotuan").attr("checked",false);
	   $("#manzuo").attr("checked",false);
		

         $("#nuomi").attr("checked",false);
		$("#meituan").attr("checked",false);
		   changeall();

		 
		 
		 
		 })
		 	 $("#nuomi").click(function(){
	    $("#lashou").attr("checked",false);
        $("#wowotuan").attr("checked",false);
	    $("#manzuo").attr("checked",false);
		

        // $("#nuomi").attr("checked",false);
		$("#meituan").attr("checked",false);
		   changeall();
		 
		 
		 })
		
	 $("#lashou").click(function(){
     //$("#lashou").attr("checked",false);
       $("#wowotuan").attr("checked",false);
	   $("#manzuo").attr("checked",false);
		

         $("#nuomi").attr("checked",false);
		$("#meituan").attr("checked",false);
  
	  
  changeall();
  
  
    })
	
	
	
	
	
	//筛选
	
		 
		
});


function changeall(){
var city="";



 city=$("#cityname option:selected").text();
 if(city=="==select city=="){
 city="";
 }

var source="";
;
 if($("#meituan").attr("checked")==true)
	  {
	source="美团";
	  }
    else if($("#lashou").attr("checked")==true)
	  {
	source="拉手";
	  }
	  else if($("#wowotuan").attr("checked")==true)
	  {
	source="窝窝团";
	  }
	   else if($("#manzuo").attr("checked")==true)
	  {
	source="满座";
	  }
	    else if($("#nuomi").attr("checked")==true)
	  {
	source="糯米";
	  }

	  if(tmpfrom!=""&&tmpto!="")
	  {
	  //alert("aa");
	  if(city==""&&source=="")
{

 var ddurl="../index.php/Special:ConfigureGB?from="+tmpfrom+"&to="+tmpto+"&pagename="+$("#pagename").val();
 	

	 
location.href=ddurl;
}

if((city!="")&&(source==""))
{  var ddurl="../index.php/Special:ConfigureGB?from="+tmpfrom+"&to="+tmpto+"&city="+city+"&pagename="+$("#pagename").val();
 	

	 
location.href=ddurl;
}

if(city==""&&source!="")
{ddurl="../index.php/Special:ConfigureGB?from="+tmpfrom+"&to="+tmpto+"&source="+source+"&pagename="+$("#pagename").val()+"";
location.href=ddurl;
}
 if(city!=""&&source!="")
{
ddurl="../index.php/Special:ConfigureGB?from="+tmpfrom+"&to="+tmpto+"&city="+city+"&source="+source+"&pagename="+$("#pagename").val()+"";
location.href=ddurl;
}
	  
	  
	  
	  
	  }
	  else {
	  
	  
	  
if(city==""&&source=="")
{
}

if((city!="")&&(source==""))
{  var ddurl="../index.php/Special:ConfigureGB?city="+city+"&pagename="+$("#pagename").val();
 	

	 
location.href=ddurl;
}

if(city==""&&source!="")
{ddurl="../index.php/Special:ConfigureGB?source="+source+"&pagename="+$("#pagename").val()+"";
location.href=ddurl;
}
 if(city!=""&&source!="")
{
ddurl="../index.php/Special:ConfigureGB?city="+city+"&source="+source+"&pagename="+$("#pagename").val()+"";
location.href=ddurl;
}
	}
}
  
  
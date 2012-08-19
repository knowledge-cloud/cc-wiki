function addtuangou(){
	 
		 var abc=""; 


 

   if(GolbalArray.length==0){
   alert("您什么都没选");
   return ;}
  
    for(var i=0;i<GolbalArray.length;i++)
    {
       abc = abc+"OR [["+GolbalArray[i]+"]] ";
	  
    }
abc=abc.substring(3);

  window.open(mediaWiki.config.values.wgServer+mediaWiki.config.values.wgScriptPath+"/extensions/mashupwiki/Interface/CreatePage1.php?DealAskWhere="+abc);


      /*  created=$.dialog({
                id: 'CreatePage',
                title:"创建新页面",
               content: 'url:'+mediaWiki.config.values.wgServer+mediaWiki.config.values.wgScriptPath+'/extensions/mashupwiki/Interface/CreatePage1.php?DealAskWhere='+abc,
                lock:true,
                min:false,
                max:false,
                padding:'2px 1px 25px 10x',
                background:'#ccc'
            });
*/

}

/*
  
	       $(".createvote").bind("click",function(){
		 
		 
		 var abc=""; 


 

   if(GolbalArray.length==0){
   alert("您什么都没选");
   return ;}
  
    for(var i=0;i<GolbalArray.length;i++)
    {
       abc = abc+"OR [["+GolbalArray[i]+"]] ";
	  
    }
abc=abc.substring(3);



            created=$.dialog({
                id: 'CreatePage',
                title:"创建新页面",
               content: 'url:'+mediaWiki.config.values.wgServer+mediaWiki.config.values.wgScriptPath+'/extensions/mashupwiki/Interface/CreatePage1.php?DealAskWhere='+abc,
                lock:true,
                min:false,
                max:false,
                padding:'2px 1px 25px 10x',
                background:'#ccc'
            });
        });
   
	
	  
*/
	

  
  
  
 function chooseall(){

    $("[name='checkbox']").attr("checked",'true');//全选
//	GolbalArray=GolbalGoupbuy;

GolbalArray = GolbalGoupbuy.concat();
    }
 function notchooseall(){
     
    $("[name='checkbox']").removeAttr("checked");//取消全选
  GolbalArray.splice(0,GolbalArray.length);
	
  
    }
  Array.prototype.search=function(reg){
	var ta=this.slice(0),d="\0",str=d+ta.join(d)+d,regstr=reg.toString();
	reg=new RegExp(regstr.replace(/\/((.|\n)+)\/.*/g,"\0$1\0"),regstr.slice(regstr.lastIndexOf("/")+1));
	t=str.search(reg);if(t==-1)return -1;return str.slice(0,t).replace(/[^\0]/g,"").length;
}
//在数组中获取指定值的元素索引
Array.prototype.getIndexByValue= function(value)
{
    var index = -1;
    for (var i = 0; i < this.length; i++)
    {
        if (this[i] == value)
        {
            index = i;
            break;
        }
    }
    return index;
}
Array.prototype.remove=function(dx)
{
    if(isNaN(dx)||dx>this.length){return false;}
    for(var i=0,n=0;i<this.length;i++)
    {
        if(this[i]!=this[dx])
        {
            this[n++]=this[i]
        }
    }
    this.length-=1
}
   GolbalArray=new Array();
  function savechoosen(textid){

   if(GolbalArray.search("/"+textid.value+"/")!=-1){
   
GolbalArray.remove(GolbalArray.getIndexByValue(textid.value));
 //  alert("删掉");
  }
  else {
  
    GolbalArray.push(textid.value);
	 //alert("新的");
	}
 

  
  
  
  
  
  }
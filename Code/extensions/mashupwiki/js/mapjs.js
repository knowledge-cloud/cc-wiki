/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
var smap; //小地图
var map; //大地图
var smakers;
var makers
function InitS(){
    smap = new BMap.Map("container");
    if(sPoints!=null){
    smakers=new Array(sPoints.length);
    makers=new Array(sPoints.length) 
    smap.addEventListener("load", function(){  
        if($.isArray(sPoints) && sPoints.length>0){
            biaddMarkers(sPoints,smap);
            }
     });
    }
    if(mapcity=="")mapcity="杭州";
    smap.centerAndZoom(mapcity, 12);
   
    smap.enableScrollWheelZoom();
    
}
/*
function InitB(){
    map = new BMap.Map("buscontainer");
    if(sPoints!=null){
    map.addEventListener("load", function(){  
        if($.isArray(sPoints) && sPoints.length>0){
            biaddMarkers(sPoints,map);
            }
     });
    }
    if(mapcity=="")mapcity="杭州";
    map.centerAndZoom(mapcity, 12);
    map.enableScrollWheelZoom();
}
*/
var opts = {  //点击标注坐标弹出信息的配置
  width : 3,     // 信息窗口宽度
  height: 3,     // 信息窗口高度
  title : ""  // 信息窗口标题
}
function search(start,end,route){ 
    var transit = new BMap.TransitRoute(mapcity,{
        renderOptions: {map: map,panel:"results"}, 
        policy: route,	 
        onSearchComplete: function(result) {
            if (transit.getStatus()!=BMAP_STATUS_SUCCESS) {
                $("#results").html("没有找到指定的公交导航路线");
            }
        }			
    });
    transit.search(start,end);
    transit.getResults();
}
function biaddMarker(varmap,point,showinfo){  //标注的函数
    var marker = new BMap.Marker(point);
    varmap.addOverlay(marker);
    var infoWindow = new BMap.InfoWindow(showinfo, opts);  // 创建信息窗口对象

    marker.addEventListener("click", function(){          
        this.openInfoWindow(infoWindow);  
    });
    return marker;
}
function biaddMarkers(points,varmap){
    if(varmap==smap)
        mmmaker=smakers;
    else
        mmmaker=makers;
    for (var i = 0; i < points.length; i ++) {  
        if(points[i][1]!=-1 && points[i][0]!=-1)
            mmmaker[i]= biaddMarker(varmap,new BMap.Point(points[i][1],points[i][0]),points[i][2]);
    }
}
$("#findway").live('click',function(){
    var start = $("#from").val() ,end = $("#to").val() ,routePolicy = [BMAP_TRANSIT_POLICY_LEAST_TIME,BMAP_TRANSIT_POLICY_LEAST_TRANSFER,BMAP_TRANSIT_POLICY_LEAST_WALKING];
    var arrInput = document.getElementById("dvPolicy").getElementsByTagName("input");
    search(start,end,routePolicy[0]);
    $("#dvPolicy").live('click',function(e){   
        e = e || window.event;
        var elem = e.srcElement || e.target , policyIndex;       
        if(elem.tagName.toLowerCase() == "input"){
            policyIndex = elem.getAttribute("id").replace("policy","");             
            map.clearOverlays();
            search(start,end,routePolicy[policyIndex]);             
        }
    }
    );
});

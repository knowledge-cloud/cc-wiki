<?php
/*
 * Copyright (C) Vulcan Inc.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program.If not, see <http://www.gnu.org/licenses/>.
 *
 */

/**
 * @file
 * @ingroup FS_Special
 *
 * A special page for doing a faceted search on the semantic data in the wiki.
 *
 * @author Thomas Schweitzer
 */

if (!defined('MEDIAWIKI')) die();


global $IP;
require_once( $IP . "/includes/SpecialPage.php" );
$dir = dirname(__FILE__).'/';
require_once( $dir."../includes/FacetedSearch/FS_Settings.php" );


/*
 * Standard class that is responsible for the creation of the Special Page
 */
class FSFacetedSearchSpecial extends SpecialPage {
	
	//--- Constants ---
	
	const SPECIAL_PAGE_HTML = '
    <link href="../extensions/EnhancedRetrieval/src/css/common.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../extensions/EnhancedRetrieval/src/css/style.css" type="text/css" />
<link rel="stylesheet" href="../extensions/EnhancedRetrieval/src/css/widget.css" type="text/css" />
<script type="text/javascript" src="../extensions/EnhancedRetrieval/src/js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="../extensions/EnhancedRetrieval/src/js/basic.js"></script>
<script type="text/javascript" src="../extensions/EnhancedRetrieval/src/js/widget.js"></script>
 <link type="text/css" rel="stylesheet" href="../extensions/EnhancedRetrieval/src/css/jscal2.css" />
    <link type="text/css" rel="stylesheet" href="../extensions/EnhancedRetrieval/src/css/border-radius.css" />
    <!-- <link type="text/css" rel="stylesheet" href="../extensions/EnhancedRetrieval/src/css/reduce-spacing.css" /> -->

    <link id="skin-win2k" title="Win 2K" type="text/css" rel="alternate stylesheet" href="../extensions/EnhancedRetrieval/src/css/win2k/win2k.css" />
    <link id="skin-steel" title="Steel" type="text/css" rel="alternate stylesheet" href="../extensions/EnhancedRetrieval/src/css/steel/steel.css" />
    <link id="skin-gold" title="Gold" type="text/css" rel="alternate stylesheet" href="../extensions/EnhancedRetrieval/src/css/gold/gold.css" />
    <link id="skin-matrix" title="Matrix" type="text/css" rel="alternate stylesheet" href="../extensions/EnhancedRetrieval/src/css/matrix/matrix.css" />

    <link id="skinhelper-compact" type="text/css" rel="alternate stylesheet" href="../extensions/EnhancedRetrieval/src/css/reduce-spacing.css" />

    <script src="../extensions/EnhancedRetrieval/src/js/jscal2.js"></script>
    <script src="../extensions/EnhancedRetrieval/src/js/unicode-letter.js"></script>


    <script src="../extensions/EnhancedRetrieval/src/js/lang/cn.js"></script>

    <script src="../extensions/EnhancedRetrieval/src/js/lang/en.js"></script>

	<script type="text/javascript" src="../extensions/EnhancedRetrieval/src/jquery-1.3.2.min.js"></script>
<script type="text/javascript">
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
	allcityname="";
tmpfrom="";
tmpto="";
 allcityname=decodeURI(request("city1"));
 tmpfrom=request("from1");
 tmpto=request("to1");

 
if(tmpfrom!=""&&tmpto!=""){

var showfrom=tmpfrom.substring(4,6)+"/"+tmpfrom.substring(6,8)+"/"+tmpfrom.substring(0,4);
var showto=tmpto.substring(4,6)+"/"+tmpto.substring(6,8)+"/"+tmpto.substring(0,4);

$("#f_rangeEnd").val(showto);

$("#f_rangeStart").val(showfrom);

}
	
	
	
	});

$(function(){
	
	$(".sxli li em").toggle(function(){
	$(this).children("img").attr("src","../extensions/EnhancedRetrieval/src/images/array03.png");	
	},function(){
		$(this).children("img").attr("src","../extensions/EnhancedRetrieval/src/images/array02.png");
	});
	$(".sxli li em").click(function(){
		$(this).parent().next("div").toggle();
	});
	
	
	
	$(".show").toggle(function(){
		$(this).val("隐藏属性");
	},function(){
		$(this).val("显示属性");
	});
	$(".show").click(function(){
		$(this).next(".sxtable").toggle();
	});
	$(".maps .hd").click(function(){
		$(this).next(".bd").slideToggle();
	});
	
});

function changeall(){
if(allcityname!="")
{
 var pageid=request("page");
	
	  if(pageid==6){
	   website="../index.php/Special:Search?from1="+tmpfrom+"&to1="+tmpto+"&city1="+allcityname+"&flag1=1&page=6&fssearch=q=smwh_search_field:&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.field=smwh_Ontology_0/city_xsdvalue_t&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:RestaurantData&fq=smwh_attributes:smwh_Ontology_0/city_xsdvalue_t&fq=smwh_Ontology_0/city_xsdvalue_s:"+allcityname+"&fq=smwh_attributes:smwh_Ontology_0/timestamp_xsdvalue_dt&fq=smwh_Ontology_0/timestamp_datevalue_l:["+tmpfrom+" TO "+tmpto+"]&pagename="+$("#pagename").val()+"";
	  
	   }
	   else if(pageid==7){
	   website="../index.php/Special:Search?from1="+tmpfrom+"&to1="+tmpto+"&city1="+allcityname+"&flag1=1&page=7&fssearch=q=smwh_search_field:&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.field=smwh_Ontology_0/city_xsdvalue_t&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:ShoppingData&fq=smwh_attributes:smwh_Ontology_0/city_xsdvalue_t&fq=smwh_Ontology_0/city_xsdvalue_s:"+allcityname+"&fq=smwh_attributes:smwh_Ontology_0/timestamp_xsdvalue_dt&fq=smwh_Ontology_0/timestamp_datevalue_l:["+tmpfrom+" TO "+tmpto+"]&pagename="+$("#pagename").val()+"";
	  
	   }
	      else if(pageid==8){
	 
		website="../index.php/Special:Search?from1="+tmpfrom+"&to1="+tmpto+"&city1="+allcityname+"&flag1=1&page=8&fssearch=q=smwh_search_field:&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.field=smwh_Ontology_0/city_xsdvalue_t&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:TravellingData&fq=smwh_attributes:smwh_Ontology_0/city_xsdvalue_t&fq=smwh_Ontology_0/city_xsdvalue_s:"+allcityname+"&fq=smwh_attributes:smwh_Ontology_0/timestamp_xsdvalue_dt&fq=smwh_Ontology_0/timestamp_datevalue_l:["+tmpfrom+" TO "+tmpto+"]";
	
	   }
	      else if(pageid==0){
	   website="../index.php/Special:Search?from1="+tmpfrom+"&to1="+tmpto+"&city1="+allcityname+"&flag1=1&page=0&fssearch=q=smwh_search_field:&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.field=smwh_Ontology_0/city_xsdvalue_t&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:UserPageData&fq=smwh_attributes:smwh_Ontology_0/city_xsdvalue_t&fq=smwh_Ontology_0/city_xsdvalue_s:"+allcityname+"&fq=smwh_attributes:smwh_Ontology_0/timestamp_xsdvalue_dt&fq=smwh_Ontology_0/timestamp_datevalue_l:["+tmpfrom+" TO "+tmpto+"]&pagename="+$("#pagename").val()+"";
	  
	   }
	      else if(pageid==1){
	   website="../index.php/Special:Search?from1="+tmpfrom+"&to1="+tmpto+"&city1="+allcityname+"&flag1=1&page=1&fssearch=q=smwh_search_field:&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.field=smwh_Ontology_0/city_xsdvalue_t&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:CosmetologyData&fq=smwh_attributes:smwh_Ontology_0/city_xsdvalue_t&fq=smwh_Ontology_0/city_xsdvalue_s:"+allcityname+"&fq=smwh_attributes:smwh_Ontology_0/timestamp_xsdvalue_dt&fq=smwh_Ontology_0/timestamp_datevalue_l:["+tmpfrom+" TO "+tmpto+"]&pagename="+$("#pagename").val()+"";
	  
	   }
	    else if(pageid==2){
	   website="../index.php/Special:Search?from1="+tmpfrom+"&to1="+tmpto+"&city1="+allcityname+"&flag1=1&page=2&fssearch=q=smwh_search_field:&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.field=smwh_Ontology_0/city_xsdvalue_t&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:EntertainmentData&fq=smwh_attributes:smwh_Ontology_0/city_xsdvalue_t&fq=smwh_Ontology_0/city_xsdvalue_s:"+allcityname+"&fq=smwh_attributes:smwh_Ontology_0/timestamp_xsdvalue_dt&fq=smwh_Ontology_0/timestamp_datevalue_l:["+tmpfrom+" TO "+tmpto+"]&pagename="+$("#pagename").val()+"";
	  
	   }
	    else if(pageid==3){
	   website="../index.php/Special:Search?from1="+tmpfrom+"&to1="+tmpto+"&city1="+allcityname+"&flag1=1&page=3&fssearch=q=smwh_search_field:&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.field=smwh_Ontology_0/city_xsdvalue_t&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:HealthCareData&fq=smwh_attributes:smwh_Ontology_0/city_xsdvalue_t&fq=smwh_Ontology_0/city_xsdvalue_s:"+allcityname+"&fq=smwh_attributes:smwh_Ontology_0/timestamp_xsdvalue_dt&fq=smwh_Ontology_0/timestamp_datevalue_l:["+tmpfrom+" TO "+tmpto+"]&pagename="+$("#pagename").val()+"";
	  
	   }
        else if(pageid==4){
	   website="../index.php/Special:Search?from1="+tmpfrom+"&to1="+tmpto+"&city1="+allcityname+"&flag1=1&page=4&fssearch=q=smwh_search_field:&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.field=smwh_Ontology_0/city_xsdvalue_t&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:LifeServiceData&fq=smwh_attributes:smwh_Ontology_0/city_xsdvalue_t&fq=smwh_Ontology_0/city_xsdvalue_s:"+allcityname+"&fq=smwh_attributes:smwh_Ontology_0/timestamp_xsdvalue_dt&fq=smwh_Ontology_0/timestamp_datevalue_l:["+tmpfrom+" TO "+tmpto+"]&pagename="+$("#pagename").val()+"";
	  
	   }
	     else if(pageid==5){
	   website="../index.php/Special:Search?from1="+tmpfrom+"&to1="+tmpto+"&city1="+allcityname+"&flag1=1&page=5&fssearch=q=smwh_search_field:&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.field=smwh_Ontology_0/city_xsdvalue_t&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:MoviesData&fq=smwh_attributes:smwh_Ontology_0/city_xsdvalue_t&fq=smwh_Ontology_0/city_xsdvalue_s:"+allcityname+"&fq=smwh_attributes:smwh_Ontology_0/timestamp_xsdvalue_dt&fq=smwh_Ontology_0/timestamp_datevalue_l:["+tmpfrom+" TO "+tmpto+"]&pagename="+$("#pagename").val()+"";
	  
	   }
location.href=website;







}

else {
var pageid=request("page");
	
	  if(pageid==6){
	   website="../index.php/Special:Search?from1="+tmpfrom+"&to1="+tmpto+"&flag1=1&page=6&fssearch=q=smwh_search_field:&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.field=smwh_Ontology_0/city_xsdvalue_t&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:RestaurantData&fq=smwh_attributes:smwh_Ontology_0/timestamp_xsdvalue_dt&fq=smwh_Ontology_0/timestamp_datevalue_l:["+tmpfrom+" TO "+tmpto+"]&pagename="+$("#pagename").val()+"";
	  
	   }
	   else if(pageid==7){
	   website="../index.php/Special:Search?from1="+tmpfrom+"&to1="+tmpto+"&flag1=1&page=7&fssearch=q=smwh_search_field:&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.field=smwh_Ontology_0/city_xsdvalue_t&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:ShoppingData&fq=smwh_attributes:smwh_Ontology_0/timestamp_xsdvalue_dt&fq=smwh_Ontology_0/timestamp_datevalue_l:["+tmpfrom+" TO "+tmpto+"]&pagename="+$("#pagename").val()+"";
	  
	   }
	      else if(pageid==8){
	 
		website="../index.php/Special:Search?from1="+tmpfrom+"&to1="+tmpto+"&flag1=1&page=8&fssearch=q=smwh_search_field:&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.field=smwh_Ontology_0/city_xsdvalue_t&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:TravellingData&fq=smwh_attributes:smwh_Ontology_0/timestamp_xsdvalue_dt&fq=smwh_Ontology_0/timestamp_datevalue_l:["+tmpfrom+" TO "+tmpto+"]";
	
	   }
	      else if(pageid==0){
	   website="../index.php/Special:Search?from1="+tmpfrom+"&to1="+tmpto+"&flag1=1&page=0&fssearch=q=smwh_search_field:&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.field=smwh_Ontology_0/city_xsdvalue_t&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:UserPageData&fq=smwh_attributes:smwh_Ontology_0/timestamp_xsdvalue_dt&fq=smwh_Ontology_0/timestamp_datevalue_l:["+tmpfrom+" TO "+tmpto+"]&pagename="+$("#pagename").val()+"";
	  
	   }
	      else if(pageid==1){
	   website="../index.php/Special:Search?from1="+tmpfrom+"&to1="+tmpto+"&flag1=1&page=1&fssearch=q=smwh_search_field:&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.field=smwh_Ontology_0/city_xsdvalue_t&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:CosmetologyData&fq=smwh_attributes:smwh_Ontology_0/timestamp_xsdvalue_dt&fq=smwh_Ontology_0/timestamp_datevalue_l:["+tmpfrom+" TO "+tmpto+"]&pagename="+$("#pagename").val()+"";
	  
	   }
	    else if(pageid==2){
	   website="../index.php/Special:Search?from1="+tmpfrom+"&to1="+tmpto+"&flag1=1&page=2&fssearch=q=smwh_search_field:&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.field=smwh_Ontology_0/city_xsdvalue_t&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:EntertainmentData&fq=smwh_attributes:smwh_Ontology_0/timestamp_xsdvalue_dt&fq=smwh_Ontology_0/timestamp_datevalue_l:["+tmpfrom+" TO "+tmpto+"]&pagename="+$("#pagename").val()+"";
	  
	   }
	    else if(pageid==3){
	   website="../index.php/Special:Search?from1="+tmpfrom+"&to1="+tmpto+"&flag1=1&page=3&fssearch=q=smwh_search_field:&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.field=smwh_Ontology_0/city_xsdvalue_t&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:HealthCareData&fq=smwh_attributes:smwh_Ontology_0/timestamp_xsdvalue_dt&fq=smwh_Ontology_0/timestamp_datevalue_l:["+tmpfrom+" TO "+tmpto+"]&pagename="+$("#pagename").val()+"";
	  
	   }
        else if(pageid==4){
	   website="../index.php/Special:Search?from1="+tmpfrom+"&to1="+tmpto+"&flag1=1&page=4&fssearch=q=smwh_search_field:&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.field=smwh_Ontology_0/city_xsdvalue_t&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:LifeServiceData&fq=smwh_attributes:smwh_Ontology_0/timestamp_xsdvalue_dt&fq=smwh_Ontology_0/timestamp_datevalue_l:["+tmpfrom+" TO "+tmpto+"]&pagename="+$("#pagename").val()+"";
	  
	   }
	     else if(pageid==5){
	   website="../index.php/Special:Search?from1="+tmpfrom+"&to1="+tmpto+"&flag1=1&page=5&fssearch=q=smwh_search_field:&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.field=smwh_Ontology_0/city_xsdvalue_t&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:MoviesData&fq=smwh_attributes:smwh_Ontology_0/timestamp_xsdvalue_dt&fq=smwh_Ontology_0/timestamp_datevalue_l:["+tmpfrom+" TO "+tmpto+"]&pagename="+$("#pagename").val()+"";
	  
	   }
location.href=website;



}



}
</script>

<div class="fll">
<div class="facets">
  <div class="hd">已选择的种类：</div>

<div id="selection">


			
			
			
			</div>





<div class="hd">
  <p>可选择的种类：</p></div>
<div class="bdd">
<div class="cuti">种类</div>
	<div id="field_categories">
	
	

			</div>


<div class="cuti">属性</div>
	<div id="field_properties">
			</div>

</div>


</div>
</div>

<div class="frr">
<div class="frtitle">
	<h2>购物全部页面</h2>
	
</div>
<div class="searchbox">
<div class="searchbox2">

<div class="maps">
<div class="hd">地图查询</div>
<div class="bd"> <center><div style="width:520px;height:340px;border:1px solid gray" id="container"></center></div></div>

</div>
 <br>
              起始时间:
          
        
                <input id="f_rangeStart" />
                <button id="f_rangeStart_trigger">...</button>
                <button id="f_clearRangeStart" onclick="clearRangeStart()">清除</button>
                <script type="text/javascript">
                  RANGE_CAL_1 = new Calendar({
                          inputField: "f_rangeStart",
                          dateFormat: "%B %d, %Y",
                          trigger: "f_rangeStart_trigger",
                          bottomBar: false,
                          onSelect: function() {
                                  var date = Calendar.intToDate(this.selection.get());
								   var date = Calendar.intToDate(this.selection.get());
							
								tmpfrom=this.selection.print("%Y%m%d");
								tmpfrom=tmpfrom+"000000"
									if(tmpto!="")
								  {changeall();
								  }
                                  LEFT_CAL.args.min = date;
                                  LEFT_CAL.redraw();
                                  this.hide();
                          }
                  });
                  function clearRangeStart() {
                          document.getElementById("f_rangeStart").value = "";
                          LEFT_CAL.args.min = null;
                          LEFT_CAL.redraw();
                  };
                </script>
             <br>
           截止时间:
             
                <input id="f_rangeEnd" />
                <button id="f_rangeEnd_trigger">...</button>
                <button id="f_clearRangeEnd" onclick="clearRangeEnd()">清除</button>
                <script type="text/javascript">
                  RANGE_CAL_2 = new Calendar({
                          inputField: "f_rangeEnd",
                          dateFormat: "%Y%m%d",
                          trigger: "f_rangeEnd_trigger",
                          bottomBar: false,
                          onSelect: function() {
                                  var date = Calendar.intToDate(this.selection.get());
								tmpto=this.selection.print("%Y%m%d");
								tmpto=tmpto+"245959"
								if(tmpfrom!="")
								  {changeall();
								  }
                                  LEFT_CAL.args.max = date;
								     
                                  LEFT_CAL.redraw();
								
                                  this.hide();
                          }
                  });
				  
				  
                  function clearRangeEnd() {
                          document.getElementById("f_rangeEnd").value = "";
                          LEFT_CAL.args.max = null;
                          LEFT_CAL.redraw();
                  };
                </script>























<div class="searchb">

  	<input type="text" id="query" name="query"  class="slk" /><input type="image" id="search_button" name="search" src="../extensions/EnhancedRetrieval/src/images/search_btn03.gif" />
 <div class="fr lh30">
  	排序方式： <select id="search_order" name="search_order" size="1">
  	   					
  	  <option value="relevance">Relevance</option>
  	   					
  	  <option value="newest" selected="selected">Latest article first</option>
  	   					
  	  <option value="oldest">Oldest article first</option>
  	   					
  	  <option value="ascending">Title ascending</option>
  	   					
  	  <option value="descending">Title descending</option>
  	   				
  	</select>
  </div>
</div>




	
				<script type="text/javascript" src="http://api.map.baidu.com/api?v=1.3"></script>
	
<script type="text/javascript">
GolbalState=0;



	
	
   function G(id) {
    return document.getElementById(id);
}

var map = new BMap.Map("container");
var point = new BMap.Point(116.3964,39.9093);
map.centerAndZoom(point,13);
map.enableScrollWheelZoom();

var ac = new BMap.Autocomplete(    //建立一个自动完成的对象
    {"input" : "query"
    ,"location" : map
});


var myValue;
ac.addEventListener("onconfirm", function(e) {    //鼠标点击下拉列表后的事件
var _value = e.item.value;
    myValue = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;

    //alert(myValue);
    setPlace();
});

function setPlace(){
    map.clearOverlays();    //清除地图上所有覆盖物
    function myFun(){
        var pp = local.getResults().getPoi(0).point;    //获取第一个智能搜索的结果
        map.centerAndZoom(pp, 18);
        map.addOverlay(new BMap.Marker(pp));    //添加标注
    }
    var local = new BMap.LocalSearch(map, { //智能搜索
      onSearchComplete: myFun
    });
    local.search(myValue);
}



var gc = new BMap.Geocoder();    

map.addEventListener("click", function(e){        
    var pt = e.point;
    gc.getLocation(pt, function(rs){
	map.clearOverlays();
        var addComp = rs.addressComponents;
		  var circle = new BMap.Circle(pt,1000,{fillColor:"blue", strokeWeight: 1 ,fillOpacity: 0.3, strokeOpacity: 0.3});
    map.addOverlay(circle);
	
		
		var cityname=addComp.city;
		//alert(cityname);
		var cityname=addComp.city.substring(0,cityname.length-1);
		//alert(cityname.length);
		alert("您选择的地点为:"+cityname);
		allcityname=cityname;
       // alert(addComp.province + ", " + addComp.city + ", " + addComp.district + ", " + addComp.street + ", " + addComp.streetNumber);
	   var pageid=request("page");
	 if(tmpfrom!=""&&tmpto!=""){
	 	  if(pageid==6){
	   website="../index.php/Special:Search?from1="+tmpfrom+"&to1="+tmpto+"&city1="+allcityname+"&flag1=1&page=6&fssearch=q=smwh_search_field:&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.field=smwh_Ontology_0/city_xsdvalue_t&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:RestaurantData&fq=smwh_attributes:smwh_Ontology_0/city_xsdvalue_t&fq=smwh_Ontology_0/city_xsdvalue_s:"+allcityname+"&fq=smwh_attributes:smwh_Ontology_0/timestamp_xsdvalue_dt&fq=smwh_Ontology_0/timestamp_datevalue_l:["+tmpfrom+" TO "+tmpto+"]&pagename="+$("#pagename").val()+"";
	  
	   }
	   else if(pageid==7){
	   website="../index.php/Special:Search?from1="+tmpfrom+"&to1="+tmpto+"&city1="+allcityname+"&flag1=1&page=7&fssearch=q=smwh_search_field:&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.field=smwh_Ontology_0/city_xsdvalue_t&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:ShoppingData&fq=smwh_attributes:smwh_Ontology_0/city_xsdvalue_t&fq=smwh_Ontology_0/city_xsdvalue_s:"+allcityname+"&fq=smwh_attributes:smwh_Ontology_0/timestamp_xsdvalue_dt&fq=smwh_Ontology_0/timestamp_datevalue_l:["+tmpfrom+" TO "+tmpto+"]&pagename="+$("#pagename").val()+"";
	  
	   }
	      else if(pageid==8){
	 
		website="../index.php/Special:Search?from1="+tmpfrom+"&to1="+tmpto+"&city1="+allcityname+"&flag1=1&page=8&fssearch=q=smwh_search_field:&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.field=smwh_Ontology_0/city_xsdvalue_t&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:TravellingData&fq=smwh_attributes:smwh_Ontology_0/city_xsdvalue_t&fq=smwh_Ontology_0/city_xsdvalue_s:"+allcityname+"&fq=smwh_attributes:smwh_Ontology_0/timestamp_xsdvalue_dt&fq=smwh_Ontology_0/timestamp_datevalue_l:["+tmpfrom+" TO "+tmpto+"]";
	
	   }
	      else if(pageid==0){
	   website="../index.php/Special:Search?from1="+tmpfrom+"&to1="+tmpto+"&city1="+allcityname+"&flag1=1&page=0&fssearch=q=smwh_search_field:&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.field=smwh_Ontology_0/city_xsdvalue_t&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:UserPageData&fq=smwh_attributes:smwh_Ontology_0/city_xsdvalue_t&fq=smwh_Ontology_0/city_xsdvalue_s:"+allcityname+"&fq=smwh_attributes:smwh_Ontology_0/timestamp_xsdvalue_dt&fq=smwh_Ontology_0/timestamp_datevalue_l:["+tmpfrom+" TO "+tmpto+"]&pagename="+$("#pagename").val()+"";
	  
	   }
	      else if(pageid==1){
	   website="../index.php/Special:Search?from1="+tmpfrom+"&to1="+tmpto+"&city1="+allcityname+"&flag1=1&page=1&fssearch=q=smwh_search_field:&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.field=smwh_Ontology_0/city_xsdvalue_t&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:CosmetologyData&fq=smwh_attributes:smwh_Ontology_0/city_xsdvalue_t&fq=smwh_Ontology_0/city_xsdvalue_s:"+allcityname+"&fq=smwh_attributes:smwh_Ontology_0/timestamp_xsdvalue_dt&fq=smwh_Ontology_0/timestamp_datevalue_l:["+tmpfrom+" TO "+tmpto+"]&pagename="+$("#pagename").val()+"";
	  
	   }
	    else if(pageid==2){
	   website="../index.php/Special:Search?from1="+tmpfrom+"&to1="+tmpto+"&city1="+allcityname+"&flag1=1&page=2&fssearch=q=smwh_search_field:&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.field=smwh_Ontology_0/city_xsdvalue_t&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:EntertainmentData&fq=smwh_attributes:smwh_Ontology_0/city_xsdvalue_t&fq=smwh_Ontology_0/city_xsdvalue_s:"+allcityname+"&fq=smwh_attributes:smwh_Ontology_0/timestamp_xsdvalue_dt&fq=smwh_Ontology_0/timestamp_datevalue_l:["+tmpfrom+" TO "+tmpto+"]&pagename="+$("#pagename").val()+"";
	  
	   }
	    else if(pageid==3){
	   website="../index.php/Special:Search?from1="+tmpfrom+"&to1="+tmpto+"&city1="+allcityname+"&flag1=1&page=3&fssearch=q=smwh_search_field:&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.field=smwh_Ontology_0/city_xsdvalue_t&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:HealthCareData&fq=smwh_attributes:smwh_Ontology_0/city_xsdvalue_t&fq=smwh_Ontology_0/city_xsdvalue_s:"+allcityname+"&fq=smwh_attributes:smwh_Ontology_0/timestamp_xsdvalue_dt&fq=smwh_Ontology_0/timestamp_datevalue_l:["+tmpfrom+" TO "+tmpto+"]&pagename="+$("#pagename").val()+"";
	  
	   }
        else if(pageid==4){
	   website="../index.php/Special:Search?from1="+tmpfrom+"&to1="+tmpto+"&city1="+allcityname+"&flag1=1&page=4&fssearch=q=smwh_search_field:&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.field=smwh_Ontology_0/city_xsdvalue_t&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:LifeServiceData&fq=smwh_attributes:smwh_Ontology_0/city_xsdvalue_t&fq=smwh_Ontology_0/city_xsdvalue_s:"+allcityname+"&fq=smwh_attributes:smwh_Ontology_0/timestamp_xsdvalue_dt&fq=smwh_Ontology_0/timestamp_datevalue_l:["+tmpfrom+" TO "+tmpto+"]&pagename="+$("#pagename").val()+"";
	  
	   }
	     else if(pageid==5){
	   website="../index.php/Special:Search?from1="+tmpfrom+"&to1="+tmpto+"&city1="+allcityname+"&flag1=1&page=5&fssearch=q=smwh_search_field:&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.field=smwh_Ontology_0/city_xsdvalue_t&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:MoviesData&fq=smwh_attributes:smwh_Ontology_0/city_xsdvalue_t&fq=smwh_Ontology_0/city_xsdvalue_s:"+allcityname+"&fq=smwh_attributes:smwh_Ontology_0/timestamp_xsdvalue_dt&fq=smwh_Ontology_0/timestamp_datevalue_l:["+tmpfrom+" TO "+tmpto+"]&pagename="+$("#pagename").val()+"";
	  
	   }
location.href=website;



	 
	 
	 
	 
	 
	 }
	 else {
	  if(pageid==6){
	   website="../index.php/Special:Search?city1="+cityname+"&flag1=1&page=6&fssearch=q=smwh_search_field:&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.field=smwh_Ontology_0/city_xsdvalue_t&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:RestaurantData&fq=smwh_attributes:smwh_Ontology_0/city_xsdvalue_t&fq=smwh_Ontology_0/city_xsdvalue_s:"+cityname+"&pagename="+$("#pagename").val()+"";
	  
	   }
	   else if(pageid==7){
	   website="../index.php/Special:Search?city1="+cityname+"&flag1=1&page=7&fssearch=q=smwh_search_field:&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.field=smwh_Ontology_0/city_xsdvalue_t&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:ShoppingData&fq=smwh_attributes:smwh_Ontology_0/city_xsdvalue_t&fq=smwh_Ontology_0/city_xsdvalue_s:"+cityname+"&pagename="+$("#pagename").val()+"";
	  
	   }
	      else if(pageid==8){
	   website="../index.php/Special:Search?city1="+cityname+"&flag1=1&page=8&fssearch=q=smwh_search_field:&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.field=smwh_Ontology_0/city_xsdvalue_t&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:TravellingData&fq=smwh_attributes:smwh_Ontology_0/city_xsdvalue_t&fq=smwh_Ontology_0/city_xsdvalue_s:"+cityname+"&pagename="+$("#pagename").val()+"";
	  
	   }
	      else if(pageid==0){
	   website="../index.php/Special:Search?city1="+cityname+"&flag1=1&page=0&fssearch=q=smwh_search_field:&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.field=smwh_Ontology_0/city_xsdvalue_t&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:UserPageData&fq=smwh_attributes:smwh_Ontology_0/city_xsdvalue_t&fq=smwh_Ontology_0/city_xsdvalue_s:"+cityname+"&pagename="+$("#pagename").val()+"";
	  
	   }
	      else if(pageid==1){
	   website="../index.php/Special:Search?city1="+cityname+"&flag1=1&page=1&fssearch=q=smwh_search_field:&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.field=smwh_Ontology_0/city_xsdvalue_t&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:CosmetologyData&fq=smwh_attributes:smwh_Ontology_0/city_xsdvalue_t&fq=smwh_Ontology_0/city_xsdvalue_s:"+cityname+"&pagename="+$("#pagename").val()+"";
	  
	   }
	    else if(pageid==2){
	   website="../index.php/Special:Search?city1="+cityname+"&flag1=1&page=2&fssearch=q=smwh_search_field:&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.field=smwh_Ontology_0/city_xsdvalue_t&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:EntertainmentData&fq=smwh_attributes:smwh_Ontology_0/city_xsdvalue_t&fq=smwh_Ontology_0/city_xsdvalue_s:"+cityname+"&pagename="+$("#pagename").val()+"";
	  
	   }
	    else if(pageid==3){
	   website="../index.php/Special:Search?city1="+cityname+"&flag1=1&page=3&fssearch=q=smwh_search_field:&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.field=smwh_Ontology_0/city_xsdvalue_t&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:HealthCareData&fq=smwh_attributes:smwh_Ontology_0/city_xsdvalue_t&fq=smwh_Ontology_0/city_xsdvalue_s:"+cityname+"&pagename="+$("#pagename").val()+"";
	  
	   }
        else if(pageid==4){
	   website="../index.php/Special:Search?city1="+cityname+"&flag1=1&page=4&fssearch=q=smwh_search_field:&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.field=smwh_Ontology_0/city_xsdvalue_t&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:LifeServiceData&fq=smwh_attributes:smwh_Ontology_0/city_xsdvalue_t&fq=smwh_Ontology_0/city_xsdvalue_s:"+cityname+"&pagename="+$("#pagename").val()+"";
	  
	   }
	     else if(pageid==5){
	   website="../index.php/Special:Search?city1="+cityname+"&flag1=1&page=5&fssearch=q=smwh_search_field:&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.field=smwh_Ontology_0/city_xsdvalue_t&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:MoviesData&fq=smwh_attributes:smwh_Ontology_0/city_xsdvalue_t&fq=smwh_Ontology_0/city_xsdvalue_s:"+cityname+"&pagename="+$("#pagename").val()+"";
	  
	   }
	   
location.href=website;
}
    });        
});
</script>





<div id="create_article"/>
		</div>
		<hr class="xfsSeparatorLine">
		<div id="navigation">
			<div id="pager-header"></div>
		</div>

		<div id="docs">
			{{fs_search_results}}
		</div>
		<div id="xfsFooter">
			<ul id="pager"></ul>
		</div>
	</div>
</div>
<div class="xfsCurrentSearchLink">
	<hr class="xfsSeparatorLine">
	<div id="current_search_link"></div>
</div>










</div>
</div>



</div>
';

    public function __construct() {
//        parent::__construct('FacetedSearch');
		parent::__construct('Search');
		global $wgHooks;
		$wgHooks['MakeGlobalVariablesScript'][] = "FSFacetedSearchSpecial::addJavaScriptVariables";
    }

    /**
     * Overloaded function that is responsible for the creation of the Special Page
     */
    public function execute($par) {
	
		global $wgOut, $wgRequest;
	
		$wgOut->setPageTitle(wfMsg('fs_title'));
			$pageid = $wgRequest->getText( 'id', '' );
			$s= $wgRequest->getText( 's', '' );
			$flag = $wgRequest->getText( 'flag', '' );
			$flag1 = $wgRequest->getText( 'flag1', '' );
				$from = $wgRequest->getText( 'from', '' );
					$to = $wgRequest->getText( 'to', '' );
			if($flag1!=1){
			
			 if($pageid==1){
		    echo "<script>";
			echo 'location.href="../index.php/Special:Search?page=1&fssearch=q=smwh_search_field:'.$s.'&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:CosmetologyData&flag=1"';
		    echo "</script>";
			
			
			
			}
			else if($pageid==2){
			   echo "<script>";
			echo 'location.href="../index.php/Special:Search?page=2&fssearch=q=smwh_search_field:'.$s.'&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:EntertainmentData&flag=1"';
		    echo "</script>";
			}
			else if($pageid==3){
			   echo "<script>";
			echo 'location.href="../index.php/Special:Search?page=3&fssearch=q=smwh_search_field:'.$s.'&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:HealthCareData&flag=1"';
		    echo "</script>";
			}
			else if($pageid==4){
			   echo "<script>";
			echo 'location.href="../index.php/Special:Search?page=4&fssearch=q=smwh_search_field:'.$s.'&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:LifeServiceData&flag=1"';
		    echo "</script>";
			}
			else if($pageid==5){
			   echo "<script>";
			echo 'location.href="../index.php/Special:Search?page=5&fssearch=q=smwh_search_field:'.$s.'&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:MoviesData&flag=1"';
		    echo "</script>";
			}
			else if($pageid==6){
			   echo "<script>";
			echo 'location.href="../index.php/Special:Search?page=6&fssearch=q=smwh_search_field:'.$s.'&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:RestaurantData&flag=1"';
		    echo "</script>";
			}
			else if($pageid==7){
			   echo "<script>";
			echo 'location.href="../index.php/Special:Search?page=7&fssearch=q=smwh_search_field:'.$s.'&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:ShoppingData&flag=1"';
		    echo "</script>";
			}
			
			else if($pageid==8){
			   echo "<script>";
			echo 'location.href="../index.php/Special:Search?page=8&fssearch=q=smwh_search_field:'.$s.'&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:TravellingData&flag=1"';
		    echo "</script>";
			}
			
			else if($pageid==0||$pageid==null){
			
			
			
		    if($flag!=1){
		
		  header('Location:../index.php/Special:Search?page=0&fssearch=q=smwh_search_field:'.$s.'&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:UserPageData&flag=1');
	
			}
			else{
			
			}
			
			}
			else {
			
			
			}
			}
		$search = str_replace( "\n", " ", $wgRequest->getText( 'search', '' ) );
		if ($search === wfMsg('smw_search_this_wiki')) {
			// If the help text of the search field is passed, assume an empty 
			// search string
			$search = '';
		}
		
		
		
		
		$restrict = $wgRequest->getText( 'restrict', '' );
		$specialPageTitle = $wgRequest->getText( 'title', '' );
		$t = Title::newFromText( $search );

		$fulltext = $wgRequest->getVal( 'fulltext', '' );
		$fulltext_x = $wgRequest->getVal( 'fulltext_x', '' );
		if ($fulltext == NULL && $fulltext_x == NULL) {
			
			# If the string cannot be used to create a title
			if(!is_null( $t ) ){

				# If there's an exact or very near match, jump right there.
				$t = SearchEngine::getNearMatch( $search );
				if( !is_null( $t ) ) {
					$wgOut->redirect( $t->getFullURL() );
					return;
				}

				# If just the case is wrong, jump right there.
//				$t = USStore::getStore()->getSingleTitle($search);
//				if (!is_null( $t ) ) {
//					$wgOut->redirect( $t->getFullURL() );
//					return;
//				}
			}
		}
        
		// Insert the search term into the input field of the UI
		$html = self::SPECIAL_PAGE_HTML;
		$html = str_replace('{{searchTerm}}', htmlspecialchars($search), $html);
		
		$wgOut->addHTML($this->replaceLanguageStrings($html));
		$wgOut->addModules('ext.facetedSearch.special');
    }

	/**
	 * Add a global JavaScript variable for the SOLR URL.
	 * @param $vars
	 * 		This array of global variables is enhanced with "wgFSSolrURL"
	 * 		and "wgFSCreateNewPageLink"
	 */
	public static function addJavaScriptVariables(&$vars) {
		global $fsgFacetedSearchConfig, $fsgCreateNewPageLink;
		$servlet = array_key_exists('proxyServlet', $fsgFacetedSearchConfig)
					? $fsgFacetedSearchConfig['proxyServlet']
					: '/solr/select';
		$port = array_key_exists('proxyPort', $fsgFacetedSearchConfig)
					? $fsgFacetedSearchConfig['proxyPort']
					: false;
					
		$solrURL = $fsgFacetedSearchConfig['proxyHost'];
		if ($port) {
			$solrURL .= ':' . $port;
		}
		
		$vars['wgFSSolrURL'] = $solrURL;
		$vars['wgFSSolrServlet'] = $servlet;
		$vars['wgFSCreateNewPageLink'] = $fsgCreateNewPageLink;
		
		return true;
	}
    
	/**
	 * Language dependent identifiers in $text that have the format {{identifier}}
	 * are replaced by the string that corresponds to the identifier.
	 * 
	 * @param string $text
	 * 		Text with language identifiers
	 * @return string
	 * 		Text with replaced language identifiers.
	 */
	private static function replaceLanguageStrings($text) {
		// Find all identifiers
		$numMatches = preg_match_all("/(\{\{(.*?)\}\})/", $text, $identifiers);
		if ($numMatches === 0) {
			return $text;
		}

		// Get all language strings
		$langStrings = array();
		foreach ($identifiers[2] as $id) {
			$langStrings[] = wfMsg($id);
		}
		
		// Replace all language identifiers
		$text = str_replace($identifiers[1], $langStrings, $text);
		return $text;
	}
    
}


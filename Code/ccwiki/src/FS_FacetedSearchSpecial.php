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
session_start();
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
    <link href="../src/css/common.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../src/css/style.css" type="text/css" />
<link rel="stylesheet" href="../src/css/widget.css" type="text/css" />
<script type="text/javascript" src="../src/js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="../src/js/basic.js"></script>
<script type="text/javascript" src="../src/js/widget.js"></script>
<script type="text/javascript">
$(function(){
	
	$(".sxli li em").toggle(function(){
	$(this).children("img").attr("src","../src/images/array03.png");	
	},function(){
		$(this).children("img").attr("src","../src/images/array02.png");
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
</script>
<div class="fll">
  <div class="select">
  <div class="hd">已选择的种类：</div>
  
<div class="bdd">
<div>没有选择种类</div>
<ul class="sxli">
		<li><em><img src="images/array02.png" /></em>&nbsp;<a href="#">生活 (8)</a> <span class="rremove"><img src="images/cha.png" width="10" height="10" /></span></li>
    <div>2012-03-27 (1600111)<br />
	&nbsp;&nbsp;&nbsp;&nbsp;<a href="">2012-03-27 (309)</a><br />
	&nbsp;&nbsp;&nbsp;&nbsp;<a href="">2012-03-27 (309)</a><br />
	&nbsp;&nbsp;&nbsp;&nbsp;<a href="">2012-03-27 (309)</a><br />
	</div>
</ul>
</div>
</div>
<div class="interestor">
<div class="hd">
  <p>可选择的种类：</p></div>
<div class="bdd">
<div class="cuti">种类</div>
<ul class="zlli">
	<li><a href="#">生活 (8)</a></li>
	<li><a href="#">生活 (8)</a></li>
	
</ul>

<div class="more"><a href="#" class=" blue_xh">更多>></a></div>
<div class="cuti">属性</div>
<ul class="sxli">
		<li><em><img src="images/array02.png" /></em>&nbsp;<a href="#">生活 (8)</a></li> <div>2012-03-27 (1600)<br />
	&nbsp;&nbsp;&nbsp;&nbsp;<a href="">2012-03-27 (309)</a><br />
	&nbsp;&nbsp;&nbsp;&nbsp;<a href="">2012-03-27 (309)</a><br />
	&nbsp;&nbsp;&nbsp;&nbsp;<a href="">2012-03-27 (309)</a><br />
	</div>
    
</ul>
<div class="more"><a href="#" class=" blue_xh">更多>></a></div>
</div>

</div>


</div>
<div class="frr">
<div class="frtitle">
	<h2>购物全部页面</h2><input name="" type="button" value="编辑页面" />
</div>
<div class="searchbox">
<div class="searchbox2">

<div class="maps">
<div class="hd">地图查询</div>
<div class="bd"><img src="images/map2.png" width="746" height="340" class="img_b" /></div>

</div>


<div class="searchb">
  <div class="fl">
  	<input type="text" name="textfield" id="textfield" class="slk" />&nbsp;&nbsp;<input type="image" name="imageField" id="imageField" src="images/search_btn03.gif" />
  </div><div class="fr lh30">
  	排序方式： <select id="search_order" name="search_order" size="1">
  	   					
  	  <option value="relevance">Relevance</option>
  	   					
  	  <option value="newest" selected="selected">Latest article first</option>
  	   					
  	  <option value="oldest">Oldest article first</option>
  	   					
  	  <option value="ascending">Title ascending</option>
  	   					
  	  <option value="descending">Title descending</option>
  	   				
  	</select>
  </div>
</div>
<div class="hrr lh25">2000 2011 2012</div>
<div class="lh25 gray">Results 1 to 10 of 872</div>

<div class="tglist">
<h5><a href="shop_detail.htm">惠普重新考虑PC分拆决定：担心损害公司利益</a></h5>
<p class="tginfo">北京时间10月12日消息，据熟知内情的消息人士周二称，惠普及其金融顾问公司最近以来进行的内部分析表明，拆分惠普PC业务部门的成本可能超出其利益，这意味着保留该部门要好</p>
<div class="listtext">
<input name="input" type="button" value="显示属性" class="show"/>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="sxtable"><tbody><tr class="s0"><td>Ontology 0/source</td><td>tencent</td></tr><tr class="s1"><td>Creator</td><td><a href="/ccwiki/index.php/User:Root">User:Root</a></td></tr><tr class="s0"><td>Ontology 0/sns id</td><td>masling</td></tr><tr class="s1"><td>Creation date</td><td>2012-04-11T13:56:29Z</td></tr><tr class="s0"><td>Ontology 0/avatar</td><td>http://app.qlogo.cn/mbloghead/49e0d196911cc60a2650/50.jpg</td></tr><tr class="s1"><td>Last modified by</td><td><a href="/ccwiki/index.php/User:Root">User:Root</a></td></tr><tr class="s0"><td>QRCUsesQueryCall</td><td><a href="/ccwiki/index.php/Person_masling">Person masling</a></td></tr><tr class="s1"><td>Ontology 0/timestamp</td><td>2012-04-11T13:56:24Z</td></tr><tr class="s0"><td>Ontology 0/create</td><td><a href="/ccwiki/index.php/Test123">Test123</a>, <a href="/ccwiki/index.php/Undefined">Undefined</a>, <a href="/ccwiki/index.php/NewTest">NewTest</a>, <a href="/ccwiki/index.php/NewTest1">NewTest1</a>, <a href="/ccwiki/index.php/NewTest11">NewTest11</a>, <a href="/ccwiki/index.php/1222222">1222222</a>, <a href="/ccwiki/index.php/22222">22222</a>, <a href="/ccwiki/index.php/222222">222222</a></td></tr><tr class="s1"><td>Modification date</td><td>2012-04-12T14:59:11Z</td></tr><tr class="s0"><td>Ontology 0/name</td><td>马桂波</td></tr><tr class="s1"><td>Ontology 0/participated</td><td><a href="/ccwiki/index.php/Deal_767918">Deal 767918</a></td></tr></tbody></table>

<div>所属目录：<a href="#">生活</a>&nbsp;&nbsp;&nbsp;&nbsp;创建人:陈娇严&nbsp;&nbsp;&nbsp;&nbsp;最后修改日期：2012年3月10日 18:50</div>
</div>
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
			if(($pageid==0)||($pageid=="")){
			
			
			}
			else if($pageid==1){
		    echo "<script>";
			echo 'location.href="http://10.214.0.147/ccwiki/index.php/Special:Search?fssearch=q=smwh_search_field:&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:Cosmetology"';
		    echo "</script>";
			
			
			
			}
			else if($pageid==2){
			   echo "<script>";
			echo 'location.href="http://10.214.0.147/ccwiki/index.php/Special:Search?fssearch=q=smwh_search_field:&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:Entertainment"';
		    echo "</script>";
			}
			else if($pageid==3){
			   echo "<script>";
			echo 'location.href="http://10.214.0.147/ccwiki/index.php/Special:Search?fssearch=q=smwh_search_field:&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:HealthCare"';
		    echo "</script>";
			}
			else if($pageid==4){
			   echo "<script>";
			echo 'location.href="http://10.214.0.147/ccwiki/index.php/Special:Search?fssearch=q=smwh_search_field:&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:LifeService"';
		    echo "</script>";
			}
			else if($pageid==5){
			   echo "<script>";
			echo 'location.href="http://10.214.0.147/ccwiki/index.php/Special:Search?fssearch=q=smwh_search_field:&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:Movies"';
		    echo "</script>";
			}
			else if($pageid==6){
			   echo "<script>";
			echo 'location.href="http://10.214.0.147/ccwiki/index.php/Special:Search?fssearch=q=smwh_search_field:&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:Restaurant"';
		    echo "</script>";
			}
			else if($pageid==7){
			   echo "<script>";
			echo 'location.href="http://10.214.0.147/ccwiki/index.php/Special:Search?fssearch=q=smwh_search_field:&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:Shopping"';
		    echo "</script>";
			}
			
			else if($pageid==8){
			   echo "<script>";
			echo 'location.href="http://10.214.0.147/ccwiki/index.php/Special:Search?fssearch=q=smwh_search_field:&facet=true&facet.field=smwh_categories&facet.field=smwh_attributes&facet.field=smwh_properties&facet.field=smwh_namespace_id&facet.mincount=1&json.nl=map&fl=smwh_Modification_date_xsdvalue_dt,smwh_categories,smwh_attributes,smwh_properties,id,smwh_title,smwh_namespace_id&hl=true&hl.fl=smwh_search_field&hl.simple.pre=<b>&hl.simple.post=</b>&hl.fragsize=250&sort=smwh_Modification_date_xsdvalue_d%20desc&fq=smwh_categories:Travelling"';
		    echo "</script>";
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


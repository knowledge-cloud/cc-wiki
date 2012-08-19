<?php

/**
 * Result printer that prints query results as a gallery.
 *
 * @file MUW_biGallery.php
 * @ingroup MashupWiki
 *
 * @author sling ma
 */
class MUWbIMap extends SMWResultPrinter {
        protected   $types = array( '_wpg' => 'text', '_num' => 'number', '_dat' => 'date', '_geo' => 'text', '_str' => 'text' );
        public function getName() {
		return wfMsg( 'muw_printername_bimap' );
	}
         static public function addJavascriptAndCSS() {
		// MW 1.17 +
                global $wgOut, $smwgJQueryIncluded,$srfgScriptPath,$srfBaiduApiIncluded,$srfgbMapncluded;
                $scripts = array();
                if(!$srfBaiduApiIncluded){
                         $scripts[]='http://api.map.baidu.com/api?v=1.2';
                         $scripts[]='http://api.map.baidu.com/library/CityList/1.2/src/CityList_min.js';
                         $srfBaiduApiIncluded=true;
                }
		
                $wgOut->addStyle($srfgScriptPath."/js/map.css");
		if ( !$srfgbMapncluded ) {
                    $scripts[] = "$srfgScriptPath/js/mapjs.js";
                    $srfgbMapncluded = true;
		}
		foreach ( $scripts as $script ) {
			$wgOut->addScriptFile( $script );
		}

		// CSS file
		//$wgOut->addExtensionStyle( "$srfgScriptPath/" );
	}
	/*public function getResult( SMWQueryResult $results, array $params, $outputmode ) {
		//$this->handleParameters( $params, $outputmode );
		return $this->getResultText( $results, SMW_OUTPUT_HTML );
	}*/
        public function getResultText( SMWQueryResult $results, $outputmode ) {
		global $wgOut,$wgUser, $wgParser;
                $biMap="";
		self::addJavascriptAndCSS();
                $this->isHTML = true;
		$resultArray=$this->getArray($results, $outputmode);
                $biMap.=$this->getIndexUI($resultArray,$outputmode,$wgOut);
                return $biMap;
                //return array( $biMap, 'nowiki' => true, 'isHTML' => true );
	}
       
        public function getIndexUI($results,$outputmode,$wgOut){
            global $wgStylePath;
            $page=false;
           /* $html='<div class="map" id="container"></div>
<div class="mapmore"><a href="javascript:">查看完整地图</a></div>
<div class="address">
<span class="cuti">商家地址</span><br />';
*/
	    $html='';
            $jsitem='[';
            $city="杭州";
           
            for($i=0;$i<1;$i++){ 
                    $info=$results[$i]; 
             //       $html.= '城市：'.$info['city'].'<br/>';
                    if(is_array($info['address'])==false){
                        if($info['address']!=""){
                            $html.='<li style="display:none"> 地址：'.$info['address'].' </li>';
                            if($info['latitude']=="")$info['latitude']=-1;
                            if($info['longitude']=="")$info['longitude']=-1;
                            $jsitem.='['.$info['latitude'].','.$info['longitude'].',"'.$info['address'].'"],';
                        }
                    }else{
                        for($j=0;$j<count($info['address']);$j++){
                            if($info['address']!=""){
                                $html.='<li style="display:none">地址：'.$info['address'][$j].' </li>';
                                 if(is_array($info['latitude'])==false){
                                     if($info['latitude']=="")$info['latitude']=-1;
                                     if($info['longitude']=="")$info['longitude']=-1;
                                     $jsitem.='['.$info['latitude'].','.$info['longitude'].',"'.$info['address'][$j].'"],';
                                 }else{
                                    if(!key_exists($j, $info['latitude']))$info['latitude'][$j]=-1;
                                    if(!key_exists($j, $info['longitude']))$info['longitude'][$j]=-1;                       
                                    $jsitem.='['.$info['latitude'][$j].','.$info['longitude'][$j].',"'.$info['address'][$j].'"],';
                                 }
                             }
                        }
                    }
        //            $html.="</div>";
                    if($info['city']!="") $city=$info['city'];
            }
            if(stripos($city, ",")!== false){
                $citys= explode(",", $city);
                $city=$citys[0];
            }
            if(strlen($jsitem)>1)$jsitem=  substr ($jsitem,0,(strlen($jsitem)-1));
            $jsitem.="]";
            $html.='<div style="display:none" id="bimapbushtml"><div class="busmap" id="container"></div>
<div class="busfrom">

起点： <input type="text" id="from" class="input150"/>&nbsp;&nbsp;终点： <input type="text" id="to" class="input150"/>&nbsp;&nbsp;<input id="findway" name="" type="image" src="'.$wgStylePath.'/ccwiki/images/btn_search.png" />

</div>


<div id="dvPolicy">

<input id="policy0" checked="true" type="radio" name="pickPolicy" class="radiobox "/><label for="policy0">较便捷 </label>&nbsp;&nbsp;<input id="policy1" type="radio" name="pickPolicy" class="radiobox"/><label for="policy1">可换乘 </label>&nbsp;&nbsp;<input id="policy2" type="radio" name="pickPolicy" class="radiobox"/><label for="policy2">少步行 </label>


</div> 

<div id="results" >
</div></div></div>';
            $html.="<div id=\"mapscriptitem\" style=\"display:none\">".$jsitem."</div>
                <div id=\"mapscriptmapcity\" style=\"display:none\">".$city."</div>";
            return $html;
        }
        protected function getArray(SMWQueryResult $res, $outputmode){
                $perPage_items = array();
		//for each page:
		while( $row = $res->getNext() ) {
			$perProperty_items = array();
			$isPageTitle = true; //first field is always the page title;
			//for each property on that page:
			foreach( $row as $field ) { // $row is array(), $field of type SMWResultArray;
				$manyValues = $field->getContent();
                                $pr=$field->getPrintRequest();
                                $item=$pr->getLabel();
				//If property is not set (has no value) on a page:
				if( count( $manyValues ) < 1 ) {
                                    $delivery='';
				} else{
                                    $value_items = array();
                                    while( $obj = efSRFGetNextDV( $field ) ) { // $manyValues of type SMWResultArray, contains many values (or just one) of one property of type SMWDataValue				
                                         if( $obj instanceof SMWRecordValue ) {		
                                            $record = $obj->getDVs();
                                            $recordLength = count( $obj->getTypeValues() );
                                            $items_value_items=array();
                                            for( $i = 0; $i < $recordLength; $i++ ) {
                                                $recordField = $record[$i];
                                                $items_value_items = $this->fillDeliveryArray( $items_value_items,  $this->deliverSingleValue($recordField ));							
                                            }
                                            $value_items = $this->fillDeliveryArray($value_items, $items_value_items);
                                        } else {						
                                            $value_items = $this->fillDeliveryArray( $value_items, $this->deliverSingleValue($obj) );
                                        }
                                    }
                                    $delivery=$value_items;// foreach...
                                }
                                $perProperty_items[$item] = is_array($delivery)?((count($delivery)==1)?$delivery[0]:$delivery):$delivery;
			} // foreach...		
			$perPage_items = $this->fillDeliveryArray( $perPage_items, $perProperty_items );
		} // while...
		return $perPage_items;
        }
        protected function fillDeliveryArray( $array = array(), $value = null ) {
		if( ! is_null( $value ) ) { 
                    if(is_array($value)){
                        if (count($value)==1)
                            $array[] = $value[0];
                        else if(count($value)==0)
                           return $array;
                        else 
                            $array[] = $value;
                    }else
			$array[] = $value;
		}
		return $array;
	}
        protected function deliverSingleValue( $value ) {
            if(!is_null($value))
		return trim( Sanitizer::decodeCharReferences( $value->getLongText( SMW_OUTPUT_HTML ) ) ); 
           else 
               return "";
	}
}

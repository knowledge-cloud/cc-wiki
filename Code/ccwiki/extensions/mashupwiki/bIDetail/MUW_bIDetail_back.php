<?php

/*
 * Result printer that prints query results as a gallery.
 *
 * @file MUW_bIDetail.php
 * @ingroup MashupWiki
 *
 *
 * @author sling ma
 */
class MUWbiDetail extends SMWResultPrinter {
    
        public function getName() {
		return wfMsg( 'muw_printername_bidetail' );
	}

	/*public function getResult( SMWQueryResult $results, array $params, $outputmode ) {
		//$this->handleParameters( $params, $outputmode );
		return $this->getResultText( $results, SMW_OUTPUT_HTML );
	}*/

	public function getResultText( SMWQueryResult $results, $outputmode ) {
		global $wgUser, $wgParser,$wgStylePath,$wgOut,$wgServer,$wgScriptPath;
                $this->isHTML = true;
		$resultArray=$this->getArray($results, $outputmode);
                $pagename="";
                if(count($resultArray)>0 && key_exists(0, $resultArray[0])){
                    $pagename=$resultArray[0][0];
                }else{
                    $pagename="Deal ".$resultArray[0]["id"];
                }
                $thispageurl=$wgServer.Skin::makeUrl("detailview ".$pagename);
                $thispageurl="$pagename";
                $html='<div class="aboutgb">
<table width="725" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="300" align="center"><img src="{picture}" width="377" height="248" /></td>
        <td width="200" bgcolor="#f4f4f4"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td height="85" align="center">
            <div class="gbyuan">{dis}折</div>
            <span class="gbprice2">{currprice}</span></td>
          </tr>
          <tr>
            <td height="29" align="center" ><span class="cuti delx">原价{orgprice}</span></td>
          </tr>
          <tr>
            <td height="50" align="center"><a href="javascript:;" id="partclick"><img src="'.$wgStylePath.'/ccwiki/images/btn_ljqg.png" width="78" height="32" ></a> <a href="javascript:;" id="intclick"><img src="'.$wgStylePath.'/ccwiki/images/btn_wgxq.png" width="78" height="32" /><a>
          </td>
          </tr>
          <tr>
            <td height="57" align="center"><span class="px14"><span class="red"> </span> 人已购买</span><br />
              数量有限，下单要快哟</td>
          </tr>
          <tr>
            <td height="27" align="center" bgcolor="#e7e7e7" class="px14"><span class="gray">剩余时间</span> {timeend}</td>
          </tr>
        </table></td>
      </tr>
    </table>
    <input type="hidden" id="currdealname" value="'.$pagename.'" />
    <input type="hidden" id="currdealhref" value="{url}" />
    <h2>{title}</h2>
    <p class="thisgb">{desc} </p>
    <div class="photoup" style="width:435px;">
    <input name="file_upload" type="file" id="file_upload" size="50" maxlength="100" /></div>
    <div id="facesPhotoWrapper" style="435px"></div>
    <div id="disqus_thread"style="width:725px;"></div>
</div><div id="div_disqus_url" style="display:none">http://www.google.com/'.$thispageurl.'</div>';
                if(count($resultArray)>0){
                    $html= preg_replace ("'{(\w+)}'e","\$resultArray[0]['\\1']",$html);
                }else
                    $html= preg_replace ("'{(\w+)}'e","",$html);
               return $html;
               //return array( $html, 'nowiki' => true, 'isHTML' => true );
	}
        protected function getArray(SMWQueryResult $res, $outputmode){
                $perPage_items = array();
		//for each page:
		while( $row = $res->getNext() ) {
			$perProperty_items = array();
			$isPageTitle = true; //first field is always the page title;
			//for each property on that page:
                        $i=0;
			foreach( $row as $field ) { // $row is array(), $field of type SMWResultArray;
				$manyValues = $field->getContent();
                                $pr=$field->getPrintRequest();
                                $item=$pr->getLabel();
                                if($item=="")
                                {
                                    $item=$i;
                                    $i++;
                                }
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
                        if($perProperty_items['orgprice']!="0" && $perProperty_items['orgprice']!=NULL)
                            $perProperty_items['dis']=round(($perProperty_items['currprice']*10/$perProperty_items['orgprice']),2);
                        else
                           $perProperty_items['dis'] =10;
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

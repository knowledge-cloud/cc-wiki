<?php

/*
 * Result printer that prints query results as a gallery.
 *
 * @file MUW_Weibo.php
 * @ingroup MashupWiki
 *
 * @author sling ma
 */
class MUWbiWeibo extends SMWResultPrinter {
        protected $mSep   =10;
        public function getName() {
		return wfMsg( 'muw_printername_biweibo' );
	}
        protected function handleParameters( array $params, $outputmode ) {
		parent::handleParameters( $params, $outputmode );
                $this->readParameters($params,$outputmode);
      }
	/*public function getResult( SMWQueryResult $results, array $params, $outputmode ) {
		//$this->handleParameters( $params, $outputmode );
		return $this->getResultText( $results, SMW_OUTPUT_HTML );
	}*/
        public function getResultText( SMWQueryResult $results, $outputmode ) {
		global $wgOut,$wgUser, $wgParser,$wgScriptPath;
                $bWeibo="";
                $this->isHTML = true;
		$resultArray=$this->getArray($results, $outputmode);
                $bWeibo.=$this->getIndexUI($resultArray,$outputmode);
                return $bWeibo;
	}
       
        public function getIndexUI($results,$outputmode){
            global  $wgStylePath;
            $page=false;
            $html='<div class="weibobox"><textarea name="weibocontent" id="weibocontent" class="text2"></textarea>
            <input type="button" class="wbbtn" name="Sina" value="发布新浪微" onclick="postSinaWeibo()"/> <input type="button" class="wbbtn" name="Tecent" value="发布腾讯微博" onclick="postQQWeibo()"/>
            </div>';

            if(count($results)==1 && is_array($results[0]['avatar'])){
                $info=$results[0];  
                if(count($info['avatar'])>$this->mSep)
                    $page=true;
                $imax=$page==true?$this->mSep:count($info['avatar']);
                for($i=0;$i<$this->mSep;$i++){ 
                    if($i<$imax){
                        $html.= '<div class="weibolist">
                        <div class="ffl">
                        <img src="'.$info['avatar'][$i].'" width="48" height="48" class="img_b2" /></div>
                        <div class="ffr">
                        <p><em>'.$info['name'][$i].'</em> ：'.$info['status'][$i].'</p>
                        <span>'.$info['published time'][$i].' 来自'.$info['source'][$i].'微博</span>
                        </div>
                        </div>'; 
                    }
                }
            }else{
                if(count($results)>$this->mSep)
                    $page=true;
                $imax=$page==true?$this->mSep:count($results);
                for($i=0;$i<$this->mSep;$i++){ 
                    if($i<$imax){
                        $info=$results[$i];
                        $html.= '<div class="weibolist">
                        <div class="ffl">
                        <img src="'.$info['avatar'].'" width="48" height="48" class="img_b2" /></div>
                        <div class="ffr">
                        <p><em>'.$info['name'].'</em> ：'.$info['status'].'</p>
                        <span>'.$info['published time'].' 来自'.$info['source'].'微博</span>
                        </div>
                        </div>'; 
                    }
                }
            } 
            return $html;
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
                                        //if( $isPageTitle ) {
                                            //$isPageTitle = false;			
                                            //continue 2; //next property						
                                        //} else
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
                        if (count($value)==1){
                            $array[] = $value[0];
                        }else if(count($value)==0)
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
        protected function readParameters( $params, $outputmode ) {
		parent::readParameters( $params, $outputmode );
		if( array_key_exists('sep', $params) )
                    $this->mSep     = trim( $params['sep'] );
                else 
                    $this->mSep     = 10;
	}
        public function getParameters() {
		return array (
			array( 'name' => 'sep',     'type' => 'int', 'description' => wfMsg( 'smw_paramdesc_sep' ) ),	
			);
	}
}

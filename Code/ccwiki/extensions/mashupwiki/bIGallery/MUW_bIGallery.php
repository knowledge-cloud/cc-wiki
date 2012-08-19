<?php

/**
 * Result printer that prints query results as a gallery.
 *
 * @file MUW_biGallery.php
 * @ingroup MashupWiki
 *
 * @author sling ma
 */
class MUWbIGallery extends SMWResultPrinter {
        protected   $types = array( '_wpg' => 'text', '_num' => 'number', '_dat' => 'date', '_geo' => 'text', '_str' => 'text' );
	protected   $tuangous =array('拉手'=>'','美团'=>'','窝窝团'=>'','糯米网'=>'','F团'=>'','满座网'=>'');
        protected   $mSep =3;
        public function getName() {
		return wfMsg( 'muw_printername_bigallery' );
	}
        
        public function getResultText( SMWQueryResult $results, $outputmode ) {
		global $wgOut,$wgUser,$wgParser,$wgScriptPath;
                $this->isHTML=true;
		$resultArray=$this->getArray($results, $outputmode);
                $biGallery=$this->getIndexUI($resultArray,$outputmode);
                return $biGallery;
	}
       
        public function getIndexUI($results,$outputmode){
            $page=false;
            $html='<ul class="gblist">';
            $imax=(count($results)>$this->mSep)==true?$this->mSep:count($results);
            for($i=0;$i<count($results);$i++){ 
                if($i==0)
                    $class='class="curr"';
                else
                    $class='';
                $info=$results[$i];  
                if(!key_exists(0, $info))$info[0]="Deal ".$info["id"];
                if(key_exists($info['source'], $this->tuangous))
                    $gurl=$this->tuangous[$info['source']];
                else
                    $gurl="#";
                //<a href="'.$info['url'].'"></a>
                $html.= '<li datapage="'.$info[0].'" '.$class.'>'.
                '<div class="gbtitle"><a href="'.$gurl.'" class="cuti orange">'.$info['source'].'</a>丨<a href="'.$info['url'].'" class="dblue">'.$info['title'].'</a></div>'.
                '<div class="gbpic"><img src="'.$info['picture'].'" width="220" height="138" class="img_b3" /></div>'.
                '<div class="gbrebate">原价：<em>￥'.$info['orgprice'].'</em> <span>'.$info['dis'].'折</span></div>'.
                '<div class="gbprice"> <em>'.$info['currprice'].'</em><input type="hidden" class="hcurrname" value="'.$info[0].'" ><input type="hidden" class="hcurrurl" value="'.$info['url'].'" ><input name="" type="button" value="去看看" class="btn_qkk qukankan" /></div>'.
                '</li>'; 
               
            }
            
            $html.= '</ul>';
           
            
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
                                if($item==""){
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
        protected function readParameters( $params, $outputmode ) {
		parent::readParameters( $params, $outputmode );
		if( array_key_exists('sep', $params) )
                    $this->mSep     = trim( $params['sep'] );
                else 
                    $this->mSep     = 3;
	}
        public function getParameters() {
		return array (
			array( 'name' => 'sep',     'type' => 'int', 'description' => wfMsg( 'smw_paramdesc_sep' ) ),	
			);
	}
}

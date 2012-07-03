<?php

/**
 * Result printer that prints query results as a gallery.
 *
 * @file MUW_biGallery.php
 * @ingroup MashupWiki
 *
 * @author sling ma
 */
class MUWbiSns extends SMWResultPrinter {
     protected $types = array( '_wpg' => 'text', '_num' => 'number', '_dat' => 'date', '_geo' => 'text', '_str' => 'text' );
     protected $mSep   =10;
     protected $stype  ="n";
     public function getName() {
		return wfMsg( 'muw_printername_bisns' );
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
                //$this->readParameters($params,$outputmode);
		global $wgOut,$wgUser, $wgParser;
                $bSns="";
                $this->isHTML = true;
		$resultArray=$this->getArray($results, $outputmode);
                $fill=array();
                foreach ($resultArray as $key => $value) {
                    if(key_exists("id",$value) && !is_array($value["id"])){  
                        if(!key_exists($value["id"], $fill)){
                            $fill[]=$value;
                        }
                    }
                }
                $bSns.=$this->getIndexUI($fill,$outputmode);
                //$wgOut->addScript("<Script>var bigalleryitem=".json_encode($resultArray).";</script>");
                return $bSns;
                //return array( $bSns, 'nowiki' => true, 'isHTML' => true );
	}
       
        public function getIndexUI($results,$outputmode){
            global $wgStylePath,$wgScriptPath;
            $html="";
            if($this->stype=="m"){
                if(count($results)>0){
                    $info=$results[0];  
                    $html='<ul class="builder">
                    <li class="title">创建者：'.$info['name'].'</li>
                    <li class="ta-c"><img src="'.$info['avatar'].'" width="120" height="120" class="img_b3" /></li>
                    </ul>';
                }else{
                   $html='<ul class="builder">
                    <li class="title">创建者：没有</li>
                    <li class="ta-c"><img src="'.$wgStylePath.'/ccwiki/images/photo.jpg" width="120" height="120" class="img_b3" /></li>
                    <li>邮件地址：</li>
                    </ul>'; 
                }
            }else{
                $page=false;
                $html.='<ul class="bd">'	;
                $imax=(count($results)>$this->mSep)==true?$this->mSep:count($results);
                for($i=0;$i<$this->mSep;$i++){ 
                    if($i<$imax){
                        $info=$results[$i];  
                        $html.= '<li><img src="'.$info['avatar'].'" width="40" height="40" class="img_b" /><br /><a href="'.$wgScriptPath.'/index.php/Person '.$info['id'].'">'.$info['name'].'</a> </li>'; 
                    }
                }
                $html.= '</ul>';
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
                        //if($perProperty_items['orgprice']!="0" && $perProperty_items['orgprice']!=NULL)
                           //$perProperty_items['dis']=($perProperty_items['currprice']*10/$perProperty_items['orgprice']);
                        //else
                           //$perProperty_items['dis'] =10;
			$perPage_items = $this->fillDeliveryArray( $perPage_items, $perProperty_items ,true);
		} // while...
		return $perPage_items;
        }
        protected function fillDeliveryArray( $array = array(), $value = null,$foce=false ) {
		if( ! is_null( $value ) ) { 
                    if(is_array($value) && $foce==false){
                        if (count($value)==1 ){
                            $array[] = $value[0];
                            var_dump($value);
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
                if( array_key_exists('stype', $params) )
                    $this->stype     = trim( $params['stype'] );
                else 
                    $this->stype     = "n";
	}
        public function getParameters() {
		return array (
			array( 'name' => 'sep',     'type' => 'int', 'description' => wfMsg( 'smw_paramdesc_sep' ) ),
                        array( 'name'=>'stype',    'type'=>'enumeration', 'description' => wfMsg( 'smw_paramdesc_stype' ), 'values'=> array( 'm', 'p','i' ) )
			);
	}
}

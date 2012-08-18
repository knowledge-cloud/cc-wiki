<?php
include_once ( dirname(__FILE__).'/../../ccgroup/conf.php');
/*
 * Result printer that prints query results as a gallery.
 *
 * @file MUW_Weibo.php
 * @ingroup MashupWiki
 *
 * @author sling ma
 */
class MUWbiTaobao  extends SMWResultPrinter {
 
        public function getName() {
		return wfMsg( 'muw_printername_biJiepang' );
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
                $biJiepang="";
                $this->isHTML = true;
		//echo "test1";
		$resultArray=$this->getArray($results, $outputmode);
		//echo "test2";
                $biJiepang.=$this->getIndexUI($resultArray,$outputmode);
                return $biJiepang;
	}
       
        public function getIndexUI($results,$outputmode){
            global  $wgStylePath,$ccHost,$ccWiki,$ccPort;
			$mSep=4; //显示店铺数量
			$html="";
            $imax=count($results)>$mSep?$mSep:count($results);
			//echo count($results);
			//echo "<br/>";
			//echo $imax;
			$html.='<table id="table"'.' style="font-family:\'雅黑\';font-size:13px;">';
			$html.='<tr>';
			for($i=0;$i<$imax;$i++)
			{
				$info=$results[$i];	
				$url=" ".$info['url'][1];
				//var_dump($info['url']);
				//echo $url;
				//echo "<br/>";
				$html.='<td class="pic" style="height:160px;width:170px;padding:5px;margin-right:5px"><a href=\''.$url.'\'><img src="'.$info['picture'].'" alt="" height="160px" width="170px"/></a></td>';
			}
	        $html.='</tr>
			<tr>';
			for($i=0;$i<$imax;$i++)
			{
				$info=$results[$i];
				$url=" ".$info['url'][1];
				$html.='<td class="text"  style="height:70px;width:180px;">
					<p><a href=\''.$url.'\' style="text-decoration:none;color:blue;margin-bottom:20px">'.$info['title'].'</a><p>
					<p><span>价格：'.$info['price'].'</span><span style="margin-left:45px">最近成交：'.$info['volume'].'</span></p>
					<p>评价：'.$info['score'].'</p>
				</td>';
			}
			$html.=	'</tr>';
			$html.='</table>';
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

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
class MUWbiJiepang  extends SMWResultPrinter {
 
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
			$mSep=2; //显示店铺数量
			$pic_show=4;//显示图片的数量
			$src_no_pic='http://'.$ccHost.':'.$ccPort.'/ccwiki/extensions/mashupwiki/bIJiepang/pic/no_pic.jpg';	
			$html="";
			if($results==null||count($results)==0)
			{
				$html.="暂无相关街旁数据！";
				return $html;
			}
            		$imax=count($results)>$mSep?$mSep:count($results);
			//echo count($results);
			//echo "<br/>";
			//echo $imax;
			for($i=0;$i<$imax;$i++)
			{
				$info=$results[$i];	
				$pic_num=count($info['url']);
				$src=array();
				$ipic=$pic_num<$pic_show?$pic_num:$pic_show;
				if($pic_num==1)
				{
					$src[0]=$info['url'];$src[1]=$src_no_pic;$src[2]=$src_no_pic;$src[3]=$src_no_pic;
				}
				else
					{
						//echo $ipic;
						for($j=0;$j<$ipic;$j++)
						{
							$src[$j]=$info['url'][$j];
						//	var_dump($src[$j]);
						//	echo "<br/>";
						}
						for($j=$ipic;$j<$pic_show;$j++)
						{
							$src[$j]=$src_no_pic;
						}
				}

				$html.= '<div style="width:744px; height:260px;">
					<table width="725" height="60">
					<tr><td>商家名称：'.$info['name'].'</td><td>所在城市：'.$info['city'].'</td></tr>
					 <tr><td>商家地址：'.$info['address'].'</td></tr>
					</table>

					<table width="725" height="200">
					<tr><td><img src="'.$src[0].'" width="170" height="160" /></td>
					<td><img src="'.$src[1].'" width="170" height="160" /></td>
					<td><img src="'.$src[2].'" width="170" height="160" /></td>
					<td><img src="'.$src[3].'" width="170" height="160" /></td> </tr>
					</table>
				    </div>';
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

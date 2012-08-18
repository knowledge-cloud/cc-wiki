<?php

/*
 * Result printer that prints query results as a gallery.
 *
 * @file MUW_Person.php
 * @ingroup MashupWiki
 *
 */
include_once (dirname(__FILE__).'/../../ccgroup/conf.php');
class MUWbiPerson extends SMWResultPrinter {
        protected $mSep=10;
	
        public function getName() {
		return wfMsg( 'muw_printername_biperson' );
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
                $bPerson="";
                $this->isHTML = true;
		$resultArray=$this->getArray($results, $outputmode);
                $bPerson.=$this->getIndexUI($resultArray,$outputmode);
                return $bPerson;
	}
       
        public function getIndexUI($results,$outputmode){
            global  $wgStylePath;
	    global  $ccHost,$ccPort,$ccSite;
            $info=$results[0];
            $countfriend=count($info['knows']);
            $countcre=count($info['create']);
            $countsupport=count($info['support']);
            $countunsp=count($info['unsupport']);
            $countpar=count($info['participate']);
            $html='<div style="width:800px;">
	<h1 style="border-bottom:1px #bcc8cf dotted;">'.$info['name'].'</h1>
	<div>
		<h4>个人信息</h4>
		<div class="basicInfo" style="background-color:#f6f6f6; overflow:hidden;_zoom:1;">
			<table>
				<tbody>
					<tr>
						<td>
			<div style="padding:5px 10px;"><img src="'.$info['avatar'].'" width="80" height="80"></div></td>
				<td>
			<div style="float:none;line-height:30px;padding:5px 10px; font-size:14px;">
				<ul>
					<li style="width:120px;">姓名：'.$info['name'].'</li>
					<li style="width:120px;">ID：'.$info['id'].'</li>
					<li style="width:120px;">来源：'.$info['source'].'</li>
				</ul>
				</div>
				</td>
				</tr>
				</tbody>
				</table>
			</div>
<p>
			<div>
<table style="border:1px #ddd solid;width:800px;">
					<tbody>
						<tr>
							<th width="20%" align="center" style="background-color:#eee;">好友名单</th>
							<td>
								<table>
									<tbody>';
									if($countfriend==1&&$info['knows']!="")
									{
										$html.='<tr><td align="center" style="border:1px #ddd solid;width:800px;"><a href="http://'.$ccHost.':'.$ccPort.'/'.$ccSite.'/index.php/'.$info['knows'].'" style="text-decoration:none;">'.$info['knows'].'</a></td></tr>';
									}
									else
									{
										for($i=0;$i< $countfriend&&$info['knows']!="";$i++){
											$html.='<tr><td align="center" style="border:1px #ddd solid;width:800px;"><a href="http://'.$ccHost.':'.$ccPort.'/'.$ccSite.'/index.php/'.$info['knows'][$i].'" style="text-decoration:none;">'.$info['knows'][$i].'</a></td></tr>';
									}
								}
								$html.='
									</tbody>
								</table>
								</td>
						</tr>
						</tbody>
					</table>
<table style="border:1px #ddd solid;width:800px;">
					<tbody>
						<tr>
							<th width="20%" align="center" style="background-color:#eee;">创建的页面</th>
							<td>
								<table>
									<tbody>';
									if($countcre==1&&$info['create']!="")
									{
										$html.='<tr><td align="center" style="border:1px #ddd solid;width:800px;"><a href="http://'.$ccHost.':'.$ccPort.'/'.$ccSite.'/index.php/'.$info['create'].'" style="text-decoration:none;">'.$info['create'].'</a></td></tr>';
									}
									else
									{
										for($i=0;$i< $countcre&&$info['create']!="";$i++)			
										$html.='<tr><td align="center" style="border:1px #ddd solid;width:800px;"><a href="http://'.$ccHost.':'.$ccPort.'/'.$ccSite.'/index.php/'.$info['create'][$i].'" style="text-decoration:none;">'.$info['create'][$i].'</a></td></tr>';
									}
									$html.='
								</tbody>
								</table>
								</td>
						</tr>
						</tbody>
					</table>
<table style="border:1px #ddd solid;width:800px;">
					<tbody>
						<tr>
							<th width="20%" align="center" style="background-color:#eee;">参与的团购</th>
							<td>
								<table>
									<tbody>';
									if($countpar==1&&$info['participate']!="")
									{
										$html.='<tr><td align="center" style="border:1px #ddd solid;width:800px;"><a href="http://'.$ccHost.':'.$ccPort.'/'.$ccSite.'/index.php/'.$info['participate'].'" style="text-decoration:none;">'.$info['participate'].'</a></td></tr>';
									}
									else
									{
										for($i=0;$i< $countpar&&$info['participate']!="";$i++)
										$html.='<tr><td align="center" style="border:1px #ddd solid;width:800px;"><a href="http://'.$ccHost.':'.$ccPort.'/'.$ccSite.'/index.php/'.$info['participate'][$i].'" style="text-decoration:none;">'.$info['participate'][$i].'</a></td></tr>';
									}
									$html.='
								</tbody>
								</table>
								</td>
						</tr>
						</tbody>
					</table>	
<table style="border:1px #ddd solid;width:800px;">
					<tbody>
						<tr>
							<th width="20%" align="center" style="background-color:#eee;">支持的团购</th>
							<td>
								<table>
									<tbody>';
									if($countsupport==1&&$info['support']!="")
									{
										$html.='<tr><td align="center" style="border:1px #ddd solid;width:800px;"><a href="http://'.$ccHost.':'.$ccPort.'/'.$ccSite.'/index.php/'.$info['support'].'" style="text-decoration:none;">'.$info['support'].'</a></td></tr>';
									}
									else
									{
									  for($i=0;$i< $countsupport&&$info['support']!="";$i++)	
									  $html.='<tr><td align="center" style="border:1px #ddd solid;width:800px;"><a href="http://'.$ccHost.':'.$ccPort.'/'.$ccSite.'/index.php/'.$info['support'][$i].'" style="text-decoration:none;">'.$info['support'][$i].'</a></td></tr>';
							       }
								$html.='
								</tbody>
								</table>
								</td>
						</tr>
						</tbody>
					</table>
<table style="border:1px #ddd solid;width:800px;">
					<tbody>
						<tr>
							<th width="20%" align="center" style="background-color:#eee;">反对的团购</th>
							<td>
								<table>
									<tbody>';
									if($countunsp==1&&$info['unsupport']!="")
									{
                                      $html.='<tr><td align="center" style="border:1px #ddd solid;width:800px;"><a href="http://'.$ccHost.':'.$ccPort.'/'.$ccSite.'/index.php/'.$info['unsupport'].'" style="text-decoration:none;">'.$info['unsupport'].'</a></td></tr>';
									}
									else
									{
										for($i=0;$i< $countunsp&&$info['unsupport']!="";$i++)	
										$html.='<tr><td align="center" style="border:1px #ddd solid;width:800px;"><a href="http://'.$ccHost.':'.$ccPort.'/'.$ccSite.'/index.php/'.$info['unsupport'][$i].'" style="text-decoration:none;">'.$info['unsupport'][$i].'</a></td></tr>';
									}
									$html.='
								</tbody>
								</table>
								</td>
						</tr>
						</tbody>
					</table>	
</p>	
				</div>
			</div>
		
		
		</div>';
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

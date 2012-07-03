<?php
/**
 */
class SRFCcPerson extends SMWResultPrinter {
     protected $types = array( '_wpg' => 'text', '_num' => 'number', '_dat' => 'date', '_geo' => 'text', '_str' => 'text' );
     public function getName() {
		return wfMsg( 'muw_printername_ccperson' );
      }

      protected function handleParameters( array $params, $outputmode ) {
		parent::handleParameters( $params, $outputmode );
                $this->readParameters($params,$outputmode);
      }
      public function getResultText( SMWQueryResult $results, $outputmode ) {
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
                return $bSns;
	}
       
        public function getIndexUI($results,$outputmode){
            global $wgStylePath,$wgScriptPath;
            $html="";
            $page=false;

            $html.='<ul class="bd">'	;
            $imax=(count($results)>$this->mSep)==true?$this->mSep:count($results);
            for($i=0;$i<$this->mSep;$i++){ 
                    if($i<$imax){
                        $info=$results[$i];  
                        $html.= '<li><img src="'.$info['avatar'].'" width="120" height="120" class="img_b" /><br /><a href="'.$wgScriptPath.'/index.php/Person '.$info['id'].'">'.$info['name'].'</a>   Source:'.$info['source'].' </li>'; 
                    }
                }
            $html.= '</ul>';
	    $html.= '<hr />';
	    $html.= '<h1>好友</h1>';
	    $html.= '<hr />';
	    $num=count($info['knows']);
	    $html.='<table>';
	    for($i=0;$i<$num;$i++){
		$url = 'http://10.214.0.147/ccwiki/api.php?action=query&prop=revisions&rvprop=content&format=xml&titles='.$info['knows'][$i];
		$doc = new DOMDocument();
                $doc->load( $url );
		$pic = $doc->getElementsByTagName('rev');
		$picture = $pic->item(0)->nodeValue;
		$tmp = explode("avatar::",$picture);
		$picture = $tmp[1];
		$tmp2 = explode("jpg",$picture);
		$picture = $tmp2[0].'jpg';
		if($i%8==0)
			$html.='<tr>';
		$html.='<td><img src="'.$picture.'" width="40" height="40" class="img_b" /><br /><a href="'.$wgScriptPath.'/index.php/'.$info['knows'][$i].'">'.$info['knows'][$i].'</a></td>';
		if($i%8==7)
			$html.='</tr>';
		if($i==($num-1) && $i%8!=7)
			$html.='</tr>';
	    }
	    $html.='</table>';
	    $html.= '<hr />';
	    $html.= '<h1>感兴趣的页面</h1>';
    	    $html.= '<hr />';
            $num=count($info['interested']);
            $html.='<table>';
            for($i=0;$i<$num;$i++){
                $url = 'http://10.214.0.147/ccwiki/api.php?action=query&prop=revisions&rvprop=content&format=xml&titles='.$info['interested'][$i];
                $doc = new DOMDocument();
                $doc->load( $url );
                $pic = $doc->getElementsByTagName('rev');
                $picture = $pic->item(0)->nodeValue;
                $tmp = explode("picture::",$picture);
                $picture = $tmp[1];
                $tmp2 = explode("jpg",$picture);
                $picture = $tmp2[0].'jpg';
                if($i%4==0)
                        $html.='<tr>';
                $html.='<td><img src="'.$picture.'" width="220" height="138" class="img_b" /><br /><a href="'.$wgScriptPath.'/index.php/'.$info['interested'][$i].'">'.$info['interested'][$i].'</a></td>';
                if($i%4==3)
                        $html.='</tr>';
                if($i==($num-1) && $i%4!=3)
                        $html.='</tr>';
            }
            $html.='</table>';
	    $html.= '<hr />';
	    $html.= '<h1>参加的页面</h1>';
 	    $html.= '<hr />';
            $num=count($info['participated']);
            $html.='<table>';
            for($i=0;$i<$num;$i++){
                $url = 'http://10.214.0.147/ccwiki/api.php?action=query&prop=revisions&rvprop=content&format=xml&titles='.$info['participated'][$i];
                $doc = new DOMDocument();
                $doc->load( $url );
                $pic = $doc->getElementsByTagName('rev');
                $picture = $pic->item(0)->nodeValue;
                $tmp = explode("picture::",$picture);
                $picture = $tmp[1];
                $tmp2 = explode("jpg",$picture);
                $picture = $tmp2[0].'jpg';
                if($i%4==0)
                        $html.='<tr>';
                $html.='<td><img src="'.$picture.'" width="220" height="138" class="img_b" /><br /><a href="'.$wgScriptPath.'/index.php/'.$info['participated'][$i].'">'.$info['participated'][$i].'</a></td>';
                if($i%4==3)
                        $html.='</tr>';
                if($i==($num-1) && $i%4!=3)
                        $html.='</tr>';
            }
            $html.='</table>';
   	    $html.= '<hr />';
	    $html.= '<h1>创建的页面</h1>';
	    $html.= '<hr />';
	    $num=count($info['create']);
	    $html.='<table>';
	    for($i=0;$i<$num;$i++){
		if($i%8==0)
                        $html.='<tr>';
		if($i%2==0)
	                $html.='<td bgcolor="pink"><a href="'.$wgScriptPath.'/index.php/'.$info['create'][$i].'">'.$info['create'][$i].'</a></td>';
		else
			$html.='<td><a href="'.$wgScriptPath.'/index.php/'.$info['create'][$i].'">'.$info['create'][$i].'</a></td>';
                if($i%8==7)
                        $html.='</tr>';
                if($i==($num-1) && $i%8!=7)
                        $html.='</tr>';
	    }
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

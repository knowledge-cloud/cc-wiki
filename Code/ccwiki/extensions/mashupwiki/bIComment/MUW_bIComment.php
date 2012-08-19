<?php

/*
 * Result printer that prints query results as a gallery.
 *
 * @file MUW_Comment.php
 * @ingroup MashupWiki
 *
 */
class MUWbiComment extends SMWResultPrinter {
        protected $mSep=10;
       /* 
        static public function addJavascriptAndCSS() {
                global $wgOut,$srfgScriptPath;		
                $wgOut->addStyle($srfgScriptPath."/js/comment.css");
	}
*/
	
        public function getName() {
		return wfMsg( 'muw_printername_bicomment' );
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
                $bComment="";
               // self::addJavascriptAndCSS();
                $this->isHTML = true;
		$resultArray=$this->getArray($results, $outputmode);
                $bComment.=$this->getIndexUI($resultArray,$outputmode);
                return $bComment;
	}
       
        public function getIndexUI($results,$outputmode){
            global  $wgStylePath;
            $page=false;
            $html='<div class="commentbox"><textarea id="CommentContent" class="text750"></textarea><p>评分：<select id="CommentScore"><option value="1">1.0</option><option value="2">2.0</option><option value="3">3.0</option><option value="4">4.0</option><option value="5">5.0</option></select> <input type="image" src="'.$wgStylePath.'/ccwiki/images/btn_comment.png" onclick="postComment()"></p></div>';

            if(count($results)==1 && is_array($results[0]['avatar'])){
                $info=$results[0];  
                if(count($info['avatar'])>$this->mSep)
                    $page=true;
                $imax=$page==true?$this->mSep:count($info['avatar']);
                for($i=0;$i<$this->mSep;$i++){ 
                    if($i<$imax){
                        $html.= '<div class="commentlist">
												<div class="ffl">
												<p><img src="'.$info['avatar'][$i].'" width="48" height="48" class="img_b2" /><br>'.$info['name'][$i].'</p>
                        </div>
                        <div class="ffr">
                        <p>'.$info['content'][$i].'</p>
                        <span><p class="red">分数：'.$info['score'][$i].'</p><br>'.$info['publishedTime'][$i].'</span>
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
                        $html.= '<div class="commentlist">
												<div class="ffl">
												<p><img src="'.$info['avatar'].'" width="48" height="48" class="img_b2" /><br>'.$info['name'].'</p>
                        </div>
                        <div class="ffr">
                        <p>'.$info['content'].'</p>
                        <span><p class="red">分数：'.$info['score'].'</p><br>'.$info['publishedTime'].'</span>
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

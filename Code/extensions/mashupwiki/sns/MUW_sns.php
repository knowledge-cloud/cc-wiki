<?php

/**
 * Result printer that prints query results as a gallery.
 *
 * @file MUW_biGallery.php
 * @ingroup MashupWiki
 *
 * @author sling ma
 */
class MUWbSns extends SMWResultPrinter {
     protected $types = array( '_wpg' => 'text', '_num' => 'number', '_dat' => 'date', '_geo' => 'text', '_str' => 'text' );
     protected   $rowsubject = false; // the wiki page value that this row is about
     protected        $valuestack = array(); // contains Property-Value pairs to characterize an Item
     protected       $addedLabel = false;
     protected       $itemstack = array(); // contains Items for the items section
	
     public function getName() {
		return wfMsg( 'muw_printername_bigallery' );
      }

	public function getResult( SMWQueryResult $results, array $params, $outputmode ) {
		$this->handleParameters( $params, $outputmode );
		return $this->getResultText( $results, SMW_OUTPUT_HTML );
	}
        protected  function getFileJson($row ,$outputmode){
            foreach ( $row as /* SMWResultArray */ $field ) {
                    $pr = $field->getPrintRequest();
                    $values = array();
                    $jsonObject = array();
            if ( $this->rowsubject === false && !$this->addedLabel ) {
						$this->valuestack[] = '"label": "' . $field->getResultSubject()->getTitle()->getFullText() . '"';
						$this->addedLabel = true;
					}
                    while ( ( $dataValue = $field->getNextDataValue() ) !== false ) {
                            switch ( $dataValue->getTypeID() ) {
                                    case '_geo':
                                            $jsonObject[] = $dataValue->getDataItem()->getCoordinateSet();
                                            $values[] = FormatJson::encode( $dataValue->getDataItem()->getCoordinateSet() );
                                            break;
                                    case '_num':
                                            $jsonObject[] = $dataValue->getDataItem()->getNumber();
                                            break;
                                    case '_dat':
                                            $jsonObject[] = 
                                                    $dataValue->getYear() . '-' .
                                                    str_pad( $dataValue->getMonth(), 2, '0', STR_PAD_LEFT ) . '-' .
                                                    str_pad( $dataValue->getDay(), 2, '0', STR_PAD_LEFT ) . ' ' .
                                                    $dataValue->getTimeString();
                                            break;
                                    default:
                                            $jsonObject[] = $dataValue->getShortText( $outputmode, null );
                            }
                    }

                    if ( !is_array( $jsonObject ) || count( $jsonObject ) > 0 ) {
                            $this->valuestack[] =
                                    '"' . str_replace( ' ', '_', strtolower( $pr->getLabel() ) ) 
                                    . '": ' . FormatJson::encode( $jsonObject ) . '';
                    }
            }				
        }
        public function getResultText( SMWQueryResult $results, $outputmode ) {
		global $wgUser, $wgParser;
                $biGallery="";
                $this->isHTML = $outputmode == SMW_OUTPUT_HTML;
		$itemstack = array(); // contains Items for the items section

                $jsonresult="";
			// generate items section
	
			//$items = "\"items\": [\t" . implode( ",\t", $itemstack ) . "\t]";
               
                $biGallery.=$this->getIndexUl($results,$outputmode);
		
                //return $biGallery;
               return array( $biGallery, 'nowiki' => true, 'isHTML' => true );
		//return $biGallery;
	}
       
        public function getIndexUl($results,$outputmode){
            global $wgServer,$wgScriptPath;
            $path=$wgScriptPath."/skins/ccwiki";
            $page=false;
            $html='<ul class="gblist">'	;
           
            /*for($i=0;$i<3;$i++){ 
                  if($subject = $results->getNext()){
                    //$this->getFileJson($subject,$outputmode);
                    $imgsrc=$this->getTextValue($subject["2"],$outputmode);
                    $href=$this->getTextValue($subject["8"],$outputmode);
                    $titledesc=$this->getTextValue($subject["10"],$outputmode);
                    $discount=$this->getTextValue($subject["6"],$outputmode);
                    $html.= '<li>'.
                    '<div class="gbtitle"><a href="#" class="cuti orange">大众点评团</a>丨<a href="'.$href.'" class="dblue">'.$titledesc.'</a></div>'.
                    '<div class="gbpic"><a href="'.$href.'"><img src="'.$imgsrc.'" width="220" height="138" class="img_b3" /></a></div>'.
                    '<div class="gbrebate">原价：<em>￥480</em> <span>'.$discount.'折</span></div>'.
                    '<div class="gbprice"> <em>48</em> <input name="" type="button" value="去看看" class="btn_qkk" /></div>'.
                    '</li>'; 
                }else{
                    $html.= '<li>'.
                    '<div class="gbtitle"><a href="#" class="cuti orange">大众点评团</a>丨<a href="#" class="dblue">灶丰年间!仅售78元,价值177元2-3人午市套餐!阳光庭院,小径花香,旧时</a></div>'.
                    '<div class="gbpic"><a href="#"><img src="images/photo3.jpg" width="220" height="138" class="img_b3" /></a></div>'.
                    '<div class="gbrebate">原价：<em>￥480</em> <span>1折</span></div>'.
                    '<div class="gbprice"> <em>48</em> <input name="" type="button" value="去看看" class="btn_qkk" /></div>'.
                    '</li>'; 
                }
            }
            
            $html.= '</ul>';
            while($subject = $results->getNext() ){
                $this->getFileJson($subject, $valuestack, $rowsubject, $addedLabel,$outputmode);
                $page=true;
            }
            if ($this->rowsubject !== false ) { // stuff in the page URI and some category data
                $this->valuestack[] = '"uri" : "' . $wgServer . $wgScriptPath . '/index.php?title=' . $this->rowsubject->getPrefixedText() . '"';
                $page_cats = smwfGetStore()->getPropertyValues( $this->rowsubject, new SMWDIProperty( '_INST' ) ); // TODO: set limit to 1 here

                if ( count( $page_cats ) > 0 ) {
                        $this->valuestack[] = '"type" : "' . reset($page_cats)->getShortHTMLText() . '"';
                }
            }
            $this->itemstack[] = "\t{\t\t\t" . implode( ",\t\t\t", $this->valuestack ) . "\t\t}";
            if($page==true){
                $html='<div class="fanye">'.
                    '<span class="back">上一页</span> <span class="curr">1</span> <a href="#" hidefocus="true">2</a> <a href="#" hidefocus="true">3</a> <a href="#" hidefocus="true">4</a> <a href="#" hidefocus="true">5</a> <a href="#" class="next" hidefocus="true">下一页</a>'.
                '</div>';
            }
            $jsonresult = "{" .$items . "}";
            $html.="<script>var item= $jsonresult</script>";     */
            $html='<div class="fl">
                <ul class="builder">
                <li class="title">创建者：陈交彦</li>
                <li class="ta-c"><img src="' . $wgServer . $path . 'images/photo.jpg" width="120" height="120" class="img_b3" /></li>
                <li>邮件地址：</li>
                <li>yshs20112011@163.com</li>
                </ul>
                <div class="participant">
                <div class="hd"><p>参与者：</p><input name="" type="button" value="邀请好友" class="invite"/></div>
                <ul class="bd">
                <li><img src="' . $wgServer . $path . '/images/photo2.jpg" width="40" height="40" class="img_b" /><br /><a href="#">好同学</a></li>
                <li><img src="' . $wgServer . $path . '/images/photo2.jpg" width="40" height="40" class="img_b" /><br /><a href="#">好同学</a></li>
                <li><img src="' . $wgServer . $path . '/images/photo2.jpg" width="40" height="40" class="img_b" /><br /><a href="#">好同学</a></li>
                <li><img src="' . $wgServer . $path . '/images/photo2.jpg" width="40" height="40" class="img_b" /><br /><a href="#">好同学</a></li>
                <li><img src="' . $wgServer . $path . '/images/photo2.jpg" width="40" height="40" class="img_b" /><br /><a href="#">好同学</a></li>
                <li><img src="' . $wgServer . $path . '/images/photo2.jpg" width="40" height="40" class="img_b" /><br /><a href="#">好同学</a></li>
                <li><img src="' . $wgServer . $path . '/images/photo2.jpg" width="40" height="40" class="img_b" /><br /><a href="#">好同学</a></li>
                <li><img src="' . $wgServer . $path . '/images/photo2.jpg" width="40" height="40" class="img_b" /><br /><a href="#">好同学</a></li>
                <li><img src="' . $wgServer . $path . '/images/photo2.jpg" width="40" height="40" class="img_b" /><br /><a href="#">好同学</a></li>
                </ul>
                <div class="more"><a href="#" class=" blue_xh">更多>></a></div>
                </div>
                <div class="interestor">
                <div class="hd"><p>感兴趣的人：</p></div>
                <ul class="bd">
                <li><img src="' . $wgServer . $path . '/images/photo2.jpg" width="40" height="40" class="img_b" /><br /><a href="#">好同学</a></li>
                <li><img src="' . $wgServer . $path . '/images/photo2.jpg" width="40" height="40" class="img_b" /><br /><a href="#">好同学</a></li>
                <li><img src="' . $wgServer . $path . '/images/photo2.jpg" width="40" height="40" class="img_b" /><br /><a href="#">好同学</a></li>
                <li><img src="' . $wgServer . $path . '/images/photo2.jpg" width="40" height="40" class="img_b" /><br /><a href="#">好同学</a></li>
                <li><img src="' . $wgServer . $path . '/images/photo2.jpg" width="40" height="40" class="img_b" /><br /><a href="#">好同学</a></li>
                <li><img src="' . $wgServer . $path . '/images/photo2.jpg" width="40" height="40" class="img_b" /><br /><a href="#">好同学</a></li>
                <li><img src="' . $wgServer . $path . '/images/photo2.jpg" width="40" height="40" class="img_b" /><br /><a href="#">好同学</a></li>
                <li><img src="' . $wgServer . $path . '/images/photo2.jpg" width="40" height="40" class="img_b" /><br /><a href="#">好同学</a></li>
                <li><img src="' . $wgServer . $path . '/images/photo2.jpg" width="40" height="40" class="img_b" /><br /><a href="#">好同学</a></li>
                </ul>
                <div class="more"><a href="#" class=" blue_xh">更多>></a></div>
                </div>
                </div>';
            return $html;
        }
        public function getTextValue( $resultArray, $outputmode){
                $values = array();
		$isFirst = true;
		if($resultArray==null) return "";
		while ( ( $dv = $resultArray->getNextDataValue() ) !== false ) {
			
			$isSubject = $resultArray->getPrintRequest()->getMode() == SMWPrintRequest::PRINT_THIS;
			$value = ( ( $dv->getTypeID() == '_wpg' ) || ( $dv->getTypeID() == '__sin' ) ) ?
				   $dv->getLongText( $outputmode, $this->getLinker( $isSubject ) ) :
				   $dv->getShortText( $outputmode, $this->getLinker( $isSubject ) );
			$values[] =  $value;
		}
		
		return implode( '<br />', $values );   
        }
        
        
}
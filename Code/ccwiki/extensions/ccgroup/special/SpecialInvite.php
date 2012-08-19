<?php
//set_include_path(dirname(__FILE__));
include_once ( dirname(__FILE__).'/../conf.php');
include_once ( dirname(__FILE__).'/../includes/snsGetFriends.php');
include_once ( dirname(__FILE__).'/../includes/snsEditPage.php');

class SpecialInvite extends SpecialPage {

        function __construct() {
                parent::__construct( 'Invite' );
        }
        function execute( $par ) {
            global $wgOut, $wgRequest, $ccHost, $ccPort, $ccWiki;
			$this->setHeaders();
			$wgOut->addHTML( $this->makeForm() );
			$this->sns=$wgRequest->getText( 'sns' );		
			$this->token=$wgRequest->getText( 'access_token' );
			$this->openid="";
			if($this->sns=="qqweibo")
				$this->openid=$wgRequest->getText( 'openid' );
			if($this->token != ''){
				$friends = $this->getFriends($this->token,$this->sns,$this->openid);
			$wgOut->addHTML( $this->getFriendsHtml($friends,$this->sns,$this->token,$this->openid) );
			//asynchronously import friends
			$url='http://'.$ccHost.':'.$ccPort.'/'.$ccWiki.'/includes/snsImportFriends.php?sns='.$this->sns.'&access_token='.$this->token.'&openid='.$this->openid;
                	$ch = curl_init($url);//打开
                	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                	curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
                	curl_exec($ch);
                	curl_close($ch);//关闭
		}
        }

        private function makeForm() {
                global $ccHost, $ccPort, $ccWiki;
                $title = self::getTitleFor( 'Invite' );
                $form = '<fieldset><legend>' . wfMsgHtml( 'Invite' ) . '</legend>';
		//login Renren
                $form .= '点击登录，邀请好友：<br /><a href="http://' .$ccHost. ':' .$ccPort. '/' .$ccWiki. '/includes/snsInterface.php?type=invite&sns=renren"><input type="image" height="60" width="140" src="http://'.$ccHost.':'.$ccPort.'/'.$ccWiki.'/special/images/renren_logo.jpg" /></a> &nbsp &nbsp';
		//login Kaixin
		        $form .= '<a href="http://' .$ccHost. ':' .$ccPort. '/' .$ccWiki. '/includes/snsInterface.php?type=invite&sns=kaixin"><input type="image" height="50" width="140" src="http://'.$ccHost.':'.$ccPort.'/'.$ccWiki.'/special/images/kaixin_logo.jpg" /></a> &nbsp &nbsp';
		//login Tencen
	      	    $form .= '<a href="http://' .$ccHost. ':' .$ccPort. '/' .$ccWiki. '/includes/snsInterface.php?type=invite&sns=qqweibo"><input type="image" height="50" width="140" src="http://'.$ccHost.':'.$ccPort.'/'.$ccWiki.'/special/images/qqweibo_logo.gif" /></a> &nbsp &nbsp';
                $form .= '</fieldset>';
                return $form;
        }

	private function getFriends($token,$sns,$openid){
		switch($sns){
			case 'renren':
				$friends = getRenrenFriends($token);
				break;
			case 'kaixin':
				$friends = getKaixinFriends($token);
				break;
			case 'qqweibo':
				$friends = getQqweiboFriends($token, $openid);
				break;
		}
		return $friends;
	}

	private function getFriendsHtml( $friends,$sns,$token,$openid ){
		global $ccHost, $ccPort, $ccWiki;
		$count = 0;
		$tag = true;
		$sendURL = 'http://' .$ccHost. ':' .$ccPort. '/' .$ccWiki. '/includes/sendMessage.php';
		if(count($friends)==0)
			return "<h2>&nbsp&nbsp对不起，该sns账号无好友！</h2>";
		$form = Xml::openElement(  'form', array( 'method' => 'get', 'action' => $sendURL ));
                $form .= Xml::openElement( 'table', array( 'border' => '0', 'cellpadding' => '20', 'id' => 'table1' ));
                $form .= '<tbody id="table2">';
		foreach ( $friends as $friend ){
             $name = $friend['name'];
             $photo = $friend['avatar'];
			 $id = $friend['sns_id'];
			 if(($count%6)==0){
				$tag = false;
				$form .= '<tr>';
			 }
			$form .= '<td><img src="'.$photo.'" /><br /><input type="checkbox" name="friends[]" id="friends" value="'.$id.'">'.$name.'</input></td>';
			if(($count%6)==5){
				$form .= '</tr>';
				$count = -1;
				$tag = true;
			}
			$count++; 
		}
		if($tag==false)
		$form .= '</tr>';
		$form .= '</tbody>';
        $form .= Xml::closeElement('table');
        $form .= '<span id="spanFirst">First</span> &nbsp <span id="spanPre">Pre</span> &nbsp <span id="spanNext">Next</span> &nbsp ';
        $form .= '<span id="spanLast">Last</span> &nbsp Page<span id="spanPageNum"></span>/Total<span id="spanTotalPage"></span>pages<br /><br />';
		$form .= 'Message:<input type="text" name="message" size="64" />';//Xml::inputLabel( 'Message', 'message', 'message', 64, $this->message);
		$form .= '<input type="hidden" name="token" value="' . $token . '" />';
		$form .= '<input type="hidden" name="openid" value="' . $openid . '" />';
		$form .= '<input type="hidden" name="sns" value="' . $sns . '" />';
		$form .= Xml::SubmitButton( 'Invite' );
		$form .= Xml::closeElement( 'form' );
		$form .= $this->fenye();
		return $form;
	}

	private function fenye() {
		$out = '<script>
		var theTable = document.getElementById("table2");
		var totalPage = document.getElementById("spanTotalPage");
		var pageNum = document.getElementById("spanPageNum");
 
		var spanPre = document.getElementById("spanPre");
		var spanNext = document.getElementById("spanNext");
		var spanFirst = document.getElementById("spanFirst");
		var spanLast = document.getElementById("spanLast");

		var numberRowsInTable = theTable.rows.length;
		var pageSize = 3;
		var page = 1;


		function next() {

		hideTable();
    
		currentRow = pageSize * page;
		maxRow = currentRow + pageSize;
		if ( maxRow > numberRowsInTable ) maxRow = numberRowsInTable;
		for ( var i = currentRow; i< maxRow; i++ ) {
        	theTable.rows[i].style.display = "";
    		}
	        page++;

		if ( maxRow == numberRowsInTable )  { nextText(); lastText(); }
    		showPage();    
		preLink();
		firstLink();
		}

		function pre() {

	        hideTable();
    
		    page--;
    
		    currentRow = pageSize * page;
		    maxRow = currentRow - pageSize;
		    if ( currentRow > numberRowsInTable ) currentRow = numberRowsInTable;
		    for ( var i = maxRow; i< currentRow; i++ ) {
		        theTable.rows[i].style.display = "";
		    }
    
    
		    if ( maxRow == 0 ) { preText(); firstText(); }
		    showPage();
		    nextLink();
		    lastLink();
		}
		
		function first() {
		    hideTable();
		    page = 1;
		    for ( var i = 0; i<pageSize; i++ ) {
		        theTable.rows[i].style.display = "";
		    }
		    showPage();
		    
		    preText();
		    nextLink();	
		    lastLink();
		}

		function last() {
		    hideTable();
		    page = pageCount();
		    currentRow = pageSize * (page - 1);
		    for ( var i = currentRow; i<numberRowsInTable; i++ ) {
		        theTable.rows[i].style.display = "";
		    }
		    showPage();
    	
		    preLink();
		    nextText();
		    firstLink();
		}
		
		function hideTable() {
		    for ( var i = 0; i<numberRowsInTable; i++ ) {
		        theTable.rows[i].style.display = "none";
		    }
		}
	
		function showPage() {
		    pageNum.innerHTML = page;
		}
		
		function pageCount() {
		    var count = 0;
		    if ( numberRowsInTable%pageSize != 0 ) count = 1; 
		    return parseInt(numberRowsInTable/pageSize) + count;
		}
		
		function preLink() { spanPre.innerHTML = "<a href=\"javascript:pre();\">Pre</a>"; }
		function preText() { spanPre.innerHTML = "Pre"; }
		
		function nextLink() { spanNext.innerHTML = "<a href=\"javascript:next();\">Next</a>"; }
		function nextText() { spanNext.innerHTML = "Next"; }
		
		function firstLink() { spanFirst.innerHTML = "<a href=\"javascript:first();\">First</a>"; }
		function firstText() { spanFirst.innerHTML = "First"; }
		
		function lastLink() { spanLast.innerHTML = "<a href=\"javascript:last();\">Last</a>"; }
		function lastText() { spanLast.innerHTML = "Last"; }
		
		function hide() {
		    for ( var i = pageSize; i<numberRowsInTable; i++ ) {
		        theTable.rows[i].style.display = "none";
		    }
		 
		    totalPage.innerHTML = pageCount();
		pageNum.innerHTML = "1";
    
		    nextLink();
		    lastLink();
		}

		hide();
		</script>';
		return $out; 
        }
}
?>

<?php
/**
 * MonoBook nouveau
 *
 * Translated from gwicke's previous TAL template version to remove
 * dependency on PHPTAL.
 *
 * @todo document
 * @file
 * @ingroup Skins
 */

if( !defined( 'MEDIAWIKI' ) )
	die( -1 );

/**
 * Inherit main code from SkinTemplate, set the CSS and template filter.
 * @todo document
 * @ingroup Skins
 */
class SkinCcWiki extends SkinTemplate {
	/** Using monobook. */
	var $skinname = 'ccwiki', $stylename = 'ccwiki',
		$template = 'CcWikiTemplate',$useHeadElement = true,
                $skinfullpath,$allpages,$invite,$alltemplatepages;
        
        function initPage(OutputPage $out) {
                global $wgStylePath;
                parent::initPage($out);
		$this->skinfullpath = $wgStylePath."/".$this->skinname;
	}
	function setupSkinUserCss( OutputPage $out ) {
		global $wgHandheldStyle;

		parent::setupSkinUserCss( $out );
		// Append to the default screen common & print styles...
		$out->addStyle( 'ccwiki/css/common.css', 'screen' );
                $out->addStyle( 'ccwiki/css/style.css', 'screen' );
		if( $wgHandheldStyle ) {
			// Currently in testing... try 'chick/main.css'
			$out->addStyle( $wgHandheldStyle, 'handheld' );
		}
	}
}

/**
 * @todo document
 * @ingroup Skins
 */
class CcWikiTemplate extends QuickTemplate {
	var $skin;
        var $menu=Array("/Special:Search?id=7"    => "购物",
                        "/Special:Search?id=6"    => "餐饮",
                        "/Special:Search?id=8"    => "旅游",
                        "/Special:Search?id=2"    => "娱乐",
                        "/Special:Search?id=5"    => "电影",
                        "/Special:Search?id=3"    => "健康",
                        "/Special:Search?id=4"    => "生活",
                        "/Special:Search?id=1"    => "美容",
                        "/Special:Search"    => "所有");
	/**
	 * Template filter callback for MonoBook skin.
	 * Takes an associative array of data set from a SkinTemplate-based
	 * class, and a wrapper for MediaWiki's localization database, and
	 * outputs a formatted page.
	 *
	 * @access private
	 */
        function listmenu($isindex,$indexhref){
            $curr="";
            if($isindex)
                $curr='class="curr"';
            $menuhtml='<li '.$curr.'><a href="'.$indexhref.'">首页</a></li>';
            foreach ($this->menu as $url=>$title) {
                $menuhtml.='<li><a href="'.substr($indexhref, 0,strlen($indexhref)-10).$url.'">'.$title.'</a></li>';
            }
            return $menuhtml;
        }
	function execute() {
		global $wgRequest,$IP;
                
                $isindex=false;$islogin=false;
		$this->skin = $skin = $this->data['skin'];
                $skinpath=$this->skin->skinfullpath."/";
		$includepath=$this->skin->skinname."/";
		//$action = $wgRequest->getText( 'action' );
                $isindex=$this->skin->mTitle->mArticleID=="1";
                $menuhtml=$this->listmenu($isindex,htmlspecialchars($this->data['nav_urls']['mainpage']['href']));
		$this->html( 'headelement' );
                if($this->data['loggedin']){
                    $item=$this->data['personal_urls']['logout'];
                    $islogin=true;
                }else{
                    if(!empty($this->data['personal_urls']['login']))
                        $item=$this->data['personal_urls']['login'];
                    else
                        $item=$this->data['personal_urls']['anonlogin'];
                }
                 include_once ($includepath."top.php");
                // echo $isindex; exit;
                 if($isindex)
                     include_once ($includepath."index.php");
                 $this->html('bodytext');
                         
                include_once ($includepath."footer.php");
                     
                wfRestoreWarnings();
	} // end of execute() method
} // end of class

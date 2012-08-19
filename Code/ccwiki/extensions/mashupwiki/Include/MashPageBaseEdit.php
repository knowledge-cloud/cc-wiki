<?php
/*
 * 管理 CCWIKI 页面的 SouceCode 包括 创建， 配置
 */

require_once dirname( __FILE__ ) .'/WikiPageImport.php';
require_once dirname( __FILE__ ) .'/PageTemplate.php';

class MashPageBaseEdit{
    protected  $PageCategory="";
    protected  $PageTemplage="";
    protected  $PageName="";
    protected  $PageKeywords="";
    protected  $CreatePerson="";
    protected  $Participant="";
    protected  $Interested="";
    protected  $Orgpagecontent="";
    protected  $Dealwhere="";
    protected  $wpImport;
    public function createPage($category,$keywords,$createdatapage,$dealAskWhere){
        $this->PageCategory=$category;
        $this->PageKeywords=$keywords;
        $this->CreatePerson=$createdatapage;
	$this->Dealwhere=$dealAskWhere;
//	echo 'category: '.$category.'<br/>';
//	echo 'keywords: '.$keywords.'<br/>';
//	echo 'createdatapage: '.$createdatapage.'<br/>';
//	echo 'dealAskWhere: '.$dealAskWhere.'<br/>';
        return $this->savePage();
    }

    public function writeToPage(){
		return $this->savePage();
	}

   //Put the original head information and the configured information to the local variables 
    public function configPage($mode,$args){
        $this->readHead();
        switch ($mode){
            case "Deal":
       		$this->Dealwhere=$args;
                break;
        }
    }
    

    public function queryDetailSub(){
	$tmpPageName = "Detailview " .  $this->PageName;
        if($this->wpImport->checkPage($tmpPageName)==-1)
		return false;
	else
		return true;
    	
    }
    
    public function createSub($type){
        global $CCPageAskQuery;
        $subpagename="";
        $pagecode="";
        switch ($type){
            case "particip":
                $pagecode=$CCPageAskQuery["Participant"];
                $subpagename="participview ";
                $pagecode= preg_replace("|{\|(\w+)\|}|",$this->PageName,$pagecode);
        	$pagecode.="[[Category:ParticipSubpage]]";
                break;
            case "aboutgb":
                $pagecode=$CCPageAskQuery["DealDetial"];
                $subpagename="detailview ";
                $pagecode= preg_replace("|{\|(\w+)\|}|","[[".$this->PageName."]]",$pagecode);
        	$pagecode.="[[Category:DetailSubpage]]";
                break;
            case "weibo":
                $pagecode=$CCPageAskQuery["Weibo"];
                $subpagename="Weiboview_";
                $pagecode= preg_replace("|{\|(\w+)\|}|",$this->PageName,$pagecode);
        	$pagecode.="[[Category:WeiboSubpage]]";
                break;
            case "taobao":
                $pagecode=$CCPageAskQuery["Taobao"];
                $subpagename="Taobaoview_";
                $pagecode= preg_replace("|{\|(\w+)\|}|",$this->PageName,$pagecode);
        	$pagecode.="[[Category:TaobaoSubpage]]";
                break;
            case "comment":
                $pagecode=$CCPageAskQuery["Comment"];
                $subpagename="Commentview_";
                $pagecode= preg_replace("|{\|(\w+)\|}|",$this->PageName,$pagecode);
        	$pagecode.="[[Category:CommentSubpage]]";
                break;
            case "jiepang":
                $pagecode=$CCPageAskQuery["Jiepang"];
                $subpagename="Jiepangview_";
                $pagecode= preg_replace("|{\|(\w+)\|}|",$this->PageName,$pagecode);
        	$pagecode.="[[Category:JiepangSubpage]]";
                break;
	    default:
		return false;
		break;
        }
        $subpagename.=$this->PageName;
        return $this->wpImport->savePage($subpagename,$pagecode);
    }
    
    //解析老的SouceCode, put the head information, like PageKeywords, to the local variables
    private function readHead(){
        $orgpagecontent =  $this->wpImport->getPageResouceCode($this->PageName);
        preg_match_all("'<!--(\w+)=(\w+)-->'e",$orgpagecontent,$arr);
        for ($i=0; $i< count($arr[0]); $i++) 
        {
            switch ($arr[1][$i]) {
                case "KeyWords":
                    $this->PageKeywords=$arr[2][$i];
                    break;
                case "Category":
                    $this->PageCategory=$arr[2][$i];
                    break;
                case "PageName":
                    $this->PageName=$arr[2][$i];
                    break;
                case "CreatePage":
                    $this->CreatePerson=$arr[2][$i];
                    break;
                case "DealWhere":
                    $this->Dealwhere=$arr[2][$i];
                    break;
                default:
                    break;
            }
        }   
    }
    
    private function getValue($c){
        $returnstr="";
        if(count($c)>1){
            switch ($c[1]) {
                case "PageName":
                     $returnstr= $this->PageName;
                    break;
                case "PageWhere":
                    $returnstr= "[[".$this->PageName."]]";
                    break;
                case "CreatePage":
                     $returnstr= $this->CreatePerson;
                    break;
                case "CreateWhere":
                    $returnstr= "[[".$this->CreatePerson."]]";
                    break;
                case "DealWhere":
                    $returnstr= $this->Dealwhere;
                        break;
                case "KeyWords":
                    $returnstr= $this->PageKeywords;
                    break;
                 case "Category":
                    $returnstr= $this->PageCategory;
                    break;
                default:
                    $returnstr="";
                    break;
            }
        }
        return $returnstr;
    }
    
    private function getPageValue($c,$Arr){
        if(array_key_exists($c, $Arr))
           return $Arr[$c];
        else  
           return preg_replace("'{{{(\w+)}}}'e","\\1",$this->getValue(Array(1,$c)));
    }
    
    private function getPageContent(){
        global $CCPageTemplate,$CCPageAskQuery;
        $this->PageTemplage=$CCPageTemplate[$this->PageCategory];
        $PageDate= preg_replace_callback("|{\|(\w+)\|}|",array( &$this, 'getValue'),$CCPageAskQuery);
        if($this->Dealwhere==""){
            $PageDate["Deal"]="";
            $PageDate["DealDetial"]="";
        }
        return preg_replace ("'{\|(\w+)\|}'e","\$this->getPageValue('\\1',\$PageDate)",$this->PageTemplage);
    }
    
    function savePage(){
	$content = $this->getPageContent();
        return $this->wpImport->savePage($this->PageName,$content);
    }
    
    function __construct($pagetitle){
        $this->PageName=$pagetitle;
        $this->wpImport=new WikiPageImport();
    }
}

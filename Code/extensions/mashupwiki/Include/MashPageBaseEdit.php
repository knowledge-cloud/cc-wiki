<?php
/*
 * 管理 CCWIKI 页面的 SouceCode 包括 创建， 配置
 */

/**
 * @author maguibo
 */
//require_once dirname( __FILE__ ) .'/../config.php';
require_once dirname( __FILE__ ) .'/IMashPageEdit.php';
require_once dirname( __FILE__ ) .'/WikiPageImport.php';
require_once dirname( __FILE__ ) .'/PageTemplate.php';

class MashPageBaseEdit implements IMashPageEdit {
    protected  $PageCateory="";
    protected  $PageTemplage="";
    protected  $PageName="";
    protected  $PageKeywords="";
    protected  $CreatePerson="";
    protected  $Participant="";
    protected  $Interested="";
    protected  $Orgpagecontent="";
    protected  $OperartEdited=false;
    protected  $Dealwhere="";
    protected  $wpImport;
    public function createPage($cate,$keyword,$createpage){
        $this->PageCateory=$cate;
        $this->PageKeywords=$keyword;
        $this->CreatePerson=$createpage;
        return $this->savePage();
    }

   //Put the original head information and the configured information to the local variables 
    public function configPage($mode,$args){
        if($this->OperartEdited==false){
            $this->OperartEdited=true;
            $this->getPageResouceCode();
        }
        switch ($mode){
            case "Deal":
                $this->configDeal($args);
                break;
        }
        
    }
    
    public function configFinish(){
        return $this->savePage();
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
            case "particip";
                $pagecode=$CCPageAskQuery["Participant"];
                $subpagename="participview ";
                $pagecode= preg_replace("|{\|(\w+)\|}|",$this->PageName,$pagecode);
                break;
            case "interep";
                $pagecode=$CCPageAskQuery["Interested"];
                $subpagename="interepview ";
                $pagecode= preg_replace("|{\|(\w+)\|}|",$this->PageName,$pagecode);
                break;
            case "aboutgb";
                $pagecode=$CCPageAskQuery["DealDetial"];
                $subpagename="detailview ";
                $pagecode= preg_replace("|{\|(\w+)\|}|","[[".$this->PageName."]]",$pagecode);
                break;
        }
        $pagecode.="[[Category:Subpage]]";
        $subpagename.=$this->PageName;
        if($pagecode=="")
		return false;
        return $this->wpImport->savaPage($subpagename,$pagecode);
    }
    
    private function configDeal($askcondition){
       $this->Dealwhere=$askcondition;
    }
    //解析老的SouceCode, put the head information, like PageKeywords, to the local variables
    private function getPageResouceCode(){
        $orgpagecontent =  $this->wpImport->getPageResouceCode($this->PageName);
        preg_match_all("'<!--(\w+)=(\w+)-->'e",$orgpagecontent,$arr);
        for ($i=0; $i< count($arr[0]); $i++) 
        {
            switch ($arr[1][$i]) {
                case "KeyWords":
                    $this->PageKeywords=$arr[2][$i];
                    break;
                case "Cateory":
                    $this->PageCateory=$arr[2][$i];
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
                 case "Cateory":
                    $returnstr= $this->PageCateory;
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
        $this->PageTemplage=$CCPageTemplate[$this->PageCateory];
        $PageDate= preg_replace_callback("|{\|(\w+)\|}|",array( &$this, 'getValue'),$CCPageAskQuery);
        if($this->Dealwhere==""){
            $PageDate["Deal"]="";
            $PageDate["DealDetial"]="";
            $PageDate["DealMap"]="";
        }
        return preg_replace ("'{\|(\w+)\|}'e","\$this->getPageValue('\\1',\$PageDate)",$this->PageTemplage);
    }
    
    function savePage(){
        return $this->wpImport->savaPage($this->PageName,$this->getPageContent());
    }
    
    function __construct($pagetitle){
        $this->PageName=$pagetitle;
        $this->wpImport=new WikiPageImport();
    }
}

<?php
include_once (dirname(__FILE__).'/../conf.php');
//签名函数
function createSign ($paramArr) {
     global $taobaoSecret;
     $sign = $taobaoSecret;
     ksort($paramArr);
     foreach ($paramArr as $key => $val) {
         if ($key != '' && $val != '') {
             $sign .= $key.$val;
         }
     }
     $sign.=$taobaoSecret;
     $sign = strtoupper(md5($sign));
     return $sign;
}
//组参函数
function createStrParam ($paramArr) {
     $strParam = '';
     foreach ($paramArr as $key => $val) {
     if ($key != '' && $val != '') {
             $strParam .= $key.'='.urlencode($val).'&';
         }
     }
     return $strParam;
}
function TaobaokeItemsGet($keyword)
{
    //参数数组
    global $taobaoKey,$taobaoNick;
    //echo $taobaoKey.$taobaoNick;
    $paramArr = array(
      'app_key' => '21030823',
      'method' => 'taobao.taobaoke.items.get',
      'format' => 'json',
      'v' => '2.0',
      'sign_method'=>'md5',
      'timestamp' => date('Y-m-d H:i:s'),
      'fields' => 'num_iid,title,price,seller_credit_score,click_url,pic_url,volume',
      'nick' => 'ief菲菲',
      'keyword' => $keyword
      );
      
    //生成签名
    $sign = createSign($paramArr);
    
    //组织参数
    $strParam = createStrParam($paramArr);
    $strParam .= 'sign='.$sign;
    
    //访问服务
    $url = 'http://gw.api.taobao.com/router/rest?'.$strParam; //正式环境调用地址
    $result = file_get_contents($url);
    $result = json_decode($result);
    
    return $result;
}
?>
<?php
include_once (dirname(__FILE__).'/TaobaoTaobaokeItemsGet.php');
include_once (dirname(__FILE__).'/../conf.php');
function getTaobaoDeal($key)
{
    $result = TaobaokeItemsGet($key);
    
    $all = array();
    $num = $result->taobaoke_items_get_response->total_results;
    //echo 'before: '.$num;
    if($num == 0)
        return null;        
    else
        $num = ($num>6)?6:$num;
    
    //echo 'after: '.$num;
    for($i = 0;$i < $num;$i = $i+1)
    {
        $deal = array();
        
        $num_iid = $result->taobaoke_items_get_response->taobaoke_items->taobaoke_item[$i]->num_iid;
        $deal['TaobaoDealId'] = $num_iid;
        $title = $result->taobaoke_items_get_response->taobaoke_items->taobaoke_item[$i]->title;
        $deal['TaobaoDealTitle'] = $title;
        $price = $result->taobaoke_items_get_response->taobaoke_items->taobaoke_item[$i]->price;
        $deal['Price'] = $price;
        $pic_url = $result->taobaoke_items_get_response->taobaoke_items->taobaoke_item[$i]->pic_url;
        $deal['TaobaoDealPicture'] = $pic_url;
        $click_url = $result->taobaoke_items_get_response->taobaoke_items->taobaoke_item[$i]->click_url;
        $deal['TaobaoDealUrl'] = $click_url;
        $seller_credit_score = $result->taobaoke_items_get_response->taobaoke_items->taobaoke_item[$i]->seller_credit_score;
        $deal['TaobaoDealScore'] = $seller_credit_score;
        $volume = $result->taobaoke_items_get_response->taobaoke_items->taobaoke_item[$i]->volume;
        $deal['Volume'] = $volume;
        
        $all[] = $deal;
    }
    return $all;
}
?>

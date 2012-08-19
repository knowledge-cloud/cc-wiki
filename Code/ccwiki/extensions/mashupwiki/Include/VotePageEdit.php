<?php
function generateSource($pageName,$categroy,$createdatapage,$DealAskWhere)
{
$deal='{{#ask: '.$DealAskWhere.'
| ?Ontology/Title=title
| ?Ontology/Picture=picture
| ?Ontology/Id=id
| ?Ontology/Url=url
| ?Ontology/Original price=orgprice
| ?Ontology/Source=source
| ?Ontology/Present price=currprice
| ?Ontology/Dscription=desc
| ?Ontology/ValidFrom=timefrom
| ?Ontology/ValidThrough=timeend
| format=bigallery
| limit=21
| sort=Ontology/validTime
| link=subject
| order=ascending
| source=html
|}}';

$DealDetial='{{#ask: '.$DealAskWhere.'
| ?Ontology/Title=title
| ?Ontology/Picture=picture
| ?Ontology/Id=id
| ?Ontology/Url=url
| ?Ontology/Original price=orgprice
| ?Ontology/Source=source
| ?Ontology/Present price=currprice
| ?Ontology/Description=desc
| ?Ontology/ValidFrom=timefrom
| ?Ontology/ValidThrough=timeend
| ?Ontology/Address=address
| ?Ontology/City=city
| ?Ontology/Latitude=latitude
| ?Ontology/Longitude=longitude
| ?Ontology/Campus=campus
| format=bidetail
| limit=1
| sort=Ontology/validTime
| link=subject
| order=ascending
| source=html
|}}';
	
$Participant='<div id="participheader" class="hd"><p>参与者：</p></div>{{#ask: [[Category:Ontology/Person]]
[[Ontology/Participated::'.$pageName.'}]]
| ?Ontology/Avatar=avatar
| ?Ontology/Name=name
| ?Ontology/Id=id
| format=bisns
| source=html
| merge=false
| headers=hidden
| link=none
| stype=p
|}}';

$Creater='{{#ask: [['.$createdatapage.']]
| ?Ontology/Avatar=avatar
| ?Ontology/Name=name
| ?Ontology/Id=id
| format=bisns
| source=html
| merge=false
| limit=1
| headers=hidden
| link=none
| stype=m
|}}';

$Weibo='{{ #ask: [[Category:Ontology/Microblog]]
[[Ontology/MicroblogRelatedPage::'.$pageName.']]
| ?Ontology/Avatar=avatar
| ?Ontology/Id=name
| ?Ontology/Status=status
| ?Ontology/Published_time=published time
| ?Ontology/Source=source
| format=biweibo
}}';

$source='<div id="selfpagename" style="display:none">'.$pageName.'</div>
<div class="fll">'.$Creater.'<div class="participant" id="particip"></div>
</div>
<div class="frr">
<div class="frtitle">
<h2>'.$pageName.'</h2>
</div>
<div class="gbbox">
<div id="gbbox2">'.$deal.'
</div>
</div>
<div class="gbinfo">
<div class="fll">
<ul class="tabs">
	<li class="curr">关于本团购</li>
	<li>公交查询</li>
	<li>微博互动</li>
</ul>
<ul class="tabc"><i id="aboutgb" style="display:block">'.$DealDetial.'</i><i id="bus"></i><i id="weibo">'.$Weibo.'</i>></ul>
</div>
</div>
</div>';

$source=$source."[[Category:".$categroy."]]";	
return $source;
}
?>

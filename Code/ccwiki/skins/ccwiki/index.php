<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<!DOCTYPE html>
<div class="banner">
  <div class="transparent">
  	<a href="#" target="_blank"><img src="<?php echo $skinpath?>images/banner.png" width="980" height="260" /></a>
  </div>
  <div class="larray" ><img src="<?php echo $skinpath?>images/larray.png" width="45" height="75" /></div>
  <div class="rarray" ><img src="<?php echo $skinpath?>images/rarray.png" width="45" height="75" /></div>
<img src="" width="980" height="260" id="banner" />
  
</div>

  <div class="search m_t10 m_b10">
      <form action="../extensions/FacetedSearch/facetedsearch.html"  id="searchform">

    <table width="98%" border="0" align="center" cellspacing="5">
      <tr>
        <td align="center">
            <input pasteNS="true" name="s" id="s" class="slk"  constraints="all" onfocus="this.value='';" type="text" <?php echo $this->skin->tooltipAndAccesskey('search'); ?>
                     value="<?php $this->msg('smw_search_this_wiki'); ?>"/></td>
        <td>
	<input type=hidden id="id" name="id" value='7' /><div class="xlk">所有</div>
        <div class="xxlk">
        	<ul>
        		<li>餐饮</li>
        		<li>购物</li>
                        <li>娱乐</li>
        		<li>生活</li>
        		<li>旅游</li>
        		<li>健康</li>
			<li>美容</li>
        		<li>其它</li>
        		<li>所有</li>
        	</ul>
        </div>
        </td>
        <td><input type="image"  src="<?php echo $skinpath?>images/search_btn01.gif" /></td>
       
      </tr>
    </table></form></div>
  
  
  

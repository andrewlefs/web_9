<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
if($site['banner_system'] != 0){
	$db->Query("UPDATE `banners` SET `expiration`='0' WHERE `expiration`<'".time()."' AND `expiration`!='0'");
	$banners = $db->QueryFetchArrayAll("SELECT id,banner_url FROM `banners` WHERE `expiration`>'0' ORDER BY rand() LIMIT 2");
	if(!empty($banners)){
		echo '<div class="footer_banners">';
		foreach($banners as $banner){
			$db->Query("UPDATE `banners` SET `views`=`views`+'1' WHERE `id`='".$banner['id']."'");
			echo '<span style="margin: 0 5px"><a href="'.$site['site_url'].'/go_banner.php?go='.$banner['id'].'" target="_blank"><img src="'.$banner['banner_url'].'" width="460" alt="Banner - '.$site['site_url'].'" border="0" /></a></span>';
		}
		echo '</div>';
	}
}?>
	</div>		
</div>
<!--Bat dau chan trang moi-->
<div class="footer">
    <div class="nav_bot">
    	<div class="cont_nav_bot">
		COPYRIGHT © 2008 - <script type='text/javascript'>d = new Date();y = d.getFullYear();document.write(y);</script> HOANG GIA INC &nbsp; &nbsp;
                <a href='/contact.php' class='link_footer' style='color: #f4fd03;' title='<?=$lang['b_47']?>'><?=$lang['b_47']?></a> <span> | </span>
                <a href='/faq.php' class='link_footer' title='<?=$lang['b_06']?>' rel="nofollow"><?=$lang['b_06']?></a> <span> | </span>
                <a href='/stats.php' class='link_footer' title='<?=$lang['b_82']?>' rel="nofollow"><?=$lang['b_82']?></a> <span> | </span>
                <?if($is_online){?><a href="bank.php" class='link_footer'><?=$lang['b_256']?></a> <span> | </span><?}?>
                <a target="_blank" href="/blog.php?bid=9" class='link_footer'>Quy định chung</a> <span> | </span> 
                <a target="_blank" href="/blog.php?bid=8" class='link_footer'>Quy định rút tiền</a> 
                 <span> | </span> Click: <?=number_format(hook_filter('tot_clicks',""))?>
                 <span> | </span> Site: <?=number_format(hook_filter('tot_sites',""))?>
                </div>
    </div> 
    <div class="service">
    	<div class="cont_serv">
	<div class="info_footer">
            <h2>LIKE MIỄN PHÍ | KIẾM TIỀN ONLINE</h2>
                <ul>  
                    <li>Vận hành bởi: Công ty Công nghệ truyền thông Hoàng Gia </li> 
                    <li>Điện thoại: 043 550 1189, Hotline: 090 550 1189</li>  
                    <li>E-mail: hanh@hoanggia.net, contact@hoanggia.net</li>
                    <li>Giấy GPĐKKD số 0103596514 - SKHĐT, Chịu trách nhiệm <a rel='author'  href='https://plus.google.com/+HuuHoang/posts'>Hoang Huu</a>
                    <a href="https://plus.google.com/+HuuHoang/posts" rel="publisher">Find us on Google+</a>
                    </li>
                </ul></div> 
    </div>
    </div>
</div>

<!--Ket thuc chan trang moi-->

<?if($is_online){?>
<div id="sidemenu_wrapper">
    <ul id="sidemenu" class="sidemenu_light sidemenu_right">
	<li class="sidemenu_first"><a href="<?=$site['site_url']?>"><span id="sidemenu_home"></span></a></li>
        <li class="sidemenu_last bottom_panel"><span id="sidemenu_kate"></span>
		<div class="sidemenu_container">
		<div class="sidemenu_2col">
                    <div class="col_2">
                        <h6><?=$lang['b_83'].' '.$data['login']?>,</h6>
                        <p>
                        <b><?=$lang['b_200']?>:</b> <?=number_format($data['coins']).' '.$lang['b_156']?><br>
                        <b><?=$lang['b_255']?>:</b> <?=$data['account_balance'].' '.get_currency_symbol($site['currency_code'])?><br>
                        <b><?=$lang['b_192']?>:</b> <?=($data['premium'] > 0 ? $lang['b_194'] : $lang['b_193'])?><br>
                        <b><?=$lang['b_201']?>:</b> <?=($data['country'] == '0' ? $lang['b_205'] : get_country($data['country']))?><br>
                        <b><?=$lang['b_202']?>:</b> <?=get_gender($data['sex'], $lang['b_203'], $lang['b_204'], $lang['b_205'])?>
                </p>
                <hr>
                    </div>
                    <div class="col_1">
                        <ul class="sidemenu_list">
                            <li class="icon_settings"><a href="<?=WEB_DOMAIN?>/edit_acc.php" style="color:#232323;text-decoration:none"><?=$lang['b_86']?></a></li>
                            <li class="icon_lock"><a href="<?=WEB_DOMAIN?>/logout.php" style="color:#232323;text-decoration:none"><?=$lang['b_87']?></a></li>
                        </ul>
                    </div>
                    <div class="col_1">
                        <ul class="sidemenu_list">
                            <li class="icon_appreciate"><a href="<?=WEB_DOMAIN?>/vip.php" style="color:#232323;text-decoration:none"><?=$lang['b_08']?></a></li>
                            <li class="icon_cart"><a href="<?=WEB_DOMAIN?>/buy.php" style="color:#232323;text-decoration:none"><?=$lang['b_07']?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </li>
    </ul>

</div>
<?}
if(!empty($site['analytics_id'])){?>
<?}?>
   
<script type="text/javascript">var subiz_account_id = "4195";(function() { var sbz = document.createElement("script"); sbz.type = "text/javascript"; sbz.async = true; sbz.src = ("https:" == document.location.protocol ? "https://" : "http://") + "widget.subiz.com/static/js/loader.js?v="+ (new Date()).getFullYear() + ("0" + ((new Date()).getMonth() + 1)).slice(-2) + ("0" + (new Date()).getDate()).slice(-2); var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(sbz, s);})();</script>
</body>
</html>
    
    <? $db->Close(); ?>




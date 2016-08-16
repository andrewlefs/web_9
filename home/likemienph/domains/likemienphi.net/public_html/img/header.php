<?php
$starttime = microtime(true);
define('BASEPATH', true);
define('WEB_DOMAIN', 'http://'.$_SERVER["HTTP_HOST"].'');
include('system/config.php');
if($site['maintenance'] > 0){$site['site_name'] .= ' - '.$lang['b_01']; if($data['admin'] < 1){redirect('maintenance');}}
if(!$is_online && isset($_SERVER['HTTP_REFERER']) && !isset($_COOKIE['PESRefSource'])){
	setcookie("PESRefSource", $db->EscapeString($_SERVER['HTTP_REFERER']), time()+1800);
}
if(isset($_GET['unsubscribe']) && isset($_GET['um'])){
	$uid = $db->EscapeString($_GET['unsubscribe']);
	$um = $db->EscapeString($_GET['um']);
	if($db->QueryGetNumRows("SELECT id FROM `users` WHERE `id`='".$uid."' AND MD5(`email`)='".$um."'") > 0){
		$db->Query("UPDATE `users` SET `newsletter`='0' WHERE `id`='".$uid."' AND MD5(`email`)='".$um."'");
		echo '<center><b style="color:green">You was successfully unsubscribed from newsletters!</b></center>';
		redirect('index.php');
		exit;
	}
}
$errMsg = '';
if(isset($_POST['connect'])) {
	$login = $db->EscapeString($_POST['login']);
	$pass = MD5($_POST['pass']);
	$data = $db->QueryFetchArray("SELECT id,login,banned,activate FROM `users` WHERE (`login`='".$login."' OR `email`='".$login."') AND `pass`='".$pass."'");

	if($data['banned'] > 0){
		$errMsg = '<div class="msg"><div class="error">'.$lang['b_02'].'</div></div>';
		$sql = $db->Query("SELECT id,reason FROM `ban_reasons` WHERE `user`='".$data['id']."'");
		if($db->GetNumRows($sql) > 0){
			$ban = $db->FetchArray($sql);
			if(!empty($ban['reason'])){
				$_SESSION['PES_Banned'] = $data['id'];
				redirect('banned.php?id='.$data['id']);
			}
		}
	}elseif($data['activate'] > 0){
		$errMsg = '<a href="register.php?resend" title="Click here" style="text-decoration:none;color:#a32326"><div class="msg"><div class="error">'.$lang['b_03'].'</div></div></a>';
	}elseif($data['id'] != '') {
		if(isset($_POST['remember'])){
			setcookie('PESAutoLogin', 'ses_user='.$data['login'].'&ses_hash='.$pass, time()+604800, '/');
		}
		$db->Query("UPDATE `users` SET `log_ip`='".VisitorIP()."', `online`=NOW() WHERE `id`='".$data['id']."'");
		$_SESSION['EX_login'] = $data['id'];
		redirect('index.php');
	}else{
		$errMsg = '<div class="msg"><div class="error">'.$lang['b_04'].'</div></div>';
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?=$titleweb ?></title>
<meta http-equiv="Content-type" content="text/html; charset=<?=$conf['lang_charset']?>" />
<meta name="description" content="<?=$site['site_description']?>" />
<meta name="keywords" content="Tăng like, tăng like miễn phí, cách tăng like, hướng dẫn tăng like, kiếm nhều like, tăng like youtube, tăng truy cập, tăng subscriber " />
<meta name="author" content="Hoang Gia Media" />
<meta name="version" content="<?=$config['version']?>" />
<meta name="google-site-verification" content="bYASCIIAPQLTBJswUjzaOV9xOT9U0oiQ5f5wXuLXE1c" />
<link rel="stylesheet" type="text/css" href="<?=WEB_DOMAIN?>/theme/css/style2012.css" />
<link rel="stylesheet" href="<?=WEB_DOMAIN?>/theme/pes/style.css?v=<?=$config['version']?>" type="text/css" />
<script type="text/javascript" src="js/jquery.js"></script>
<meta itemprop="image" content="http://www.likemienphi.net/theme/images/logo-hoanggia-net-vn.png" />
<meta property="og:image" content="http://www.likemienphi.net/theme/images/logo-hoanggia-net-vn.png" />
<meta property="og:title" content="http://www.likemienphi.net/theme/images/logo-hoanggia-net-vn.png"/>
<meta property="og:description" content="<?=$site['site_description']?>."/>
<?if($is_online){?>
<script type="text/javascript"> var auto_refresh = setInterval( function() { $('#c_coins').load('system/uCoins.php'); }, 15000); </script><?}?>
</head>
<body>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/vi_VN/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<body onload="hello();">
<div class="header">
	<div class="cont_header">
    	<div class="top_cont_header">
            <div class="banner_980">
		<a href="<?=WEB_DOMAIN?>" title="Tang like facebook mien phi"><h1 onclick="location='<?=WEB_DOMAIN?>'" id="js_logo"><img src="http://www.likemienphi.net/theme/images/logo-hoanggia-net-vn.png" alt="Tang like facebook mien phi" border="0"/></h1></a>
            </div>
		<div class="nav_top">
                    <div class="banner_980">
                    <ul>
                        
                        
                        <?if(!$is_online){?>
                        <?if($site['reg_status'] == 0){?>
                        <li><a href="<?=WEB_DOMAIN?>/register.php" class="bold" rel='nofollow'><?=$lang['b_05']?></a></li>
                        <?}?>
                        <?}else{ ?>
                        <li><a href='<?=WEB_DOMAIN?>/bonus.php' title='<?=$lang['b_09']?>'>Thưởng Vcoin</a></li>
                        <li><a href='<?=WEB_DOMAIN?>/buy.php' rel='nofollow' title='<?=$lang['b_07']?>'><?=$lang['b_07']?></a></li>
                        <li><a href='<?=WEB_DOMAIN?>/vip.php' title='<?=$lang['b_08']?>'><?=$lang['b_08']?></a></li>
                        <li><a href='<?=WEB_DOMAIN?>/refer.php' title='<?=$lang['b_12']?>'><?=$lang['b_12']?></a></li>
                        <li><a href='<?=WEB_DOMAIN?>/coupons.php' title='<?=$lang['b_10']?>'><?=$lang['b_10']?></a></li>
                        <? }?>
                        <li><a href='<?=WEB_DOMAIN?>/faq.php' rel='nofollow' title='<?=$lang['b_06']?>'><?=$lang['b_06']?></a></li>
                        <li><a href='<?=WEB_DOMAIN?>/blog.php' title='<?=$lang['b_287']?>'><?=$lang['b_287']?></a></li>
                        
                        
                        <?if(!$is_online){?>
                        <?if($site['reg_status'] == 0){?>
                        <li><a href='<?=WEB_DOMAIN?>/tin-tuc/' title='Tin tức like miễn phí'>Tin tức</a></li>
                        <li><a href='<?=WEB_DOMAIN?>' title='Tăng like miễn phí'> Trang chủ</a></li>
                        <?}?>
                        <?}else{ }?>
                        
                        
                    </ul>
                    </div>
                </div>
       </div>	
</div>
</div> 

<div class="wrapper">
<!--	<div class="main">
<div class="container" style="padding-top:58px; ">-->
<div class="main" >
	
<div style=" text-align:centre; margin-left:auto; margin-right:auto; padding-top:3px;">
        <a  href="http://www.bachmoc.vn/" target="_blank">
        <img src="<?=WEB_DOMAIN?>/img/banner-bachmoc_vn.jpg" alt="Sàn bất động sản bách mộc" border="0" width="964" /></a></div>
	<div class="sidebar">
                 
	<?if(!$is_online){?>
            <div class="signin">
            <h2 class="title"><?=$lang['b_13']?></h2>
            <form method="post" action="">
				<input class="l_form" onfocus="if(this.value == '<?=$lang['b_14']?>') { this.value = ''; }" onblur="if(this.value=='') { this.value = this.defaultValue }" value="<?=$lang['b_14']?>" name="login" type="text" />
				<input class="l_form" onfocus="if(this.value == '<?=$lang['b_15']?>') { this.value = ''; }" onblur="if(this.value=='') { this.value = this.defaultValue }" value="<?=$lang['b_15']?>" name="pass" type="password" />			 						
				<input type="checkbox" name="remember" /> <span style="color:#fff;"><?=$lang['b_229']?></span>
				<?=$errMsg?>
				<div class="buttons">
					<input class="gbut" name="connect" value="<?=$lang['b_13']?>" type="submit" /><br /><br />
					<span style="float:right;display:inline"><a href="<?=WEB_DOMAIN?>/recover.php" style="font-size:10px"><?=$lang['b_16']?></a></span>
				</div>						  				  
			</form>
        </div>
		<div style="clear:both"></div>
		<div class="sideblock">
                    <?php 
                    $luottc = $db->QueryGetNumRows("SELECT id FROM `users`");
                    $somacdinh = 20000;
                    $tongthanhvien = $luottc + $somacdinh; 
                    
                    ?>
			<p class="user_count"><?=number_format($tongthanhvien)?></p> <?=$lang['b_230']?>
		</div>
	<div style="clear:both"></div>
        <div class="sideblockhg">
            <p><a href='ymsgr:sendim?huuhv'><img src=' http://opi.yahoo.com/online?u=huuhv&m=g&t=2'  border='0' alt='Xin chào' /></a></p>
            <p class="title">0906.292.000 </p>
        </div>	
        <div class="sideblockfb">
        <div class="fb-like-box" data-href="https://www.facebook.com/likemienphi.net" data-width="234" data-show-faces="true" data-stream="false" data-header="true" data-colorscheme="dark"></div>
        </div>
		<? 
			$sql = $db->Query("SELECT uid, SUM(`today_clicks`) AS `clicks` FROM `user_clicks` GROUP BY uid ORDER BY `clicks` DESC LIMIT 3");
			if($db->GetNumRows($sql) >= 3){
		?>
		<div class="home_top">
			<table class="table">
				<thead>
					<tr><td colspan="2"><?=$lang['b_239']?></td></tr>
				</thead>
				<tbody>
					<?
						$j = 0;
						foreach($db->FetchArrayAll($sql) as $top){
							$j++;
							$uname = $db->QueryFetchArray("SELECT login FROM `users` WHERE `id`='".$top['uid']."'");
							echo '<tr><td><center><img src="img/place/place_'.$j.'.png" height="15" alt="'.$j.'" border="0" /></center></td><td>'.$uname['login'].'</td></tr>';
						}
					?>
				</tbody>
			</table>
		</div>
		<?}?>
	<?}else{?>
	<div class="sideblock_ucp">
		<h2><?=lang_rep($lang['b_17'], array('-NUM-' => '&nbsp;<p class="coincount" id="c_coins">'.number_format($data['coins']).'</p>&nbsp;'))?></h2>
	</div>
	<div class="sideblock_ucp" style="margin-top:-3px">
        <h2><?=lang_rep($lang['b_261'], array('-NUM-' => '&nbsp;<a href="bank.php" title="'.$lang['b_256'].'" style="text-decoration:none"><p class="accBalance">'.$data['account_balance'].' '.get_currency_symbol($site['currency_code']).'</p></a>&nbsp;'))?></h2>
	</div>
	<div style="clear:both"></div>
        <div class="sideblockhg">
            <p><a href='ymsgr:sendim?huuhv'><img src=' http://opi.yahoo.com/online?u=huuhv&m=g&t=2'  border='0' alt='Xin chào' /></a></p>
            <p class="title">0906.292.000 </p>
        </div>	
	<div class="ucp_menu"> 
		<div class="ucp_inner">
		    <h2><?=$lang['b_18']?></h2>
			<div class="ucp_link"><img src="img/icons2/mysites.png" align="left">&nbsp;<a href="addurl.php"> <?=$lang['b_19']?></a></div>
			<div class="ucp_link"><img src="img/icons2/addsite.png" align="left">&nbsp;<a href="mysites.php"> <?=$lang['b_20']?></a></div>
			<?if($site['banner_system'] != 0){?><div class="ucp_link"><img src="img/icons2/banner.png" align="left">&nbsp;<a href="banners.php"> <?=$lang['b_189']?></a></div><?}?>
			<div class="ucp_link"><img src="img/icons2/bank.png" align="left">&nbsp;<a href="bank.php" style="color:gold"> <?=$lang['b_256']?></a></div>
                        <div class="ucp_link"><img src="img/icons2/bank.png" align="left">&nbsp;<a href="bank.php?withdraw" style="color:gold"> <?=$lang['b_97']?></a></div>
		</div>
	</div>
	<div class="ucp_menu" > 
		<div class="ucp_inner">
			<h2><?=$lang['b_22']?></h2>
			<?=hook_filter('top_menu_earn',"")?>
		</div>
		
	</div>
	<div>
	
	<a  href="<?=WEB_DOMAIN?>/bank.php" ><img src="<?=WEB_DOMAIN?>/img/adv_naptien.png" alt="Tang like Facebook" border="0" /></a>
	
	</div>
<?}?></div>



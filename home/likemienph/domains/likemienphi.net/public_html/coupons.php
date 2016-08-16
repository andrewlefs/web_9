<?php
$titleweb =  'Phiếu quà tặng, Tăng like Facebook, tang like facebook';
include('header.php');
if(!mysql_query("SELECT used FROM coupons")){$db->Query("ALTER TABLE `coupons` ADD `used` INT( 255 ) NOT NULL DEFAULT '0'");}
if(!$is_online){
	redirect('index.php');
	exit;
}

$msg = '<div class="msg"><div class="info">'.$lang['b_62'].'</div></div>';
if(isset($_POST['submit'])) {
	$code = $db->EscapeString($_POST['code']);
	
	$sql = $db->Query("SELECT id,coins,uses,type FROM `coupons` WHERE `code`='".$code."' AND (`uses`>'0' OR `uses`='u')");
	$ext = $db->FetchArray($sql);
	$used = $db->GetNumRows($db->Query("SELECT id FROM `used_coupons` WHERE `user_id`='".$data['id']."' AND `coupon_id`='".$ext['id']."'"));
	if($ext['id'] != "" && $used == 0){
		if($ext['type'] == 1){
			$premium = ($data['premium'] == 0 ? (time()+(86400*$ext['coins'])) : ((86400*$ext['coins'])+$data['premium']));
			$db_add = "`premium`='".$premium."'";
		}else{
			$db_add = "`coins`=`coins`+'".$ext['coins']."'";
		}
		
		$db->Query("UPDATE `users` SET ".$db_add." WHERE `id`='".$data['id']."'");
		$db_custom = ($ext['uses'] != 'u' ? "`uses`=`uses`-'1', " : "");
		$db->Query("UPDATE `coupons` SET ".$db_custom."`used`=`used`+'1' WHERE `code`='".$code."'");
		$db->Query("INSERT INTO `used_coupons` (user_id, coupon_id) VALUES('".$data['id']."','".$ext['id']."')");
		$msg = '<div class="msg"><div class="msg success">'.lang_rep(($ext['type'] == 1 ? $lang['b_270'] : $lang['b_60']), array('-NUM-' => $ext['coins'])).'</div></div>';
	}else{
		$msg = '<div class="msg"><div class="error">'.$lang['b_61'].'</div></div>';
	}
}?>
<div class="content">
<h2 class="title"><?=$lang['b_10']?></h2>
<form method="post">
	<input class="l_form" onfocus="if(this.value == '<?=$lang['b_59']?>') { this.value = ''; }" onblur="if(this.value=='') { this.value = this.defaultValue }" value="<?=$lang['b_59']?>" name="code" type="text">
	<input type="submit" class="gbut" name="submit" value="<?=$lang['b_58']?>" />
</form><?=$msg?>
</div>	
<?include('footer.php');?>
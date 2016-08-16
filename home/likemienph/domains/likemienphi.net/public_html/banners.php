<?php
$titleweb =  'Quản lý quảng cáo, Tăng like Facebook, tang like facebook';
include('header.php');
if(!$is_online || $site['banner_system'] == 0){
	redirect('index.php');
	exit;
}
if($site['banner_system'] == 2 && $data['premium'] == 0){
?>
<div class="content t-left"><h2 class="title"><?=$lang['b_173']?></h2>
	<div class="msg"><div class="error"><?=$lang['b_234']?></div></div>
</div>
<?
}else{
if(isset($_GET['add'])){

$msg = '';
if(isset($_POST['submit'])){
	$url = $db->EscapeString($_POST['url']);
	$pack = $db->EscapeString($_POST['pack']);
	$pack = $db->QueryFetchArray("SELECT * FROM `banner_packs` WHERE `id`='".$pack."'");

	$MAX_SIZE = 500;	// Max banner size in kb
	function getExtension($str) {
		if($str == 'image/jpeg'){
			return 'jpg';
		}elseif($str == 'image/png'){
			return 'png';
		}elseif($str == 'image/gif'){
			return 'gif';
		}
	}
	function random_string($length) {
		$key = '';
		$keys = array_merge(range(0, 9), range('a', 'z'));
		for ($i = 0; $i < $length; $i++) {
			$key .= $keys[array_rand($keys)];
		}
		return $key;
	}

	if(!empty($url) && !empty($pack) && $_FILES['cons_image']['name']){
		$tmpFile = $_FILES['cons_image']['tmp_name'];
		$b_info = getimagesize($tmpFile);
		$extension = getExtension($b_info['mime']);
		
		if($pack['id'] == ''){
			$msg = '<div class="msg"><div class="error">'.$lang['b_168'].'</div></div>';
		}elseif($pack['coins'] > $data['coins']){
			$msg = '<div class="msg"><div class="error">'.$lang['b_146'].'</div></div>';
		}elseif(!preg_match("|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i", $url) || substr($url,-4) == '.exe'){
			$msg = '<div class="msg"><div class="error">'.$lang['b_167'].'</div></div>';
		}elseif($b_info['mime'] != 'image/jpeg' && $b_info['mime'] != 'image/png' && $b_info['mime'] != 'image/gif'){
			$msg = '<div class="msg"><div class="error">'.$lang['b_171'].'</div></div>';
		}elseif($b_info[0] != '468' && $b_info[1] != '60'){
			$msg = '<div class="msg"><div class="error">'.$lang['b_172'].'</div></div>';
		}elseif(filesize($tmpFile) > $MAX_SIZE*1024){
			$msg = '<div class="msg"><div class="error">Your banner must have less than '.$MAX_SIZE.'KB</div></div>';
		}else{	
			$image_name = 'b-'.$data['id'].'_'.random_string(rand(7,14)).'.'.$extension;
			$copied = copy($tmpFile, dirname( __FILE__ )."/files/banners/".$image_name);

			if(!$copied){
				$msg = '<div class="msg"><div class="error"><b>ERROR:</b> Banner wasn\'t uploaded, please contact your site admin!</div></div>';
			}else{
				$banner = $site['site_url'].'/files/banners/'.$image_name;
				$expiration = ($pack['days']*86400)+time();
				$db->Query("UPDATE `users` SET `coins`=`coins`-'".$pack['coins']."' WHERE `id`='".$data['id']."'");
				$db->Query("INSERT INTO `banners` (user, banner_url, site_url, expiration) VALUES('".$data['id']."', '".$banner."', '".$url."', '".$expiration."')");
				$msg = '<div class="msg"><div class="success">'.$lang['b_170'].'</div></div><script> document.getElementById("c_coins").innerHTML = "'.number_format($data['coins']-$pack['coins']).'"; </script>';
			}
		}
	}else{
		$msg = '<div class="msg"><div class="error">'.$lang['b_25'].'</div></div>';
	}
}
?>
<div class="content t-left"><h2 class="title"><?=$lang['b_173']?></h2>
<div class="infobox" style="text-align:center"><div class="ucp_link<?=(!isset($_GET['add']) ? ' active' : '')?>" style="margin-right:5px;display:inline-block"><a href="banners.php"><?=$lang['b_179']?></a></div><div class="ucp_link<?=(isset($_GET['add']) ? ' active' : '')?>" style="margin-right:5px;display:inline-block"><a href="banners.php?add"><?=$lang['b_173']?></a></div></div><br /><?=$msg?>
<form method="post" enctype="multipart/form-data">
    <p>
		<label><?=$lang['b_174']?></label> <small style="float:right"><?=$lang['b_34']?></small><br/>
		<input class="text-max" type="text" value="http://" name="url" />
	</p>
	<p>
		<label><?=$lang['b_175']?></label> <small style="float:right"><?=$lang['b_176']?></small>
		<div style="background:#efefef;padding:8px;color:#000;border-radius:3px"><input type="file" name="cons_image" /></div> 
	</p>
	<p>
		<label><?=$lang['b_177']?></label> <br/>
		<select name="pack" class="styled">
		<?
		$packs = $db->QueryFetchArrayAll("SELECT * FROM `banner_packs` ORDER BY `coins` ASC");
		foreach($packs as $pack){
			echo (isset($_POST["pack"]) && $_POST["pack"] == $pack['id'] ? '<option value="'.$pack['id'].'" selected>'.$pack['days'].' '.$lang['b_178'].' - '.$pack['coins'].' '.$lang['b_156'].'</option>' : '<option value="'.$pack['id'].'">'.$pack['days'].' '.$lang['b_178'].' - '.$pack['coins'].' '.$lang['b_156'].'</option>');;
		}
		?>
		</select>
	</p>
    <p>
		<input type="submit" name="submit" class="gbut" value="<?=$lang['b_58']?>" />
	</p>
</form>
	</div>	
<?
}elseif(isset($_GET['edit'])){
$id = $db->EscapeString($_GET['edit']);
$banner = $db->QueryFetchArray("SELECT * FROM `banners` WHERE `id`='".$id."'");

if($banner['id'] == ''){
	redirect("banners.php");
	exit;
}


if(isset($_POST['delete'])){
    $db->Query("DELETE FROM `banners` WHERE `id`='".$id."' AND `user`='".$data['id']."'");
	redirect('banners.php');
    exit;
}elseif(isset($_POST['update'])){
	$pack = $db->EscapeString($_POST['pack']);
	$pack = $db->QueryFetchArray("SELECT * FROM `banner_packs` WHERE `id`='".$pack."'");

	if($pack['id'] == ''){
		$msg = '<div class="msg"><div class="error">'.$lang['b_168'].'</div></div>';
	}elseif($pack['coins'] > $data['coins']){
		$msg = '<div class="msg"><div class="error">'.$lang['b_146'].'</div></div>';
	}else{
		$banner = $db->QueryFetchArray("SELECT id,expiration FROM `banners` WHERE `id`='".$id."' AND `user`='".$data['id']."'");
		if($banner['id'] != ''){
			$banner['expiration'] = ($banner['expiration'] > 0 ? ($pack['days']*86400)+$banner['expiration'] : ($pack['days']*86400)+time());
			$db->Query("UPDATE `users` SET `coins`=`coins`-'".$pack['coins']."' WHERE `id`='".$data['id']."'");
			$db->Query("UPDATE `banners` SET `expiration`='".$banner['expiration']."' WHERE `id`='".$id."' AND `user`='".$data['id']."'");
			$msg = '<div class="msg"><div class="success">'.$lang['b_74'].'</div></div><script> document.getElementById("c_coins").innerHTML="'.($data['coins']-$pack['coins']).'"; </script>';
		}
	}
}
?>
<div class="content t-left"><h2 class="title"><?=$lang['b_179']?></h2><?=$msg?>
	<form method="post">
			<div class="infobox"><b><?=$lang['b_186']?>:</b> <?=date('d-m-Y H:i', $banner['expiration'])?></div>
			<p>
				<label><?=$lang['b_187']?></label><br />
				<select name="pack" class="styled">
				<?
					$packs = $db->QueryFetchArrayAll("SELECT * FROM `banner_packs` ORDER BY `coins` ASC");
					foreach($packs as $pack){echo (isset($_POST["pack"]) && $_POST["pack"] == $pack['id'] ? '<option value="'.$pack['id'].'" selected>'.$pack['days'].' '.$lang['b_178'].' - '.number_format($pack['coins']).' '.$lang['b_156'].'</option>' : '<option value="'.$pack['id'].'">'.$pack['days'].' '.$lang['b_178'].' - '.number_format($pack['coins']).' '.$lang['b_156'].'</option>');;}
				?>
				</select>
			</p>
			<p>
				<input type="submit" name="update" class="gbut" value="<?=$lang['b_188']?>" />
				<input type="submit" name="delete" class="bbut" onclick="return confirm('<?=$lang['b_80']?>');" value="<?=$lang['b_81']?>" />
			</p>
	</form>
</div>
<?}else{?>
<div class="content t-left"><h2 class="title"><?=$lang['b_179']?></h2>
<div class="infobox" style="text-align:center"><div class="ucp_link<?=(!isset($_GET['add']) ? ' active' : '')?>" style="margin-right:5px;display:inline-block"><a href="banners.php"><?=$lang['b_179']?></a></div><div class="ucp_link<?=(isset($_GET['add']) ? ' active' : '')?>" style="margin-right:5px;display:inline-block"><a href="banners.php?add"><?=$lang['b_173']?></a></div></div><br />
<table cellpadding="5" class="table">
	<thead>
		<tr><td><?=$lang['b_182']?></td><td width="75px"><?=$lang['b_183']?></td><td width="75px"><?=$lang['b_184']?></td><td width="60px"><?=$lang['b_75']?></td><td width="50px"><?=$lang['b_185']?></td></tr>
	</thead>
	<tbody>
<?
$banners = $db->QueryFetchArrayAll("SELECT * FROM `banners` WHERE `user`='".$data['id']."'");
foreach($banners as $banner){
$x = 1; $x++;
$status = ($banner['expiration'] != 0 ? '<font color="green">'.$lang['b_180'].'</font>' : ($mysite['status'] == 2 ? '<font color="red"><b>'.$lang['b_78'].'</b></font>' : '<font color="red">'.$lang['b_181'].'</font>'));
$color = ($x%2) ? 3 : 1;
?>
    <tr class="c_<?=$color?>"><td><a href="<?=$banner['site_url']?>" title="<?=$banner['site_url']?>" target="_blank"><img src="<?=$banner['banner_url']?>" width="312" border="0" /></a></td><td><?=$banner['views']?></td><td><?=$banner['clicks']?></td><td><?=$status?></td><td><?if($banner['status'] != 2){?><a href="banners.php?edit=<?=$banner['id']?>"><?=$lang['b_96']?></a><?}?></td></tr>
<?}?>
	</tbody>
</table>
</div>
<?php
}
}
include('footer.php');
?>
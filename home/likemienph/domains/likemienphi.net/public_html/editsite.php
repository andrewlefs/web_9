<?
include('header.php');
if(!$is_online){
	redirect('index.php');
	exit;
}

if(!isset($_GET['x']) || !isset($_GET['t'])){
	redirect('index.php');
	exit;
}

$id = $db->EscapeString($_GET['x']);
$type = hook_filter($_GET['t'].'_info', "type");
$table = hook_filter($_GET['t'].'_info', "db");

$sql = $db->Query("SELECT * FROM `".$table."` WHERE `id`='".$id."' AND `user`='".$data['id']."' AND `active`!='2'");
$mysite = $db->FetchArray($sql);
if($db->GetNumRows($sql) == 0){
	redirect('mysites.php?p='.$_GET['t']);
	exit;
}

$target_system = true;
if($site['target_system'] == 1){
	if($data['premium'] > 0){
		$target_system = true;
	}else{
		$target_system = false;
	}
}elseif($site['target_system'] == 2){
	$target_system = false;
}

$msg = '';
$maxcpc = ($data['premium'] > 0 ? $site['premium_cpc'] : $site['free_cpc']);
if(isset($_POST['delete'])){
    $db->Query("DELETE FROM `".$table."` WHERE `id`='".$id."' AND `user`='".$data['id']."'");
	redirect('mysites.php?p='.$_GET['t']);
    exit;
}elseif(isset($_POST['update'])){
	$title = $db->EscapeString($_POST['title']);
	$cpc = $db->EscapeString($_POST['cpc']);
	$status = $db->EscapeString($_POST['active']);
	$gender = $db->EscapeString($_POST['gender']);
	$gender = ($target_system ? $gender : 0);
	$country = $db->EscapeString($_POST['country']);
	$country = ($target_system ? $country : 0);
	
	$sql = $db->Query("SELECT code FROM `list_countries` ORDER BY country");
	$ctrs = array();
	while ($row = $db->FetchArray($sql)) {
		$ctrs[] = $row['code'];
	}
	
	if(!preg_match("/^[A-Za-z0-9-_.!]([A-Za-z\s]*[A-Za-z0-9-_.!])*$/", $title)){
		$msg = '<div class="msg"><div class="error">'.$lang['b_28'].'</div></div>';
	}elseif($cpc < 2 || $cpc > $maxcpc || !is_numeric($cpc)){
		$msg = '<div class="msg"><div class="error">'.lang_rep($lang['b_29'], array('-MIN-' => '2', '-MAX-' => $maxcpc)).'</div></div>';
	}elseif($gender < 0 || $gender > 2) {
		$msg = '<div class="msg"><div class="error">'.$lang['b_219'].'</div></div>';
	}elseif(!in_array($country, $ctrs) && $country != '0') {
		$msg = '<div class="msg"><div class="error">'.$lang['b_220'].'</div></div>';
	}elseif($status == 2){
		$msg = '<div class="msg"><div class="error">'.$lang['b_73'].'</div></div>';
	}else{
		$db->Query("UPDATE `".$table."` SET `title`='".$title."', `cpc`='".$cpc."', `active`='".$status."', `country`='".$country."', `sex`='".$gender."' WHERE `id`='".$id."' AND `user`='".$data['id']."'");
		$msg = '<div class="msg"><div class="success">'.$lang['b_74'].'</div></div>';
	}
}
?>
<div class="content">
	<h2 class="title"><?=$lang['b_212']?> - <?=$type?></h2><?=$msg?>
	<form method="post">
		<table style="text-align:left">
            <tr>
				<td width="33%" class="t-left"><?=$lang['b_32']?></td>
				<td><input type="text" class="l_form" disabled="disabled" value="<?=$mysite['url']?>"/></td>
			</tr>
			<tr>
				<td class="t-left"><?=$lang['b_33']?></td>
				<td><input type="text" class="l_form" name="title" value="<?=(isset($_POST['title']) ? $_POST['title'] : $mysite['title'])?>"/></td>
			</tr>
			<tr>
				<td class="t-left"><?=$lang['b_36']?></td>
				<td><select name="cpc" class="styled"><?for($cpc = 2; $cpc <= $maxcpc; $cpc++) { echo (isset($_POST["cpc"]) && $_POST["cpc"] == $cpc || $mysite['cpc'] == $cpc ? '<option value="'.$cpc.'" selected>'.$cpc.'</option>' : '<option value="'.$cpc.'">'.$cpc.'</option>');}?></select></td>
            </tr>
			<tr>
				<td class="t-left"><?=$lang['b_75']?></td>
				<td><select name="active" class="styled"><option value="0"><?=$lang['b_76']?></option><option value="1"<?=(isset($_POST["active"]) && $_POST["active"] == 1 ? ' selected' : ($mysite['active'] == 1 ? ' selected' : ''))?>><?=$lang['b_77']?></option></select></td>
			</tr>
			<?if($target_system){?>
			<tr>
				<td class="t-left"><?=$lang['b_213']?></td>
				<td><select name="gender" class="styled"><option value="0"><?=$lang['b_214']?></option><option value="1"<?=(isset($_POST["gender"]) && $_POST["gender"] == 1 ? ' selected' : ($mysite['sex'] == 1 ? ' selected' : ''))?>><?=$lang['b_215']?></option><option value="2"<?=(isset($_POST["gender"]) && $_POST["gender"] == 2 ? ' selected' : ($mysite['sex'] == 2 ? ' selected' : ''))?>><?=$lang['b_216']?></option></select> <?=$lang['b_217']?> <select name="country" class="styled"><option value="0"><?=$lang['b_218']?></option><? $countries = $db->QueryFetchArrayAll("SELECT country,code FROM `list_countries` ORDER BY country ASC"); foreach($countries as $country){ echo '<option value="'.$country['code'].'"'.(isset($_POST["country"]) && $_POST["country"] == $country['code'] ? ' selected' : ($mysite['country'] == $country['code'] ? ' selected' : '')).'>'.$country['country'].'</option>';}?></select></td>
			</tr>
			<?}?>
			<tr>
				<td>&nbsp;</td>
				<td><br>
					<input class="gbut" name="update" type="submit" value="<?=$lang['b_79']?>" />
					
				</td>
			</tr>
		</table>
	</form>
</div>
<?include('footer.php');?>
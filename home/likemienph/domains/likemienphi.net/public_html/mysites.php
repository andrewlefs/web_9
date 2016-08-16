<?php
$titleweb =  'Quản lý trang, Tăng like Facebook, tang like facebook';
include('header.php');
if(!$is_online){
	redirect('index.php');
	exit;
}
?>
<script type="text/javascript">
function goSelect(selectobj){
 window.location.href='mysites.php?p='+selectobj
}
</script>
<div class="content t-left">
<b><?=$lang['b_93']?>:</b> <select onChange="goSelect(this.value)">
	<?=hook_filter('site_menu', "")?>
</select><hr>

<?
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

$custom = '';
if(isset($_GET['p'])){
	$page = $_GET['p'];
	$table = hook_filter($_GET['p'].'_info', "db");
	$custom = ($_GET['p'] == 'surf' && $site['surf_type'] != 2 ? " AND `confirm`!='1'" : '');
}else{
	$page = 'facebook';
	$table = hook_filter('facebook_info', "db");
}

$sql = $db->Query("SELECT * FROM `".$table."` WHERE `user`='".$data['id']."'".$custom);
?>
    <table cellpadding="5" class="table">
	<thead>
		<tr><td><?=$lang['b_33']?></td><td width="50"><?=$lang['b_94']?></td><td width="30"><?=$lang['b_95']?></td><?if($target_system){?><td width="175"><?=$lang['b_213']?></td><?}?><td width="60"><?=$lang['b_75']?></td><td width="60"><?=$lang['b_96']?></td></tr>
	</thead>
	<tbody>
<?
for($x=1; $mysite = $db->FetchArray($sql); $x++){
$status = ($mysite['active'] == 0 ? '<font color="green">'.$lang['b_76'].'</font>' : ($mysite['active'] == 2 ? '<font color="red"><b>'.$lang['b_78'].'</b></font>' : '<font color="red">'.$lang['b_77'].'</font>'));
$rec = ($mysite['sex'] == '0' ? $lang['b_214'] : ($mysite['sex'] == 1 ? $lang['b_215'] : $lang['b_216'])).' </b>|</b> '.($mysite['country'] == '0' ? $lang['b_218'] : $mysite['country']);
$color = ($x%2) ? 3 : 1;
?>
    <tr class="c_<?=$color?>"><td><?=truncate($mysite['title'], 50)?></td><td><?=$mysite['clicks']?></td><td><?=$mysite['cpc']?></td><?if($target_system){?><td align="center"><?=$rec?></td><?}?><td><?=$status?></td><td><?if($mysite['active'] != 2){?><a href="editsite.php?x=<?=$mysite['id']?>&t=<?=$page?>"><?=$lang['b_96']?></a><?}?></td></tr>
<?}?>
	</tbody>
    </table>
</div>
<?
include('footer.php');
?>
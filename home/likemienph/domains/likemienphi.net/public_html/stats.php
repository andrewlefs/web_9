<?php
$titleweb =  'Thống kê like miễn phí | Tăng like Facebook, tang like facebook, tăng like, tang like, like facebook';
include('header.php');
if(isset($_GET['referrers'])){
	$db_custom = '';
	if(!isset($_GET['total'])){
		$db_custom = " AND MONTH(a.signup) = '".date('m')."'";
	}
?>
<script type="text/javascript">
function topOrder() {
	var oid = document.getElementById("oid").value;
	if(oid == '1') {
		var url = "<?=$site['site_url']?>/stats.php?referrers&total";
	} else{
		var url = "<?=$site['site_url']?>/stats.php?referrers";
	}
	location.href= url;
	return false;
}
</script>
<div class="content">
    <h2 class="title"><?=$lang['b_271']?> <span style="float:right"><select id="oid" onchange="topOrder()"><option value="0"><?=$lang['b_274']?></option><option value="1"<?=(isset($_GET['total']) ? ' selected' : '')?>><?=$lang['b_275']?></option></select></span></h2>
	<div class="infobox"><div class="ucp_link" style="margin-right:5px;display:inline-block"><a href="stats.php"><?=$lang['b_82']?></a></div><div class="ucp_link active" style="margin-right:5px;display:inline-block"><a href="stats.php?referrers"><?=$lang['b_271']?></a></div></div>
	<table class="table">
		<thead>
			<tr><td>#</td><td><?=$lang['b_272']?></td><td><?=$lang['b_273']?></td></tr>
		</thead>
		<tbody>
		<?
			$refs = $db->QueryFetchArrayAll("SELECT a.ref, COUNT(a.id) AS total, b.login FROM users a LEFT JOIN users b ON b.id = a.ref WHERE (a.ref != '0' AND a.ref_paid = 1)".$db_custom." GROUP BY a.ref ORDER BY total DESC LIMIT 15");	
			$j = 0;
			foreach($refs as $ref){
				$j++;
				echo '<tr><td>'.($j == 1 ? '<font color="#FFD700"><b>'.$j.'</b></font>' : ($j == 2 ? '<font color="#E3E4E5"><b>'.$j.'</b></font>' : ($j == 3 ? '<font color="#C9AE5D"><b>'.$j.'</b></font>' : $j))).'</td><td>'.$ref['login'].'</td><td>'.number_format($ref['total']).'</td></tr>';
			}
			if($j == 0){
				echo '<tr><td colspan="3">'.$lang['b_250'].'</td></tr>';
			}
		?>
		</tbody>
		<tfoot>
			<tr><td colspan="3"><b><?=$lang['b_271']?></b></td></tr>
		</tfoot>
	</table>
</div>
<?
}else{
$users = $db->QueryGetNumRows("SELECT id FROM `users`");
$online = $db->QueryGetNumRows("SELECT id FROM `users` WHERE (".time()."-UNIX_TIMESTAMP(`online`)) < 3600");
$banned = $db->QueryGetNumRows("SELECT id FROM `users` WHERE `banned`='1'");

if($site['banner_system'] != 0){
	$banners = $db->QueryGetNumRows("SELECT id FROM `banners`");
	$banner_stats = $db->QueryFetchArray("SELECT SUM(`views`) AS `views`, SUM(`clicks`) AS `clicks` FROM `banners`");
}
if($site['paysys'] == 1){
	$payouts = $db->QueryGetNumRows("SELECT id FROM `requests` WHERE `paid`='1'");
	$total_paid = $db->QueryFetchArray("SELECT SUM(`amount`) AS `total` FROM `requests` WHERE `paid`='1'");
}
?>
<div class="content">
    <h2 class="title"><?=$lang['b_82']?></h2>
    <div class="infobox"><div class="ucp_link active" style="margin-right:5px;display:inline-block"><a href="stats.php"><?=$lang['b_82']?></a></div><div class="ucp_link" style="margin-right:5px;display:inline-block"><a href="stats.php?referrers"><?=$lang['b_271']?></a></div></div>
	<table class="table">
		<thead>
			<tr><td><?=$lang['b_135']?></td><td><?=$lang['b_136']?></td><td><?=$lang['b_137']?></td><td><?=$lang['b_138']?></td></tr>
		</thead>
		<tbody>
			<tr><td><?=number_format($users-$banned)?></td><td><?=number_format($online)?></td><td><?=number_format($banned)?></td><td><?=number_format($users)?></td></tr>
		</tbody>
		<tfoot>
			<tr><td colspan="4"><b><?=$lang['b_139']?></b></td></tr>
		</tfoot>
	</table>

	<table class="table">
		<thead>
			<tr><td width="40%"><?=$lang['b_31']?></td><td width="30%"><?=$lang['b_140']?></td><td width="30%"><?=$lang['b_141']?></td></tr>
		<thead>
		<tbody>
			<?=hook_filter('stats',"")?>
		</tbody>
		<tfoot>
			<tr><td><b><?=$lang['b_142']?></b></td><td><b><?=number_format(hook_filter('tot_sites',""))?></b></td><td><b><?=number_format(hook_filter('tot_clicks',""))?></b></td></tr>
			<tr><td colspan="4"><b><?=$lang['b_140']?></b></td></tr>
		</tfoot>
	</table>
	<? 
		$sql = $db->Query("SELECT uid, SUM(`today_clicks`) AS `clicks` FROM `user_clicks` GROUP BY uid ORDER BY `clicks` DESC LIMIT 3");
		$tops = $db->FetchArrayAll($sql);
		if($db->GetNumRows($sql) >= 3){
	?>
	<table class="table">
		<thead>
			<tr><td><img src="img/place/place_1.png" height="20px" alt="1" border="0" /></td><td><img src="img/place/place_2.png" height="20px" alt="2" border="0" /></td><td><img src="img/place/place_3.png" height="20px" alt="3" border="0" /></td></tr>
		</thead>
		<tbody>
			<tr><?
				$j = 0;
				foreach($tops as $top){
					$j++;
					$uname = $db->QueryFetchArray("SELECT login FROM `users` WHERE `id`='".$top['uid']."'");
					echo '<td>'.$uname['login'].'</td>';
				}
			?></tr>
		</tbody>
		<tfoot>
			<tr><td colspan="3"><b><?=$lang['b_239']?></b></td></tr>
		</tfoot>
	</table>
	<?}?>
	<?if($site['paysys'] == 1){?>
	<table class="table">
		<thead>
			<tr><td>Total Payouts</td><td>Total Paid</td></tr>
		</thead>
		<tbody>
			<tr><td><?=number_format($payouts)?></td><td><?=get_currency_symbol($site['currency_code']).number_format($total_paid['total'], 2)?></td></tr>
		</tbody>
		<tfoot>
			<tr><td colspan="3"><b>Payouts</b></td></tr>
		</tfoot>
	</table>
	<?}?>
	<?if($site['banner_system'] != 0){?>
	<table class="table">
		<thead>
			<tr><td><?=$lang['banners_02']?></td><td><?=$lang['banners_03']?></td><td><?=$lang['banners_04']?></td></tr>
		</thead>
		<tbody>
			<tr><td><?=number_format($banners)?></td><td><?=number_format($banner_stats['views'])?></td><td><?=number_format($banner_stats['clicks'])?></td></tr>
		</tbody>
		<tfoot>
			<tr><td colspan="3"><b><?=$lang['banners_01']?></b></td></tr>
		</tfoot>
	</table>
	<?}?>
</div>
<?}include('footer.php');?>
<?php
$titleweb =  'Nạp Vcoin, Tăng like Facebook, tang like facebook';
include('header.php');
if(!$is_online){
	redirect('index.php');
	exit;
}
if(isset($_GET['history'])){
?>
<div class="content"><h2 class="title"><?=$lang['b_247']?> <span style="float:right"><a href="buy.php"><?=$lang['b_07']?></a></span></h2>
	<table class="table">
		<thead>
			<tr>
				<td width="20">#</td>
				<td><?=$lang['b_248']?></td>
				<td><?=$lang['b_249']?></td>
				<td><?=$lang['b_106']?></td>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td>#</td>
				<td><?=$lang['b_248']?></td>
				<td><?=$lang['b_249']?></td>
				<td><?=$lang['b_106']?></td>
			</tr>
		</tfoot>
		<tbody>
<?php
  $sql = $db->Query("SELECT * FROM `user_transactions` WHERE `user_id`='".$data['id']."' AND `type`='0' ORDER BY `date` DESC");
  $tras = $db->FetchArrayAll($sql);
  if($db->GetNumRows($sql) == 0){
	echo '<tr><td colspan="5">'.$lang['b_250'].'</td></tr>';
  }
  foreach($tras as $tra){
?>	
			<tr>
				<td><?=$tra['id']?></td>
				<td><?=$tra['value']?> <?=($tra['type'] == 0 ? $lang['b_42'] : $lang['b_246'])?></td>
				<td><?=$tra['cash'].' '.get_currency_symbol($site['currency_code'])?></td>
				<td><?=date('Y-m-d H:i',$tra['date'])?></td>
			</tr>
<?}?>
		</tbody>
	</table>
</div>
<?php
}else{
	$msg = '';
	if(isset($_POST['submit']) && isset($_POST['pack_id'])){
		$pid = $db->EscapeString($_POST['pack_id']);
		$sql = $db->Query("SELECT id,coins,price FROM `c_pack` WHERE `id`='".$pid."'");
		$pack = $db->FetchArray($sql);
		
		$price = ($site['c_discount'] > 0 ? number_format($pack['price'] * ((100-$site['c_discount']) / 100), 2) : $pack['price']);
		if($db->GetNumRows($sql) == 0){
			$msg = '<div class="msg"><div class="error">'.$lang['b_262'].'</div></div>';
		}elseif($data['account_balance'] < $price){
			$msg = '<div class="msg"><div class="error">'.$lang['b_263'].' <a href="bank.php"><b>'.$lang['b_256'].'...</b></a></div></div>';
		}else{
			$db->Query("UPDATE `users` SET `account_balance`=`account_balance`-'".$price."', `coins`=`coins`+'".$pack['coins']."' WHERE `id`='".$data['id']."'");
			$db->Query("INSERT INTO `user_transactions` (`user_id`,`type`,`value`,`cash`,`date`)VALUES('".$data['id']."','0','".$pack['coins']."','".$pack['price']."','".time()."')");
			$msg = '<div class="msg"><div class="success">'.lang_rep($lang['b_264'], array('-NUM-' => $pack['coins'].' '.$lang['b_156'])).'</div></div>';
		}
	}
?>
<div class="content"><h2 class="title"><?=$lang['b_07']?> <span style="float:right"><a href="buy.php?history"><?=$lang['b_247']?></a></span></h2><?=$msg?>
<?
$sql = $db->Query("SELECT id,coins,price FROM `c_pack` ORDER BY `id` ASC");
$check = $db->GetNumRows($sql);
if($check > 0){
$packs = $db->FetchArrayAll($sql);
foreach($packs as $pack){
	$price = ($site['c_discount'] > 0 ? number_format($pack['price'] * ((100-$site['c_discount']) / 100), 2) : $pack['price']);

?>
<div class="purchase">
	<div class="purchase-hdr">
		<span style="font-size:16px"><?=$pack['coins'].' '.$lang['b_42'].' = '.($site['currency_code'] == '' ? get_currency_symbol('USD') : get_currency_symbol($site['currency_code'])).$price?></span><br /> 
		<span style="font-size:12px;color:#efefef">1 coin = <?=($site['c_discount'] > 0 ? '<s>'.get_currency_symbol($site['currency_code']).round($pack['price']/$pack['coins'], 4).'</s> -> '.get_currency_symbol($site['currency_code']).round($price/$pack['coins'], 4) : get_currency_symbol($site['currency_code']).round($price/$pack['coins'], 4))?> </span>
	</div>
	<form method="POST">
		<input type="hidden" name="pack_id" value="<?=$pack['id']?>" />
		<input type="submit" name="submit" class="bbut" style="color:#fff" value="<?=$lang['b_199']?>" />
	</form>
</div>
<?}}else{?>	
<div class="msg"><div class="error"><?=$lang['b_43']?></div></div>
<?}?>
</div>
<?}include('footer.php');?> 
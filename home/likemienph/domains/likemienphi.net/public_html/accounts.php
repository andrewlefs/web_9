<?php
include('header.php');
if(!$is_online){
	redirect('index.php');
	exit;
}
?>
<div class="content">
<? 
if(isset($_GET['a'])){
	$account = $_GET['a'];
	if(file_exists('system/modules/'.$account.'/config.php')){
		include('system/modules/'.$account.'/config.php');
	}else{
		redirect('accounts.php');
		exit;
	}
}else{?>
<h2 class="title"><?=$lang['b_21']?></h2>
<div class="infobox"><?=hook_filter('account_menu',"")?></div>
<?}?>
</div>	
<?include('footer.php');?>
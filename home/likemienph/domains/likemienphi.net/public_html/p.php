<?php
$titleweb =  'Tăng like Facebook, tang like facebook - Likemienphi.Net';
include('header.php');
if(!$is_online){
	redirect('index.php');
	exit;
}
if(isset($_GET['p'])){
	$module = $_GET['p'];
}else{
	redirect('index.php');
	exit;
}
?>
<div class="content">
<?include("system/modules/".$module."/module.php");?>
</div>
<?include('footer.php');?>  
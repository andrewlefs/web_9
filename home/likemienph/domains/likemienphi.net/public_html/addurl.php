<?php
$titleweb =  'Thêm trang, Tăng like Facebook, tang like facebook';
include('header.php');
if(!$is_online){
	redirect('index.php');
	exit;
}
$maxcpc = ($data['premium'] > 0 ? $site['premium_cpc'] : $site['free_cpc']);
$error = 1;
$msg = '';

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

if(isset($_POST['type'])){
	$type = $db->EscapeString($_POST['type']);
	$cpc = $db->EscapeString($_POST['cpc']);
	$gender = $db->EscapeString($_POST['gender']);
	$gender = ($target_system ? $gender : 0);
	$country = $db->EscapeString($_POST['country']);
	$country = ($target_system ? $country : 0);
	
	$sql = $db->Query("SELECT code FROM `list_countries` ORDER BY country");
	$ctrs = array();
	while ($row = $db->FetchArray($sql)) {
		$ctrs[] = $row['code'];
	}
	
	if($cpc < 2 || $cpc > $maxcpc || !is_numeric($cpc)){
		$msg = '<div class="msg"><div class="error">'.lang_rep($lang['b_29'], array('-MIN-' => '2', '-MAX-' => $maxcpc)).'</div></div>';
	}else{
		include("system/modules/".$type."/addsite.php");
	}
}
?>
<div class="content t-left"><h2 class="title"><?=$lang['b_30']?></h2><?=$msg?>
<script type="text/javascript">
	function getFields() { var sVal = $("#type").val(); $('#load').show(); $.get('system/modules/'+sVal+'/add_form.php', function(data) { $('#custom_fields').html(data); $('#load').hide(); }); }
</script>
<form method="post">
	<p>
		<label><?=$lang['b_31']?></label> <br/>
        <select class="styled" name="type" id="type" onchange="getFields()">
			<?=hook_filter('add_site_select', "")?>
		</select> <span id="load" style="display:none"><img src="img/ajax-loader.gif" alt="" /> Please wait...</span>
	</p>
	<div id="custom_fields">
    <p>
		<label><?=$lang['b_32']?></label> <small style="float:right"><?=$lang['b_34']?></small><br/>
		<input class="text-max" type="text" value="http://" name="url" />
	</p>
	<p>
		<label><?=$lang['b_33']?></label> <small style="float:right"><?=$lang['b_35']?></small><br/>
		<input class="text-max" type="text" value="" name="title" maxlength="30" />
	</p>
	</div>
	<p>
		<label><?=$lang['b_36']?></label> <br/>
		<select name="cpc" class="styled">
		<?for($cpc = 2; $cpc <= $maxcpc; $cpc++) { echo (isset($_POST["cpc"]) && $_POST["cpc"] == $cpc ? '<option value="'.$cpc.'" selected>'.$cpc.'</option>' : (!isset($_POST["cpc"]) && $cpc == $maxcpc ? '<option value="'.$cpc.'" selected>'.$cpc.'</option>' : '<option value="'.$cpc.'">'.$cpc.'</option>'));}?></select>
	</p>
	<?if($target_system){?>
	<p>
		<label><?=$lang['b_213']?></label> <br/>
		<select name="gender" class="styled"><option value="0"><?=$lang['b_214']?></option><option value="1"><?=$lang['b_215']?></option><option value="2"><?=$lang['b_216']?></option></select>
		<?=$lang['b_217']?>
		<select name="country" class="styled"><option value="0"><?=$lang['b_218']?></option><? $countries = $db->QueryFetchArrayAll("SELECT country,code FROM `list_countries` ORDER BY country ASC"); foreach($countries as $country){ echo '<option value="'.$country['code'].'">'.$country['country'].'</option>';}?></select>
	</p>
	<?}?>
    <p>
		<input type="submit" class="gbut" value="<?=$lang['b_37']?>" />
	</p>
</form>
</div>		
<?include('footer.php');?>
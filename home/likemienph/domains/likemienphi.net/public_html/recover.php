<?php
include('header.php');
if($site['captcha_sys'] == 1){
	require_once('system/libs/recaptchalib.php');
}else{
	include_once('captcha.php');
}

$mesaj = '';
if(isset($_GET['hash']) && $_GET['hash'] > 0 && is_numeric($_GET['hash'])){
	$pass1 = $_POST['pass1'];
	$pass2 = $_POST['pass2'];
	$hash = $db->EscapeString($_GET['hash']);
	$rec = $db->FetchArray($db->Query("SELECT id,login,email FROM `users` WHERE `rec_hash`='".$hash."' LIMIT 1"));
	
	$captcha_valid = 0;
	if($site['captcha_sys'] == 1){
		$recaptcha_error = null;
		$resp = recaptcha_check_answer($site['recaptcha_sec'],$_SERVER["REMOTE_ADDR"],$_POST["recaptcha_challenge_field"],$_POST["recaptcha_response_field"]);
		if($resp->is_valid){
			$captcha_valid = 1;
		}else{
			$recaptcha_error = $resp->error;
			$captcha_valid = 0;
		}
	}else{
		if(check_captcha($_POST['captcha'])){
			$captcha_valid = 1;
		}else{
			$captcha_valid = 0;
		}
	}

	if($rec['id'] != ''){
		if(isset($_POST['change'])) {
			if(!$captcha_valid){
				$mesaj = '<div class="msg"><div class="error">'.$lang['b_54'].'</div></div>';
			}elseif(!checkPwd($pass1,$pass2)) {
				$mesaj = '<div class="error">'.$lang['b_63'].'</div>';
			}else{
				$passc = MD5($pass1);
				$db->Query("UPDATE `users` SET `pass`='".$passc."', `rec_hash`='0' WHERE `email`='".$rec['email']."'");
				$mesaj = '<div class="success">'.$lang['b_64'].'</div>';
			}
		}
?>
<div class="content t-left"><h2 class="title"><?=$lang['b_68']?></h2>
<div class="msg"><?=$mesaj?></div>
	<div class="infobox">
		<form id="form" method="post">
			<p>
				<label><?=$lang['b_71']?></label><br />
				<input class="text big" name="pass1" type="password" value="" required="required" />
			</p>
			<p>
				<label><?=$lang['b_72']?></label><br />
				<input class="text big" name="pass2" type="password" value="" required="required" />
			</p>
			<p>
				<?
				if($site['captcha_sys'] == 1){
					echo '<script type="text/javascript"> var RecaptchaOptions = { theme : \'white\' }; </script>'.recaptcha_get_html($site['recaptcha_pub'], $recaptcha_error);
				}else{
				?>
				<label><?=$lang['b_51']?></label><br />
				<span style="background:#fff;padding:1px;border-radius:3px;margin-right:3px;display:inline-block"><img src="captcha.php?img=<?=time();?>" alt="" /></span>
				<span style="margin-left:2px;display:inline;position:absolute"><input class="text big" type="text" value="" name="captcha" required="required" /></span>
				<?}?>
			</p>
			<p>
			<input class="gbut" type="submit" name="change" value="<?=$lang['b_58']?>" />
			</p>
		</form>
	</div>
</div>
<?
	}
}else{

if(isset($_POST['send'])) {
	$email = $db->EscapeString($_POST['email']);
	$rec = $db->FetchArray($db->Query("SELECT id,login FROM `users` WHERE `email`='".$email."'"));

	$captcha_valid = 0;
	if($site['captcha_sys'] == 1){
		$recaptcha_error = null;
		$resp = recaptcha_check_answer($site['recaptcha_sec'],$_SERVER["REMOTE_ADDR"],$_POST["recaptcha_challenge_field"],$_POST["recaptcha_response_field"]);
		if($resp->is_valid){
			$captcha_valid = 1;
		}else{
			$recaptcha_error = $resp->error;
			$captcha_valid = 0;
		}
	}else{
		if(check_captcha($_POST['captcha'])){
			$captcha_valid = 1;
		}else{
			$captcha_valid = 0;
		}
	}

	if(!$captcha_valid){
		$mesaj = '<div class="error">'.$lang['b_54'].'</div>';
	}elseif($_POST['email'] == ""){
		$mesaj = '<div class="error">'.$lang['b_65'].'</div>';
	}elseif($rec['login'] == ""){
		$mesaj = '<div class="error">'.$lang['b_110'].'</div>';
	}else{
		$newhash = rand(1000000,9999999);
		$db->Query("UPDATE `users` SET `rec_hash`='".$newhash."' WHERE `email`='".$email."'");
		
		$subject = $lang['b_15'];
		$message = "Hello {$rec['login']},

You asked for password recovery.
To get your new password, access this URL: {$site['site_url']}/recover.php?hash={$newhash}

Best Regards!";
		$header = "From: {$site['site_email']}";
		mail($email,$subject,$message,$header);
		$mesaj = '<div class="success">'.$lang['b_111'].'</div>';
	}
}?>
<div class="content t-left"><h2 class="title"><?=$lang['b_112']?></h2><div class="msg"><?echo $mesaj;?></div>
	<div class="infobox">
		<form id="form" method="post">
			<p>
				<label><?=$lang['b_70']?></label><br />
				<input class="text big" name="email" type="email" value="" required="required" />
			</p>
			<p>
				<?
				if($site['captcha_sys'] == 1){
					echo '<script type="text/javascript"> var RecaptchaOptions = { theme : \'white\' }; </script>'.recaptcha_get_html($site['recaptcha_pub'], $recaptcha_error);
				}else{
				?>
				<label><?=$lang['b_51']?></label><br />
				<span style="background:#fff;padding:1px;border-radius:3px;margin-right:3px;display:inline-block"><img src="captcha.php?img=<?=time();?>" alt="" /></span>
				<span style="margin-left:2px;display:inline;position:absolute"><input class="text big" type="text" value="" name="captcha" required="required" /></span>
				<?}?>
			</p>
			<p>
			<input class="gbut" type="submit" name="send" value="<?=$lang['b_52']?>" />
			</p>
		</form>
	</div>
</div>
<?}
include('footer.php');?>
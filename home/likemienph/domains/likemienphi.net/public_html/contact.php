<?php
$titleweb =  'Liên hệ | Tăng like Facebook, tang like facebook, tăng like, tang like, like facebook';
include('header.php');
if($site['captcha_sys'] == 1){
	require_once('system/libs/recaptchalib.php');
}else{
	include_once('captcha.php');
}

$mesaj = '';
if(isset($_POST['send'])) {

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
		$mesaj = '<div class="msg"><div class="error">'.$lang['b_54'].'</div></div>';
	}elseif($_POST['name'] == ""){
		$mesaj = '<div class="msg"><div class="error">'.$lang['b_55'].'</div></div>';
	}elseif($_POST['email'] == ""){
		$mesaj = '<div class="msg"><div class="error">'.$lang['b_56'].'</div></div>';
	}elseif($_POST['message'] == ""){
		$mesaj = '<div class="msg"><div class="error">'.$lang['b_57'].'</div></div>';
	}else{
		$subject = "PES Contact";
		$header = "From: ".$_POST['name']." <".$_POST['email'].">";
		mail($site['site_email'],$subject,$_POST['message'],$header);
		$mesaj = '<div class="msg"><div class="success">'.$lang['b_53'].'</div></div>';
	}
}?>
<div class="content t-left">
<h2 class="title"><?=$lang['b_47']?></h2><?=$mesaj?>
<div class="infobox">
	<form method="post">
		<p>
			<label><?=$lang['b_48']?></label> <br/>
			<input class="text big" type="text" value="<?=$data['login']?>" name="name" required="required" />
		</p>
		<p>
			<label><?=$lang['b_49']?></label> <br/>
			<input class="text big" type="email" value="<?=$data['email']?>" name="email" required="required" />
		</p>
		<p>
			<label><?=$lang['b_50']?></label> <br/>
			<textarea name="message" rows="6" cols="76" required="required"></textarea>
		</p>
		<p>
			<?
			if($site['captcha_sys'] == 1){
				echo '<script type="text/javascript"> var RecaptchaOptions = { theme : \'white\' }; </script>'.recaptcha_get_html($site['recaptcha_pub'], $recaptcha_error);
			}else{
			?>
			<label><?=$lang['b_51']?></label><br />
			<span style="background:#fff;padding:1px;border-radius:3px;margin-right:5px;display:inline-block"><img src="captcha.php?img=<?=time();?>" alt="" /></span>
			<span style="display:inline;position:absolute"><input class="text big" type="text" value="" name="captcha" required="required" /></span>
			<?}?>
		</p>
		<p>
			<input type="submit" class="gbut" value="<?=$lang['b_52']?>" name="send" />
		</p>
	</form>
</div>
</div>
<?include('footer.php');?>
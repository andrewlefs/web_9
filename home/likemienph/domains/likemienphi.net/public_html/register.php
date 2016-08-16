<?php 
include('header.php');
if($is_online){
	redirect('index.php');
	exit;
}
require_once('captcha.php');
require_once('system/libs/recaptchalib.php');

if(isset($_GET['resend'])){
	if(isset($_POST['resend'])){
		$email = $db->EscapeString($_POST['email']);

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
		}elseif ($db->GetNumRows($db->Query("SELECT id FROM `users` WHERE `email`='".$email ."' AND `activate`!='0'")) < 1) {
			$mesaj = '<div class="msg"><div class="error">'.$lang['b_191'].'</div></div>';
		}else{
			$row = $db->QueryFetchArray("SELECT login,activate FROM `users` WHERE `email`='".$email ."' AND `activate`!='0'");
                        require_once 'phpmailer/class.phpmailer.php';
                        $mail  = new PHPMailer();
			$body = '<html>
				<body style="font-family: Verdana; color: #333333; font-size: 12px;">
					<table style="width: 400px; margin: 0px auto;">
						<tr style="text-align: center;">
							<td style="border-bottom: solid 1px #cccccc;"><h2 style="text-align: right; font-size: 14px; margin: 7px 0 10px 0;">'.$lang['b_130'].'</h2></td>
						</tr>
						<tr style="text-align: justify;">
							<td style="padding-top: 15px; padding-bottom: 15px;">
								Hello '.$row['login'].',
								<br />
								<br />
								Click on this link to activate your account:<br />
								<a href="'.$site['site_url'].'/activate.php?code='.$row['activate'].'">'.$site['site_url'].'/activate.php?code='.$row['activate'].'</a>
							</td>
						</tr>
						<tr style="text-align: right; color: #777777;">
							<td style="padding-top: 10px; border-top: solid 1px #cccccc;">
								Best Regards!
							</td>
						</tr>
					</table>
				</body>
                            </html>';
                    $body             = eregi_replace("[\]",'',$body);
                    $mail->IsSMTP(); 
                    $mail->SMTPAuth   = true;                
                    $mail->SMTPSecure = "ssl";                 
                    $mail->Host       = "smtp.gmail.com";      
                    $mail->Port       = 465;                 
                    $mail->Username   = "az123456";  
                    $mail->Password   = "az";          
                    $mail->SetFrom($site['site_email'], 'Like mien phi');
                    $mail->AddReplyTo($site['site_email'],"Hoang Gia Media");
                    $mail->Subject    = $lang['b_130'];
                    $mail->CharSet = "utf-8";
                    $mail->MsgHTML($body);
                    $mail->AddAddress($email , $name);
                    $mail->AddBCC($email, $name);
                    $mail->Send();  
                    $mesaj = '<div class="msg"><div class="success">'.$lang['b_190'].'</div></div>';
		}
	}
?>
<div class="content-ex" style="text-align:left"><?=$mesaj?>
	<form action="" method="post">
			<p>
				<label><?=$lang['b_70']?></label><br />
				<input class="text-max" type="email" value="<?=(isset($_POST['email']) ? $_POST['email'] : '')?>" name="email" required="required" />
			</p>
			<p>
				<?
				if($site['captcha_sys'] == 1){
					echo '<script type="text/javascript"> var RecaptchaOptions = { theme : \'white\' }; </script>'.recaptcha_get_html($site['recaptcha_pub'], $recaptcha_error);
				}else{
				?>
				<label><?=$lang['b_51']?></label><br />
				<span style="background:#efefef;padding:7px;border-radius:3px;display:inline-block"><img src="captcha.php?img=<?=time();?>" alt="" /></span>
				<span style="margin-left:2px;display:inline;position:absolute"><input class="l_form" type="text" value="" name="captcha" required="required" /></span>
				<?}?>
			</p>
			<p>
				<input type="submit" class="gbut" value="<?=$lang['b_58']?>" name="resend" />
			</p>
	</form>
</div>
<?php
}else{
$mesaj = '';
if($site['reg_status'] == 0){
	$IP = VisitorIP();
	$IP = ($IP != '' ? $IP : 0);

	$sql = $db->Query("SELECT code FROM `list_countries` ORDER BY country");
	$ctrs = array();
	while ($row = $db->FetchArray($sql)) {
		$ctrs[] = $row['code'];
	}

	$c_done = 0;
	if($site['auto_country'] == 1){
		$a_country = iptocountry($IP);
		if(in_array($a_country, $ctrs)){
			$c_done = 1;
			$country = $a_country;
		}
	}

	if(isset($_POST['register'])){
		$name = $db->EscapeString($_POST['user']);
		$email = $db->EscapeString($_POST['email']);
		$email2 = $db->EscapeString($_POST['email2']);
		$gender = $db->EscapeString($_POST['gender']);
		$pass1 = $db->EscapeString($_POST['password']);
		$pass2 = $db->EscapeString($_POST['password2']);
		$subs = $db->EscapeString($_POST['subscribe']);
		$subs = ($subs != 0 && $subs != 1 ? 1 : $subs);
		if($c_done == 0){
			$country = $db->EscapeString($_POST['country']);
		}
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
		}elseif($db->QueryGetNumRows("SELECT id FROM `users` WHERE `login`='".$name."' OR `email`='".$email."'") > 0) {
			$mesaj = '<div class="msg"><div class="error">'.$lang['b_127'].'</div></div>';
		}elseif($site['more_per_ip'] != 1 && $db->QueryGetNumRows("SELECT id FROM `users` WHERE `IP`='".$IP."' OR `log_ip`='".$IP."'") > 0) {
			$mesaj = '<div class="msg"><div class="error"><b>'.$lang['b_128'].'</b></div></div>';
		}elseif($email != $email2) {
			$mesaj = '<div class="msg"><div class="error">'.$lang['b_279'] .'</div></div>';
		}elseif(!isUserID($name)) {
			$mesaj = '<div class="msg"><div class="error">'.$lang['b_129'].'</div></div>';
		}elseif(!isEmail($email)) {
			$mesaj = '<div class="msg"><div class="error">'.$lang['b_65'] .'</div></div>';
		}elseif($gender != 1 && $gender != 2) {
			$mesaj = '<div class="msg"><div class="error">'.$lang['b_208'].'</div></div>';
		}elseif($c_done == 0 && !in_array($country, $ctrs)) {
			$mesaj = '<div class="msg"><div class="error">'.$lang['b_209'].'</div></div>';
		}elseif(!checkPwd($pass1,$pass2)) {
			$mesaj = '<div class="msg"><div class="error">'.$lang['b_63'].'</div></div>';
		}else{
                    $referal = (isset($_COOKIE['PlusREF']) ? $db->EscapeString($_COOKIE['PlusREF']) : 0);
                    $ref_paid = 0;
                    if($site['reg_reqmail'] == 0){
                        $activate = rand(100000000, 999999999);
                        require_once 'phpmailer/class.phpmailer.php';
                        $mail  = new PHPMailer();
                        $body = '<html>
                                <body style="font-family: Verdana; color: #333333; font-size: 12px;">
                                        <table style="width: 350px; margin: 0px auto;">
                                                <tr style="text-align: center;">
                                                        <td style="border-bottom: solid 1px #cccccc;"><h2 style="text-align: right; font-size: 14px; margin: 7px 0 10px 0;">'.$lang['b_130'].'</h2></td>
                                                </tr>
                                                <tr style="text-align: justify;">
                                                        <td style="padding-top: 15px; padding-bottom: 15px;">
                                                                Hello '.$name.',
                                                                <br />
                                                                <br />
                                                                Click on this link to activate your account:<br />
                                                                <a href="'.$site['site_url'].'/activate.php?code='.$activate.'">'.$site['site_url'].'/activate.php?code='.$activate.'</a>
                                                        </td>
                                                </tr>
                                                <tr style="text-align: right; color: #777777;">
                                                        <td style="padding-top: 10px; border-top: solid 1px #cccccc;">
                                                                Best Regards!
                                                        </td>
                                                </tr>
                                        </table>
                                </body>
                        </html>';
                    $body             = eregi_replace("[\]",'',$body);
                    $mail->IsSMTP(); 
                    $mail->SMTPAuth   = true;                
                    $mail->SMTPSecure = "ssl";                 
                    $mail->Host       = "smtp.gmail.com";      
                    $mail->Port       = 465;                 
                    $mail->Username   = "info@hoanggia.net";  
                    $mail->Password   = "info@hoanggia.net";          
                    $mail->SetFrom($site['site_email'], 'Like mien phi');
                    $mail->AddReplyTo($site['site_email'],"Hoang Gia Media");
                    $mail->Subject    = $lang['b_130'];
                    $mail->CharSet = "utf-8";
                    $mail->MsgHTML($body);
                    $mail->AddAddress($email , $name);
                    $mail->AddBCC($email, $name);
                    $mail->Send();          
                    }else{
                        if($referal > 0 && is_numeric($referal) && $site['refsys'] == 1 && $site['aff_click_req'] == 0){
                            $sql = $db->Query("SELECT id FROM `users` WHERE `id`='".$referal."'");
                            $user = $db->FetchArray($sql);
                            if($user['id'] > 0){
                                    $add_cash = $site['paysys'] == 1 ? ", `account_balance`=`account_balance`+'".$site['ref_cash']."'" : '';
                                    $db->Query("UPDATE `users` SET `coins`=`coins`+'".$site['ref_coins']."'".$add_cash." WHERE `id`='".$user['id']."'");
                                    $ref_paid = 1;
                            }
                        }
                        $activate = 0;
			}
                        $passc = MD5($pass1);
			if(isset($_COOKIE['PESRefSource'])){
				$ref_source = $_COOKIE['PESRefSource'];
			}else{
				$ref_source = '0';
			}
			$db->Query("INSERT INTO `users`(email,login,country,sex,coins,account_balance,IP,pass,ref,ref_paid,signup,newsletter,activate,ref_source) values('".$email."','".$name."','".$country."','".$gender."','".$site['reg_coins']."','".$site['reg_cash']."','".$IP."','".$passc."','".$referal."','".$ref_paid."',NOW(),'".$subs."','".$activate."','".$ref_source."')");
			$mesaj = '<div class="msg"><div class="success">'.$lang['b_131'].' '.($site['reg_reqmail'] == 0 ? $lang['b_132'] : $lang['b_133']).'</div></div>';
		}
	}
?>	
<div class="content-ex" style="text-align:left"><?=$mesaj?>
	<form action="" method="post">
			<p class="reg_row_1">
				<label><?=$lang['b_122']?></label><br />
				<input class="text-max" type="text" value="<?=(isset($_POST['user']) ? $_POST['user'] : '')?>" name="user" required="required" />
			</p>
			<p class="reg_row_2"> </p>
			<p class="reg_row_1">
				<label><?=$lang['b_70']?></label><br />
				<input class="text-max" type="email" value="<?=(isset($_POST['email']) ? $_POST['email'] : '')?>" name="email" required="required" />
			</p>
			<p class="reg_row_2">
				<label><?=$lang['b_278']?></label><br />
				<input class="text-max" type="email" name="email2" required="required" />
			</p>
			<p class="reg_row_1">
				<label><?=$lang['b_15']?></label><br />
				<input class="text-max" type="password" value="" name="password" required="required" />
			</p>
			<p class="reg_row_2">
				<label><?=$lang['b_72']?></label><br />
				<input class="text-max" type="password" value="" name="password2" required="required" />
			</p>
			<p class="reg_row_1">
				<label><?=$lang['b_202']?></label><br />
				<select name="gender" class="styled" style="height:40px;width:315px">
					<option value="0"></option>
					<option value="1"><?=$lang['b_203']?></option>
					<option value="2"><?=$lang['b_204']?></option>
				</select>
			</p>
			<p class="reg_row_2">
				<label><?=$lang['b_201']?></label><br />
				<select name="country" class="styled" style="height:40px;width:315px" <?=($c_done == 1 ? 'disabled' : '')?>>
					<? 
						if($c_done == 1){
							$ctr = $db->QueryFetchArray("SELECT country,code FROM `list_countries` WHERE `code`='".$country."'"); 
							echo '<option value="'.$ctr['code'].'">'.$ctr['country'].'</option>';
						}else{
							$countries = $db->QueryFetchArrayAll("SELECT country,code FROM `list_countries` ORDER BY country ASC"); 
							echo '<option value="0"></option>';
							foreach($countries as $country){ 
								echo '<option value="'.$country['code'].'">'.$country['country'].'</option>';
							}
						}
					?>
				</select>
			</p>
			<p>
				<?
				if($site['captcha_sys'] == 1){
					echo '<script type="text/javascript"> var RecaptchaOptions = { theme : \'white\' }; </script>'.recaptcha_get_html($site['recaptcha_pub'], $recaptcha_error);
				}else{
				?>
				<label><?=$lang['b_51']?></label><br />
				<span style="background:#efefef;padding:7px;border-radius:3px;display:inline-block"><img src="captcha.php?img=<?=time();?>" alt="" /></span>
				<span style="margin-left:2px;display:inline;position:absolute"><input class="l_form" type="text" value="" name="captcha" required="required" /></span>
				<?}?>
			</p>
			<p>
				<label><?=$lang['b_245']?></label>
				<input type="radio" name="subscribe" value="1" checked="checked" /> <?=$lang['b_124']?> <input type="radio" name="subscribe" value="0" /> <?=$lang['b_125']?>
			</p>
			<p>
				<input type="submit" class="gbut" value="<?=$lang['b_58']?>" name="register" />
			</p>
	</form>
	<span style="float:right"><b><a href="register.php?resend" style="text-decoration:none"><?=$lang['b_227']?></a></b></span>
</div>
<?}else{?>
	<div class="content-ex"><div class="msg"><div class="error"><?=$lang['b_134']?></div></div></div>
<?php 
}}
include('footer.php');
?>
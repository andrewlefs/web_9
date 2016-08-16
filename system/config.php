<?php
error_reporting(0);
@ini_set('display_errors', 0);
@ini_set('allow_url_fopen', 1);
session_start();
require_once("database.php");
require_once("libs/functions.php");
require_once("libs/MySQL_connection.php");
define('LANG_PATH', realpath(dirname(__FILE__).'/..'));

/* Database connection */
$db = new MySQLConnection($config['sql_host'], $config['sql_username'], $config['sql_password'], $config['sql_database']);
$db->Connect();

unset($config['sql_password']);
	
/* Cron */
include_once("cron.php");

/* Website settings */
include_once("version.php");
$sql = $db->Query("SELECT * FROM settings LIMIT 1");
$site = $db->FetchArray($sql);
$CONF['dpb'] = '0';

/* User Session */
if(isset($_SESSION['EX_login'])){
	$ses_id = $db->EscapeString($_SESSION['EX_login']);
    $sql	= $db->Query("SELECT *,UNIX_TIMESTAMP(`online`) AS `online` FROM `users` WHERE `id`='".$ses_id."' AND `banned`='0'");
    $data	= $db->FetchArray($sql);
	$is_online = true;
	if(empty($data['id'])){
		session_destroy();
		$is_online = false;
	}elseif($data['online']+60 < time()){
		$db->Query("UPDATE `users` SET `online`=NOW() WHERE `id`='".$data['id']."'");
		$_SESSION['EX_login'] = $data['id'];
	}
}else{
	if(isset($_COOKIE['PESAutoLogin'])){
		parse_str($_COOKIE['PESAutoLogin']);
		if(!empty($ses_user) && !empty($ses_hash)){
			$sql	= $db->Query("SELECT *,UNIX_TIMESTAMP(`online`) AS `online` FROM `users` WHERE  (`login`='".$ses_user."' OR `email`='".$ses_user."') AND (`pass`='".$ses_hash."' AND `banned`='0')");
			$data	= $db->FetchArray($sql);
			if(empty($data['id'])){
				unset($_COOKIE['PESAutoLogin']); 
				$is_online = false;
			}else{
				$_SESSION['EX_login'] = $data['id'];
				$is_online = true;
			}
		}else{
			unset($_COOKIE['PESAutoLogin']); 
			$is_online = false;
		}
	}else{
		$is_online = false;
	}
}

/* Referral System */
if(isset($_GET['ref']) && is_numeric($_GET['ref'])){setcookie("PlusREF", $db->EscapeString($_GET['ref']), time()+3600);}
if($is_online){
	if($data['ref'] > 0 && $data['ref_paid'] != 1){
		$sql = $db->Query("SELECT SUM(`total_clicks`) AS `clicks` FROM `user_clicks` WHERE `uid`='".$data['id']."'");
		$ref_valid = $db->FetchArray($sql);
		if($ref_valid['clicks'] >= $site['aff_click_req']){
			$add_cash = $site['paysys'] == 1 ? ", `account_balance`=`account_balance`+'".$site['ref_cash']."'" : '';
			$db->Query("UPDATE `users` SET `coins`=`coins`+'".$site['ref_coins']."'".$add_cash." WHERE `id`='".$data['ref']."'");
			$db->Query("UPDATE `users` SET `ref_paid`='1' WHERE `id`='".$data['id']."'");
		}
	}
	if($data['premium'] > 0 && $data['premium'] < time()){
		$db->Query("UPDATE `users` SET `premium`='0' WHERE `id`='".$data['id']."'");
	}
}

/* Language system */
$lang_select = '';
$CONF['language'] = ($site['def_lang'] != '' && file_exists('languages/'.$site['def_lang'].'/index.php') ? $site['def_lang'] : 'en');
foreach(glob("languages/*/index.php") as $langname)
{
	$langarray[] = str_replace(array('languages/', '/index.php'), '', $langname);
	include_once($langname);
	if(isset($_COOKIE['peslang']) && !isset($_GET['peslang'])){
		$selected = ($_COOKIE['peslang'] == $c_lang['code'] ? ' selected' : '');
	}elseif(isset($_GET['peslang'])){
		$selected = ($_GET['peslang'] == $c_lang['code'] ? ' selected' : '');
	}else{
		$selected = ($CONF['language'] == $c_lang['code'] ? ' selected' : '');
	}
	if($c_lang['active'] != 0){
		$lang_select .= '<option value="'.$c_lang['code'].'"'.$selected.'>'.$c_lang['lang'].'</option>';
	}
}

if(isset($_GET['peslang']))
{
	if(in_array($_GET['peslang'], $langarray))
	{
		setcookie("peslang", $_GET['peslang'], time()+360000);
		$_COOKIE['peslang'] = $_GET['peslang'];
	}
}

if(isset($_COOKIE['peslang']) && $_COOKIE['peslang'] != ''){
	$CONF['language'] = $_COOKIE['peslang'];
}

foreach(glob(LANG_PATH."/languages/".$CONF['language']."/*/*.php") as $langfile) {  
	include_once($langfile);  
}  
$conf['lang_charset'] = (!empty($c_lang[$CONF['language'].'_charset']) ? $c_lang[$CONF['language'].'_charset'] : 'UTF-8');
ini_set('default_charset',$conf['lang_charset']); 

/* Modules system */
require_once("pluggable.php");
foreach(glob("system/modules/*/index.php") as $plugin) {  
	require_once($plugin);  
}  
hook_action('initialize');

/* Clear expired module sessions */
$clear_time = (time()-($site['surf_time_type'] == 1 ? (($site['surf_time']*$site['premium_cpc'])+30) : 120));
$db->Query("DELETE FROM `module_session` WHERE `timestamp`<'".$clear_time."'");

/* All rights reserved (c) Death God - XF_UG */
?>
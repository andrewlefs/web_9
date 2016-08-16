<?
$sql = $db->Query("SELECT time FROM `cron` WHERE `name`='day'");
$update = $db->FetchArray($sql);

$date = date('j F Y');
$timestamp = strtotime($date);

if($update['time'] < $timestamp){
	$db->Query("UPDATE `cron` SET `time`='".$timestamp."' WHERE `name`='day'");
	$db->Query("DELETE FROM `surfed`");
	$db->Query("DELETE FROM `viewed`");
	$db->Query("UPDATE `users` SET `premium`='0' WHERE `premium`>'0' AND `premium`<'".time()."'");
	$db->Query("UPDATE `users` SET `coins`='0' WHERE `coins`<'0'");
	$db->Query("UPDATE `user_clicks` SET `today_clicks`='0' WHERE `today_clicks`>'0'");
}
unset($update);
?>

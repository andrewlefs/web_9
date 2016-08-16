<?php
define('BASEPATH', true);
include('system/config.php');

$id = $db->EscapeString($_GET['go']);
$banners = $db->QueryFetchArray("SELECT site_url FROM `banners` WHERE `id`='".$id."'");
$db->Query("UPDATE `banners` SET `clicks`=`clicks`+'1' WHERE `id`='".$id."'");
header("Location: ".$banners['site_url']);
?>
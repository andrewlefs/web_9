<?php
define('BASEPATH', true);
include("system/config.php");
if(isset($_COOKIE['PESAutoLogin'])){
	setcookie('PESAutoLogin', '0', time()-604800);
}
session_destroy();
header("Location: index.php");
?> 
<?php
function executeSql($sqlFileToExecute){
    $templine = '';
	$lines    = file($sqlFileToExecute);
	$impError = 0;
	foreach($lines as $line) {
		if(substr($line, 0, 2) == '--' || $line == '')
			continue;
		$templine .= $line;
		if (substr(trim($line), -1, 1) == ';') {
			if (mysql_query($templine)) {
			} else {
				$impError = 1;
			}
			$templine = '';
		}
	}
    if ($impError == 0) {
        return "Script is executed succesfully!";
    } else {
        return "An error occured during installation!<br>"
        . "Error code: $sqlErrorCode<br/>"
        . "Error text: $sqlErrorText<br/>"
        . "Statement:<br/> $sqlStmt<br/>";
    }
}

function redirect($location){
    $hs = headers_sent();
    if($hs === false){
        header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        header("Location: $location");
    }elseif($hs == true){
        echo "<script>document.location.href='".htmlspecialchars($location)."'</script>";
    }
    exit(0);
}

function checkPwd($x,$y){
	if(empty($x) || empty($y) ) { return false; }
	if (strlen($x) < 4 || strlen($y) < 4) { return false; }
	if (strcmp($x,$y) != 0) { return false; } 
	return true;
}

function VisitorIP(){ 
    if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{ 
		$ip = $_SERVER['REMOTE_ADDR'];
	}
    return trim($ip);
}

function isEmail($email){
	return preg_match('/^\S+@[\w\d.-]{2,}\.[\w]{2,6}$/iU', $email) ? true : false;
}

function isUserID($username){
	return preg_match('/^[a-z\d_]{3,20}$/i', $username) ? true : false;
}

function isUserNume($nume){
	return preg_match('/^[a-zA-Z]$/i', $nume) ? true : false;
}

function is_404($url){
    $handle = curl_init($url);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
    $response = curl_exec($handle);
    $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
    curl_close($handle);
    if ($httpCode >= 200 && $httpCode < 300) {
        return false;
    } else {
        return true;
    }
}

function truncate($str, $length, $trailing='...'){
	if(function_exists('mb_strlen') && function_exists('mb_substr')){
		$length-=mb_strlen($trailing);
		if(mb_strlen($str)> $length){
			return mb_substr($str,0,$length).$trailing;
		}else{
			$res = $str;
		}
		return $res;
	}else{
		return $str;
	}
} 

function get_data($url, $timeout = 30){
	$ch = curl_init();
	$header = array(
                "Accept: text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*\/*;q=0.5",
                "Accept-Language: en-us,en;q=0.5",
                "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7",
				"Cache-Control: must-revalidate, max-age=0",
				"Connection: keep-alive",
				"Keep-Alive: 300",
				"Pragma: public");
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_HTTPHEADER,$header); 
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_FORBID_REUSE,1); 
	curl_setopt($ch,CURLOPT_FRESH_CONNECT,1); 
	curl_setopt($ch,CURLOPT_IPRESOLVE,CURL_IPRESOLVE_V4); 
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
	$data = curl_exec($ch);
	curl_close($ch);
	if(!empty($data)){
		return $data;
	}elseif(function_exists('file_get_contents')){
		return file_get_contents($url);
	}else{
		return '';
	}
}

function reverse_url($url){
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_HEADER,1);
	curl_setopt($ch,CURLOPT_NOBODY,1);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_TIMEOUT,10);
	curl_setopt($ch,CURLOPT_IPRESOLVE,CURL_IPRESOLVE_V4); 
	$result = curl_exec($ch);
	if(!empty($result)){
		return $result;
	}else{
		return null;
	}
}

function check_license($email,$domain){
	$qry_str = "email=".$email."&host=".$domain;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, base64_decode('aHR0cDovL21uLXNob3AubmV0L2xpY2Vuc2UvcGVzX2NoZWNrLnBocA==')); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $qry_str);
	curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4); 
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
	$result = trim(curl_exec($ch));
	curl_close($ch);
	if($result != ''){
		return $result;
	}else{
		return 'true';
	}
}

function BBCode($string){
	$search = array(
			'@\[(?i)b\](.*?)\[/(?i)b\]@si',
			'@\[(?i)i\](.*?)\[/(?i)i\]@si',
			'@\[(?i)u\](.*?)\[/(?i)u\]@si',
			'@\[(?i)img\](.*?)\[/(?i)img\]@si',
			'@\[(?i)url=(.*?)\](.*?)\[/(?i)url\]@si',
			'@\[(?i)code\](.*?)\[/(?i)code\]@si'
	);
	$replace = array(
			'<b>\\1</b>',
			'<i>\\1</i>',
			'<u>\\1</u>',
			'<img src="\\1">',
			'<a href="\\1">\\2</a>',
			'<code>\\1</code>'
	);
	return preg_replace($search, $replace, $string);
}
 
function percent($num_amount, $num_total){
	$count = ($num_amount/$num_total)*100;
	return number_format($count,0);
}

function get_country($code){
	global $db;
	$code = $db->EscapeString($code);
	$country = $db->QueryFetchArray("SELECT country FROM `list_countries` WHERE `code`='".$code."' LIMIT 1");
	return $country['country'];
}

function get_gender($id, $man='Man', $woman='Woman', $unknow='Unknown'){
	$gender = ($id == 1 ? $man : ($id == 2 ? $woman : $unknow));
	return $gender;
}

function get_currency_symbol($code){
	$code = ($code == 'USD' ? '$' : ($code == 'EUR' ? '&euro;' : ($code == 'GBP' ? '&pound;' : ($code == 'HUF' ? 'Ft' : ($code == 'JPY' ? '&yen;' : $code)))));
	return $code;
}

function hideref($strUrl, $protect = 1, $key = 0){
	if($protect == 0){
		return $strUrl;
	}else{
		return "http://anonym.to/?".$strUrl;
	}
}

function iptocountry($ip) {
	$numbers = preg_split( "/\./", $ip); 
	if(!is_numeric($numbers[0]) && $numbers[0] >= 0 && $numbers[0] <= 255){
		return false;
	}
	include('ip_files/'.$numbers[0].'.php');
	$code = ($numbers[0] * 16777216) + ($numbers[1] * 65536) + ($numbers[2] * 256) + ($numbers[3]);   
	foreach($ranges as $key => $value){
		if($key <= $code){
			if($ranges[$key][0] >= $code){
				$country = $ranges[$key][1];
				break;
			}
		}
	}
	return (empty($country) ? 'unknown' : $country);
}

function lang_rep($text, $inputs = array()){
	if (empty($inputs) || !is_array($inputs)) return $text;
			
		foreach ($inputs as $search => $replace){
			$text = str_replace($search, $replace, $text);
		}

	return $text;
}
?>
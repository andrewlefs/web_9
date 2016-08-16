<?php
$titleweb =  'Thưởng hàng ngày | Tăng like Facebook, tang like facebook, tăng like, tang like, like facebook';
include('header.php');
if(!$is_online || $site['daily_bonus'] == 0){
	redirect('index.php');
	exit;
}

function r_time($seconds) {
  $measures = array(
    'day'=>24*60*60,
    'hour'=>60*60,
    'minute'=>60,
    'second'=>1,
    );
  foreach ($measures as $label=>$amount) {
    if ($seconds >= $amount) {  
      $howMany = floor($seconds / $amount);
      return $howMany." ".$label.($howMany > 1 ? "s" : "");
    }
  } 
}  

$sql = $db->Query("SELECT SUM(`today_clicks`) AS `clicks` FROM `user_clicks` WHERE `uid`='".$data['id']."'");
$cf_bonus = $db->FetchArray($sql);
if($cf_bonus['clicks'] > 0){
	$cf_bonus = $cf_bonus['clicks'];
}else{
	$cf_bonus = 0;
}

if(($data['daily_bonus']+86400) < time()){
?>
<script type="text/javascript">
	var msg1 = '<?=mysql_escape_string(lang_rep($lang['b_38'], array('-NUM-' => ($data['premium'] > 0 ? $site['daily_bonus_vip'] : $site['daily_bonus']))))?>';
	var msg2 = '<?=mysql_escape_string($lang['b_39'])?>';
	eval(function(p,a,c,k,e,r){e=function(c){return c.toString(a)};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('5 c(){$("#6").3();$("#4").d();$.e({f:"g",h:"i/6.j",k:"l=1",m:n,7:5(a){o(a==1){$("#4").3();$("#8").9(\'<0 2="b"><0 2="7">\'+p+\'</0></0>\')}q{$("#4").3();$("#8").9(\'<0 2="b"><0 2="r">\'+s+\'</0></0>\')}}})}',29,29,'div||class|hide|loading|function|bonus|success|txtHint|html||msg|checkBonus|show|ajax|type|POST|url|system|php|data|get|cache|false|if|msg1|else|error|msg2'.split('|'),0,{}))
</script><?}?>
<div class="content">
	<h2 class="title"><?=$lang['b_09']?></h2>
	<h2><?=lang_rep($lang['b_40'], array('-NUM-' => ($data['premium'] > 0 ? $site['daily_bonus_vip'] : $site['daily_bonus'])))?></h2> <br/>	<img src="http://www.likemienphi.net/likemienphi-vcoins.png" >
<?if(($data['daily_bonus']+86400) < time()){?><div id="txtHint"></div>
<?if($cf_bonus < $site['crf_bonus']){?>
	<div class="msg"><div class="error"><?=lang_rep($lang['b_225'], array('-NUM-' => $site['crf_bonus'], '-REM-' => ($site['crf_bonus'] - $cf_bonus)))?></div></div>
<?}else{?>
	<img src="img/loader.gif" alt="Loading..." title="Loading..." id="loading" style="display:none" />
	<input type="button" id="bonus" class="gbut" name="bonnus" onclick="checkBonus()" value="<?=$lang['b_166']?>" />
<?}}else{?>
	<div class="msg"><div class="error"><?=lang_rep($lang['b_41'], array('-TIME-' => r_time(($data['daily_bonus']+86400)-time())))?></div></div>
<?}?>
</div>	
<?include('footer.php');?> 
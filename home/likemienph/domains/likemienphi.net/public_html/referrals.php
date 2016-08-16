<?php
include('header.php');
if(!$is_online || $site['refsys'] != 1){
	redirect('index.php');
	exit;
}

$refs = $db->GetNumRows($db->Query("SELECT id FROM `users` WHERE `ref`='".$data['id']."'"));
?>
<div class="content"><h2 class="title"><?=$lang['b_121']?></h2>
	<div class="infobox" style="width:250px;margin:10px auto;display:inline-block"><b><?=$lang['b_119']?>: <span style="font-weight:600;color:blue"><?=$refs?></span></b></div>
	<?if($site['paysys'] == 1){?><div class="infobox" style="width:250px;margin:10px auto;display:inline-block"><b><?=$lang['b_255']?>: <span style="font-weight:600;color:green"><?=$data['account_balance']?> $</span></b></div><?}?>
	<br><hr><br>
							<table class="table">
                                <thead>
                                    <tr>
                                        <td width="20">ID</td>
                                        <td><?=$lang['b_122']?></td>
                                        <td><?=$lang['b_106']?></td>
										<td><?=$lang['b_244']?></td>
                                    </tr>
                                </thead>
								<tfoot>
                                    <tr>
                                        <td>ID</td>
                                        <td><?=$lang['b_122']?></td>
                                        <td><?=$lang['b_106']?></td>
										<td><?=$lang['b_244']?></td>
                                    </tr>
                                </tfoot>
                                <tbody>
<?
  $sql = $db->Query("SELECT id FROM `users` WHERE `ref`='".$data['id']."'");
  $num = $db->GetNumRows($sql);
  $pages = floor($num/20+1);
  $begin = ($_GET['p'] >= 0) ? $_GET['p']*20 : 0;
  $sql = $db->Query("SELECT id,login,signup,ref_paid FROM `users` WHERE `ref`='".$data['id']."' ORDER BY `signup` DESC LIMIT ".$begin.",20");
  $users = $db->FetchArrayAll($sql);
  if($db->GetNumRows($sql) == 0){
	echo '<tr><td colspan="4">'.$lang['b_250'].'</td></tr>';
  }
  foreach($users as $user){
?>	
                                    <tr>
                                        <td>#<?=$user['id']?></td>
                                        <td><?=$user['login']?></td>
                                        <td><?=$user['signup']?></td>
										<td><?=($user['ref_paid'] == 1 ? $lang['b_124'] : $lang['b_125'])?></td>
                                    </tr>
<?}?>
                                </tbody>
                            </table>
<?if($pages > 1){?>
<div class="infobox">
    <div style="float:left;">
<?
$prev = $_GET['p']-1;
$next = $_GET['p']+1;
if($num >= 0) {
	if($begin/20 == 0) {
		echo '<img src="theme/pes/images/black_arrow_left.png" />';
	}else{
		echo '<a href="?p='.($begin/20-1).'"><img src="theme/pes/images/black_arrow_left.png" /></a>';
	}
	
	$bg1 = $begin+1;
	$bg2 = ($begin+20 >= $num ? $num : $begin+20);
	echo "&nbsp;&nbsp; {$bg1} - {$bg2} &nbsp;&nbsp;";
	
	if($begin+20 >= $num) {
		echo '<img src="theme/pes/images/black_arrow_right.png" />';
	}else{
		echo '<a href="?p='.($begin/20+1).'"><img src="theme/pes/images/black_arrow_right.png" /></a>';
	}
}
?>
	</div>
	<div style="float:right;">
		<b><?=$bg1?> - <?=$bg2?></b> <?=lang_rep($lang['b_126'], array('-NUM-' => $num))?>
	</div>
	<div style="display:block; clear:both;"></div>
</div>
<?}?>
</div>
<?include('footer.php');?>
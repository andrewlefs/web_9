<?php

$titleweb =  'Tăng like Facebook, tang like facebook';
include('header.php');?>
<link rel="stylesheet" type="text/css" href="theme/pes/blog.css" />
<div class="content t-left">
<h2 class="title"><?=$lang['b_287']?></h2>
<?php

if(isset($_GET['bid']) && is_numeric($_GET['bid'])){
	$id = $db->EscapeString($_GET['bid']);
	$blog = $db->QueryFetchArray("SELECT a.*, b.login FROM blog a LEFT JOIN users b ON b.id = a.author WHERE a.id = '".$id."'");
	if(empty($blog['id'])){
		redirect('blog.php');
		exit;
	}
	
	if(isset($_GET['cd']) && is_numeric($_GET['cd']) && $data['admin'] == 1){
		$id = $db->EscapeString($_GET['cd']);
		$db->Query("DELETE FROM `blog_comments` WHERE `id`='".$id."'");
	}
	
	$msg = '';
	if($site['blog_comments'] == 0){
		$msg = '<div class="msg"><div class="info">'.$lang['b_280'].'</div></div>';
	}elseif(!$is_online){
		$msg = '<div class="msg"><div class="info">'.$lang['b_281'].'</div></div>';
	}else{
		$n1 = rand(1,49);
		$n2 = rand(1,49);
		if(isset($_POST['comment']) && !empty($data['id'])){
			$comment = $db->EscapeString($_POST['comment_text']);

			if(($_POST['n1']+$_POST['n2']) != $_POST['captcha']){
				$msg = '<div class="msg"><div class="error">'.$lang['b_54'].'</div></div>';
			}elseif(strlen($comment) < 20 || strlen($comment) > 2255){
				$msg = '<div class="msg"><div class="error">'.$lang['b_282'].'</div></div>';
			}elseif($db->QueryGetNumRows("SELECT id FROM `blog_comments` WHERE `author`='".$data['id']."' AND `timestamp`>'".(time()-60)."'") > 0){
				$msg = '<div class="msg"><div class="error">'.$lang['b_288'].'</div></div>';
			}else{
				$db->Query("INSERT INTO `blog_comments` (`bid`,`author`,`comment`,`timestamp`)VALUES('".$blog['id']."','".$data['id']."','".$comment."','".time()."')");
			}
		}
	}

	$comments = $db->QueryGetNumRows("SELECT id FROM `blog_comments` WHERE `bid`='".$blog['id']."'");
	$content = stripslashes($blog['content']);
	$content = BBCode($content);
	$content = nl2br($content);
?>
	<div class="br_row2">
		<div class="blog_title">
			<div class="user_tooltip_info_user"><span><a href="blog.php?bid=<?=$blog['id']?>"><b><?=truncate($blog['title'], 100)?></b></a></span></div>
		</div>
		<div class="blog_info">
			<span><?=$lang['b_283']?>: <i><?=$blog['login']?></i></span><span style="float:right"><?=$lang['b_284']?>: <i><?=number_format($comments)?></i> | <?=$lang['b_106']?>: <i><?=date('d M Y H:i', $blog['timestamp'])?></i></span>
		</div>
		<div class="user_browse_info" style="padding-top:4px">
			<blockquote class="blog_content"><span><?=$content?></span></blockquote>
		</div>
	</div>
<?
echo $msg;
if($is_online && $site['blog_comments'] == 1){
	$num = $db->QueryGetNumRows("SELECT id FROM `blog_comments` WHERE `bid`='".$blog['id']."'");
	$bpp = 30;
	$pages = ceil($num/$bpp);
	$page = intval($_GET['p']);
	$begin = ($page >= 0 ? ($page*$bpp) : 0);

	$sql = $db->Query("SELECT a.id, a.author, a.comment, a.timestamp, b.login FROM blog_comments a LEFT JOIN users b ON b.id = a.author WHERE a.bid = '".$blog['id']."' ORDER BY a.timestamp DESC LIMIT ".$begin.", ".$bpp);
	$comments = $db->FetchArrayAll($sql);
	foreach($comments as $comm){
?>
	<div class="comments_wrap">
		<div class="content_top"><?=$comm['login']?> <span style="float:right"><i><?=date('d M Y H:i', $comm['timestamp'])?></i><?=($data['admin'] == 1 ? ' - <a href="blog.php?bid='.$blog['id'].'&cd='.$comm['id'].'" onclick="return confirm(\'Are you sure?\');" style="color:red">Delete</a>' : '')?></span></div>
		<div class="content_text">
			<?=nl2br(stripslashes($comm['comment']))?>
		</div>
	</div>
<?}
if($pages > 1){?>
<div class="infobox" style="width:580px;margin:10px auto 0">
<?
$prev = $page-1;
$next = $page+1;
if($num >= 0) {
	if($begin/$bpp == 0) {
		echo '<span><img src="theme/pes/images/black_arrow_left.png" /></span>';
	}else{
		echo '<span><a href="?bid='.$blog['id'].'&p='.($begin/$bpp-1).'"><img src="theme/pes/images/black_arrow_left.png" /></a></span>';
	}

	if($begin+$bpp >= $num) {
		echo '<span style="float:right;"><img src="theme/pes/images/black_arrow_right.png" /></span>';
	}else{
		echo '<span style="float:right;"><a href="?bid='.$blog['id'].'&p='.($begin/$bpp+1).'"><img src="theme/pes/images/black_arrow_right.png" /></a></span>';
	}
}
?>
</div>
<?}?>
	<div class="blog_comment"><div class="com_title"><?=$lang['b_285']?></div>
        <form method="post">
			<input type="hidden" name="n1" value="<?=$n1?>" /><input type="hidden" name="n2" value="<?=$n2?>" />
			<textarea name="comment_text" rows="4" required="required"></textarea>
			<?=$n1?> + <?=$n2?> = <input type="text" name="captcha" maxlength="2" style="width:18px;" required="required" />
            <p align="center">
                <input type="submit" name="comment" class="gbut" value="<?=$lang['b_58']?>" />
            </p>
        </form>
    </div>
<?}}else{
$num = $db->QueryGetNumRows("SELECT id FROM `blog`");
$bpp = 30;
$pages = ceil($num/$bpp);
$page = intval($_GET['p']);
$begin = ($page >= 0 ? ($page*$bpp) : 0);
$sql = $db->Query("SELECT a.*, b.login FROM blog a LEFT JOIN users b ON b.id = a.author ORDER BY a.timestamp DESC LIMIT ".$begin.", ".$bpp);
if($db->GetNumRows($sql) == 0){
	echo '<div class="msg"><div class="info">'.$lang['b_250'].'</div></div>';
}
$blogs = $db->FetchArrayAll($sql);
foreach($blogs as $blog){
	$comments = $db->QueryGetNumRows("SELECT id FROM `blog_comments` WHERE `bid`='".$blog['id']."'");
	$content = stripslashes($blog['content']);
	$content = truncate($content, 275);
	$content = BBCode($content);
	$content = nl2br($content);
?>
	<div class="br_row2">
		<div class="blog_title">
			<div class="user_tooltip_info_user"><span><a href="blog.php?bid=<?=$blog['id']?>"><b><?=truncate($blog['title'], 100)?></b></a></span></div>
		</div>
		<div class="blog_info">
			<span><?=$lang['b_283']?>: <i><?=$blog['login']?></i></span><span style="float:right"><?=$lang['b_284']?>: <i><?=number_format($comments)?></i> | <?=$lang['b_106']?>: <i><?=date('d M Y H:i', $blog['timestamp'])?></i></span>
		</div>
		<div class="user_browse_info" style="padding-top:4px">
			<blockquote class="blog_content"><span><?=$content?></span></blockquote>
		</div>
		<div class="blog_foot">
			<a href="blog.php?bid=<?=$blog['id']?>"><i><?=$lang['b_286']?></i></a>
		</div>
		<div class="clear"></div>
	</div>
<?}if($pages > 1){?>
<div class="infobox">
    <div style="float:right;">
<?
$prev = $page-1;
$next = $page+1;
if($num >= 0) {
	if($begin/$bpp == 0) {
		echo '<img src="theme/pes/images/black_arrow_left.png" />';
	}else{
		echo '<a href="?p='.($begin/$bpp-1).'"><img src="theme/pes/images/black_arrow_left.png" /></a>';
	}
	echo "&nbsp;&nbsp; ".floor(($begin/$bpp)+1)." / ".$pages." &nbsp;&nbsp;";
	
	if($begin+$bpp >= $num) {
		echo '<img src="theme/pes/images/black_arrow_right.png" />';
	}else{
		echo '<a href="?p='.($begin/$bpp+1).'"><img src="theme/pes/images/black_arrow_right.png" /></a>';
	}
}
?>
	</div>
	<div style="display:block;clear:both;"></div>
</div>
<?}}?>
</div>
<?include('footer.php');?>  
<?php if(!defined('IN_MEMBER')) exit('Request Error!'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $cfg_webname; ?> - 会员中心 - 课程管理</title>
<link href="<?php echo $cfg_webpath; ?>/templates/default/style/member.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo $cfg_webpath; ?>/templates/default/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $cfg_webpath; ?>/templates/default/js/member.js"></script>
</head>

<body>
<div class="header">
	<?php require_once(dirname(__FILE__).'/header.php'); ?>
</div>
<div class="mainbody">
	<div class="leftarea">
		<?php require_once(dirname(__FILE__).'/lefter.php'); ?>
	</div>
	<div class="rightarea">
		<h3 class="subtitle"><a href="?c=course_edit" style="float:right; color:#00F;">添加课程</a>课程管理</h3>
		<?php
		$r = $dosql->GetOne("SELECT id FROM `#@__member` WHERE `username`='$c_uname'");
	    $uid = $r['id'];
		$dopage->GetPage("SELECT * FROM `#@__course` WHERE uid='$uid' AND is_delete='false' ORDER BY id DESC");
		if($dosql->GetTotalRow() > 0)
		{
		?>
		<form name="form" id="form" method="post">
		<ul class="list">
			<?php
			while($row = $dosql->GetArray())
			{
			?>
			<li><span class="time"><?php echo GetDateTime($row['posttime']); ?> &nbsp;|&nbsp; <a href="?c=lesson&id=<?php echo $row['id']; ?>">章节管理</a> &nbsp;|&nbsp; <a href="?c=course_edit&id=<?php echo $row['id']; ?>">修改</a></span><input type="checkbox" name="checkid[]" id="checkid[]" value="<?php echo $row['id']; ?>" />&nbsp;&nbsp;<a href="course.php?id=<?php echo $row['id'];?>" target="_blank"><?php echo $row['title']; ?></a></li>
			<?php
			}
			?>
		</ul>
		</form>
		<div class="options_b">选择： <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:DelAllNone('?c=delcourse');" onclick="return ConfDelAll(2);">删除</a></div>
		<?php echo $dopage->GetList(); ?>
		<?php
		}
		else
		{
		?>
		<div class="nonelist">您还没有课程哦！</div>
		<?php
		}
		?>
	</div>
	<div class="cl"></div>
</div>
<div class="footer"><?php echo $cfg_copyright; ?></div>
</body>
</html>

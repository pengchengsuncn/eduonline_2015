<?php if(!defined('IN_MEMBER')) exit('Request Error!'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $cfg_webname; ?> - 会员中心 - 学生列表</title>
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
		<?php
			$class = $dosql->GetOne("SELECT name FROM `#@__class` WHERE id=$classid");
		?>
		<h3 class="subtitle">选择学生添加到&nbsp;&nbsp;<?php echo $class['name']; ?></h3>
		<?php
			//列出当前没有加入任何班级的所有学生
			$dopage->GetPage("
				SELECT *
				FROM `#@__member`
				WHERE id NOT IN(
					SELECT DISTINCT stu_id
					FROM `#@__class_stu`
					WHERE status='enroll'
				)
				AND is_teacher = 'false'
				ORDER BY cnname, username
			",9);
		if($dosql->GetTotalRow() > 0)
		{
		?>
		<form name="form" id="form" method="post" action="?a=enrollstu">
			<ul class="msglist">
				<?php
					while($row = $dosql->GetArray()){
				?>
				<li>
					<p>
						<?php
							echo "<input type='checkbox' name='checkid[]' id='checkid[]' value='".$row['id']."' />";
							echo "&nbsp;&nbsp;";
							echo $row['cnname']."(".$row['username'].")";
						?>
					</p>
					<div class="cl"></div>
				</li>
				<?php
					}
				?>
			</ul>
			<div class="btn_area">
				<input type="submit" class="btn" value="提 交" />
				<input type="hidden" name="classId" id="class-id" value="<?php echo $classid; ?>" />
				<input type="hidden" name="userId" id="user-id" value="<?php echo $r_user['id']; ?>" />
			</div>
		</form>
		<div class="options_b">选择： <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a></div>
		<?php echo $dopage->GetList(); ?>
		<?php
		}
		else
		{
		?>
		<div class="nonelist">暂未查到学生哦！</div>
		<?php
		}
		?>
	</div>
	<div class="cl"></div>
</div>
<div class="footer"><?php echo $cfg_copyright; ?></div>
</body>
</html>

<?php if(!defined('IN_MEMBER')) exit('Request Error!'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $cfg_webname; ?> - 会员中心 - 班级学生列表</title>
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
		<h3 class="subtitle"><?php echo $class['name']; ?>&nbsp;&nbsp;学生列表
			<a style='float:right; color:blue;' href="?c=stulist&classid=<?php echo $classid; ?>">添加学生</a>
		</h3>
		<?php
			$dopage->GetPage("
				SELECT
					c.id,
					c.username AS sUserName,c.cnname AS sCNName
				FROM `#@__class_stu` a
					INNER JOIN `#@__class` b
					ON a.class_id = b.id
					INNER JOIN `#@__member` c
					ON a.stu_id = c.id
				WHERE a.class_id = $classid
				AND a.status = 'enroll'
			",9);
		if($dosql->GetTotalRow() > 0)
		{
		?>
		<form name="form" id="form" method="post">
			<ul class="msglist">
				<?php
					while($row = $dosql->GetArray()){
				?>
				<li>
					<p>
						<?php
							echo "<input type='checkbox' name='checkid[]' id='checkid[]' value='".$row['id']."' />";
							echo "&nbsp;&nbsp;";
							echo $row['sCNName']."(".$row['sUserName'].")";
						?>
					</p>
					<div class="cl"></div>
				</li>
				<?php
					}
				?>
			</ul>
			<input type="hidden" name="classId" id="class-id" value="<?php echo $classid; ?>" />
			<input type="hidden" name="userId" id="user-id" value="<?php echo $r_user['id']; ?>" />
		</form>
		<div class="options_b">选择： <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:DelAllNone('?a=stuout');" onclick="return ConfDelAll(0);">删除</a></div>
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

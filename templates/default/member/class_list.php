<?php if(!defined('IN_MEMBER')) exit('Request Error!'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $cfg_webname; ?> - 会员中心 - 班级列表</title>
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
		<h3 class="subtitle">班级列表
			<a style='float:right; color:blue;' href="?c=newclass">创建班级</a>
		</h3>
		<?php
			$dopage->GetPage("
				SELECT a.*,b.username AS tUserName,b.cnname AS tCNName
				FROM `#@__class` a
					INNER JOIN `#@__member` b
					ON a.head_teacher = b.id
				WHERE a.status = 'active'
				ORDER BY id DESC
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
						echo "<a href='?c=classstu&classid=".$row['id']."'>".$row['name']."</a>";
						echo "<span style='float:right;'><a style='color:green;' href='?c=classedit&id=".$row['id']."'>编辑</a></span>";
					?>
				</p>
				<span class="from">班主任：
					<?php
						echo $row['tCNName']."(".$row['tUserName'].")";	
						echo "&nbsp;&nbsp;";
						echo "<span style='color:red;'>学生人数：0</span>";										
					?>
				</span>
				<span class="time">创建时间：
					<?php echo $row['create_date']; ?>
				</span>
				<div class="cl"></div>
			</li>
			<?php
				}
			?>
		</ul>
		</form>
		<div class="options_b">选择： <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:DelAllNone('?a=delclass');" onclick="return ConfDelAll(0);">删除</a></div>
		<?php echo $dopage->GetList(); ?>
		<?php
		}
		else
		{
		?>
		<div class="nonelist">暂未查到班级哦！</div>
		<?php
		}
		?>
	</div>
	<div class="cl"></div>
</div>
<div class="footer"><?php echo $cfg_copyright; ?></div>
</body>
</html>

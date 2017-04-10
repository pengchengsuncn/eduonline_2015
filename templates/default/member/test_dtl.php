<?php if(!defined('IN_MEMBER')) exit('Request Error!'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $cfg_webname; ?> - 会员中心 - 试卷题目</title>
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
			$test = $dosql->GetOne("SELECT * FROM `#@__test` WHERE id=$testid");
			$userTestSummary = $dosql->GetOne("SELECT * FROM `#@__test_summary` WHERE test_id=$testid");
		?>
		<h3 class="subtitle">《<?php echo $test['name']; ?>》&nbsp;&nbsp;题目列表
			<?php
				if(!is_array($userTestSummary)){
			?>
			<a style="float:right; font-size:1em; color:green;" href="javascript:createQues(<?php echo $testid ?>);">添加题目</a>
			<?php
				}
			?>
		</h3>
		<?php
		$dopage->GetPage("SELECT * FROM `#@__question` WHERE test_id=".$test['id']." AND status = 'active' ORDER BY id DESC",9);
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
						// 前端只有老师只能对未有学员参加考试的试卷进行删除、编辑
						if(!is_array($userTestSummary)){
							echo "<input type='checkbox' name='checkid[]' id='checkid[]' value='".$row['id']."' />";
							echo "&nbsp;&nbsp;";
							echo "<a>".$row['title']."</a>";
							echo "<span style='float:right;'><a style='color:green;' href='?c=ques_editor&id=".$row['id']."'>编辑</a></span>";
						}else{
							echo "<a>".$row['title']."</a>";
							echo "<span style='float:right;'><a style='color:green;' href='?c=ques_editor&id=".$row['id']."'>查看</a></span>";
						}
					?>
				</p>
				<span class="from">试题类型：
					<?php
						$type= $dosql->GetOne("SELECT * FROM `#@__subject_type` WHERE id=$row[type]");
						echo $type['type'];
					?>
				</span>
				<span class="time">分值：
					<?php echo $row['score']; ?>
				</span>
				<div class="cl"></div>
			</li>
			<?php
				}
			?>
		</ul>
		</form>
		<?php
			// 前端只有老师能删除试卷
			if($r_user['is_teacher']=="true"){
		?>
			<div class="options_b">选择： <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:DelAllNone('?a=delquestion');" onclick="return ConfDelAll(0);">删除</a></div>
		<?php
			}
		?>
		
		<?php echo $dopage->GetList(); ?>
		<?php
		}
		else
		{
		?>
		<div class="nonelist">暂未查到题目哦！</div>
		<?php
		}
		?>
	</div>
	<div class="cl"></div>
</div>
<div class="footer"><?php echo $cfg_copyright; ?></div>
</body>
</html>

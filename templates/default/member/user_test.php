<?php if(!defined('IN_MEMBER')) exit('Request Error!'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $cfg_webname; ?> - 会员中心 - 学生考试情况</title>
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
			$userTestSummary = $dosql->GetOne("SELECT *, NOW() AS curr_date FROM `#@__test_summary` WHERE test_id=$testid");
		?>
		<h3 class="subtitle">《<?php echo $test['name']; ?>》&nbsp;&nbsp;学生考试情况</h3>
		<?php
		$dopage->GetPage("
			SELECT a.*,b.username,NOW() AS curr_date FROM `#@__test_summary` a
				INNER JOIN `#@__member` b
				ON a.user_id = b.id
			WHERE a.test_id=".$test['id']
		,9);
		if($dosql->GetTotalRow() > 0)
		{
		?>
		<ul class="msglist">
			<?php
				while($row = $dosql->GetArray()){
			?>
			<li>
				<p>
					<?php
						echo $row['username'];
						if($row['test_result'] != ""){
							if($row['test_result'] == "P"){
								echo "<span style='float:right; color:green;'>(".$row['test_score'].")及格</span>";								
							}else{
								echo "<span style='float:right; color:red;'>(".$row['test_score'].")不及格</span>";	
							}
						}else{
							if($row['submit_date'] != "0000-00-00 00:00:00"){
								echo "<span style='float:right;'><a href='?c=evaltest&testid=".$row['test_id']."&userid=".$row['user_id']."'>立即批卷</a></span>";
							}else{
								if(strtotime($userTestSummary['end_date']) < strtotime($userTestSummary['curr_date'])){
									echo "<span style='float:right;'><a href='?c=evaltest&testid=".$row['test_id']."&userid=".$row['user_id']."'>立即批卷</a></span>";
								}
							}
						}
					?>
				</p>
				<span class="from">考试状态：
					<?php
						if($row['test_result'] != ""){
							echo "考试完成";
						}else{
							if($row['submit_date'] != "0000-00-00 00:00:00"){
								echo "已提交考试";
							}else{
								if(strtotime($userTestSummary['end_date']) < strtotime($userTestSummary['curr_date'])){
									echo "考试结束未提交";
								}else{
									echo "考试正在进行";
								}
							}
						}						
					?>
				</span>
				<span class="time">
					<?php
						if($row['submit_date'] != "0000-00-00 00:00:00"){
							echo "提交时间：".$row['submit_date'];
						}else{
							if(strtotime($userTestSummary['end_date']) < strtotime($userTestSummary['curr_date'])){
								echo "结束时间：".$row['end_date'];
							}else{
								echo "开始时间：".$row['start_date'];
							}
						}						
					?>
				</span>
				<div class="cl"></div>
			</li>
			<?php
				}
			?>
		</ul>
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
		<div class="nonelist">暂未查到试卷哦！</div>
		<?php
		}
		?>
	</div>
	<div class="cl"></div>
</div>
<div class="footer"><?php echo $cfg_copyright; ?></div>
</body>
</html>

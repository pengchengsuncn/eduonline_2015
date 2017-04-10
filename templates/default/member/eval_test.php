<?php if(!defined('IN_MEMBER')) exit('Request Error!'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $cfg_webname; ?> - 会员中心 - 批卷</title>
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
			$userTestSummary = $dosql->GetOne("
				SELECT b.username,c.name FROM `#@__test_summary` a
					INNER JOIN `#@__member` b
					ON a.user_id = b.id
					INNER JOIN `#@__test` c
					ON a.test_id = c.id
				WHERE a.test_id=$testid
				AND a.user_id=$userid
			");
		?>
		<h3 class="subtitle">《<?php echo $userTestSummary['name']; ?>》&nbsp;&nbsp;<?php echo $userTestSummary['username']; ?>&nbsp;&nbsp;答题情况</h3>
		<?php
		$dopage->GetPage("
			SELECT a.id, a.response, a.response_score, a.test_id, a.user_id, a.ques_id,
				b.title, b.type, b.answer
			FROM `#@__test_dtl` a
				INNER JOIN `#@__question` b
				ON a.ques_id = b.id
			WHERE a.test_id=$testid
			AND a.user_id=$userid
			AND b.type IN (5,6)
			AND b.status = 'active'
		",9);
		if($dosql->GetTotalRow() > 0)
		{
		?>
		<form name="form" id="form" method="post" action="?a=evaltest">
			<ul class="msglist">
				<?php
					while($row = $dosql->GetArray()){
				?>
				<li>
					<?php
						echo "<strong>".$row['title']."</strong>";
						echo "<br>参考答案：";
						echo $row['answer'];
						echo "<br>学生答案：";
						echo $row['response'];
						echo "<br>打分：<input id='eval-score-".$row['ques_id']."' style='width:30px;' type='text' value='".$row['response_score']."'>";
						echo "&nbsp;&nbsp;<a href='javascript:saveEvalScore(".$row['test_id'].",".$row['user_id'].",".$row['ques_id'].");'><img alt='点击保存答案' src='/images/save.gif'></a>";
						echo "<span id='save-msg-".$row['ques_id']."'></span>";
					?>
				</li>
				<?php
					}
				?>
			</ul>
			<div class="btn_area">
				<input type="submit" class="btn" value="提 交" />
				<input type="hidden" name="testId" id="test-id" value="<?php echo $testid; ?>" />
				<input type="hidden" name="userId" id="user-id" value="<?php echo $userid; ?>" />
				<input type="hidden" name="evalBy" id="eval-by" value="<?php echo $r_user['id']; ?>" />
			</div>
		</form>
		
		<?php
		}
		else
		{
		?>
		<div class="nonelist">暂未查到答题哦！</div>
		<?php
		}
		?>
	</div>
	<div class="cl"></div>
</div>
<div class="footer"><?php echo $cfg_copyright; ?></div>
<script type="text/javascript">
	var ajaxRefImg = "&nbsp;&nbsp;<img src='/templates/default/images/ref.gif'>";
	var ajaxSuccImg = "&nbsp;&nbsp;<img src='/templates/default/images/succ.gif'>";
	function saveEvalScore(testId, userId, quesId){
		$("#save-msg-"+quesId).html(ajaxRefImg);
		var evalScore = $("#eval-score-"+quesId).val();
		var cknum = /^[1-9]\d*|0$/;		
		if(!cknum.test(evalScore)){
			alert("保存失败，题目成绩必须是数字");
			$("#save-msg-"+quesId).html("");
		}else{
			$.get("/templates/default/member/save_eval_score.php", {qid:quesId,tid:testId,uid:userId,score:evalScore}, function(reStr) {
				$("#save-msg-"+quesId).html(ajaxSuccImg);
			});
		}
	}
</script>
</body>
</html>

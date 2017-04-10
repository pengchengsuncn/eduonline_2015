<?php if(!defined('IN_MEMBER')) exit('Request Error!'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $cfg_webname; ?> - 会员中心 - 学生考试</title>
<link href="<?php echo $cfg_webpath; ?>/templates/default/style/member.css" type="text/css" rel="stylesheet" />
<link href="<?php echo $cfg_webpath; ?>/templates/default/style/edubase.css" type="text/css" rel="stylesheet" />
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
		<form name="form" id="form" method="post" action="?a=submittest">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<?php
					$test = $dosql->GetOne("SELECT * FROM `#@__test` WHERE id=$testid");
					// 获取当前登录用户是否已经参加过此考试
					$userTestSummary = $dosql->GetOne("SELECT * FROM `#@__test_summary` WHERE user_id=".$r_user['id']." AND test_id=".$test['id']);
					if(!is_array($userTestSummary)){					
						$insertTestSummary = "INSERT INTO `#@__test_summary` (user_id,test_id,start_date,end_date) VALUES (".$r_user['id'].",".$test['id'].",null,DATE_ADD(NOW(),INTERVAL ".$test['duration']." MINUTE))";
						$dosql->ExecNoneQuery($insertTestSummary);
					}
				?>
				<tr>
					<td colspan="2" height="26"><h3 class="subtitle">《<?php echo $test['name']; ?>》<span style="float:right;">请点击 <img alt='点击保存答案' src='/images/save.gif'> 保存答案</span></h3></td>
				</tr>
				<?php
					$dosql->Execute("SELECT * FROM `#@__question` WHERE status='active' AND test_id=$testid ORDER BY `id` ASC");
					if($dosql->GetTotalRow() > 0){
						$rownum = 0;
						while($ques = $dosql->GetArray()){
							$rownum = $rownum + 1;
							// 获取当前登录用户是否已回答过此问题
							$userTestDtl = $dosql->GetOne("SELECT * FROM `#@__test_dtl` WHERE test_id=".$test['id']." AND user_id=".$r_user['id']." AND ques_id=".$ques['id']);
							echo "<tr><td>";
							echo $rownum.". ".$ques['title']."<br>";
							echo "<div class='option-list'>";
							switch ($ques['type']) {
								case 1:
									echo "<input type='radio' name='resp-".$ques['id']."' value='1'";
										if(is_array($userTestDtl) && $userTestDtl['response'] == "1"){ echo "checked" ;}
									echo ">".$ques['option_1']."<br>";
									echo "<input type='radio' name='resp-".$ques['id']."' value='2'";
										if(is_array($userTestDtl) && $userTestDtl['response'] == "2"){ echo "checked" ;}
									echo ">".$ques['option_2']."<br>";
									echo "<input type='radio' name='resp-".$ques['id']."' value='3'";
										if(is_array($userTestDtl) && $userTestDtl['response'] == "3"){ echo "checked" ;}
									echo ">".$ques['option_3']."<br>";
									echo "<input type='radio' name='resp-".$ques['id']."' value='4'";
										if(is_array($userTestDtl) && $userTestDtl['response'] == "4"){ echo "checked" ;}
									echo ">".$ques['option_4']."<br>";
									break;
								case 2:									
									echo "<input type='checkbox' name='resp-".$ques['id']."' value='1'";
										if(is_array($userTestDtl)){
											$resp = explode(',',$userTestDtl['response']);
											for($i=0; $i<count($resp); $i++){
												if($resp[$i] == "1"){ echo "checked"; }
											}
										}
									echo ">".$ques['option_1']."<br>";
									echo "<input type='checkbox' name='resp-".$ques['id']."' value='2'";
										if(is_array($userTestDtl)){
											$resp = explode(',',$userTestDtl['response']);
											for($i=0; $i<count($resp); $i++){
												if($resp[$i] == "2"){ echo "checked"; }
											}
										}
									echo ">".$ques['option_2']."<br>";
									echo "<input type='checkbox' name='resp-".$ques['id']."' value='3'";
										if(is_array($userTestDtl)){
											$resp = explode(',',$userTestDtl['response']);
											for($i=0; $i<count($resp); $i++){
												if($resp[$i] == "3"){ echo "checked"; }
											}
										}
									echo ">".$ques['option_3']."<br>";
									echo "<input type='checkbox' name='resp-".$ques['id']."' value='4'";
										if(is_array($userTestDtl)){
											$resp = explode(',',$userTestDtl['response']);
											for($i=0; $i<count($resp); $i++){
												if($resp[$i] == "4"){ echo "checked"; }
											}
										}
									echo ">".$ques['option_4']."<br>";
									break;
								case 3:
									echo "<input type='radio' name='resp-".$ques['id']."' value='Y'";
										if(is_array($userTestDtl) && $userTestDtl['response'] == "Y"){ echo "checked" ;}
									echo ">正确<br>";
									echo "<input type='radio' name='resp-".$ques['id']."' value='N'";
										if(is_array($userTestDtl) && $userTestDtl['response'] == "N"){ echo "checked" ;}
									echo ">错误<br>";
									break;
								default:
									echo "<textarea name='resp-".$ques['id']."' class='class_areatext'>";
										if(is_array($userTestDtl)){ echo $userTestDtl['response']; }
									echo "</textarea><br>";
									break;
							}
							echo "<br><a href='javascript:saveAnswer(".$ques['id'].",".$ques['type'].");'><img alt='点击保存答案' src='/images/save.gif'></a>";
							echo "<span id='save-msg-".$ques['id']."'></span></div>";
							echo "<br>";
							echo "</td></tr>";
						}
					}else{
						echo "<tr><td>当前试卷没有题目哦！</td></tr>";
					}
				?>
			</table>
			<div class="btn_area">
				<input type="submit" class="btn" value="交 卷" />
				<input type="hidden" name="testId" id="test-id" value="<?php echo $test['id']; ?>" />
				<input type="hidden" name="userId" id="user-id" value="<?php echo $r_user['id']; ?>" />
			</div>
		</form>
	</div>
	<div class="cl"></div>
</div>
<div class="footer"><?php echo $cfg_copyright; ?></div>
<script type="text/javascript">
	var ajaxRefImg = "&nbsp;&nbsp;<img src='/templates/default/images/ref.gif'>";
	var ajaxSuccImg = "&nbsp;&nbsp;<img src='/templates/default/images/succ.gif'>";
	function saveAnswer(quesId, quesType){
		$("#save-msg-"+quesId).html(ajaxRefImg);
		var userId = $("#user-id").val();
		var testId = $("#test-id").val();
		var response = "";
		switch(quesType){
			case 1:
				response = $("input[name='resp-"+quesId+"']:checked").val();
				break;
			case 2:
				response = [];
				$("input[name='resp-"+quesId+"']:checked").each(function() {
					response.push($(this).val());
				});
				break;
			case 3:
				response = $("input[name='resp-"+quesId+"']:checked").val();
				break;
			default:
				response = $.trim($("textarea[name='resp-"+quesId+"']").val());
		}

		$.get("/templates/default/member/save_answer.php", {qid:quesId,tid:testId,uid:userId,resp:response}, function(reStr) {
			$("#save-msg-"+quesId).html(ajaxSuccImg);
		});
	}
</script>
</body>
</html>
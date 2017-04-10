<?php
	require_once('../../../include/config.inc.php');

	//获取题目详情
	$quesDtl = $dosql->GetOne("SELECT * FROM `#@__question` WHERE test_id=$tid AND id=$qid");

	$score = 0;
	if($quesDtl['type'] == 2){
		$resp = implode(",",$resp);
	}
	if($quesDtl['type'] != 5 && $quesDtl['type'] != 6 && $quesDtl['answer'] == $resp){
		$score = $quesDtl['score'];
	}

	// 获取当前登录用户是否已回答过此问题
	$userTestDtl = $dosql->GetOne("SELECT * FROM `#@__test_dtl` WHERE test_id=$tid AND user_id=$uid AND ques_id=$qid");
	// 如果已经回答过此问题，则更新即可
	if(is_array($userTestDtl)){
		$updateTestDtl = "
			UPDATE `#@__test_dtl` SET
				response = '".$resp."',
				response_score = ".$score.",
				response_date = null
			WHERE test_id=$tid
			AND user_id=$uid
			AND ques_id=$qid
		";
		$dosql->ExecNoneQuery($updateTestDtl);
	}else{		
		$insertTestDtl = "
			INSERT INTO `#@__test_dtl` (
				user_id,
				test_id,
				ques_id,
				response,
				response_score,
				response_date
			)VALUES(
				".$uid.",
				".$tid.",
				".$qid.",
				'".$resp."',
				".$score.",
				null
			)";
		$dosql->ExecNoneQuery($insertTestDtl);
	}

	//计算答题总分
	$calcTestScore = $dosql->GetOne("SELECT SUM(response_score) AS score FROM `#@__test_dtl` WHERE test_id=$tid AND user_id=$uid");

	$updateTestSummary = "
		UPDATE `#@__test_summary` SET
			test_score = ".$calcTestScore['score']."
		WHERE test_id=$tid
		AND user_id=$uid
	";
	$dosql->ExecNoneQuery($updateTestSummary);
	echo "保存成功！";
?>
<?php
	require_once('../../../include/config.inc.php');

	//获取题目详情
	$quesDtl = $dosql->GetOne("SELECT score FROM `#@__question` WHERE test_id=$tid AND id=$qid");

	if(!is_numeric($score)){
		$score = 0;
	}else{
		// 如果打的分数大于题目分值，则分数就为题目分值
		if($score > $quesDtl['score']){
			$score = $quesDtl['score'];
		}
	}

	$updateTestDtl = "
		UPDATE `#@__test_dtl` SET
			response_score = ".$score."
		WHERE test_id=$tid
		AND user_id=$uid
		AND ques_id=$qid
	";
	$dosql->ExecNoneQuery($updateTestDtl);
	echo "保存成功！";
?>
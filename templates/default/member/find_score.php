<?php if(!defined('IN_MEMBER')) exit('Request Error!'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $cfg_webname; ?>- 会员中心 - 试卷列表</title>
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
            $FindPaperName = $dosql->GetOne("SELECT * FROM `#@__test` WHERE id=".$id); 
    ?>
  		<span>试卷名称:&nbsp;&nbsp;<b style="color:red; font-size:14px;"><?php echo $FindPaperName['name']; ?></b></span>
	<?php 
            $FindPaperTotalScore = $dosql->GetOne("SELECT SUM(response_score) AS total_score FROM `#@__test_dtl` WHERE test_id=".$id);
    ?>
  		<span style="float:right;">您的考试成绩:&nbsp;&nbsp;<b style="color:red; font-size:14px;"><?php echo $FindPaperTotalScore['total_score']; ?></b></span> <hr />
  <?php 
  	$dosql->Execute("SELECT a.type,a.title,a.answer,a.score,a.option_1,a.option_2,a.option_3,a.option_4,b.response FROM #@__question a INNER JOIN #@__test_dtl b ON a.id=b.ques_id WHERE a.test_id=".$id);
	while($FindPaperDetail=$dosql->GetArray()){
  ?>
      <span><b>题目：</b></span>
      <span><strong><?php echo $FindPaperDetail['title']; ?>。</strong>&nbsp;&nbsp;(<?php echo $FindPaperDetail['score']; ?>分）</span>
      <p>您的回答：<span style="color:red;">
   <?php 
   	  switch ($FindPaperDetail['type']) {
		  						//单选题
								case 1:
									 echo '<input type="radio" ';
								if($FindPaperDetail['response'] == '1'){
									 echo'checked="checked"';
								} 
									 echo'/>&nbsp;&nbsp;'.$FindPaperDetail['option_1'].'&nbsp;&nbsp;</input>';
									 echo '<input type="radio" ';
								if($FindPaperDetail['response'] == '2'){
									 echo'checked="checked"';
								} 
									 echo'/>&nbsp;&nbsp;'.$FindPaperDetail['option_2'].'&nbsp;&nbsp;</input>';
									 echo '<input type="radio" ';
								if($FindPaperDetail['response'] == '3'){
									 echo'checked="checked"';
								} 
									 echo'/>&nbsp;&nbsp;'.$FindPaperDetail['option_3'].'&nbsp;&nbsp;</input>';
									 echo '<input type="radio" ';
								if($FindPaperDetail['response'] == '4'){
									 echo'checked="checked"';
								} 
									 echo'/>&nbsp;&nbsp;'.$FindPaperDetail['option_4'].'&nbsp;&nbsp;</input>';
									 break;
								//多选题	 
								case 2:									
									echo "<input type='checkbox'";
										if(is_array($FindPaperDetail)){
											$resp = explode(',',$FindPaperDetail['response']);
											for($i=0; $i<count($resp); $i++){
												if($resp[$i] == "1"){ echo "checked"; }
											}
										}
									echo '/>&nbsp;&nbsp;'.$FindPaperDetail['option_1'].'&nbsp;&nbsp;</input>';
									echo "<input type='checkbox'";
										if(is_array($FindPaperDetail)){
											$resp = explode(',',$FindPaperDetail['response']);
											for($i=0; $i<count($resp); $i++){
												if($resp[$i] == "2"){ echo "checked"; }
											}
										}
									echo '/>&nbsp;&nbsp;'.$FindPaperDetail['option_2'].'&nbsp;&nbsp;</input>';
									echo "<input type='checkbox'";
										if(is_array($FindPaperDetail)){
											$resp = explode(',',$FindPaperDetail['response']);
											for($i=0; $i<count($resp); $i++){
												if($resp[$i] == "3"){ echo "checked"; }
											}
										}
									echo '/>&nbsp;&nbsp;'.$FindPaperDetail['option_3'].'&nbsp;&nbsp;</input>';
									echo "<input type='checkbox'";
										if(is_array($FindPaperDetail)){
											$resp = explode(',',$FindPaperDetail['response']);
											for($i=0; $i<count($resp); $i++){
												if($resp[$i] == "4"){ echo "checked"; }
											}
										}
									echo '/>&nbsp;&nbsp;'.$FindPaperDetail['option_4'].'&nbsp;&nbsp;</input>';
									break;
								//判断题	
								case 3:
									echo '<input type="radio"';
								if($FindPaperDetail['response'] == 'Y'){
									 echo'checked="checked"';
								} 
									 echo'/>&nbsp;&nbsp;正确&nbsp;&nbsp;</input>';
									 echo '<input type="radio" ';
								if($FindPaperDetail['response'] == 'N'){
									 echo'checked="checked"';
								} 
									 echo'/>&nbsp;&nbsp;错误&nbsp;&nbsp;</input>';
									 break;
								//填空 解答 等描述题默认
								default:
									echo $FindPaperDetail['response'];
								
							}
   ?>      
      </span></p>
      <p>正确答案：<span style="color:green"><?php echo $FindPaperDetail['answer']; ?></span> </p>
	<?php 
    }
    ?>
    </div>
  <div class="cl"></div>
</div>
<div class="footer"><?php echo $cfg_copyright; ?></div>
</body>
</html>
<?php if(!defined('IN_MEMBER')) exit('Request Error!'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $cfg_webname; ?> - 会员中心 - 试卷编辑</title>
<link href="<?php echo $cfg_webpath; ?>/templates/default/style/member.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo $cfg_webpath; ?>/templates/default/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $cfg_webpath; ?>/templates/default/js/member.js"></script>
</head>
<body>
<?php 
	if(isset($_POST['paperId'])){
			$paperId = $_POST['paperId'];
			$name =$_POST['name'];
			$passScore =$_POST['passScore'];
			$duration =$_POST['duration'];
			$status =$_POST['status'];
			$description =$_POST['description'];
			$dosql->Execute("UPDATE `#@__test` SET name='$name', pass_score='$passScore',duration='$duration',status='$status',description='$description' where id=".$paperId);
			ShowMsg('试卷更新成功！','?c=testlist');
			exit();
		}
?>
<div class="header">
	<?php require_once(dirname(__FILE__).'/header.php'); ?>
</div>
<div class="mainbody">
	<div class="leftarea">
		<?php require_once(dirname(__FILE__).'/lefter.php'); ?>
	</div>
    <?php 
				$row = $dosql->GetOne("SELECT * FROM `#@__test` where id =".$id);
	?>
	<div class="rightarea">
		<form name="form" id="form" method="post" action="?c=paperedit" onsubmit="return check_test();">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td colspan="2" height="26"><h3 class="subtitle">试卷设置</h3></td>
				</tr>
				<tr>
					<td height="40" align="right">试卷名称：</td>
					<td><input type="text" name="name" id="name" class="class_input" value="<?php echo $row['name']; ?>" /></td>
				</tr>
				<tr>
					<td height="40" align="right">及格分数：</td>
					<td><input type="text" name="passScore" id="pass-score" class="class_input" value="<?php echo $row['pass_score']; ?>" /></td>
				</tr>
				<tr>
					<td height="40" align="right">考试时长：</td>
					<td><input type="text" name="duration" id="duration" class="class_input" value="<?php echo $row['duration']; ?>" /> （分钟）</td>
				</tr>
				<tr>
					<td height="40" align="right">试卷状态：</td>
					<td><input name="status" type="radio" value="open" <?php if($row['status'] == 'open') echo 'checked="checked"'; ?> />
						开放&nbsp;
						<input name="status" type="radio" value="closed" <?php if($row['status'] == 'closed') echo 'checked="checked"'; ?> />
						关闭</td>
				</tr>
				<tr>
					<td height="116" align="right">试卷说明：</td>
					<td><textarea name="description" id="description" class="class_areatext"><?php echo $row['description']; ?></textarea></td>
				</tr>
			</table>
			<div class="btn_area">
				<input type="submit" class="btn" value="保 存" />
				<input type="button" class="btn" value="取 消" onclick="history.go(-1)" />
				<input type="hidden" name="paperId" id="paper-id" value="<?php echo $row['id']; ?>" />
			</div>
		</form>
	</div>
	<div class="cl"></div>
</div>
<div class="footer"><?php echo $cfg_copyright; ?></div>
</body>
</html>
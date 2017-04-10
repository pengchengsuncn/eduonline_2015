<?php if(!defined('IN_MEMBER')) exit('Request Error!'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $cfg_webname; ?> - 会员中心 - 编辑班级</title>
<link href="<?php echo $cfg_webpath; ?>/templates/default/style/member.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo $cfg_webpath; ?>/templates/default/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $cfg_webpath; ?>/templates/default/js/member.js"></script>
</head>
<?php
	if(isset($_POST['classId'])){
		$classId = $_POST['classId'];
		$ClassName = $_POST['name'];
		$HeaderTeacher = $_POST['headTeacher'];
		$GradeName = $_POST['grade'];
		$GradeDescription = $_POST['description'];
		$dosql->Execute("UPDATE `#@__class` SET name='$ClassName', head_teacher='$HeaderTeacher',grade='$GradeName',description='$GradeDescription' where id=".$classId);
		ShowMsg('班级更新成功！','?c=classlist');
		exit();
		}
 ?>
<body>
<div class="header">
	<?php require_once(dirname(__FILE__).'/header.php'); ?>
</div>
<div class="mainbody">
	<div class="leftarea">
		<?php require_once(dirname(__FILE__).'/lefter.php'); ?>
	</div>
    <?php 
		$crow = $dosql->GetOne("SELECT * FROM `#@__class` where id =".$id);
	?>
	<div class="rightarea">
		<form name="form" id="form" method="post" action="?c=classedit" onsubmit="return check_class();">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td colspan="2" height="26"><h3 class="subtitle">编辑班级</h3></td>
				</tr>
				<tr>
					<td height="40" align="right">班级名称：</td>
					<td><input type="text" name="name" id="name" class="class_input" value="<?php echo $crow['name']; ?>" /></td>
				</tr>
				<tr>
					<td height="40" align="right">班主任：</td>
					<td>
						<select name="headTeacher" id="head-teacher">
							<!--<option value="请选择">请选择</option>-->							
							<?php
								$dosql->Execute("SELECT id,username,cnname FROM `#@__member` WHERE is_teacher='true' ORDER BY username ASC");
								while($row = $dosql->GetArray())
								{
									
							?>
									<option value="<?php echo $row['id']; ?>"<?php if($crow['head_teacher'] == $row['id']){ echo 'selected="selected"';}; ?>><?php echo $row['username'].' - '.$row['cnname'];?></option>';
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td height="40" align="right">年级：</td>
					<td>
						<select name="grade" id="grade">
							<option value="一年级" <?php if($crow['grade'] == '一年级'){ echo 'selected="selected"';}; ?>>一年级</option>
							<option value="二年级" <?php if($crow['grade'] == '二年级'){ echo 'selected="selected"';}; ?>>二年级</option>
							<option value="三年级" <?php if($crow['grade'] == '三年级'){ echo 'selected="selected"';}; ?>>三年级</option>
							<option value="四年级" <?php if($crow['grade'] == '四年级'){ echo 'selected="selected"';}; ?>>四年级</option>
							<option value="五年级" <?php if($crow['grade'] == '五年级'){ echo 'selected="selected"';}; ?>>五年级</option>
							<option value="六年级" <?php if($crow['grade'] == '六年级'){ echo 'selected="selected"';}; ?>>六年级</option>
							<option value="七年级" <?php if($crow['grade'] == '七年级'){ echo 'selected="selected"';}; ?>>七年级</option>
							<option value="八年级" <?php if($crow['grade'] == '八年级'){ echo 'selected="selected"';}; ?>>八年级</option>
							<option value="九年级" <?php if($crow['grade'] == '九年级'){ echo 'selected="selected"';}; ?>>九年级</option>
							<option value="高中生" <?php if($crow['grade'] == '高中生'){ echo 'selected="selected"';}; ?>>高中生</option>
						</select>
					</td>
				</tr>
				<tr>
					<td height="116" align="right">班级简介：</td>
					<td><textarea name="description" id="description" class="class_areatext"><?php echo $crow['description']; ?></textarea></td>
				</tr>
			</table>
			<div class="btn_area">
				<input type="submit" class="btn" value="保 存" />
				<input type="button" class="btn" value="取 消" onclick="history.go(-1)" />
				<input type="hidden" name="classId" id="class-id" value="<?php echo $crow['id']; ?>" />
			</div>
		</form>
	</div>
	<div class="cl"></div>
</div>
<div class="footer"><?php echo $cfg_copyright; ?></div>
</body>
</html>
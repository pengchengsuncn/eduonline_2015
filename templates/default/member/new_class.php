<?php if(!defined('IN_MEMBER')) exit('Request Error!'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $cfg_webname; ?> - 会员中心 - 创建班级</title>
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
		<form name="form" id="form" method="post" action="?a=newclass" onsubmit="return check_class();">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td colspan="2" height="26"><h3 class="subtitle">创建班级</h3></td>
				</tr>
				<tr>
					<td height="40" align="right">班级名称：</td>
					<td><input type="text" name="name" id="name" class="class_input" value="" /></td>
				</tr>
				<tr>
					<td height="40" align="right">班主任：</td>
					<td>
						<select name="headTeacher" id="head-teacher">
							<option value="">请选择</option>
							<?php
								$dosql->Execute("SELECT id,username,cnname FROM `#@__member` WHERE is_teacher='true' ORDER BY username ASC");
								while($row = $dosql->GetArray())
								{
									echo '<option value="'.$row['id'].'">'.$row['username'].' - '.$row['cnname'].'</option>';
								}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td height="40" align="right">年级：</td>
					<td>
						<select name="grade" id="grade">
							<option value="一年级">一年级</option>
							<option value="二年级">二年级</option>
							<option value="三年级">三年级</option>
							<option value="四年级">四年级</option>
							<option value="五年级">五年级</option>
							<option value="六年级">六年级</option>
							<option value="七年级">七年级</option>
							<option value="八年级">八年级</option>
							<option value="九年级">九年级</option>
							<option value="高中">高中</option>
						</select>
					</td>
				</tr>
				<tr>
					<td height="116" align="right">班级简介：</td>
					<td><textarea name="description" id="description" class="class_areatext"></textarea></td>
				</tr>
			</table>
			<div class="btn_area">
				<input type="submit" class="btn" value="保 存" />
				<input type="button" class="btn" value="取 消" onclick="history.go(-1)" />
				<input type="hidden" name="useId" id="user-id" value="<?php echo $r_user['id']; ?>" />
			</div>
		</form>
	</div>
	<div class="cl"></div>
</div>
<div class="footer"><?php echo $cfg_copyright; ?></div>
</body>
</html>
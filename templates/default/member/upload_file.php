<?php if(!defined('IN_MEMBER')) exit('Request Error!'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $cfg_webname; ?> - 会员中心 - 学习资料</title>
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
		<h3 class="subtitle">上传学习资料</h3>

		<form name="form" id="form" method="post" action="?a=uploadfile" enctype="multipart/form-data" onsubmit="return check_learning_file();">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td height="40" align="right">资料名称：</td>
					<td><input type="file" name="file" id="file" /> </td>
				</tr>
				<tr>
					<td height="40" align="right">下载积分：</td>
					<td><input type="text" name="points" id="points" class="class_input" value="0" /></td>
				</tr>
				<tr>
					<td height="116" align="right">试卷说明：</td>
					<td><textarea name="description" id="description" class="class_areatext"></textarea></td>
				</tr>
			</table>
			<div class="btn_area">
				<input type="submit" class="btn" value="上 传" />
				<input type="hidden" name="userId" id="user-id" value="<?php echo $r_user['id']; ?>" />
			</div>
		</form>
	</div>
	<div class="cl"></div>
</div>
<div class="footer"><?php echo $cfg_copyright; ?></div>
</body>
</html>
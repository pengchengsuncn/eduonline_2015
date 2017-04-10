<?php require_once(dirname(__FILE__).'/inc/config.inc.php');//IsModelPriv('soft'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>教师申请审核</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript">
function app_chk(){
	if($('#remark').val()==''){
		alert('备注不能为空！');
		$('#remark').focus();
		return false;
	}
	return true;
}
</script>
</head>
<body>
<div class="formHeader"> <span class="title">教师申请审核</span> <a href="javascript:location.reload();" class="reload">刷新</a> </div>
<?php
$row = $dosql->GetOne("SELECT * FROM `#@__applyteacher` WHERE `id`=$id");
?>
<form name="form" id="form" method="post" action="teacher_app_save.php" onsubmit="return app_chk();">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formTable">
		<tr>
			<td height="40" align="right">审核：</td>
			<td><input type="radio" name="checkinfo" value="1" <?php if($row['checkinfo']==1) echo 'checked="checked"';?> />
				通过 &nbsp;
				<input type="radio" name="checkinfo" value="2" <?php if($row['checkinfo']==2) echo 'checked="checked"';?> /> 不通过</td>
		</tr>
		<tr>
			<td height="110" align="right">备注：</td>
			<td><textarea name="remark" id="remark" class="input" style="height:80px;"><?php echo $row['remark'];?></textarea></td>
		</tr>
	</table>
	<div class="formSubBtn">
		<input type="submit" class="submit" value="提交" />
		<input type="button" class="back" value="返回" onclick="history.go(-1);" />
		<input type="hidden" name="action" id="action" value="update" />
        <input type="hidden" name="id" id="id" value="<?php echo $row['id'];?>" />
		<input type="hidden" name="uid" id="uid" value="<?php echo $row['uid']; ?>" />
	</div>
</form>
</body>
</html>
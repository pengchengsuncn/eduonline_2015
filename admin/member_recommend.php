<?php require_once(dirname(__FILE__).'/inc/config.inc.php');
$info = $dosql->GetOne("SELECT * FROM `#@__member_recommend` WHERE `uid`=$id");
if(isset($info['id'])){
	$dosql->ExecNoneQuery("DELETE FROM `#@__member_recommend` WHERE id=".$info['id']);
	ShowMsg('成功取消推荐！','member.php');
	exit();
}
$row = $dosql->GetOne("SELECT * FROM `#@__member` WHERE `id`=$id");
if($row['is_teacher']!='true'){
	ShowMsg('该用户不是老师，不能推荐！','-1');
	exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>教师推荐</title>
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
<div class="formHeader"> <span class="title">教师推荐</span> <a href="javascript:location.reload();" class="reload">刷新</a> </div>
<form name="form" id="form" method="post" action="member_save.php" onsubmit="return app_chk();">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formTable">
		<tr>
			<td height="40" align="right">排序：</td>
			<td><input name="orderid" type="text" class="input" value="<?php echo GetOrderID('#@__member_recommend'); ?>" /> <span class="red">*数字越大越靠前</span></td>
		</tr>
		<tr>
			<td height="110" align="right">推荐语：</td>
			<td><textarea name="remark" id="remark" class="input" style="height:80px;"></textarea><span class="red">*首页显示，30字以内</span></td>
		</tr>
	</table>
	<div class="formSubBtn">
		<input type="submit" class="submit" value="提交" />
		<input type="button" class="back" value="返回" onclick="history.go(-1);" />
		<input type="hidden" name="action" id="action" value="tuijian" />
        <input type="hidden" name="id" id="id" value="<?php echo $row['id'];?>" />
	</div>
</form>
</body>
</html>
<?php require_once(dirname(__FILE__).'/inc/config.inc.php');//IsModelPriv('course_type'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>添加课程分类</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/getuploadify.js"></script>
<script type="text/javascript" src="templates/js/getcatpsize.js"></script>
<script type="text/javascript" src="templates/js/checkf.func.js"></script>
</head>
<body>
<div class="formHeader"> <span class="title">添加课程分类</span> <a href="javascript:location.reload();" class="reload">刷新</a> </div>
<form name="form" id="form" method="post" action="course_type_save.php" onsubmit="return cfm_infoclass();">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formTable">
		<tr>
			<td width="25%" height="40" align="right">所属分类：</td>
			<td><select name="parentid" id="parentid" onchange="GetCatpSize(this.value);">
					<option value="0">一级分类</option>
					<?php GetAllType('#@__course_type','#@__course_type','id'); ?>
				</select>
				<span class="maroon">*</span></td>
			<td><span class="cnote">带<span class="maroon">*</span>号表示为必填项</span></td>
		</tr>
		<tr>
			<td height="40" align="right">分类名称：</td>
			<td><input name="classname" type="text" id="classname" class="input" />
				<span class="maroon">*</span></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td height="40" align="right">排列排序：</td>
			<td><input type="text" name="orderid" id="orderid" class="inputs" value="<?php echo GetOrderID('#@__course_type'); ?>" /></td>
			<td>&nbsp;</td>
		</tr>
		<tr class="nb">
			<td height="40" align="right">隐藏栏目：</td>
			<td><input type="radio" name="checkinfo" value="true" checked="checked"  />
				显示&nbsp;
				<input type="radio" name="checkinfo" value="false" />
				隐藏</td>
			<td>&nbsp;</td>
		</tr>
	</table>
	<div class="formSubBtn">
		<input type="submit" class="submit" value="提交" />
		<input type="button" class="back" value="返回" onclick="history.go(-1);" />
		<input type="hidden" name="action" id="action" value="add" />
	</div>
</form>
</body>
</html>
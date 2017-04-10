<?php require_once(dirname(__FILE__).'/inc/config.inc.php');//IsModelPriv('member'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>教师申请管理</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/forms.func.js"></script>
</head>
<body>
<div class="topToolbar"> <span class="title">教师申请管理</span> <a href="javascript:location.reload();" class="reload">刷新</a></div>
<form name="form" id="form" method="post" action="teacher_app_save.php">
	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dataTable">
		<tr align="left" class="head">
			<td width="5%" height="36" class="firstCol"><input type="checkbox" name="checkid" id="checkid" onclick="CheckAll(this.checked);"></td>
			<td width="5%">ID</td>
			<td>用户名</td>
			<td>所在学校</td>
			<td>所带班级</td>
			<td>任课班级</td>
			<td>申请时间</td>
			<td>申请状态</td>
			<td class="endCol">操作</td>
		</tr>
		<?php
		$dopage->GetPage("SELECT * FROM `#@__applyteacher`");
		while($row = $dosql->GetArray())
		{
		?>
		<tr align="left" class="dataTr">
			<td height="60" class="firstCol"><input type="checkbox" name="checkid[]" id="checkid[]" value="<?php echo $row['id']; ?>" /></td>
			<td><?php echo $row['id']; ?></td>
			<td><?php echo $row['uname']; ?></td>
			<td><?php echo $row['school']; ?></td>
			<td><?php echo $row['class']; ?></td>
			<td><?php echo $row['teach']; ?></td>
			<td class="number"><?php echo GetDateMk($row['apptime']); ?></td>
			<td><?php if($row['checkinfo']==0) echo '等待审核'; elseif($row['checkinfo']==1) echo '申请已通过！';elseif($row['checkinfo']==2) echo '申请未通过！'.$row['remark']; ?></td>
			<td class="action endCol"><span><a href="member_update.php?id=<?php echo $row['uid'];?>" target="_blank">会员详情</a></span> | <span><a href="teacher_app_update.php?id=<?php echo $row['id']; ?>">审核</a></span> | <span class="nb"><a href="teacher_app_save.php?action=del2&id=<?php echo $row['id']; ?>" onclick="return ConfDel(0)">删除</a></span></td>
		</tr>
		<?php
		}
		?>
	</table>
</form>
<?php

//判断无记录样式
if($dosql->GetTotalRow() == 0)
{
	echo '<div class="dataEmpty">暂时没有相关的记录</div>';
}
?>
<div class="bottomToolbar"><span class="selArea"><span>选择：</span> <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:DelAllNone('teacher_app_save.php');" onclick="return ConfDelAll(0);">删除</a></span> </div>
<div class="page"> <?php echo $dopage->GetList(); ?> </div>
<?php

//判断是否启用快捷工具栏
if($cfg_quicktool == 'Y')
{
?>
<div class="quickToolbar">
	<div class="qiuckWarp">
		<div class="quickArea"><span class="selArea"><span>选择：</span> <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:DelAllNone('teacher_app_save.php');" onclick="return ConfDelAll(0);">删除</a></span> <span class="pageSmall"> <?php echo $dopage->GetList(); ?> </span></div>
		<div class="quickAreaBg"></div>
	</div>
</div>
<?php
}
?>
</body>
</html>
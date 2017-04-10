<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('course_type');
$cid = empty($cid) ? 0 : intval($cid);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>课程章节管理</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/forms.func.js"></script>
</head>
<body>
<div class="topToolbar"> <span class="title">课程章节管理</span> <a href="javascript:location.reload();" class="reload">刷新</a></div>
<form name="form" id="form" method="post" action="course_lesson_lesson.php">
	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dataTable">
		<tr align="left" class="head">
			<td width="5%" height="36" class="firstCol"><input type="checkbox" name="checkid" id="checkid" onclick="CheckAll(this.checked);"></td>
			<td width="5%">ID</td>
			<td>分类</td>
			<td>课程名称</td>
			<td>章节名称</td>
			<td>是否显示</td>
			<td>是否免费</td>
            <td>添加时间</td>
			<td class="endCol">操作</td>
		</tr>
		<?php
		if($cid==0){
			$where = '';
		}else{
			$where = " AND course_id=$cid";
		}
		$dopage->GetPage("SELECT * FROM `#@__course_lesson` WHERE is_delete != 'true' $where");
		while($row = $dosql->GetArray())
		{
			$course = $dosql->GetOne("SELECT * FROM #@__course WHERE is_delete != 'true' AND id=".$row['course_id']);
		?>
		<tr align="left" class="dataTr">
			<td height="60" class="firstCol"><input type="checkbox" name="checkid[]" id="checkid[]" value="<?php echo $row['id']; ?>" /></td>
			<td><?php echo $row['id']; ?></td>
			<td><?php if(isset($course['classid'])){ $type = $dosql->GetOne("SELECT * FROM #@__course_type WHERE id=".$course['classid']); if($type['parentid']>0) $ptype = $dosql->GetOne("SELECT * FROM #@__course_type WHERE id=".$type['parentid']); if(!empty($ptype['classname'])) echo $ptype['classname']; if(!empty($type['classname'])) echo "/".$type['classname']; else echo '/分类已删除'; } else{ echo '该课程已删除！';}?></td>
			<td><?php if(isset($course['title'])) echo $course['title']; else echo '该课程已删除！';?></td>
            <td><?php echo $row['description']; ?></td>
            <td><?php if($row['checkinfo']=='true') echo '是'; else echo '否';?></td>
            <td><?php if($row['is_free']=='true') echo '是'; else echo '否';?></td>
			<td class="number"><?php echo GetDateMk($row['posttime']); ?></td>
			<td class="action endCol"><span><a href="course_lesson_update.php?id=<?php echo $row['id']; ?>">查看</a></span> | <span class="nb"><a href="course_lesson_save.php?action=dellesson&id=<?php echo $row['id']; ?>" onclick="return ConfDel(0)">删除</a></span></td>
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
<div class="bottomToolbar"><span class="selArea"><span>选择：</span> <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:SubUrlParam('course_lesson_save.php?action=delalllesson');" onclick="return ConfDelAll(0);">删除</a></span> </div>
<div class="page"> <?php echo $dopage->GetList(); ?> </div>
<?php
//判断是否启用快捷工具栏
if($cfg_quicktool == 'Y')
{
?>
<div class="quickToolbar">
	<div class="qiuckWarp">
		<div class="quickArea"><span class="selArea"><span>选择：</span> <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:DelAllNone('course_save.php');" onclick="return ConfDelAll(0);">删除</a></span> <span class="pageSmall"> <?php echo $dopage->GetList(); ?> </span></div>
		<div class="quickAreaBg"></div>
	</div>
</div>
<?php
}
?>
</body>
</html>
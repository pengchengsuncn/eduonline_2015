<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('course_type'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>课程管理</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/forms.func.js"></script>
</head>
<body>
<div class="topToolbar"> <span class="title">课程管理</span> <a href="javascript:location.reload();" class="reload">刷新</a></div>
<form name="form" id="form" method="post" action="course_save.php">
	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dataTable">
		<tr align="left" class="head">
			<td width="5%" height="36" class="firstCol"><input type="checkbox" name="checkid" id="checkid" onclick="CheckAll(this.checked);"></td>
			<td width="5%">ID</td>
			<td>分类</td>
			<td>课程名称</td>
			<td>图片</td>
			<td>属性</td>
			<td>排序</td>
			<td>是否显示</td>
            <td>添加时间</td>
			<td class="endCol">操作</td>
		</tr>
		<?php
		$dopage->GetPage("SELECT * FROM `#@__course` WHERE is_delete != 'true'");
		while($row = $dosql->GetArray())
		{
		?>
		<tr align="left" class="dataTr">
			<td height="60" class="firstCol"><input type="checkbox" name="checkid[]" id="checkid[]" value="<?php echo $row['id']; ?>" /></td>
			<td><?php echo $row['id']; ?></td>
			<td><?php $type = $dosql->GetOne("SELECT * FROM #@__course_type WHERE id=".$row['classid']); if($type['parentid']>0) $ptype = $dosql->GetOne("SELECT * FROM #@__course_type WHERE id=".$type['parentid']); if(!empty($ptype['classname'])) echo $ptype['classname']; if(!empty($type['classname'])) echo "/".$type['classname']; else echo '/分类已删除'; ?></td>
			<td><?php echo $row['title']; ?></td>
			<td><?php if(!empty($row['picurl'])) echo '<a href="..'.$row['picurl'].'" target="_blank"><img src="..'.$row['picurl'].'" width="50" height="50" /></a>'; ?></td>
            <td><?php $array=array('n'=>'最新','r'=>'推荐','h'=>'最热'); foreach(explode(',',$row['flag']) as $v){ if(!empty($v)) echo $array[$v]."|";} ?></td>
			<td><?php echo $row['orderid']; ?></td>
            <td><?php if($row['checkinfo']=='true') echo '是'; else echo '否';?></td>
			<td class="number"><?php echo GetDateMk($row['posttime']); ?></td>
			<td class="action endCol"><span><a href="course_lesson.php?cid=<?php echo $row['id']; ?>">章节管理</a></span> |<span><a href="course_update.php?id=<?php echo $row['id']; ?>">查看</a></span> | <span class="nb"><a href="course_save.php?action=delcourse&id=<?php echo $row['id']; ?>" onclick="return ConfDel(0)">删除</a></span></td>
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
<div class="bottomToolbar"><span class="selArea"><span>选择：</span> <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:SubUrlParam('course_save.php?action=delallcourse');" onclick="return ConfDelAll(0);">删除</a></span> </div>
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
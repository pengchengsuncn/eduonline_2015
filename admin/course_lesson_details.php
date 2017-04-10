<?php require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('course_type'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>课程分类管理</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/forms.func.js"></script>
</head>
<body>
<div class="topToolbar"> <span class="title">课程章节管理</span> <a href="javascript:location.reload();" class="reload">刷新</a></div>
<form name="form" id="form" method="post" action="">
	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dataTable">
		<tr align="left" class="head">
			<td width="5%" height="36" class="firstCol"><input type="checkbox" name="checkid" onclick="CheckAll(this.checked);" /></td>
			<td width="5%">ID</td>
			<td width="15%">章节描述</td>
            <td width="15%">章节详情</td>
            <td width="10%">视频链接</td>
            <td width="10%">文件链接</td>
            <td width="10%">是否免费</td>
			<td width="10%"	>更新时间</td>
			<td width="20%" class="endCol">操作</td>
		</tr>
	</table>
	<?php

	//权限验证
	if($cfg_adminlevel != 1)
	{
		//初始化参数
		$catgoryListPriv   = array();
		$catgoryAddPriv    = array();
		$catgoryUpdatePriv = array();
		$catgoryDelPriv    = array();

		$dosql->Execute("SELECT * FROM `#@__adminprivacy` WHERE `groupid`=".$cfg_adminlevel." AND `model`='category'");
		while($row = $dosql->GetArray())
		{
			//查看权限
			if($row['action'] == 'list')
				$catgoryListPriv[]   = $row['classid'];

			//添加权限
			if($row['action'] == 'add')
				$catgoryAddPriv[]    = $row['classid'];

			//修改权限
			if($row['action'] == 'update')
				$catgoryUpdatePriv[] = $row['classid'];

			//删除权限
			if($row['action'] == 'del')
				$catgoryDelPriv[]    = $row['classid'];

		}
	}


	//循环栏目函数
	function Show($id=0, $i=0)
	{
		global $dosql,$cfg_siteid,$cfg_adminlevel,
		       $catgoryListPriv,$catgoryAddPriv,
			   $catgoryUpdatePriv,$catgoryDelPriv;

		$i++;
		$did = $_GET['id'];
		$dosql->Execute("SELECT * FROM `#@__course_lesson` WHERE is_delete = 'false' AND course_id =".$did."  ORDER BY `id` DESC", 0);
		while($row = $dosql->GetArray(0))
		{

			if($cfg_adminlevel != 1)
			{
				if(in_array($row['id'], $catgoryUpdatePriv))
					$updateStr = '<a href="course_lesson_look.php?id='.$row['id'].'">查看</a>';
				else
					$updateStr = '查看';
			}
			else
			{
				$updateStr = '<a href="course_lesson_look.php?id='.$row['id'].'">查看</a>';
			}


			//删除权限
			if($cfg_adminlevel != 1)
			{
				if(in_array($row['id'], $catgoryDelPriv))
					$delStr = '<a href="course_lesson_save.php?action=delclass&id='.$row['id'].'" onclick="return ConfDel(2);">删除</a>';
				else
					$delStr = '删除';
			}
			else
			{
				$delStr = '<a href="course_lesson_save.php?action=delclass&id='.$row['id'].'" onclick="return ConfDel(2);">删除</a>';
			}


			//审核状态
			switch($row['checkinfo'])
			{
				case 'true':
					$checkinfo = '已审核';
					break;  
				case 'false':
					$checkinfo = '审核';
					break;
				default:
					$checkinfo = '没有获取到参数';
			}


			//审核权限
			if($cfg_adminlevel != 1)
			{
				if(in_array($row['id'], $catgoryUpdatePriv))
					$checkStr = '<a href="course_save.php?action=check&id='.$row['id'].'&checkinfo='.$row['checkinfo'].'" title="点击进行审核操作">'.$checkinfo.'</a>';
				else
					$checkStr = $checkinfo;
			}
			else
			{
				$checkStr = '<a href="course_save.php?action=check&id='.$row['id'].'&checkinfo='.$row['checkinfo'].'" title="点击进行审核操作">'.$checkinfo.'</a>';
			}

	?>
	<div rel="rowpid_<?php echo $id; ?>">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dataTable">
			<tr align="left" class="dataTr">
				<td width="5%" height="36" class="firstCol"><input type="checkbox" name="checkid[]" id="checkid[]" value="<?php echo $row['id']; ?>" /></td>
				<td width="5%"><?php echo $row['id']; ?>
					<input type="hidden" name="id[]" id="id[]" value="<?php echo $row['id']; ?>" /></td>
				<td width="15%"><?php echo $row['description']; ?></td>
				<td width="15%"><?php echo $row['content']; ?></td>
                <td width="10%"><?php echo $row['videourl']; ?></td>
                <td width="10%"><?php echo $row['fileurl']; ?></td>
                <td width="10%"><?php
				 switch($row['is_free'])
                {
                    case 'true':
                        $is_free = '免费';
                        break;  
                    case 'false':
                        $is_free = '收费';
                        break;
                    default:
                        $is_free = '没有获取到参数';
                }
				 echo $is_free; 
				 ?>
                 </td>
                <td width="10%"><?php echo date('Y-m-d H:i:s',$row['posttime']); ?></td>
				<td width="20%" class="action endCol"><span><?php echo $checkStr; ?></span> | <span><?php echo $updateStr; ?></span> | <span class="nb"><?php echo $delStr; ?></span></td>
			</tr>
		</table>
	</div>
	<?php
			
		}
	}
	Show();


	//判断无记录样式
	if($dosql->GetTotalRow(0) == 0)
	{
		echo '<div class="dataEmpty">暂时没有相关的记录</div>';
	}
	
	
	//判断类别页是否折叠
	if($cfg_typefold == 'Y')
	{
		echo '<script>HideAllRows();</script>';
	}
	?>
</form>
<div class="bottomToolbar"> <span class="selArea"><span>选择：</span> <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:SubUrlParam('course_lesson_save.php?action=delallclass');" onclick="return ConfDelAll(1);">删除</a>　<span></span></span></div>
<div class="page">
	<div class="pageText">共有<span><?php echo $dosql->GetTableRow('#@__course_lesson'); ?></span>条记录</div>
</div>

<?php

//判断是否启用快捷工具栏
if($cfg_quicktool == 'Y')
{
?>
<div class="quickToolbar">
	<div class="qiuckWarp">
		<div class="quickArea"><span class="selArea"><span>选择：</span> <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:SubUrlParam('course_lesson_save.php?action=delallclass');" onclick="return ConfDelAll(1);">删除</a>　<span></span></span><span class="pageSmall">
			<div class="pageText">共有<span><?php echo $dosql->GetTableRow('#@__course_lesson'); ?></span>条记录</div>
			</span></div>
		<div class="quickAreaBg"></div>
	</div>
</div>
<?php
}
?>
</body>
</html>
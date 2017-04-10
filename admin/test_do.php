<?php	require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('test');

//初始化参数
$action  = isset($action)  ? $action  : '';
$keyword = isset($keyword) ? $keyword : '';


//删除单条记录
if($action == 'del')
{
	//栏目权限验证
	$r = $dosql->GetOne("SELECT `classid` FROM `#@__$tbname` WHERE `id`=$id");
	IsCategoryPriv($r['classid'],'del',1);

	$deltime = time();
	$dosql->ExecNoneQuery("UPDATE `#@__$tbname` SET delstate='true', deltime='$deltime' WHERE id=$id");
}


//删除选中记录
if($action == 'delall')
{
	if($ids != '')
	{
		//解析id,验证是否有删除权限
		$ids = explode(',',$ids);
		$idstr = '';
		foreach($ids as $id)
		{
			$r = $dosql->GetOne("SELECT `classid` FROM `#@__$tbname` WHERE `id`=$id");
			if(IsCategoryPriv($r['classid'],'del',1))
			{
				$idstr .= $id.',';
			}
		}
		$idstr .= trim($idstr,',');

		if($idstr != '')
		{
			$deltime = time();
			$dosql->ExecNoneQuery("UPDATE `#@__$tbname` SET delstate='true', deltime='$deltime' WHERE `id` IN ($idstr)");
		}
	}
}
?>
<div class="toolbarTab">
	<ul>
		<?php
			$flagArr = array('all'=>'全部', 'notcheck'=>'未审', 'ischeck'=>'已审');	
			$iFlag = 0;	
			foreach($flagArr as $k => $v)
			{
				$iFlag += 1;
				if($flag == $k)
					$flagOn = 'on';
				else
					$flagOn = '';
		
				echo '<li class="'.$flagOn.'"><a href="javascript:;" onclick="GetFlag(\''.$k.'\')">'.$v.'</a></li>';
				if($iFlag != 3){
					echo '<li class="line">-</li>';
				}
			}
		?>
	</ul>
	<div id="search" class="search"> <span class="s">
		<input name="keyword" id="keyword" type="text" title="输入试卷名称进行搜索" value="<?php echo $keyword; ?>" />
		</span> <span class="b"><a href="javascript:;" onclick="GetSearch();"></a></span></div>
	<div class="cl"></div>
</div>

<table width="100%" border="0" cellpadding="0" cellspacing="0" id="ajaxlist" class="dataTable">
	<tr align="left" class="head">
		<td width="30%">试卷名称</td>
		<td width="5%">试卷总分</td>
		<td width="5%">及格分数</td>
		<td width="13%">时长</td>
		<td width="10%">创建者</td>
		<td width="15%">创建时间</td>
		<td width="7%">审核状态</td>
		<td width="15%" class="endCol">审核</td>
	</tr>
	<?php

	//检查全局分页数
	if(empty($cfg_pagenum))  $cfg_pagenum = 20;


	//权限验证
	if($cfg_adminlevel != 1)
	{
		//初始化参数
		$catgoryListPriv   = '';
		$catgoryUpdatePriv = array();
		$catgoryDelPriv    = array();

		$dosql->Execute("SELECT * FROM `#@__adminprivacy` WHERE `groupid`=".$cfg_adminlevel." AND `model`='category' AND `action`<>'add'");
		while($row = $dosql->GetArray())
		{
			//查看权限
			if($row['action'] == 'list')
				$catgoryListPriv .= $row['classid'].',';

			//修改权限
			if($row['action'] == 'update')
				$catgoryUpdatePriv[] = $row['classid'];

			//删除权限
			if($row['action'] == 'del')
				$catgoryDelPriv[]    = $row['classid'];
			
		}

		$catgoryListPriv = trim($catgoryListPriv,',');
	}


	//设置sql
	$sql = "
		SELECT a.*,b.username,b.cnname
		FROM `#@__$tbname` a
			INNER JOIN `#@__member` b
			ON a.create_by = b.id
		WHERE `status`<>'deleted'
	";	
	if(!empty($keyword)) $sql .= " AND name LIKE '%$keyword%'";

	if(!empty($flag))
	{		
		if($flag == 'notcheck')
			$sql .= "AND approved=0";
		else if($flag == 'ischeck')
			$sql .= "AND approved IN (1,2)";
		else
		{
			$sql .= 'AND 1=1';
		}
	}

	$dopage->GetPage($sql);
	while($row = $dosql->GetArray())
	{

		//标题名称
		$name  = '<span class="title">'.$row['name'].'</span>';
		$score  = '<span class="score">'.$row['score'].'</span>';
		$passScore  = '<span class="pass-score">'.$row['pass_score'].'</span>';
		$duration  = '<span class="duration">'.$row['duration'].'</span>';
		$createBy  = '<span class="create-by">'.$row['cnname'].'('.$row['username'].')</span>';
		$createDate  = '<span class="create-date">'.$row['create_date'].'</span>';

		//获取审核状态
		switch($row['approved'])
		{
			case 1:
				$checkinfo = '通过';
				break;  
			case 2:
				$checkinfo = '拒绝';
				break;
			default:
				$checkinfo = '未审';
		}
		

		//修改权限
		if($cfg_adminlevel != 1)
		{
			$updateStr = '修改';
		}
		else
		{
			$updateStr = '<a href="goods_update.php?cid='.$cid.'&id='.$row['id'].'">修改</a>';
		}


		//删除权限
		if($cfg_adminlevel != 1)
		{
			$delStr = '删除';
		}
		else
		{
			$delStr = '<a href="javascript:;" onclick="ClearInfo('.$row['id'].')">删除</a>';
		}
		
		
		//审核权限
		if($cfg_adminlevel != 1)
		{
			$checkStr = $checkinfo;
		}
		else
		{
			$checkStr = '
				<select onchange="CheckInfo('.$row['id'].',this.value)">
					<option value="">请选择</option>
					<option value="1">通过</option>
					<option value="2">拒绝</option>
				</select>
			';
			//$checkStr = '<a href="javascript:;" title="点击进行审核与未审操作" onclick="CheckInfo('.$row['id'].',\''.$checkinfo.'\')">'.$checkinfo.'</a>';
		}
	?>
	<tr align="left" class="dataTr" onmouseover="this.className='dataTrOn'" onmouseout="this.className='dataTr'">
		<td><?php echo $name; ?></td>
		<td><?php echo $score; ?></td>
		<td><?php echo $passScore; ?></td>
		<td><?php echo $duration; ?></td>
		<td><?php echo $createBy; ?></td>
		<td><?php echo $createDate; ?></td>
		<td><?php echo $checkinfo; ?></td>
		<td class="action endCol"><span id="check<?php echo $row['id']; ?>"><?php echo $checkStr; ?></span> <!-- | <span><?php echo $updateStr; ?></span> | <span class="nb"><?php echo $delStr; ?></span> --></td>
	</tr>
	<?php
	}	
	?>
</table>
<?php

//判断无记录样式
if($dosql->GetTotalRow() == 0)
{
	echo '<div class="dataEmpty">暂时没有相关的记录</div>';
}
?>
<!-- <div class="bottomToolbar"> <span class="selArea"><span>选择：</span> <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:;" onclick="AjaxClearAll();">删除</a></span> <a href="goods_add.php" class="dataBtn">添加商品信息</a></span> </div> -->
<div class="page"> <?php echo $dopage->AjaxPage(); ?> </div>
<?php

//判断是否启用快捷工具栏
if($cfg_quicktool == 'Y')
{
?>
<div class="quickToolbar">
	<div class="qiuckWarp">
		<div class="quickArea"> <span class="selArea"><span>选择：</span> <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:;" onclick="AjaxClearAll();">删除</a></span> <a href="goods_add.php" class="dataBtn">添加商品信息</a></span> <span class="pageSmall"><?php echo $dopage->AjaxPageSmall(); ?></span> </div>
		<div class="quickAreaBg"></div>
	</div>
</div>
<?php
}
?>
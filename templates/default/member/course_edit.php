<?php if(!defined('IN_MEMBER')) exit('Request Error!');
$id = empty($id) ? 0 : intval($id);
$r = $dosql->GetOne("SELECT * FROM `#@__member` WHERE `username`='$c_uname'");
$uid = $r['id'];
$info = $dosql->GetOne("SELECT * FROM #@__course WHERE id=$id AND uid=$uid AND is_delete='false'");
if($id>0 && empty($info)){
	ShowMsg('非法操作！','-1');
	exit();
}
//print_r($info);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $cfg_webname; ?> - 会员中心 - 编辑课程</title>
<link href="<?php echo $cfg_webpath; ?>/templates/default/style/member.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo $cfg_webpath; ?>/templates/default/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $cfg_webpath; ?>/templates/default/js/member.js"></script>

<link rel="stylesheet" href="<?php echo $cfg_webpath; ?>/data/editor/themes/default/default.css" />
<script type="text/javascript" src="<?php echo $cfg_webpath; ?>/data/editor/kindeditor-min.js"></script>
<script src="<?php echo $cfg_webpath; ?>/data/editor/lang/zh_CN.js"></script>
<script type="text/javascript">
KindEditor.ready(function(K) {
	var editor = K.editor({
		allowFileManager : true
	});
	K('#upimg').click(function() {
		editor.loadPlugin('image', function() {
			editor.plugin.imageDialog({
				showRemote : false,
				imageUrl : K('#picurl').val(),
				clickFn : function(url, title, width, height, border, align) {
					K('#picurl').val(url);
					editor.hideDialog();
				}
			});
		});
	});
});
function selgrade(val,input)
{
	$.ajax({
		url : "<?php echo $cfg_webpath; ?>/action.php?act=selgrade&id="+val,
		type:'post',
		dataType:'html',
		success:function(data){
			$("#"+input).html(data);
		}
	});
}
</script>
</head>

<body>
<div class="header">
	<?php require_once(dirname(__FILE__).'/header.php'); ?>
</div>
<div class="mainbody">
	<div class="leftarea">
		<?php require_once(dirname(__FILE__).'/lefter.php'); $id = empty($id) ? 0 : intval($id);?>
	</div>
	<div class="rightarea">
		<form name="form" id="form" method="post" action="?c=course_save" onsubmit="return check_course();">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td colspan="2" height="26"><h3 class="subtitle">编辑课程</h3></td>
				</tr>
                <tr>
					<td height="40" align="right">选择分类：</td>
					<td><select name="grade" id="grade" onchange="selgrade(this.value,'classid');">
                          <option value="">请选择班级</option>
                          <?php
						  if(!empty($info['id'])){
							  $grade = $dosql->GetOne("SELECT * FROM `#@__course_type` WHERE checkinfo='true' AND id=".$info['classid']);
						  }
						  $dosql->Execute("SELECT * FROM `#@__course_type` WHERE checkinfo='true' AND `parentid`=0 ORDER BY orderid ASC");
						  while($row = $dosql->GetArray()){
							  if(!empty($grade['parentid']) && $row['id']==$grade['parentid']){ $sel=' selected="selected"';}else{ $sel='';}
							  echo '<option value="'.$row['id'].'" '.$sel.'>'.$row['classname'].'</option>';
						  }
						  ?>
                        </select>
                        <select name="classid" id="classid">
                          <option value="">请选择科目</option>
                          <?php
						  if(!empty($grade['id'])){
							  $dosql->Execute("SELECT * FROM `#@__course_type` WHERE checkinfo='true' AND `parentid`='".$grade['parentid']."'");
							  while($row = $dosql->GetArray()){
								  if($row['id']==$grade['id']) $sel2 = ' selected="selected"'; else $sel2='';
								  echo '<option value="'.$row['id'].'" '.$sel2.'>'.$row['classname'].'</option>';
							  }
						  }
						  ?>
                        </select></td>
				</tr>
				<tr>
					<td height="40" align="right">课程名称：</td>
					<td><input type="text" name="title" id="title" class="class_input" value="<?php if(isset($info['title'])) echo $info['title'];?>" /></td>
				</tr>
                <tr>
					<td height="40" align="right">图片：</td>
					<td><input type="text" name="picurl" id="picurl" class="class_input" value="<?php if(isset($info['picurl'])) echo $info['picurl'];?>" readonly="readonly" /> &nbsp; <input type="button" id="upimg" class="btn" value="上传" /></td>
				</tr>
                <tr>
					<td height="40" align="right">价格：</td>
					<td><input type="text" name="price" id="price" class="class_input" value="<?php if(isset($info['price'])) echo $info['price'];?>" /></td>
				</tr>
                <tr>
					<td height="40" align="right">关键字：</td>
					<td><input type="text" name="keywords" id="keywords" class="class_input" value="<?php if(isset($info['keywords'])) echo $info['keywords'];?>" /></td>
				</tr>
				<tr>
					<td height="124" align="right">简介：</td>
					<td><textarea name="description" id="description" class="class_areatext"><?php if(isset($info['description'])) echo $info['description'];?></textarea></td>
				</tr>
				<tr>
					<td height="124" align="right">详情：</td>
					<td><textarea name="content" id="content" class="class_areatext"><?php if(isset($info['content'])) echo $info['content'];?></textarea></td>
				</tr>
                <tr>
					<td height="40" align="right">连载状态：</td>
					<td><select name="serialstatus">
                          <option value="1" <?php if(!isset($info['serialstatus']) || $info['serialstatus']==1) echo 'selected="selected"';?>>不连载</option>
                          <option value="2" <?php if(isset($info['serialstatus']) && $info['serialstatus']==2) echo 'selected="selected"';?>>更新中</option>
                          <option value="3" <?php if(isset($info['serialstatus']) && $info['serialstatus']==3) echo 'selected="selected"';?>>完结</option>
                        </select></td>
				</tr>
                <tr>
					<td height="40" align="right">是否显示：</td>
					<td><input type="radio" name="checkinfo" value="true" <?php if(!isset($info['checkinfo']) || $info['checkinfo']=='true') echo 'checked="checked"';?> /> 是 <input type="radio" name="checkinfo" value="false" <?php if(isset($info['checkinfo']) && $info['checkinfo']=='false') echo 'checked="checked"';?> /> 否</td>
				</tr>
			</table>
			<div class="btn_area">
				<input type="submit" class="btn" value="保 存" />
				<input type="button" class="btn" value="取 消" onclick="history.go(-1)" />
                <input type="hidden" name="id" value="<?php if(isset($info['id'])) echo $info['id'];?>" />
			</div>
		</form>
	</div>
	<div class="cl"></div>
</div>
<div class="footer"><?php echo $cfg_copyright; ?></div>
</body>
</html>
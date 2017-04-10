<?php if(!defined('IN_MEMBER')) exit('Request Error!');
$cid = empty($cid) ? 0 : intval($cid);
$id = empty($id) ? 0 : intval($id);
$r = $dosql->GetOne("SELECT * FROM `#@__member` WHERE `username`='$c_uname'");
$uid = $r['id'];
//判断是否存在课程
$cinfo = $dosql->GetOne("SELECT * FROM #@__course WHERE id=$cid AND uid=$uid AND is_delete='false'");
if($cid>0 && empty($cinfo)){
	ShowMsg('非法操作！','-1');
	exit();
}

$info = $dosql->GetOne("SELECT * FROM #@__course_lesson WHERE id=$id AND uid=$uid AND is_delete='false'");
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
<title><?php echo $cfg_webname; ?> - 会员中心 - 编辑课程章节</title>
<link href="<?php echo $cfg_webpath; ?>/templates/default/style/member.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo $cfg_webpath; ?>/templates/default/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $cfg_webpath; ?>/templates/default/js/member.js"></script>
<script type="text/javascript" src="<?php echo $cfg_webpath; ?>/templates/default/js/getuploadify.js"></script>
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
		<form name="form" id="form" method="post" action="?c=course_lesson_save" onsubmit="return check_lesson();">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td colspan="2" height="26"><h3 class="subtitle">编辑课程章节</h3></td>
				</tr>
				<tr>
					<td height="40" align="right">章节描述：</td>
					<td><input type="text" name="description" id="description" class="class_input" value="<?php if(isset($info['description'])) echo $info['description'];?>" /></td>
				</tr>
                <tr>
					<td height="40" align="right">视频地址：</td>
					<td><input type="text" name="videourl" id="picurl" class="class_input" value="<?php if(isset($info['videourl'])) echo $info['videourl'];?>" readonly="readonly" /> &nbsp; <input type="button" class="btn" value="上传" onclick="GetUploadify('uploadify','视频上传','media','media',1,<?php echo $cfg_max_file_size; ?>,'picurl')" /></td>
				</tr>
				<tr>
					<td height="124" align="right">详情：</td>
					<td><textarea name="content" id="content" class="class_areatext"><?php if(isset($info['content'])) echo $info['content'];?></textarea></td>
				</tr>
                <tr>
					<td height="40" align="right">是否显示：</td>
					<td><input type="radio" name="checkinfo" value="true" <?php if(!isset($info['checkinfo']) || $info['checkinfo']=='true') echo 'checked="checked"';?> /> 是 <input type="radio" name="checkinfo" value="false" <?php if(isset($info['checkinfo']) && $info['checkinfo']=='false') echo 'checked="checked"';?> /> 否</td>
				</tr>
                <tr>
					<td height="40" align="right">是否免费：</td>
					<td><input type="radio" name="is_free" value="true" <?php if(!isset($info['is_free']) || $info['is_free']=='true') echo 'checked="checked"';?> /> 是 <input type="radio" name="checkinfo" value="false" <?php if(isset($info['is_free']) && $info['is_free']=='false') echo 'checked="checked"';?> /> 否</td>
				</tr>
			</table>
			<div class="btn_area">
				<input type="submit" class="btn" value="保 存" />
				<input type="button" class="btn" value="取 消" onclick="history.go(-1)" />
                <input type="hidden" name="id" value="<?php if(isset($info['id'])) echo $info['id'];?>" />
                <input type="hidden" name="course_id" value="<?php echo $cid;?>" />
			</div>
		</form>
	</div>
	<div class="cl"></div>
</div>
<div class="footer"><?php echo $cfg_copyright; ?></div>
</body>
</html>
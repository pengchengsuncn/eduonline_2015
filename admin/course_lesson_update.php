<?php require_once(dirname(__FILE__).'/inc/config.inc.php');//IsModelPriv('course_type'); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>修改课程分类</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/checkf.func.js"></script>
<link href="templates/js/video/video-js.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="templates/js/video/video.js"></script>
<script type="text/javascript">
videojs.options.flash.swf = "templates/js/video/video-js.swf";
</script>
</head>
<body>
<?php
$row = $dosql->GetOne("SELECT * FROM `#@__course_lesson` WHERE `id`=$id");
?>
<div class="formHeader"> <span class="title">查看章节</span> <a href="javascript:location.reload();" class="reload">刷新</a> </div>
<form name="form" id="form" method="post" action="course_lesson_save.php" onsubmit="return cfm_course_type();">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="formTable">
    <tr>
      <td width="25%" height="40" align="right">章节名称：</td>
      <td><input type="text" name="description" id="description"  value="<?php echo $row['description']; ?>" class="input" /></td>
    </tr>
    <tr>
      <td height="40" align="right">章节详情：</td>
      <td><textarea name="content" id="content" class="textarea"><?php echo $row['content']; ?></textarea></td>
    </tr>
    <tr>
      <td height="320" align="right">相关视频：</td>
      <td><?php
		  if(!empty($row['videourl'])){
		  ?>
          <video id="example_video_1" class="video-js vjs-default-skin" controls preload="none" width="580" height="300" data-setup="{}">
            <source src="../<?php echo $row['videourl'];?>" type='video/mp4' />
          </video>
          <?php
		  }
		  ?></td>
    </tr>
    <tr>
      <td height="40" align="right">是否审核：</td>
      <td><input type="radio" name="checkinfo" id="checkinfo" value="true" <?php if($row['checkinfo']=='true') echo 'checked="checked"';?> /> 是
          <input type="radio" name="checkinfo" id="checkinfo" value="false" <?php if($row['checkinfo']=='' || $row['checkinfo']=='false') echo 'checked="checked"';?> /> 否</td>
    </tr>
    <tr>
      <td height="40" align="right">是否免费：</td>
      <td><input type="radio" id="is_free" value="true" <?php if($row['is_free']=='true') echo 'checked="checked"';?> /> 是
          <input type="radio" id="is_free" value="false" <?php if($row['is_free']=='' || $row['checkinfo']=='false') echo 'checked="checked"';?> /> 否</td>
    </tr>
    <tr>
      <td height="40" align="right">点击次数：</td>
      <td><input type="text" name="hits" id="hits" class="input" value="<?php echo $row['hits']; ?>" /></td>
    </tr>
  </table>
  <div class="formSubBtn">
    <input type="submit" class="submit" value="提交" />
    <input type="button" class="back" value="返回" onclick="history.go(-1);" />
    <input type="hidden" name="action" id="action" value="update" />
    <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
  </div>
</form>
</body>
</html>
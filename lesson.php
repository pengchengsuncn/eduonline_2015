<?php require_once(dirname(__FILE__).'/include/config.inc.php');
$id  = empty($id)  ? 0 : intval($id);
$lesson = $dosql->GetOne("SELECT * FROM #@__course_lesson WHERE id=$id");
if(empty($lesson)){
	ShowMsg('您访问的信息不存在！');
	exit();
}elseif($lesson['is_delete']!='false'){
	ShowMsg('您访问的信息已删除！');
	exit();
}elseif($lesson['checkinfo']!='true'){
	ShowMsg('您访问的信息未发布！');
	exit();
}
//更新点击次数
$dosql->ExecNoneQuery("UPDATE `#@__course_lesson` SET hits=hits+1 WHERE id=$id");
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<?php echo GetHeader(1,'','',$lesson['description']); ?>
<link type="text/css" rel="stylesheet" href="style/moudle.css">
<link type="text/css" rel="stylesheet" href="style/course/main-1.css">
<link href="js/video/video-js.css" rel="stylesheet" type="text/css">
<script src="js/video/video.js"></script>
<script>
videojs.options.flash.swf = "js/video/video-js.swf";
</script>
</head>
<body>
<?php require_once('header.php');?>
<div id="container" class="fl">
  <div class="c">
    <div class="teach-v-warp">
      <div class="main-a">
        <?php
		if(!empty($lesson['videourl'])){
		?>
		<video id="example_video_1" class="video-js vjs-default-skin" controls preload="none" width="750" height="420" data-setup="{}">
		  <source src="<?php echo $lesson['videourl'];?>" type='video/mp4' />
		</video>
		<?php
		}
		?>
      </div>
      <div class="main-b">
        <h2><?php echo $lesson['description'];?></h2>
        <div style="line-height:24px;"><?php echo $lesson['content'];?></div>
      </div>
    </div>
  </div>
</div>
<?php require_once('footer.php');?>
</body>
</html>

<?php if(!defined('IN_MEMBER')) exit('Request Error!'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $cfg_webname; ?> - 会员中心 - 学习资料</title>
<link href="<?php echo $cfg_webpath; ?>/templates/default/style/member.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo $cfg_webpath; ?>/templates/default/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $cfg_webpath; ?>/templates/default/js/member.js"></script>
</head>

<body>
<div class="header">
	<?php require_once(dirname(__FILE__).'/header.php'); ?>
</div>
<div class="mainbody">
	<div class="leftarea">
		<?php require_once(dirname(__FILE__).'/lefter.php'); ?>
	</div>
	<div class="rightarea">
		<h3 class="subtitle">学习资料
			<a style='float:right; color:blue;' href="?c=uploadlearningfile">上传学习资料</a>
		</h3>
		<?php
			$dopage->GetPage("
				SELECT *
				FROM `#@__learning_file`
				ORDER BY id DESC
			",9);
		if($dosql->GetTotalRow() > 0)
		{
		?>
		<form name="form" id="form" method="post">
		<ul class="msglist">
			<?php
				while($row = $dosql->GetArray()){
			?>
			<li>
				<p>
					<?php
						if($row['approved'] == 0){
							echo $row['title'];
						}else{
							echo "<input type='checkbox' name='checkid[]' id='checkid[]' value='".$row['id']."' />";
							echo "&nbsp;&nbsp;";
							echo "<a href='".$row['file_path']."'>".$row['title']."</a>";
							echo "<span style='float:right;'><a style='color:green;' href='?c=learningfileedit&id=".$row['id']."'>编辑</a></span>";
						}						
					?>
				</p>
				<span class="from">
					<?php
						if($row['approved'] == 0){
							echo "状态：";
							echo "等待审核";
						}else{
							echo "文件类型：";
							if($row['file_type'] == "application/msword"){
								$fileType = "Word文档";
							}elseif($row['file_type'] == "application/vnd.ms-excel"){
								$fileType = "Excel文档";
							}elseif($row['file_type'] == "application/octet-stream"){
								$fileType = "压缩文件";
							}elseif($row['file_type'] == "image/png"){
								$fileType = "图片文件";
							}elseif($row['file_type'] == "image/gif"){
								$fileType = "图片文件";
							}elseif($row['file_type'] == "image/jpeg"){
								$fileType = "图片文件";
							}else{
								$fileType = "未知类型";
							}
							echo $fileType;
							echo "&nbsp;&nbsp;";
							echo "下载次数：0";
						}
					?>
				</span>
				<span class="time">创建时间：
					<?php echo $row['upload_date']; ?>
				</span>
				<div class="cl"></div>
			</li>
			<?php
				}
			?>
		</ul>
		</form>
		<div class="options_b">选择： <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:DelAllNone('?a=delclass');" onclick="return ConfDelAll(0);">删除</a></div>
		<?php echo $dopage->GetList(); ?>
		<?php
		}
		else
		{
		?>
		<div class="nonelist">暂未查到学习资料哦！</div>
		<?php
		}
		?>
	</div>
	<div class="cl"></div>
</div>
<div class="footer"><?php echo $cfg_copyright; ?></div>
</body>
</html>

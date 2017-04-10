<?php if(!defined('IN_MEMBER')) exit('Request Error!'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $cfg_webname; ?> - 会员中心 - 申请成为教师</title>
<link href="<?php echo $cfg_webpath; ?>/templates/default/style/member.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo $cfg_webpath; ?>/templates/default/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $cfg_webpath; ?>/templates/default/js/member.js"></script>
<script type="text/javascript">
function app_chk(){
	if($('#school').val()==''){
		alert('所在学校不能为空！');
		$('#school').focus();
		return false;
	}
	if($('#class').val()==''){
		alert('所带班级不能为空！');
		$('#class').focus();
		return false;
	}
	if($('#teach').val()==''){
		alert('任课班级不能为空！');
		$('#teach').focus();
		return false;
	}
	return true;
}
</script>
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
		<h3 class="subtitle">申请成为教师</h3>
		<div class="applyteacher">
          <?php
		  //判断是否已提交
	      $row = $dosql->GetOne("SELECT * FROM `#@__applyteacher` WHERE uname = '$c_uname'");
		  if(isset($row['id']) && $row['checkinfo']=='0'){
			  echo '<p style="color:#F00; text-align:center; line-height:50px;">您已经申请过了，正在等待管理员审核！</p>';
		  }elseif(isset($row['checkinfo']) && $row['checkinfo']=='1'){
			  echo '<p style="color:#F00; text-align:center; line-height:50px;">您的申请已通过！</p>';
		  }elseif(isset($row['checkinfo']) && $row['checkinfo']=='2'){
			  echo '<p style="color:#F00; text-align:center; line-height:50px;">您的申请未通过，'.$row['remark'].'！</p>';
		  }else{
		  ?>
          <form action="" method="post" onsubmit="return app_chk();">
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="20%" height="40" align="right">所在学校：</td>
              <td><input name="school" id="school" maxlength="120" type="text" class="class_input" /></td>
            </tr>
            <tr>
              <td height="40" align="right">所带班级：</td>
              <td><input name="class" id="class" type="text" maxlength="20" class="class_input" /></td>
            </tr>
            <tr>
              <td height="40" align="right">任课班级：</td>
              <td><input name="teach" id="teach" type="text" maxlength="20" class="class_input" /></td>
            </tr>
            <tr>
              <td height="40"></td>
              <td><input type="submit" class="btn" value="保存" /> <input type="button" onclick="history.go(-1)" value="取 消" class="btn"><input type="hidden" value="add" id="action" name="action"></td>
            </tr>
          </table>
          </form>
          <?php
		  }
		  ?>
        </div>
	</div>
	<div class="cl"></div>
</div>
<div class="footer"><?php echo $cfg_copyright; ?></div>
</body>
</html>

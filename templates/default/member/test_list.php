<?php if(!defined('IN_MEMBER')) exit('Request Error!'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $cfg_webname; ?> - 会员中心 - 试卷列表</title>
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
		<h3 class="subtitle">试卷列表
			<?php
				//只有老师才能创建试卷
				if($r_user['is_teacher']=="true"){
			?>
			<a style='float:right; color:blue;' href="?c=newtest">创建试卷</a>
			<?php
				}
			?>
		</h3>
		<?php
		if($r_user['is_teacher']=="true"){
			//老师只能看到自己创建的试卷
			$dopage->GetPage("SELECT * FROM `#@__test` WHERE status <> 'deleted' AND create_by=".$r_user['id']." ORDER BY id DESC",9);
		}else{
			//学生只能看到已经审核通过且状态为open的试卷
			$dopage->GetPage("SELECT * FROM `#@__test` WHERE status <> 'deleted' AND approved=1 AND status='open' ORDER BY id DESC",9);
		}
		if($dosql->GetTotalRow() > 0)
		{
		?>
		<form name="form" id="form" method="post">
		<ul class="msglist">
			<?php
				while($row = $dosql->GetArray()){

				if($r_user['is_teacher']=="true"){
					$userTestSummary = $dosql->GetOne("SELECT * FROM `#@__test_summary` WHERE test_id=".$row['id']);
				}else{
					// 获取当前登录用户是否已经参加过此考试
					$userTestSummary = $dosql->GetOne("SELECT *, NOW() AS curr_date FROM `#@__test_summary` WHERE user_id=".$r_user['id']." AND test_id=".$row['id']);
				}
			?>
			<li>
				<p>
					<?php
						//试卷是否通过审核
						if($row['approved'] == 1){
							// 前端只有老师只能对未有学员参加考试的试卷进行删除、编辑
							if($r_user['is_teacher']=="true" ){
								if(!is_array($userTestSummary)){
									echo "<input type='checkbox' name='checkid[]' id='checkid[]' value='".$row['id']."' />";
									echo "&nbsp;&nbsp;";
									echo "<a href='?c=testdtl&testid=".$row['id']."'>".$row['name']."</a>";
									echo "<span style='float:right;'><a style='color:green;' href='?c=paperedit&id=".$row['id']."'>编辑</a></span>";
								}else{
									echo "<a href='?c=testdtl&testid=".$row['id']."'>".$row['name']."</a>";
									echo "<span style='float:right;'><a href='?c=usertest&testid=".$row['id']."'>查看学员考试</a></span>";
								}
							}else{
								echo $row['name'];
								//只有已经参加过考试的学生才能查看自己的成绩
								if(is_array($userTestSummary) && $r_user['is_teacher']!="true"){
									if($userTestSummary['submit_date'] != "0000-00-00 00:00:00"){
										echo "<a href='?c=findscore&id=".$row['id']."'><span style ='float:right;color:green;'>查看成绩</span></a>";
										}
								}
								if($row['status'] == "open"){
									// 只有试卷状态为开放并且当前登录用户没有参加过此测试，才会显示参加考试链接
									if(!is_array($userTestSummary) || (is_array($userTestSummary) && $userTestSummary['submit_date'] == "0000-00-00 00:00:00" && strtotime($userTestSummary['end_date']) > strtotime($userTestSummary['curr_date']))){
										echo "<span style='float:right;'><a style='color:green;' href='?c=taketest&testid=".$row['id']."'>参加考试</a></span>";
									}
								}else{
									echo "<span style='float:right;'>试卷已关闭</span>";
								}
							}
						}else{
							echo $row['name'];
						}
					?>
				</p>
				<span class="from">状态：
					<?php
						if($row['approved'] == 1){
							if(is_array($userTestSummary) && $r_user['is_teacher']!="true"){
								if($userTestSummary['submit_date'] != "0000-00-00 00:00:00"){
									echo "已参加过";
								}elseif(strtotime($userTestSummary['end_date']) < strtotime($userTestSummary['curr_date'])){
									echo "时间已过";
								}else{
									echo "正在进行";
								}
							}else{
								if($row['status'] == "closed"){
									echo "关闭";
								}elseif($row['status'] == "open") {
									echo "开放";
								}else{
									echo "未知";
								}
							}
						}elseif($row['approved'] == 2){
							echo "审核不通过，原因：".$row['reject_reason'];
						}else{
							echo "正在审核";
						}
					?>
				</span>
				<span class="time">创建时间：
					<?php echo $row['create_date']; ?>
				</span>
				<div class="cl"></div>
			</li>
			<?php
				}
			?>
		</ul>
		</form>
		<?php
			// 前端只有老师能删除试卷
			if($r_user['is_teacher']=="true"){
		?>
			<div class="options_b">选择： <a href="javascript:CheckAll(true);">全部</a> - <a href="javascript:CheckAll(false);">无</a> - <a href="javascript:DelAllNone('?a=delpaper');" onclick="return ConfDelAll(0);">删除</a></div>
		<?php
			}
		?>
		
		<?php echo $dopage->GetList(); ?>
		<?php
		}
		else
		{
		?>
		<div class="nonelist">暂未查到试卷哦！</div>
		<?php
		}
		?>
	</div>
	<div class="cl"></div>
</div>
<div class="footer"><?php echo $cfg_copyright; ?></div>
</body>
</html>

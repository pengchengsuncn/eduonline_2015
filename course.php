<?php require_once(dirname(__FILE__).'/include/config.inc.php');
$id  = empty($id)  ? 0 : intval($id);
$course = $dosql->GetOne("SELECT * FROM #@__course WHERE id=$id");
if(empty($course)){
	ShowMsg('您访问的信息不存在！');
	exit();
}elseif($course['is_delete']!='false'){
	ShowMsg('您访问的信息已删除！');
	exit();
}elseif($course['checkinfo']!='true'){
	ShowMsg('您访问的信息未发布！');
	exit();
}
//更新点击次数
$dosql->ExecNoneQuery("UPDATE `#@__course` SET hits=hits+1 WHERE id=$id");
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<?php echo GetHeader(1,'','',$course['title']); ?>
<link type="text/css" rel="stylesheet" href="style/moudle.css">
<link type="text/css" rel="stylesheet" href="style/course/main-1.css">
</head>
<body>
<?php require_once('header.php');?>
<div id="container" class="fl">
  <div class="c">
    <div class="teach-v-warp">
      <div class="main-a"><img src="<?php echo $course['picurl'];?>" style="max-height:350px;" /></div>
      <div class="main-b">
        <h2><?php echo $course['title'];?></h2>
        <p class="main-b-val"><span>价格：</span><b class="ftred">
          <?php if(empty($course['price'])) echo '免费'; else echo "￥".$course['price']."元";?>
          </b></p>
        <p class="main-b-assess"> <span class="fl">评论人数：&nbsp;&nbsp;(<?php $ctot=$dosql->GetOne("SELECT COUNT(*) AS num FROM #@__course_comment WHERE course_id=$id"); echo $ctot['num']; ?>人)</span> <span class="fr">学员：<b class="ftred"><?php $mtot=$dosql->GetOne("SELECT COUNT(*) AS num FROM #@__course_user WHERE course_id=$id"); echo $mtot['num']; ?></b>人&nbsp;&nbsp;</span> </p>
        <p class="main-b-btn">
          <?php
		  if(isset($userinfo['id'])){
		      $join = $dosql->GetOne("SELECT * FROM `#@__course_user` WHERE is_delete='false' AND `course_id`='$id' AND uid=".$userinfo['id']);
		  }
		  if(empty($join)){
		  ?>
          <button type="button" class="btn-red" onClick="joincourse(<?php echo $id;?>);">加入学习</button>
          <?php
		  }else{
		  ?>
          <button type="button" class="btn-grey" onClick="quitcourse(<?php echo $id;?>);">退出学习</button>
          <?php
		  }
		  ?>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <button type="button" class="btn-grey" onClick="collectcourse(<?php echo $id;?>);"><?php if(isset($userinfo['id'])) $collect = $dosql->GetOne("SELECT * FROM `#@__course_collect` WHERE `course_id`='$id' AND uid=".$userinfo['id']); if(empty($collect)) echo '收藏课程';else echo '取消收藏';?></button>
        </p>
      </div>
    </div>
    <div class="main fl">
      <section class="glb"><!--  这里是课程介绍  start-->
        <h1 class="grey">课程介绍</h1>
        <article class="course-info">
          <?php
		if(!empty($course['content'])){
			echo '<div class="course-info-no">'.$course['content'].'</div>';
		}else{
			echo '<p class="course-info-no ftred">还没有课程介绍</p>';
		}
		?>
        </article>
      </section>
      <section class="glb"><!--  这里是课时列表  start-->
        <h1 class="grey">课时列表</h1>
        <article class="course-list">
          <ul>
            <?php
		  $dosql->Execute("SELECT * FROM #@__course_lesson WHERE course_id=$id");
		  $i=1;
		  while($row = $dosql->GetArray()){
		  ?>
            <li><i>课时<?php echo $i;?></i><a href="lesson.php?id=<?php echo $row['id'];?>" class="ftblack" target="_blank"><?php echo $row['description'];?><?php if($row['is_free']=='true') echo '<span>免费</span>';?></a></li>
            <?php
		  $i++;
		  }
		  if($i==1) echo '<li class="ftred">还没有课时</li>';
		  ?>
          </ul>
        </article>
      </section>
      <section class="glb"><!--  这里是课程评价  start-->
        <h1 class="grey"><span class="fr"><a href="javascript:;" class="ftblack" onClick="commentshow();" style="font-size:12px;">我要评论 >></a></span>课程评论</h1>
        <article class="assess">
          <ul>
            <?php
			$dosql->Execute("SELECT c.*, m.id AS uid, m.username, m.cnname, m.is_teacher FROM #@__course_comment AS c LEFT JOIN #@__member AS m ON c.uid=m.id WHERE c.course_id=$id AND c.checkinfo='true' ORDER BY c.posttime DESC");
			while($row=$dosql->GetArray()){
				if($row['is_teacher']=='true'){
					$url = 'teacher.php?id='.$row['id'];
				}else{
					$url = 'student.php?id='.$row['id'];
				}
			?>
            <li>
              <dl>
                <dt><a href="<?php echo $url;?>" target="_blank"><img src="course/images/largeavatar.png" alt="**" /></a></dt>
                <dd>
                  <p><a href="<?php echo $url;?>" class="ftred"><?php if(!empty($row['cnname'])) echo $row['cnname'];else echo $row['username'];?></a></a><span class="fr"><?php echo GetDateMk($row['posttime']);?></span></p>
                  <!--<p><i class="star-3"></i></p>-->
                  <p class="assess-say"><?php echo $row['content'];?></p>
                </dd>
              </dl>
            </li>
            <?php
			}
			?>
          </ul>
          <?php
		  if(!empty($join)){
		  ?>
          <div class="comment" id="comment">
            <form action="" method="post">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="80" height="136">评论内容：</td>
                <td><textarea name="content" id="content"></textarea></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td><input type="button" value="提交" class="btn-red" onClick="comment();" /></td>
              </tr>
            </table>
            </form>
          </div>
          <?php
		  }
		  ?>
        </article>
      </section>
    </div>
    <!--main部分  end--> 
    <!--  右边 部分  -->
    <div class="sidebar fr"> 
      <!--  这里课程教师区域  start-->
      <section class="glb">
        <h1 class="grey">课程教师</h1>
        <article class="glb-pho">
          <ul>
            <li>
              <?php
			  $teacher = $dosql->GetOne("SELECT * FROM #@__member WHERE id=".$course['uid']);
			  if(!empty($teacher)){
			  ?>
              <dl>
                <dt><a href="teacher.php?id=<?php echo $teacher['id'];?>"><img src="images/photo-1.png" alt="教师头像" /></a></dt>
                <dd class="glb-pho-title">
                  <p><a href="teacher.php?id=<?php echo $teacher['id'];?>" class="ftblack"><?php if(!empty($teacher['cnname'])) echo $teacher['cnname'];else echo $teacher['username'];?></a></p>
                </dd>
                <dd class="glb-pho-desc glb-pho-more">
                  <p><?php if(empty($teacher['intro'])) echo '暂无简介';else echo $teacher['intro'];?></p>
                </dd>
              </dl>
              <?php
			  }else{
				 echo '暂无相关教师信息'; 
			  }
			  ?>
            </li>
          </ul>
        </article>
      </section>
      <!--<section class="glb">
        <h1 class="grey">最新讨论</h1>
        <article class="glb-tails">
          <ul>
            <li><a href="###" class="ftblack">全国特级数学教师王燕谋教授高考数学点睛十二</a><span>2014-10-26</span></li>
            <li><a href="###" class="ftblack">全国特级数学教师王燕谋教授高考数学点睛十二</a><span>2014-10-29</span></li>
            <li><a href="###" class="ftblack">全国特级数学教师王燕谋教授高考数学点睛十二</a><span>2015-01-24</span></li>
            <li><a href="###" class="ftblack">全国特级数学教师王燕谋教授高考数学点睛十二</a><span>2015-05-16</span></li>
          </ul>
        </article>
      </section>-->
      <!--  这里是学员展示区域  start-->
      <section class="glb">
        <h1 class="grey">最新学员</h1>
        <article class="stud-list">
          <ul>
            <?php
			$dosql->Execute("SELECT m.* FROM #@__member AS m LEFT JOIN #@__course_user AS u ON m.id=u.uid WHERE course_id=$id AND is_delete='false' ORDER BY u.posttime DESC");
			while($row=$dosql->GetArray()){
				if($row['is_teacher']=='true'){
					$url = 'teacher.php?id='.$row['id'];
				}else{
					$url = 'student.php?id='.$row['id'];
				}
			?>
            <li>
              <dl>
                <dt><a href="<?php echo $url;?>" target="_blank"><img src="course/images/largeavatar.png" alt="**" /></a></dt>
                <dd><a href="<?php echo $url;?>" target="_blank" class="ftblack"><?php if(!empty($row['cnname'])) echo $row['cnname'];else echo $row['username'];?></a></dd>
              </dl>
            </li>
            <?php
			}
			?>
          </ul>
        </article>
      </section>
    </div>
  </div>
</div>
<?php require_once('footer.php');?>
<script type="text/javascript">
//加入学习
function joincourse(id){
	if(id == ''){
		alert('非法操作！');
		return false;
	}
	$.ajax({
		url:"action.php",
		type:"post",
		data:"act=joincourse&"+"id="+id,
		dataType:'json',
		success:function(data){     
		   if(data.state=='true'){
			   alert(data.info);
			   location.reload();
		   }else{
			   alert(data.info);
		   }
		},
		error:function(){     
		   alert('error');    
		}
	});
}
//退出学习
function quitcourse(id){
	if(id == ''){
		alert('非法操作！');
		return false;
	}
	$.ajax({
		url:"action.php",
		type:"post",
		data:"act=quitcourse&"+"id="+id,
		dataType:'json',
		success:function(data){     
		   if(data.state=='true'){
			   alert(data.info);
			   location.reload();
		   }else{
			   alert(data.info);
		   }
		},
		error:function(){     
		   alert('error');    
		}
	});
}
//收藏课程
function collectcourse(id){
	if(id == ''){
		alert('非法操作！');
		return false;
	}
	$.ajax({
		url:"action.php",
		type:"post",
		data:"act=collectcourse&"+"id="+id,
		dataType:'json',
		success:function(data){     
		   if(data.state=='true'){
			   alert(data.info);
			   location.reload();
		   }else{
			   alert(data.info);
		   }
		},
		error:function(){     
		   alert('error');    
		}
	});
}
//显示评论表单
function commentshow(){
	<?php if(!isset($userinfo['id'])) echo 'alert("您还未登录，请登录后评论！"); return false;';?>
	<?php if(empty($join)) echo 'alert("您还未加入此课程，暂不能评论！"); return false;';?>
	$('#content').focus();
}
//提交评论
function comment(){
	if($('#content').val()==''){
		alert('评论内容不能为空！');
		$('#content').focus();
		return false;
	}else if($('#content').val().length<5 || $('#content').val().length>120){
		alert('评论内容在5-120个字之间！');
		$('#content').focus();
		return false;
	}
	$.ajax({
		url:"action.php",
		type:"post",
		data:"act=comment&id=<?php echo $id;?>&content="+$('#content').val(),
		dataType:'json',
		success:function(data){     
		   if(data.state=='true'){
			   alert(data.info);
			   $('#content').val('')
			   location.reload();
		   }else{
			   alert(data.info);
		   }
		},
		error:function(){     
		   alert('error');    
		}
	});
}
</script>
</body>
</html>
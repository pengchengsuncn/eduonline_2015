<?php
require_once(dirname(__FILE__).'/include/config.inc.php');
//初始化参数检测正确性
$id = empty($id) ? 0 : intval($id);
$info = $dosql->GetOne("SELECT * FROM #@__member WHERE id=$id AND is_teacher='true'");
if(empty($info)){
	echo '<script type="text/javascript">alert("非法操作！");history.go(-1);</script>';
	exit();
}elseif($info['expval']<0){
	echo '<script type="text/javascript">alert("您访问的用户因违规被冻结！");history.go(-1);</script>';
	exit();
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<?php echo GetHeader(1,'','','师资介绍'); ?>
<link type="text/css" rel="stylesheet" href="style/moudle.css">
<link type="text/css" rel="stylesheet" href="style/user/teacher.css">
</head>
<body>
<?php require_once('header.php');?>
<div id="container" class="fl">
  <div class="c">
    <div class="main-con">
      <section class="teacher">
        <ul>
          <li style="padding:0;border:none">
            <div class="teacher-warp">
              <!--<p>
                <button type="button" class="btn-2">关注</button>
                <button type="button" class="btn-1">私信</button>
              </p>-->
              <dl>
                <dt><a href="javascript:;"><img src="data/avatar/index.php?uid=<?php echo $info['id'];?>&size=big" width="120" /></a></dt>
                <dd>
                  <h3><?php if(empty($info['cnname'])) echo $info['username'];else echo $info['cnname'];?></h3>
                  <p class="teacher-warp-title">讲师</p>
                  <p class="teacher-warp-desc"><?php if(empty($info['intro'])) echo '暂无简介';else echo $info['intro'];?></p>
                </dd>
              </dl>
            </div>
          </li>
        </ul>
      </section>
    </div>
    <div class="userpage">
      <p class="nav-pills"> <a href="javascript:;" class="ftblack tap">在教的课程</a> <!--<a href="user/learn.html" class="ftblack">在学的课程</a> <a href="user/concern.html" class="ftblack">收藏的课程</a> <a href="user/group.html" class="ftblack">加入的小组</a> <a href="user/following.html" class="ftblack">关注/粉丝</a>--> </p>
      <section class="main-con">
        <ul>
          <?php
		  $dopage->GetPage("SELECT * FROM #@__course WHERE is_delete='false' AND uid=$id ORDER BY hits DESC",8);
		  while($row = $dosql->GetArray())
		  {
		  ?>
          <li>
            <div class="teach-warp">
              <dl>
                <dt><a href="course.php?id=<?php echo $row['id'];?>" target="_blank"><img src="<?php echo $row['picurl'];?>" width="235" height="135" /></a></dt>
                <dd>
                  <h3><a href="course.php?id=<?php echo $row['id'];?>" target="_blank" class="ftblack fl"><?php echo $row['title'];?></a></a><!--<span class="ftred">免费</span>--></h3>
                  <div class="teach-warp-con">
                    <div class="teach-warp-master">
                      <?php echo $row['description'];?>
                    </div>
                    <!--<div class="teach-warp-assess"> <span class="star-1"></span><span>10学员</span> </div>-->
                  </div>
                </dd>
              </dl>
            </div>
          </li>
          <?php
		  }
		  ?>
        </ul>
        <div class="pagenation">
          <?php echo $dopage->GetList(); ?>
        </div>
        <div class="fc"></div>
      </section>
    </div>
  </div>
</div>
<?php require_once('footer.php');?>
</body>
</html>
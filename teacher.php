<?php
require_once(dirname(__FILE__).'/include/config.inc.php');

//初始化参数检测正确性
$cid = empty($cid) ? 1 : intval($cid);
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
    <div class="glb main-con">
      <h1>师资介绍</h1>
      <section class="teacher">
        <ul>
          <?php
		  $dopage->GetPage("SELECT * FROM #@__member WHERE is_teacher='true' AND expval>=0 ORDER BY logintime DESC",5);
		  while($row = $dosql->GetArray())
		  {
		  ?>
          <li>
            <div class="teacher-warp">
              <!--<p>
                <button type="button" class="btn-1">私信</button>
              </p>-->
              <dl>
                <dt><a href="teacher_detail.php?id=<?php echo $row['id'];?>" target="_blank"><img src="data/avatar/index.php?uid=<?php echo $row['id'];?>&size=big" /></a></dt>
                <dd>
                  <h3><a href="teacher_detail.php?id=<?php echo $row['id'];?>" target="_blank" class="ftblack"><?php if(empty($row['cnname'])) echo $row['username'];else echo $row['cnname'];?></a></h3>
                  <p class="teacher-warp-title">讲师</p>
                  <p class="teacher-warp-desc"><?php if(empty($row['intro'])) echo '暂无简介';else echo $row['intro'];?></p>
                </dd>
              </dl>
            </div>
          </li>
          <?php
		  }
		  ?>
        </ul>
      </section>
      <div class="pagenation">
        <?php echo $dopage->GetList(); ?>
      </div>
      <div class="fc"></div>
    </div>
  </div>
</div>
<?php require_once('footer.php');?>
</body>
</html>
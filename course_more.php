<?php
require_once(dirname(__FILE__).'/include/config.inc.php');

//初始化参数检测正确性
$aid = empty($aid) ? 0 : intval($aid);
$bid = empty($bid) ? 0 : intval($bid);
$flag = empty($attr) ? '' : htmlspecialchars($attr);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<?php echo GetHeader(1,'','','课程专区'); ?>
<link type="text/css" rel="stylesheet" href="style/moudle.css">
<link type="text/css" rel="stylesheet" href="style/course/main-1.css">
</head>
<body>
<?php require_once('header.php');?>
<div id="container" class="fl">
  <div class="c">
    <div class="main fl">
      <div class="cate cate-menu-1 fl"> <span>一级分类：</span>
        <ul>
          <li <?php if($aid==0) echo 'class="selected"';?>><a href="course_more.php">全部</a></li>
          <?php
		  $dosql->Execute("SELECT * FROM #@__course_type WHERE parentid=0 ORDER BY orderid ASC");
		  while($row=$dosql->GetArray()){
		  ?>
          <li <?php if($aid==$row['id']) echo 'class="selected"';?>><a href="?aid=<?php echo $row['id'];?>"><?php echo $row['classname'];?></a></li>
          <?php
		  }
		  ?>
        </ul>
      </div>
      <?php
	  if($aid!=0){
	  ?>
      <div class="cate cate-menu-2 fl"> <span>二级分类：</span>
        <ul>
          <li><a href="course_more.php" <?php if($bid==0) echo 'class="ftred"';?>>全部</a></li>
          <?php
		  $dosql->Execute("SELECT * FROM #@__course_type WHERE parentid=$aid ORDER BY orderid ASC");
		  while($row=$dosql->GetArray()){
		  ?>
          <li><a href="?aid=<?php echo $aid;?>&bid=<?php echo $row['id'];?>" <?php if($bid==$row['id']) echo 'class="ftred"';?>><?php echo $row['classname'];?></a></li>
          <?php
		  }
		  ?>
        </ul>
      </div>
      <?php
	  }
	  ?>
      <!--  下面是列表内容  -->
      <section class="main-list">
        <aside>
          <p> <a href="?aid=<?php echo $aid;?>&bid=<?php echo $bid;?>&attr=n" class="<?php if($flag=='n') echo 'ftred'; else echo 'ftblack';?>">最新课程</a> <a href="?aid=<?php echo $aid;?>&bid=<?php echo $bid;?>&attr=r" class="<?php if($flag=='r') echo 'ftred'; else echo 'ftblack';?>">推荐课程</a> <a href="?aid=<?php echo $aid;?>&bid=<?php echo $bid;?>&attr=h" class="<?php if($flag=='h') echo 'ftred'; else echo 'ftblack';?>">最热课程</a> </p>
        </aside>
        <article>
          <ul>
            <?php
			$where = '';
			if($aid!=0 && $bid==0){
				$dosql->Execute("SELECT * FROM #@__course_type WHERE parentid=$aid ORDER BY orderid ASC");
		        while($row=$dosql->GetArray()){
					$cid[] = $row['id'];
				}
				if(!empty($cid)){
				    $cid = implode(',',$cid);
					$where = " AND classid IN($cid) ";
				}
			}elseif($bid!=0){
				$where = " AND classid=$bid ";
			}
			if(!empty($flag)){
				$where = " AND flag LIKE '%$flag%'";
			}
			$dopage->GetPage("SELECT * FROM #@__course WHERE checkinfo='true' AND is_delete='false' $where ORDER BY hits DESC",8);
			while($row = $dosql->GetArray())
			{
				//获取授课老师
				$t = $dosql->GetOne('SELECT * FROM #@__member WHERE id='.$row['uid']);
			?>
            <li>
              <div class="main-list-pic"><a href="course.php?id=<?php echo $row['id'];?>" target="_blank"><img src="<?php echo $row['picurl'];?>" width="235" /></a></div>
              <div class="main-list-desc">
                <p><a href="course.php?id=<?php echo $row['id'];?>" target="_blank" class="ftblack"><?php echo $row['title'];?></a></p>
                <!--<p><span>已有<b class="ftred">16</b>人学习</span><i class="star-0"></i></p>-->
              </div>
              <div class="main-list-pho">
                <dl>
                  <dt><a href="teacher_detail.php?id=<?php echo $t['id'];?>" target="_blank"><img src="data/avatar/index.php?uid=<?php echo $t['id'];?>&size=big" /></a></dt>
                  <dd><a href="teacher_detail.php?id=<?php echo $t['id'];?>" target="_blank" class="ftblack" title="<?php echo $t['cnname'];?>"><?php if(empty($t['cnname'])) echo ReStrLen($t['username'],3,' '); else echo ReStrLen($t['cnname'],3,' ');?></a></dd>
                </dl>
              </div>
            </li>
            <?php
			}
			?>
          </ul>
        </article>
      </section>
      <div class="pagenation fl">
        <?php echo $dopage->GetList(); ?>
      </div>
    </div>
    <!--main部分  end--> 
    <!--  右边 部分  -->
    <div class="sidebar fr">
      <section class="glb"><!--  这里是资讯开始  start-->
        <h1>资讯</h1>
        <article class="glb-news">
          <ul>
            <?php
			$dopage->GetPage("SELECT * FROM `#@__infolist` WHERE (classid=2 OR parentstr LIKE '%,2,%') AND flag LIKE '%h%' AND delstate='' AND checkinfo=true ORDER BY orderid DESC",10);
			$i=1;
			while($row = $dosql->GetArray())
			{
			?>
            <li><a href="article_show.php?cid=<?php echo $row['classid'];?>&id=<?php echo $row['id'];?>" class="ftblack" target="_blank"><small><?php echo $i;?></small><span><?php echo ReStrLen($row['title'],17);?></span></a></li>
            <?php
			    $i++;
			}
			?>
          </ul>
        </article>
      </section>
      <!--  这里是教师展示区域  start-->
      <section class="glb">
        <h1>教师</h1>
        <article class="glb-pho">
          <ul>
            <?php
			$dosql->Execute("SELECT m.*,r.remark FROM `#@__member` AS m LEFT JOIN `#@__member_recommend` AS r ON m.id=r.uid WHERE m.is_teacher='true' AND m.cnname!='' ORDER BY r.orderid DESC,m.id DESC LIMIT 0,5");
			while($row=$dosql->GetArray()){
			?>
            <li>
              <dl>
                <dt><a href="teacher_detail.php?id=<?php echo $row['id'];?>" target="_blank"><img src="data/avatar/index.php?uid=<?php echo $row['id'];?>&size=big" /></a></dt>
                <dd class="glb-pho-title">
                  <p><a href="teacher_detail.php?id=<?php echo $row['id'];?>" target="_blank" class="ftblack"><?php if(empty($row['cnname'])) echo $row['username'];else echo $row['cnname'];?></a></p>
                  <!--<p><span class="ftred">刘老师</span></p>-->
                </dd>
                <dd class="glb-pho-desc">
                  <p><?php echo ReStrLen($row['remark'],25);?></p>
                </dd>
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
</body>
</html>
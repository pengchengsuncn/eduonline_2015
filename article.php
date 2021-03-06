<?php
require_once(dirname(__FILE__).'/include/config.inc.php');

//初始化参数检测正确性
$cid = empty($cid) ? 1 : intval($cid);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<?php echo GetHeader(1,$cid); ?>
<link type="text/css" rel="stylesheet" href="style/moudle.css">
<link type="text/css" rel="stylesheet" href="style/article/article.css">
</head>
<body>
<?php require_once('header.php');?>
<div id="container" class="fl">
  <div class="c">
    <div class="main fl">
      <section class="news">
        <h1><?php echo GetCatName($cid);?></h1>
        <!--<p class="tab-slide"> <a href="javascript:;" class="ftblack selected">资讯频道</a><a href="javascript:;" class="ftblack"><?php echo GetCatName($cid);?></a> </p>-->
        <!--  这里是新闻资讯列表  start-->
        <div class="news-list">
          <ul>
            <?php
			$dopage->GetPage("SELECT * FROM `#@__infolist` WHERE (classid=$cid OR parentstr LIKE '%,$cid,%') AND delstate='' AND checkinfo=true ORDER BY orderid DESC",10);
			while($row = $dosql->GetArray())
			{
			?>
            <li>
              <article>
                <p><a href="?cid=<?php echo $row['classid'];?>" class="ftred"><?php echo GetCatName($row['classid']);?></a><span><?php echo GetDateTime($row['posttime']);?></span></p>
                <div class="article">
                  <dl>
                    <dt><a href="article_show.php?cid=<?php echo $row['classid'];?>&id=<?php echo $row['id'];?>" class="ftblack" target="_blank"><?php echo ReStrLen($row['title'],20);?></a></dt>
                    <dd><?php echo ReStrLen($row['description'],130);?></dd>
                  </dl>
                </div>
                <div class="news-list-thumb"><a href="article_show.php?cid=<?php echo $row['classid'];?>&id=<?php echo $row['id'];?>" target="_blank"><img src="<?php echo $row['picurl'];?>" alt="<?php echo $row['title'];?>" /></a></div>
              </article>
            </li>
            <?php
			}
			?>
          </ul>
        </div>
        <!--  分页功能  -->
        <div class="pagenation fl">
          <?php echo $dopage->GetList(); ?>
        </div>
      </section>
    </div>
    <!--main部分  end--> 
    <!--  右边 部分  -->
    <div class="sidebar fr">
      <section class="glb"><!--  这里是资讯开始  start-->
        <h1>热门资讯</h1>
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
      <section class="glb"><!--  这里是资讯开始  start-->
        <h1>推荐资讯</h1>
        <article class="glb-news">
          <ul>
            <?php
			$dopage->GetPage("SELECT * FROM `#@__infolist` WHERE (classid=2 OR parentstr LIKE '%,2,%') AND flag LIKE '%c%' AND delstate='' AND checkinfo=true ORDER BY orderid DESC",10);
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
    </div>
  </div>
</div>
<?php require_once('footer.php');?>
</body>
</html>
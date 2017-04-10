<?php
require_once(dirname(__FILE__).'/include/config.inc.php');

//初始化参数检测正确性
$cid = empty($cid) ? 1 : intval($cid);
$id  = empty($id)  ? 0 : intval($id);
?>
<html>
<head>
<meta charset="utf-8">
<?php echo GetHeader(1,$cid,$id); ?>
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
        <!--  这里是新闻  start-->
        <?php
		//检测文档正确性
		$row = $dosql->GetOne("SELECT * FROM `#@__infolist` WHERE id=$id");
		if(is_array($row))
		{
			//增加一次点击量
			$dosql->ExecNoneQuery("UPDATE `#@__infolist` SET hits=hits+1 WHERE id=$id");
		?>
        <div class="news-article">
          <h1><?php echo $row['title'];?></h1>
          <p class="news-article-time"> <span class="fl"><?php echo GetDateTime($row['posttime']); ?></span><span class="fr"><?php echo $row['hits']; ?> 次浏览</span> </p>
          <article>
            <?php
			if($row['content'] != '')
				echo GetContPage($row['content']);
			else
				echo '网站资料更新中...';
			?>
          </article>
        </div>
        <!--  分页功能  -->
        <div class="pagenation fl">
          <?php
		  //获取上一篇信息
		  $r = $dosql->GetOne("SELECT * FROM `#@__infolist` WHERE classid=".$row['classid']." AND id<".$row['id']." AND delstate='' AND checkinfo=true ORDER BY orderid DESC");
		  if($r < 1)
		  {
			  echo '<a class="fl" title="没有了">&larr;上一篇</a>';
		  }
		  else
		  {
			  if($cfg_isreurl != 'Y')
				  $gourl = 'article_show.php?cid='.$r['classid'].'&id='.$r['id'];
			  else
				  $gourl = 'article_show-'.$r['classid'].'-'.$r['id'].'-1.html';

			  echo '<a href="'.$gourl.'" class="fl" title="'.$row['title'].'">&larr;上一篇</a></li>';
		  }
		  
		  //获取下一篇信息
		  $r = $dosql->GetOne("SELECT * FROM `#@__infolist` WHERE classid=".$row['classid']." AND id>".$row['id']." AND delstate='' AND checkinfo=true ORDER BY orderid ASC");
		  if($r < 1)
		  {
			  echo '<a class="fr" title="没有了">下一篇&rarr;</a>';
		  }
		  else
		  {
			  if($cfg_isreurl != 'Y')
				  $gourl = 'article_show.php?cid='.$r['classid'].'&id='.$r['id'];
			  else
				  $gourl = 'article_show-'.$r['classid'].'-'.$r['id'].'-1.html';

			  echo '<a href="'.$gourl.'" class="fl" title="'.$row['title'].'">下一篇&rarr;</a></li>';
		  }
		  ?>
        </div>
        <?php
		}
		?>
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
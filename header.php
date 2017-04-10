<script src="<?php echo $cfg_webpath; ?>/js/jq.js"></script>
<script src="<?php echo $cfg_webpath; ?>/js/jquery.SuperSlide.js"></script>
<header>
  <div class="c">
    <ul>
      <li class="fl"><a href="index.html" class="logo"></a></li>
      <li class="fl"><a href="index.html" class="web-desc"></a></li>
      <li class="fl">
        <form name="search" action="###">
          <div id="search">
            <input type="text" name="" placeholder="请输入搜索关键字">
            <input type="submit" name="" value="">
          </div>
        </form>
      </li>
      <li class="fl log_info"><?php if(isset($_COOKIE['username'])){?><a href="member.php?c=default">会员中心</a>&nbsp;|&nbsp;<a href="member.php?a=logout">退出</a><?php }else{?><a href="member.php?c=login">登录</a> | <a href="member.php?c=reg">注册</a><?php }?></li>
      <li class="fr"><span class="web-tright"></span></li>
    </ul>
  </div>
</header>
<nav>
  <div class="c">
    <ul class="fl" id="kjnav">
      <li><a href="javascript:;">推荐产品服务</a>
        <div class="leftbar">
          <ul>
            <?php
			$dosql->Execute("SELECT * FROM `#@__nav` WHERE parentid=18 AND checkinfo=true ORDER BY orderid ASC");
			while($row = $dosql->GetArray()){
			?>
            <li><i></i><a href="<?php echo $row['linkurl'];?>" <?php if($row['target'] != '') echo 'target="'.$row['target'].'"';?>><b><?php echo $row['classname'];?></b><span><?php echo $row['relinkurl'];?></span></a></li>
            <?php
			}
			?>
          </ul>
        </div>
      </li>
    </ul>
    <ul class="fl">
      <?php echo GetNav(); ?>
    </ul>
  </div>
</nav>
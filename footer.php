<footer class="fl">
  <div class="c">
    <div class="footer-1">
      <ul>
        <?php echo GetNav(13); ?>
      </ul>
    </div>
    <div class="footer-2">
      <p><?php if(!empty($cfg_icp)) echo "经营许可证编号：".$cfg_icp;?>&nbsp;<?php if(!empty($cfg_address)) echo "地址：".$cfg_address;?></p>
      <p><?php echo $cfg_copyright;?></p>
    </div>
  </div>
</footer>
<!--  左边的树形菜单  -->
<div id="menu">
  <ul>
    <li><a href="/"><i class="ico-index"></i><span>首页</span></a></li>
    <li><a href="javascript:;"><i class="ico-uu"></i><span>高考</span></a></li>
    <li><a href="javascript:;"><i class="ico-uu"></i><span>中考</span></a></li>
    <li><a href="javascript:;"><i class="ico-uu"></i><span>家长</span></a></li>
    <li><a href="javascript:;"><i class="ico-ting"></i><span>咨询</span></a></li>
    <li><a href="javascript:;" id="back-to-top"><i class="ico-uh"></i><span>顶部</span></a></li>
  </ul>
</div>
<script type="text/javascript">
<!--
//广告banner切换功能
jQuery(".banner").slide({titCell:".hd ul",autoPage:true,mainCell:".bd ul",autoPlay:true,effect:"fade"});

//banner下面tab切换功能
jQuery(".tab").slide({trigger:"click"});

//pagebody  tab切换功能
jQuery(".con").slide({trigger:"click"});

//推荐产品服务下拉
$(function(){
	var href = window.location.href.split('/')[window.location.href.split('/').length-1].substr(0,5);
	if(href.length == 0 || href == 'index'){
		$('.leftbar').fadeIn(0);
	}else{
		$('#kjnav').hover(function(){
			$('.leftbar').toggle();
		});
	}
	
	//返回顶部
	$("#back-to-top").click(function(){  
		$('body,html').animate({scrollTop:0},1000);  
		return false;  
	});
});
//-->
</script>
<?php if(!defined('IN_MEMBER')) exit('Request Error!'); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $cfg_webname; ?> - 会员登录</title>
<link href="<?php echo $cfg_webpath; ?>/style/regpwd.css" type="text/css" rel="stylesheet" />
</head>

<body>
<?php require_once('header.php');?>
<script type="text/javascript" src="<?php echo $cfg_webpath; ?>/templates/default/js/member.js"></script>
<div id="container" class="fl">
  <div class="c">
    <div class="block-center">
      <h2>登录</h2>
      <section>
        <form id="form" method="post" action="?a=login" onsubmit="return CheckLog();">
          <p class="p1">
            <label>账号：</label>
            <input type="text" class="ipt-1" name="username" id="username" />
          </p>
          <p class="p2"><span>请输入用户名/邮箱地址</span></p>
          <p class="p1">
            <label>密码：</label>
            <input type="password" name="password" id="password" class="ipt-1" />
          </p>
          <!--<p class="p1">
            <label>验证码：</label>
            <input type="text" name="validate" id="validate" class="ipt-2" maxlength="4" />
			<span><img id="ckstr" src="data/captcha/ckstr.php" title="看不清？点击更换" align="absmiddle" style="cursor:pointer;" onClick="this.src=this.src+'?'" /> <a href="javascript:;" onClick="var v=document.getElementById('ckstr');v.src=v.src+'?';return false;">看不清?</a></span><br />
          </p>-->
          <p class="p1">
            <label>&nbsp;</label>
            <input type="submit" value="登 录" class="ipt-1 sub" style="width:100px;" />
            <span style="margin-left:50px;">
            <input id="autologin" type="checkbox" value="1" name="autologin" title="两周内自动登录">
            <span title="为了您的信息安全，请不要在网吧或公用电脑上使用此功能！" for="autologin"> 两周内自动登录</span>
            </span>
          </p>
        </form>
        <p class="findpwd"><a href="?c=findpwd">找回密码</a> | 还没有注册帐号？ <a href="?c=reg">立即注册</a></p>
        <p class="other-login">其他登陆方式：<a target="_blank" href="data/api/oauth/connect.php?method=weibo_token"><button class="other-1" title="微博登录" type="button"></button></a><a target="_blank" href="data/api/oauth/connect.php?method=qq_token"><button class="other-2" title="QQ登录" type="button"></button></a>
        </p>
      </section>
    </div>
  </div>
</div>
<?php require_once('footer.php');?>
</body>
</html>
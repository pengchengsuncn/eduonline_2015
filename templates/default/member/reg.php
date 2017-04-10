<?php if(!defined('IN_MEMBER')) exit('Request Error!'); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $cfg_webname; ?> - 会员注册</title>
<link href="<?php echo $cfg_webpath; ?>/style/regpwd.css" type="text/css" rel="stylesheet" />
</head>

<body>
<?php require_once('header.php');?>
<script type="text/javascript" src="<?php echo $cfg_webpath; ?>/templates/default/js/member.js"></script>
<script type="text/javascript">
var countdown=120; 
function settime(obj) { 
    if (countdown == 0) { 
        obj.removeAttribute("disabled");    
        obj.value="免费获取短信验证码"; 
        countdown = 120; 
        return;
    } else { 
        obj.setAttribute("disabled", true); 
        obj.value="重新发送(" + countdown + ")"; 
        countdown--; 
    } 
    setTimeout(function() { 
        settime(obj) }
    ,1000) 
}
function sendsms(obj){
	var ckmobile = /^13[0-9]{9}$|14[0-9]{9}|15[0-9]{9}$|17[0-9]{9}$|18[0-9]{9}$/;
	if($("#mobile").val() == "")
	{
		alert("手机号码不能为空！");
		$("#mobile").focus();
		return false;
	}
	else if(!ckmobile.test($("#mobile").val()))
	{
		alert("手机号码格式不正确！");
		$("#mobile").focus();
		return false;
	}
	$.ajax({
		url:"action.php",
		type:"post",
		data:"act=sms&affect=reg&mobile="+$("#mobile").val(),
		dataType:'json',
		success:function(data){     
		   if(data.state=='true'){
			   //alert(data.info);
			   //location.reload();
		   }else{
			   alert(data.info);
		   }
		},
		error:function(){     
		   alert('error');    
		}
	});
	settime(obj);
}
</script>
<div id="container" class="fl">
  <div class="c">
    <div class="block-center">
      <h2>注册</h2>
      <section>
        <form id="form" method="post" action="?a=reg" onsubmit="return CheckReg();">
          <p class="p1">
            <label>用户名：</label>
            <input type="text" class="ipt-1" name="username" id="username" />
          </p>
          <p class="p2"><span>长度为6~16位字符</span></p>
          <p class="p1">
            <label>密码：</label>
            <input type="password" name="password" id="password" class="ipt-1" />
          </p>
          <p class="p2"><span>5-20位英文、数字、符号，区分大小写</span></p>
          <p class="p1">
            <label>确认密码：</label>
            <input type="password" name="repassword" id="repassword" class="ipt-1" />
          </p>
          <p class="p2"><span>再输入一次密码</span></p>
          <p class="p1">
            <label>邮箱地址：</label>
            <input type="text" name="email" id="email" class="ipt-1" />
          </p>
          <p class="p2"><span>填写你常用的邮箱作为登录帐号</span></p>
          <p class="p1">
            <label>手机号：</label>
            <input type="text" name="mobile" id="mobile" class="ipt-1" />
          </p>
          <p class="p2"><span>输入手机号码</span></p>
          <p class="p1">
            <label>短信验证码：</label>
            <input type="text" name="mobilechk" id="mobilechk" class="ipt-2" />
            &nbsp;&nbsp;
            <input type="button" class="checkma" onClick="sendsms(this)" value="获取短信验证码" style="cursor:pointer;" />
          </p>
          <p class="p2"><span>输入手机验证码</span></p>
          <p class="p1">
            <label>验证码：</label>
            <input type="text" name="validate" id="validate" class="ipt-2" maxlength="4" />
			<span><img id="ckstr" src="data/captcha/ckstr.php" title="看不清？点击更换" align="absmiddle" style="cursor:pointer;" onClick="this.src=this.src+'?'" /> <a href="javascript:;" onClick="var v=document.getElementById('ckstr');v.src=v.src+'?';return false;">看不清?</a></span><br />
          </p>          
          <p class="p1">
            <label>&nbsp;</label>
            <input type="submit" value="注 册" class="ipt-1 sub" />
          </p>
        </form>
      </section>
    </div>
  </div>
</div>
<?php require_once('footer.php');?>
</body>
</html>

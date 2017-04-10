<?php if(!defined('IN_MEMBER')) exit('Request Error!'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $cfg_webname; ?> - 找回密码</title>
<link href="<?php echo $cfg_webpath; ?>/templates/default/style/member.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo $cfg_webpath; ?>/templates/default/js/jquery.min.js"></script>
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
		data:"act=sms&affect=pwd&mobile="+$("#mobile").val(),
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
</head>

<body>
<div class="header">
	<div class="area">
		<div class="logo"><a href="<?php echo $cfg_webpath; ?>/"></a></div>
		<div class="retxt"><a href="<?php echo $cfg_webpath; ?>/">网站首页</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="?c=login">登录</a></div>
	</div>
</div>
<div class="mainbody">
	<div class="top">
		<h2>找回密码</h2>
		<div class="txt">请牢记您注册时填写的账户信息，以便方便您找回密码<a href="?c=reg"></a></div>
	</div>
	<form id="form" method="post" action="?c=findpwd3" onsubmit="return CheckFind();">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<td width="80" height="50">手机号：</td>
					<td><input type="text" name="username" id="mobile" class="input" /></td>
				</tr>
				<tr>
					<td height="50">验证码：</td>
					<td><input type="text" name="validate" id="validate" class="input" maxlength="6" />
						<span><input type="button" value="获取短信验证码" onclick="sendsms(this)" style="border: 1px solid #cecece; border-radius: 2px; font-size: 1em; height: 32px; padding: 0 16px; cursor:pointer;" /></span></td>
				</tr>
				<tr>
					<td height="70"> </td>
					<td><input type="submit" value="下一步" class="sub" /></td>
				</tr>
			</tbody>
		</table>
		<input type="hidden" name="a" value="findpwd3" />
	</form>
	<div class="cl"></div>
</div>
<div class="footer"><?php echo $cfg_copyright; ?></div>
</body>
</html>

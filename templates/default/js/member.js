
/*
**************************
(C)2010-2014 phpMyWind.com
update: 2012-10-16 14:31:32
person: Feng
**************************
*/


$(function(){
	$(".input").focus(function(){
		$(this).attr("class","inputon");
	}).blur(function(){
		$(this).attr("class","input");
	});
	
	/*$(".sub").mouseover(function(){
		$(this).attr("class","subon");
	}).mouseout(function(){
		$(this).attr("class","sub");
	}).mousedown(function(){
		$(this).attr("class","subdown");
	});*/
	
	$("#username").focus();
	
	
	$(".class_input").focus(function(){
		$(this).attr("class","class_input_on");
	}).blur(function(){
		$(this).attr("class","class_input");
	});
	
	$(".class_areatext").focus(function(){
		$(this).attr("class","class_areatext_on");
	}).blur(function(){
		$(this).attr("class","class_areatext");
	});
	
});

function CheckReg()
{
	var ckuname = /^[0-9a-zA-Z_@\.-]+$/;
	if($("#username").val() == "")
	{
		alert("用户名不能为空！");
		$("#username").focus();
		return false;
	}
	else if($("#username").val().length < 6 || $("#username").val().length > 16)
	{
		alert("用户名长度为6~16位字符！");
		$("#username").focus();
		return false;
	}
	else if(!ckuname.test($("#username").val()))
	{
		alert("请使用[数字/字母/中划线/下划线/@.]！");
		$("#username").focus();
		return false;
	}

	var ckupwd = /^[0-9a-zA-Z_-]+$/;
	if($("#password").val() == "")
	{
		alert("密码不能为空！");
		$("#password").focus();
		return false;
	}
	else if($("#password").val().length < 6 || $("#password").val().length > 16)
	{
		alert("密码长度为6~16位字符！");
		$("#password").focus();
		return false;
	}
	else if(!ckupwd.test($("#password").val()))
	{
		alert("请使用[数字/字母/中划线/下划线]！");
		$("#password").focus();
		return false;
	}


	if($("#repassword").val() == "")
	{
		alert("确认密码不能为空！");
		$("#repassword").focus();
		return false;
	}
	else if($("#password").val() != $("#repassword").val())
	{
		alert("两次输入的密码不相同！");
		$("#repassword").focus();
		return false;
	}

	var ckmail = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
	if($("#email").val() == "")
	{
		alert("E-mail不能为空！");
		$("#email").focus();
		return false;
	}
	else if(!ckmail.test($("#email").val()))
	{
		alert("E-mail格式不正确！");
		$("#email").focus();
		return false;
	}
	
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
	
	if($("#mobilechk").val() == "")
	{
		alert("短信验证码不能为空！");
		$("#mobilechk").focus();
		return false;
	}

	if($("#validate").val() == "")
	{
		alert("验证码不能为空！");
		$("#validate").focus();
		return false;
	}
}

function CheckLog()
{
	if($("#username").val() == "")
	{
		alert("请输入用户名/邮箱地址！");
		$("#username").focus();
		return false;
	}


	if($("#password").val() == "")
	{
		alert("请输入密码！");
		$("#password").focus();
		return false;
	}
	
	/*if($("#validate").val() == "")
	{
		alert("请输入验证码！");
		$("#validate").focus();
		return false;
	}*/
}

function CheckFind()
{
	if($("#mobile").val() == "")
	{
		alert("请输入手机号！");
		$("#mobile").focus();
		return false;
	}
	
	if($("#validate").val() == "")
	{
		alert("请输入验证码！");
		$("#validate").focus();
		return false;
	}
}

function CheckFindQues()
{
	if($("#question").val() == "-1")
	{
		alert("请选择验证问题！");
		$("#question").focus();
		return false;
	}
	if($("#answer").val() == "")
	{
		alert("请输入问题答案！");
		$("#answer").focus();
		return false;
	}
}

function CheckFindMail()
{
	var ckmail = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
	if($("#email").val() == "")
	{
		alert("请输入E-mail！");
		$("#email").focus();
		return false;
	}
	else if(!ckmail.test($("#email").val()))
	{
		alert("E-mail格式不正确！");
		$("#email").focus();
		return false;
	}
}

function CheckNewPwd()
{
	var ckupwd = /^[0-9a-zA-Z_=]+$/;
	if($("#password").val() == "")
	{
		alert("密码不能为空！");
		$("#password").focus();
		return false;
	}
	else if($("#password").val().length < 6 || $("#password").val().length > 16)
	{
		alert("密码长度为6~16位字符！");
		$("#password").focus();
		return false;
	}
	else if(!ckupwd.test($("#password").val()))
	{
		alert("请使用[数字/字母/中划线/下划线]！");
		$("#password").focus();
		return false;
	}


	if($("#repassword").val() == "")
	{
		alert("确认密码不能为空！");
		$("#repassword").focus();
		return false;
	}
	else if($("#password").val() != $("#repassword").val())
	{
		alert("两次输入的密码不相同！");
		$("#repassword").focus();
		return false;
	}
}

function cfm_upmember()
{
	if($("#password").val() != "")
	{
		var ckupwd = /^[0-9a-zA-Z_-]+$/;
		if($("#password").val() == "")
		{
			alert("密码不能为空！");
			$("#password").focus();
			return false;
		}
		else if($("#password").val().length < 6 || $("#password").val().length > 16)
		{
			alert("密码长度为6~16位字符！");
			$("#password").focus();
			return false;
		}
		else if(!ckupwd.test($("#password").val()))
		{
			alert("请使用[数字/字母/中划线/下划线]！");
			$("#password").focus();
			return false;
		}

		if($("#repassword").val() == "")
		{
			alert("确认密码不能为空！");
			$("#repassword").focus();
			return false;
		}
		else if($("#password").val() != $("#repassword").val())
		{
			alert("两次输入的密码不相同！");
			$("#repassword").focus();
			return false;
		}

		var ckmail = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
		if($("#email").val() == "")
		{
			alert("E-mail不能为空！");
			$("#email").focus();
			return false;
		}
		else if(!ckmail.test($("#email").val()))
		{
			alert("E-mail格式不正确！");
			$("#email").focus();
			return false;
		}
	}
	else
	{
		var ckmail = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
		if($("#email").val() == "")
		{
			alert("E-mail不能为空！");
			$("#email").focus();
			return false;
		}
		else if(!ckmail.test($("#email").val()))
		{
			alert("E-mail格式不正确！");
			$("#email").focus();
			return false;
		}
	}
}

function check_test(){
	if($.trim($("#name").val()) == ""){
		alert("试卷名称不能为空！");
		return false;
	}

	var cknum = /^[1-9]\d*$/;
	if(!cknum.test($("#pass-score").val())){
		alert("及格分数必须为正整数！");
		return false;
	}

	if(!cknum.test($("#duration").val())){
		alert("考试时常必须为正整数！");
		return false;
	}

	return true;
}

function check_learning_file(){
	if($.trim($("#file").val()) == ""){
		alert("请选择学习资料！");
		return false;
	}

	var cknum = /^[0-9]\d*$/;
	if(!cknum.test($("#points").val())){
		alert("下载积分必须为整数！");
		return false;
	}

	return true;
}

function check_class(){
	if($.trim($("#name").val()) == ""){
		alert("班级名称不能为空！");
		return false;
	}

	if($.trim($("#head-teacher").val()) == ""){
		alert("请选择班主任！");
		return false;
	}

	return true;
}

function createQues(test_id){
	window.location.href = "./member.php?c=newques&testid=" + test_id;
}

/*
 * 级联获取城市
 *
 * @access   public
 * @val      string  选择的省枚举值
 * @input    string  返回的select
 * @return   string  返回的option
 */

function SelProv(val,input)
{
	$("#"+input+"_country").html("<option>--</option>");

	$.ajax({
		url : "?a=getarea&datagroup=area&level=1&areaval="+val,
		type:'get',
		dataType:'html',
		success:function(data){
			$("#"+input+"_city").html(data);
		}
	});
}


/*
 * 级联选择区县
 *
 * @access   public
 * @val      string  选择的市枚举值
 * @input    string  返回的select
 * @return   string  返回的option
 */

function SelCity(val,input)
{
	$.ajax({
		url : "?a=getarea&datagroup=area&level=2&areaval="+val,
		type:'get',
		dataType:'html',
		success:function(data){
			$("#"+input+"_country").html(data);
		}
	});
}


//选择所有
function CheckAll(value)
{
	$("input[type='checkbox'][name^='checkid']").attr("checked",value);
}


//删除选中提示
function ConfDelAll(i)
{
	var tips = Array();
	tips[0] = "确定要删除选中的信息吗？";
	tips[1] = "系统会自动删除类别下所有子类别以及信息，确定删除吗？";
	tips[2] = "系统会自动删除类别下所有子类别，确定删除吗？";

	if($("input[type='checkbox'][name!='checkid'][name^='checkid']:checked").size() > 0)
	{
		if(confirm(tips[i])) return true;
		else return false;
	}
	else
	{
		alert('没有任何选中信息！');
		return false;
	}
}
//删除所有(不包含子分类)
function DelAllNone(url)
{
	$("#form").attr("action", url).submit();
}


//绑定账号
function cfm_binding()
{
	var ckuname = /^[0-9a-zA-Z_@\.-]+$/;
	if($("#username").val() == "")
	{
		alert("用户名不能为空！");
		$("#username").focus();
		return false;
	}
	else if($("#username").val().length < 6 || $("#username").val().length > 16)
	{
		alert("用户名长度为6~16位字符！");
		$("#username").focus();
		return false;
	}
	else if(!ckuname.test($("#username").val()))
	{
		alert("请使用[数字/字母/中划线/下划线/@.]！");
		$("#username").focus();
		return false;
	}

	var ckupwd = /^[0-9a-zA-Z_-]+$/;
	if($("#password").val() == "")
	{
		alert("密码不能为空！");
		$("#password").focus();
		return false;
	}
	else if($("#password").val().length < 6 || $("#password").val().length > 16)
	{
		alert("密码长度为6~16位字符！");
		$("#password").focus();
		return false;
	}
	else if(!ckupwd.test($("#password").val()))
	{
		alert("请使用[数字/字母/中划线/下划线]！");
		$("#password").focus();
		return false;
	}
}


function cfm_perfect()
{
	var ckuname = /^[0-9a-zA-Z_@\.-]+$/;
	if($("#username").val() == "")
	{
		alert("用户名不能为空！");
		$("#username").focus();
		return false;
	}
	else if($("#username").val().length < 6 || $("#username").val().length > 16)
	{
		alert("用户名长度为6~16位字符！");
		$("#username").focus();
		return false;
	}
	else if(!ckuname.test($("#username").val()))
	{
		alert("请使用[数字/字母/中划线/下划线/@.]！");
		$("#username").focus();
		return false;
	}
	


	var ckupwd = /^[0-9a-zA-Z_-]+$/;
	if($("#password").val() == "")
	{
		alert("密码不能为空！");
		$("#password").focus();
		return false;
	}
	else if($("#password").val().length < 6 || $("#password").val().length > 16)
	{
		alert("密码长度为6~16位字符！");
		$("#password").focus();
		return false;
	}
	else if(!ckupwd.test($("#password").val()))
	{
		alert("请使用[数字/字母/中划线/下划线]！");
		$("#password").focus();
		return false;
	}


	if($("#repassword").val() == "")
	{
		alert("确认密码不能为空！");
		$("#repassword").focus();
		return false;
	}
	else if($("#password").val() != $("#repassword").val())
	{
		alert("两次输入的密码不相同！");
		$("#repassword").focus();
		return false;
	}

	

	var ckmail = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
	if($("#email").val() == "")
	{
		alert("E-mail不能为空！");
		$("#email").focus();
		return false;
	}
	else if(!ckmail.test($("#email").val()))
	{
		alert("E-mail格式不正确！");
		$("#email").focus();
		return false;
	}
}

function check_course(){
	if($('#grade').val()==''){
		alert("请选择班级！");
		return false;
	}
	
	if($('#classid').val()==''){
		alert("请选择科目！");
		return false;
	}
	
	if($.trim($("#title").val()) == ""){
		alert("课程名称不能为空！");
		return false;
	}
	
	if($.trim($("#picurl").val()) == ""){
		alert("图片不能为空！");
		return false;
	}

	return true;
}

function check_lesson(){
	
	if($.trim($("#description").val()) == ""){
		alert("章节描述不能为空！");
		return false;
	}
	
	if($.trim($("#picurl").val()) == ""){
		alert("视频地址不能为空！");
		return false;
	}

	return true;
}
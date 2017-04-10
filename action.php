<?php
require_once(dirname(__FILE__).'/include/config.inc.php');
//加入课程学习
if(isset($act) && $act=='joincourse'){
	if(isset($id) && intval($id)!=0){
		//判断该课程是否存在
		$c = $dosql->GetOne("SELECT `id` FROM `#@__course` WHERE `id`='$id'");
		if(empty($c)){
			echo json_encode(array('info'=>'参数有误！','state'=>'false'));
			exit();
		}
		if(!isset($_COOKIE['username'])){
			echo json_encode(array('info'=>'您还未登录，请先登录！','state'=>'false'));
			exit();
		}
		$c_uname = AuthCode($_COOKIE['username']);
		$r = $dosql->GetOne("SELECT `id` FROM `#@__member` WHERE `username`='$c_uname'");
		//判断是否加入过
		$join = $dosql->GetOne("SELECT * FROM `#@__course_user` WHERE `course_id`='$id' AND uid=".$r['id']);
		if(isset($join['is_delete']) && $join['is_delete']=='false'){
			echo json_encode(array('info'=>'您已经加入过了，请勿重复操作！','state'=>'false'));
			exit();
		}elseif(isset($join['is_delete']) && $join['is_delete']=='true'){
			$sql = "UPDATE `#@__course_user` SET is_delete='false' WHERE id=".$join['id'];
		}else{
			$uid = $r['id'];
			$posttime = GetMkTime(time());
			$is_delete = 'false';
			$sql = "INSERT INTO `#@__course_user` (uid, course_id, posttime, is_delete) VALUES ('$uid', '$id', '$posttime', '$is_delete')";
		}
		if($dosql->ExecNoneQuery($sql))
		{
			echo json_encode(array('info'=>'加入学习成功！','state'=>'true'));
			exit();
		}else{
			echo json_encode(array('info'=>'加入学习失败！','state'=>'false'));
			exit();
		}
	}
}
//退出课程学习
if(isset($act) && $act=='quitcourse'){
	if(isset($id) && intval($id)!=0){
		//判断该课程是否存在
		$c = $dosql->GetOne("SELECT `id` FROM `#@__course` WHERE `id`='$id'");
		if(empty($c)){
			echo json_encode(array('info'=>'参数有误！','state'=>'false'));
			exit();
		}
		if(!isset($_COOKIE['username'])){
			echo json_encode(array('info'=>'您还未登录，请先登录！','state'=>'false'));
			exit();
		}
		$c_uname = AuthCode($_COOKIE['username']);
		$r = $dosql->GetOne("SELECT `id` FROM `#@__member` WHERE `username`='$c_uname'");
		//判断是否加入过
		$join = $dosql->GetOne("SELECT * FROM `#@__course_user` WHERE `course_id`='$id' AND uid=".$r['id']);
		if(isset($join['is_delete']) && $join['is_delete']=='false'){
			$sql = "UPDATE `#@__course_user` SET is_delete='true' WHERE id=".$join['id'];
		}elseif(empty($join['is_delete']) or $join['is_delete']=='true'){
			echo json_encode(array('info'=>'您没有加入此课程！','state'=>'false'));
			exit();
		}
		if($dosql->ExecNoneQuery($sql))
		{
			echo json_encode(array('info'=>'退出学习成功！','state'=>'true'));
			exit();
		}else{
			echo json_encode(array('info'=>'退出学习失败！','state'=>'false'));
			exit();
		}
	}
}
//收藏课程
if(isset($act) && $act=='collectcourse'){
	if(isset($id) && intval($id)!=0){
		//判断该课程是否存在
		$c = $dosql->GetOne("SELECT `id` FROM `#@__course` WHERE `id`='$id'");
		if(empty($c)){
			echo json_encode(array('info'=>'参数有误！','state'=>'false'));
			exit();
		}
		if(!isset($_COOKIE['username'])){
			echo json_encode(array('info'=>'您还未登录，请先登录！','state'=>'false'));
			exit();
		}
		$c_uname = AuthCode($_COOKIE['username']);
		$r = $dosql->GetOne("SELECT `id` FROM `#@__member` WHERE `username`='$c_uname'");
		//判断是否收藏过
		$collect = $dosql->GetOne("SELECT * FROM `#@__course_collect` WHERE `course_id`='$id' AND uid=".$r['id']);
		if(!empty($collect['id'])){
			$sql = "DELETE FROM `#@__course_collect` WHERE id=".$collect['id'];
			$dosql->ExecNoneQuery($sql);
			echo json_encode(array('info'=>'取消收藏成功！','state'=>'true'));
			exit();
		}else{
			$uid = $r['id'];
			$posttime = GetMkTime(time());
			$sql = "INSERT INTO `#@__course_collect` (uid, course_id, posttime) VALUES ('$uid', '$id', '$posttime')";
			$dosql->ExecNoneQuery($sql);
			echo json_encode(array('info'=>'收藏成功！','state'=>'true'));
			exit();
		}
	}
}
//课程评论
if(isset($act) && $act=='comment'){
	$id = empty($id) ? 0 : intval($id);
	$content = empty($content) ? '' : strip_tags($content);
	
	if(!empty($id) && !empty($content)){
		if(!isset($_COOKIE['username'])){
			echo json_encode(array('info'=>'您还未登录，请先登录！','state'=>'false'));
			exit();
		}
		$c_uname = AuthCode($_COOKIE['username']);
		$r = $dosql->GetOne("SELECT `id` FROM `#@__member` WHERE `username`='$c_uname'");
		$uid = $r['id'];
		$posttime = GetMkTime(time());
		$sql = "INSERT INTO `#@__course_comment` (uid, course_id, content, checkinfo, posttime) VALUES ('$uid', '$id', '$content', 'false', '$posttime')";
		$dosql->ExecNoneQuery($sql);
		echo json_encode(array('info'=>'评论成功，请等待教师审核！','state'=>'true'));
		exit();
	}else{
		echo json_encode(array('info'=>'参数有误！','state'=>'false'));
		exit();
	}	
}
//获取二级课程分类
if(isset($act) && $act=='selgrade'){
	$id = empty($id) ? (-1) : $id;
	$dosql->Execute("SELECT * FROM `#@__course_type` WHERE checkinfo='true' AND `parentid`='$id'");
	$str = '<option value="">请选择课程</option>';
	while($row = $dosql->GetArray()){
		$str .= '<option value="'.$row['id'].'">'.$row['classname'].'</option>';
	}
	echo $str;
	exit();
}
//获取短信验证码
if(isset($act) && $act=='sms'){
	$mobile = empty($mobile)     ? '' : $mobile;
	$affect = empty($affect)     ? '' : $affect;
	if(empty($mobile) || empty($affect)){
		echo json_encode(array('info'=>'非法操作！','state'=>'false'));
		exit();
	}
	if(!preg_match("/^13[0-9]{9}$|14[0-9]{9}|15[0-9]{9}$|17[0-9]{9}$|18[0-9]{9}$/", $mobile)){
		echo json_encode(array('info'=>'手机号格式不正确！','state'=>'false'));
		exit();
	}
	
	//验证手机号是否已注册
	$r = $dosql->GetOne("SELECT `id` FROM `#@__member` WHERE `mobile`='$mobile'");
	if(isset($r['id']) && $affect=='reg')
	{
		echo json_encode(array('info'=>'您填写的手机号已被注册，请更换！','state'=>'false'));
		exit();
	}elseif(!isset($r['id']) && $affect=='pwd'){
		echo json_encode(array('info'=>'您填写的手机号未在本站注册，请更换！','state'=>'false'));
		exit();
	}
	//检测之前的验证码
	$r = $dosql->GetOne("SELECT `id` FROM `#@__sms_log` WHERE `mobile`='$mobile' AND `status`=0");
	if(isset($r['id']))
	{
		//设置为过期
		$dosql->ExecNoneQuery("UPDATE `#@__sms_log` SET `status`=2 WHERE id=".$r['id']);
	}
	//生成6位验证码
	$smscode = rand(1,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
	$posttime = time();
	if($affect=='reg'){
		$content = str_replace('{sms}',$smscode,$cfg_sms_reg);
	}elseif($affect=='pwd'){
		$content = str_replace('{sms}',$smscode,$cfg_sms_pwd);
	}else{
		$content = '';
	}

	$content = iconv("UTF-8","gbk//TRANSLIT",$content);
	//获取验证码
	$str = "http://gateway.iems.net.cn/GsmsHttp?username=$cfg_sms_uid:$cfg_sms_uname&password=$cfg_sms_upwd&from=000&to=$mobile&content=$content";
	$result = file_get_contents($str);
	$result = iconv("GBK","UTF-8",$result);
	//状态提醒
	$error_msg=array('ERROR:eUser'=>'发送方用户名称有误!','ERROR:eIllegalPhone'=>'手机号错误！','ERROR:ePassword'=>'密码错误！','ERROR:eStop'=>'用户已停用！','ERROR:eDenyDate'=>'账户过期！','ERROR:eBalance'=>'发送方余额不足！','ERROR:不明错误！'=>'不明错误！','ERROR:eFrequent'=>'请求频繁！','ERROR:eContentLen'=>'短信内容超长！','ERROR:nContent'=>'短信内容不能为空！','ERROR:未知错误'=>'未知错误！');
	if($result=='ERROR:eUser'){
		echo json_encode(array('info'=>'发送方用户名称有误','state'=>'false'));
		exit();
	}elseif($result=='ERROR:eIllegalPhone'){
		echo json_encode(array('info'=>'手机号错误','state'=>'false'));
		exit();
	}elseif($result=='ERROR:ePassword'){
		echo json_encode(array('info'=>'密码错误','state'=>'false'));
		exit();
	}elseif($result=='ERROR:eStop'){
		echo json_encode(array('info'=>'用户已停用','state'=>'false'));
		exit();
	}elseif($result=='ERROR:eDenyDate'){
		echo json_encode(array('info'=>'账户过期','state'=>'false'));
		exit();
	}elseif($result=='ERROR:eBalance'){
		echo json_encode(array('info'=>'发送方余额不足','state'=>'false'));
		exit();
	}elseif($result=='ERROR:不明错误！'){
		echo json_encode(array('info'=>'不明错误','state'=>'false'));
		exit();
	}elseif($result=='ERROR:eFrequent'){
		echo json_encode(array('info'=>'请求频繁','state'=>'false'));
		exit();
	}elseif($result=='ERROR:eContentLen'){
		echo json_encode(array('info'=>'短信内容超长','state'=>'false'));
		exit();
	}elseif($result=='ERROR:未知错误'){
		echo json_encode(array('info'=>'未知错误','state'=>'false'));
		exit();
	}
	$sql = "INSERT INTO `#@__sms_log` (return_msg, mobile, smscode, posttime, status, affect) VALUES ('$result', '$mobile', '$smscode', '$posttime', '0', '$affect')";
	$dosql->ExecNoneQuery($sql);
	echo json_encode(array('info'=>'发送成功！','state'=>'true'));
	exit();	
}
echo json_encode(array('info'=>'非法操作！','state'=>'false'));
?>
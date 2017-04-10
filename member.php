<?php	require_once(dirname(__FILE__).'/include/config.inc.php');

/*
**************************
(C)2010-2014 phpMyWind.com
update: 2014-6-17 22:44:56
person: Feng
**************************
*/


//定义入口常量
define('IN_MEMBER', TRUE);


//初始化参数
$c  = isset($c)  ? $c : 'login';
$a  = isset($a)  ? $a : '';
$d  = isset($d)  ? $d : '';
$id = isset($id) ? intval($id) : 0;


//检测是否启用会员
//允许在不开启会员功能的情况下进行游客评论
if($cfg_member == 'N' && $a != 'savecomment')
{
	ShowMsg('抱歉，本站没有启用会员功能！','-1');
	exit();
}


//一键登录文件
if($cfg_oauth == 'Y')
{
	require_once(PHPMYWIND_DATA.'/api/oauth/system/core.php');
}


//初始登录信息
if(!empty($_COOKIE['username']) &&
   !empty($_COOKIE['lastlogintime']) &&
   !empty($_COOKIE['lastloginip']))
{
	$c_uname     = AuthCode($_COOKIE['username']);
	$c_logintime = AuthCode($_COOKIE['lastlogintime']);
	$c_loginip   = AuthCode($_COOKIE['lastloginip']);
}
else
{
	$c_uname     = '';
	$c_logintime = '';
	$c_loginip   = '';
}


//验证是否登录和用户合法
if($a=='saveedit'    or $a=='getarea'    or $a=='savefavorite' or
   $a=='delfavorite' or $a=='delcomment' or $a=='delmsg' or
   $a=='delorder'    or $a=='avatar'     or $a=='getgoods' or
   $a=='applyreturn' or $a=='perfect'    or $a=='binding' or
   $a=='removeoqq'   or $a=='removeoweibo')
{
	if(!empty($c_uname))
	{
		//guest为一键登录未绑定账号时的临时用户
		if($c_uname != 'guest')
		{
			$r = $dosql->GetOne("SELECT `id`,`expval` FROM `#@__member` WHERE `username`='$c_uname'");
			if(!is_array($r))
			{
				setcookie('username',      '', time()-3600);
				setcookie('lastlogintime', '', time()-3600);
				setcookie('lastloginip',   '', time()-3600);
				ShowMsg('该用户已不存在！','?c=login');
				exit();
			}
			else if($r['expval'] <= 0)
			{
				ShowMsg('抱歉，您的账号被禁止登录！','?c=login');
				exit();
			}
		}
	}
	else
	{
		header('location:?c=login');
		exit();
	}
}


//登录账户
if($a == 'login')
{

	//一键登录
	if($cfg_oauth == 'Y' && isset($method) && $method == 'callback')
	{

		//初始化参数
		$logintime = time();
		$loginip   = GetIP();


		//检测账号登录状态
		if(check_app_login('qq') or check_app_login('weibo'))
		{
			//检查一键登录账号类型
			if(check_app_login('qq'))
				$sql = "SELECT * FROM `#@__member` WHERE `qqid`='".$_SESSION['app']['qq']['uid']."'";
			else if(check_app_login('weibo'))
				$sql = "SELECT * FROM `#@__member` WHERE `weiboid`='".$_SESSION['app']['weibo']['idstr']."'";
			
			$row = $dosql->GetOne($sql);

			//合作账号没有绑定过
			if(empty($row['id']))
			{
				//设置COOKIE时间
				$cookie_time = time() + ini_get('session.gc_maxlifetime');

				//发放临时账号登录
				setcookie('username',      AuthCode('guest'    ,'ENCODE'), $cookie_time);
				setcookie('lastlogintime', AuthCode($logintime ,'ENCODE'), $cookie_time);
				setcookie('lastloginip',   AuthCode($loginip   ,'ENCODE'), $cookie_time);
				
				header('location:?c=default');
				exit();
			}
			
			//已经绑定账号
			else
			{
				//验证成功，查看是否被禁止登录
				if($row['expval'] <= 0)
				{
					ShowMsg('抱歉，您的账号被禁止登录！','?c=login');
					exit();
				}

				//验证成功，开始登录
				else
				{

					//删除禁止登录
					if(is_array($r))
					{
						$dosql->ExecNoneQuery("DELETE FROM `#@__failedlogin` WHERE `username`='".$row['username']."'");
					}

					$cookie_time = time()+3600;
		
					setcookie('username',      AuthCode($row['username'] ,'ENCODE'), $cookie_time);
					setcookie('lastlogintime', AuthCode($row['logintime'],'ENCODE'), $cookie_time);
					setcookie('lastloginip',   AuthCode($row['loginip']  ,'ENCODE'), $cookie_time);
		
		
					//每天登录增加10点经验
					if(MyDate('d',time()) != MyDate('d',$row['logintime']))
					{
						$dosql->ExecNoneQuery("UPDATE `#@__member` SET `expval`='".($row['expval'] + 10)."' WHERE `username`='".$row['username']."'");
					}
					
					$dosql->ExecNoneQuery("UPDATE `#@__member` SET `loginip`='$loginip',`logintime`='$logintime' WHERE `id`=".$row['id']);
					
		
					header('location:?c=default');
					exit();
				}
			}
		}
		
		else
		{
			header('location:?c=login');
			exit();
		}
	}
	
	
	//注册用户登录
	else
	{

		//初始化参数
		$username = empty($username) ? '' : $username;
		$password = empty($password) ? '' : md5(md5($password));
		//$validate = empty($validate) ? '' : strtolower($validate);
	

		//验证输入数据
		if($username == '' or
		   $password == '')
		{
			header('location:?c=login');
			exit();
		}
		
		
		//删除所有已过时记录
		$dosql->ExecNoneQuery("DELETE FROM `#@__failedlogin` WHERE (UNIX_TIMESTAMP(NOW())-time)/60>15");
	
	
		//判断是否被暂时禁止登录
		$r = $dosql->GetOne("SELECT * FROM `#@__failedlogin` WHERE username='$username'");
		if(is_array($r))
		{
			$min = round((time()-$r['time']))/60;
			if($r['num']==0 and $min<=15)
			{
				ShowMsg('您的密码已连续错误6次，请15分钟后再进行登录！','?c=login');
				exit();
			}
		}
	
	
		//检测数据正确性
		/*if($validate != strtolower(GetCkVdValue()))
		{
			ResetVdValue();
			ShowMsg('验证码不正确！','?c=login');
			exit();
		}
		else
		{*/
	
			$row = $dosql->GetOne("SELECT `id`,`password`,`logintime`,`loginip`,`expval` FROM `#@__member` WHERE `username`='$username' OR email='$username'");
	
	
			//密码错误
			if(!is_array($row) or $password!=$row['password'])
			{
				$logintime = time();
				$loginip   = GetIP();
	
				$r = $dosql->GetOne("SELECT * FROM `#@__failedlogin` WHERE `username`='$username'");
				if(is_array($r))
				{
					$num = $r['num']-1;
	
					if($num == 0)
					{
						$dosql->ExecNoneQuery("UPDATE `#@__failedlogin` SET `time`=$logintime, `num`=$num WHERE `username`='$username'");
						ShowMsg('您的密码已连续错误6次，请15分钟后再进行登录！','?c=login');
						exit();
					}
					else if($r['num']<=5 and $r['num']>0)
					{
						$dosql->ExecNoneQuery("UPDATE `#@__failedlogin` SET `time`=$logintime, `num`=$num WHERE `username`='$username'");
						ShowMsg('账号或密码不正确！您还有'.$num.'次尝试的机会！','?c=login');
						exit();
					}
				}
				else
				{
					$dosql->ExecNoneQuery("INSERT INTO `#@__failedlogin` (username, ip, time, num, isadmin) VALUES ('$username', '$loginip', '$logintime', 5, 0)");
					ShowMsg('账号或密码不正确！您还有5次尝试的机会！','?c=login');
					exit();
				}
			}
	
	
			//密码正确，查看是否被禁止登录
			else if($row['expval'] <= 0)
			{
				ShowMsg('抱歉，您的账号被禁止登录！','?c=login');
				exit();
			}
	
	
			//用户名密码正确
			else
			{
	
				$logintime = time();
				$loginip = GetIP();
				
				
				//删除禁止登录
				if(is_array($r))
				{
					$dosql->ExecNoneQuery("DELETE FROM `#@__failedlogin` WHERE `username`='$username'");
				}
	
	
				//是否自动登录
				if(isset($autologin))
					$cookie_time = time()+14*24*60*60;
				else
					$cookie_time = time()+3600;
	
				setcookie('username',      AuthCode($username        ,'ENCODE'), $cookie_time);
				setcookie('lastlogintime', AuthCode($row['logintime'],'ENCODE'), $cookie_time);
				setcookie('lastloginip',   AuthCode($row['loginip']  ,'ENCODE'), $cookie_time);
	
	
				//每天登录增加10点经验
				if(MyDate('d',time()) != MyDate('d',$row['logintime']))
				{
					$dosql->ExecNoneQuery("UPDATE `#@__member` SET `expval`='".($row['expval'] + 10)."' WHERE `username`='$username'");
				}
				
				$dosql->ExecNoneQuery("UPDATE `#@__member` SET `loginip`='$loginip',`logintime`='$logintime' WHERE `id`=".$row['id']);
				
	
				header('location:?c=default');
				exit();
			}
		//}
	}
}


//注册账户
else if($a == 'reg')
{
	
	//初始化参数
	$username   = empty($username)   ? '' : $username;
	$password   = empty($password)   ? '' : md5(md5($password));
	$repassword = empty($repassword) ? '' : md5(md5($repassword));
	$email      = empty($email)      ? '' : $email;
	$mobile     = empty($mobile)     ? '' : $mobile;
	$mobilechk  = empty($mobilechk)  ? '' : $mobilechk;
	$validate   = empty($validate)   ? '' : strtolower($validate);


	//验证输入数据
	if($username   == '' or
	   $password   == '' or
	   $repassword == '' or
	   $email      == '' or
	   $validate   == '' or
	   $mobilechk  == '' or
	   $mobile     == '')
	{
		header('location:?c=reg');
		exit();
	}


	//验证数据准确性
	if($validate != strtolower(GetCkVdValue()))
	{
		ResetVdValue();
		ShowMsg('验证码不正确！','?c=reg');
		exit();
	}
	
	//验证短信验证码的准确性
	$sms = $dosql->GetOne("SELECT * FROM `#@__sms_log` WHERE `mobile`='$mobile' AND affect='reg' AND `status`=0 ORDER BY posttime DESC");
	if(!isset($sms['smscode']))
	{
		ShowMsg('短信验证码不正确！','-1');
		exit();
	}elseif((time()-$sms['posttime'])/60 > 30){
		$dosql->ExecNoneQuery("UPDATE `#@__sms_log` SET `status`=2 WHERE id=".$sms['id']);
		ShowMsg('短信验证码已过期！','-1');
		exit();
	}elseif($sms['smscode']!=$mobilechk){
		ShowMsg('短信验证码不正确！','-1');
		exit();
	}

	if($password != $repassword)
	{
		header('location:?c=reg');
		exit();
	}

    $uname_len = strlen($username);
	$upwd_len  = strlen($_POST['password']);
	if($uname_len<6 or $uname_len>16 or $upwd_len<6 or $upwd_len>16)
	{
		header('location:?c=reg');
		exit();
	}

	if(preg_match("/[^0-9a-zA-Z_@!\.-]/",$username) or
	   preg_match("/[^0-9a-zA-Z_-]/",$password) or
	   !preg_match("/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/", $email) or !preg_match("/^13[0-9]{9}$|14[0-9]{9}|15[0-9]{9}$|17[0-9]{9}$|18[0-9]{9}$/", $mobile))
	{
		header('location:?c=reg');
		exit();
	}

	$r = $dosql->GetOne("SELECT `id` FROM `#@__member` WHERE `username`='$username'");
	if(isset($r['id']))
	{
		ShowMsg('用户名已存在！','-1');
		exit();
	}

	$r = $dosql->GetOne("SELECT `id` FROM `#@__member` WHERE `email`='$email'");
	if(isset($r['id']))
	{
		ShowMsg('您填写的邮箱已被注册！','-1');
		exit();
	}
	
	$r = $dosql->GetOne("SELECT `id` FROM `#@__member` WHERE `mobile`='$mobile'");
	if(isset($r['id']))
	{
		ShowMsg('您填写的手机号已被注册！','-1');
		exit();
	}


	//添加用户数据
	$regtime  = time();
	$regip    = GetIP();

	$sql = "INSERT INTO `#@__member` (username, password, email, expval, regtime, regip, logintime, loginip, mobile) VALUES ('$username', '$password', '$email', '10', '$regtime', '$regip', '$regtime', '$regip', '$mobile')";	
	if($dosql->ExecNoneQuery($sql))
	{
		$dosql->ExecNoneQuery("UPDATE `#@__sms_log` SET `status`=1 WHERE id=".$sms['id']);
		header('location:?c=login&d='.md5('reg'));
		exit();
	}
}


//退出账户
else if($a == 'logout')
{
	setcookie('username',      '', time()-3600);
	setcookie('lastlogintime', '', time()-3600);
	setcookie('lastloginip',   '', time()-3600);

	header('location:?c=login');
	exit();
}


//找回密码
else if($a == 'findpwd2')
{
	if(!isset($_POST['username']))
	{
		header('location:?c=findpwd');
		exit();
	}


	//检测验证码
	$validate = empty($validate) ? '' : strtolower($validate);
	if($validate == '' || $validate != strtolower(GetCkVdValue()))
	{
		ResetVdValue();
		ShowMsg('验证码不正确！','?c=findpwd');
		exit();
	}
	else
	{
		$r = $dosql->GetOne("SELECT `id` FROM `#@__member` WHERE `username`='$username'");
		if(!isset($r['id']))
		{
			ShowMsg('请输入正确的账号信息！','?c=findpwd');
			exit();
		}
	}
}


//找回密码
else if($a == 'quesfind')
{
	if(!isset($_POST['uname']))
	{
		header('location:?c=findpwd');
		exit();
	}


	//验证输入数据
	if($question == '-1' or $answer == '')
	{
		header('location:?c=findpwd');
		exit();
	}


	$r = $dosql->GetOne("SELECT `question`,`answer` FROM `#@__member` WHERE `username`='$uname'");
	if($r['question']==0 or !isset($r['answer']))
	{
		ShowMsg('此账号未填写验证问题，请选择其他方式找回！','?c=findpwd');
		exit();
	}
	else if($question != $r['question'] or $answer != $r['answer'])
	{
		ShowMsg('您填写的验证问题或答案不符！','?c=findpwd');
		exit();
	}
	else
	{
		//验证通过，采用SESSION存储用户名
		@session_start();
		$_SESSION['fid_'.$uname] = $uname;
	}
}


//设置新密码
else if($a == 'setnewpwd')
{
	/*@session_start();

	if(isset($_SESSION['fid_'.$_POST['uname']]))
	{
		
		if($_SESSION['fid_'.$_POST['uname']] != $_POST['uname'])
		{
			ShowMsg('非法操作，找回用户名与上一步输入不符合！','?c=findpwd');
			unset($_SESSION['fid_'.$_POST['uname']]);
			exit();
		}
	}
	else
	{
		header('location:?c=findpwd');
		exit();
	}*/


	//初始化参数
	$uname      = empty($uname)      ? '' : $uname;
	$password   = empty($password)   ? '' : md5(md5($password));
	$repassword = empty($repassword) ? '' : md5(md5($repassword));


	//验证输入数据
	if($uname == '' or
	   $password == '' or
	   $repassword == '' or
	   $password != $repassword or
	   preg_match("/[^0-9a-zA-Z_-]/",$password))
	{
		header('location:?c=findpwd');
		exit();
	}
	
	$r = $dosql->GetOne("SELECT * FROM `#@__sms_log` WHERE `mobile`='$uname' AND `status`=0 AND affect='pwd' ORDER BY posttime DESC");
	if(!isset($r['id']))
	{
		ShowMsg('非法操作，手机号与上一步输入不符合！','?c=findpwd');
		exit();
	}

	if($dosql->ExecNoneQuery("UPDATE `#@__member` SET password='$password' WHERE username='$uname'"))
	{
		$dosql->ExecNoneQuery("UPDATE `#@__sms_log` SET `status`=1 WHERE id=".$r['id']);
		header("location:?c=login&d=".md5('newpwd'));
		unset($_SESSION['fid_'.$_POST['uname']]);
		exit();
	}
}


//找回密码
else if($a == 'mailfind')
{
	if(!isset($_POST['uname']))
	{
		header('location:?c=findpwd');
		exit();
	}


	//验证输入数据
	if($email == '')
	{
		header('location:?c=findpwd');
		exit();
	}


	$r = $dosql->GetOne("SELECT `email` FROM `#@__member` WHERE `username`='$uname'");
	if($r['email'] == $email)
	{
		
	}
	else
	{
		ShowMsg('您填写的邮箱不符！','?c=findpwd');
		exit();
	}
}

//更新资料
else if($a == 'saveedit')
{
	
	//检测数据完整性
	if($password!=$repassword or $email=='')
	{
		header('location:?c=edit');
		exit();
	}


	//HTML转义变量
	$answer    = htmlspecialchars($answer);
	$cnname    = htmlspecialchars($cnname);
	$enname    = htmlspecialchars($enname);
	$cardnum   = htmlspecialchars($cardnum);
	$intro     = htmlspecialchars($intro);
	$email     = htmlspecialchars($email);
	$qqnum     = htmlspecialchars($qqnum);
	$mobile    = htmlspecialchars($mobile);
	$telephone = htmlspecialchars($telephone);
	$address   = htmlspecialchars($address);
	$zipcode   = htmlspecialchars($zipcode);


	//检测旧密码是否正确
	if($password != '')
	{
		$oldpassword = md5(md5($oldpassword));
		$r = $dosql->GetOne("SELECT `password` FROM `#@__member` WHERE `username`='$c_uname'");
		if($r['password'] != $oldpassword)
		{
			ShowMsg('抱歉，旧密码错误！','-1');
			exit();
		}
	}

	$sql = "UPDATE `#@__member` SET ";
	if($password != '')
	{
		$password = md5(md5($password));
		$sql .= "password='$password', ";
	}
	@$sql .= "question='$question', answer='$answer', cnname='$cnname', enname='$enname', sex='$sex', birthtype='$birthtype', birth_year='$birth_year', birth_month='$birth_month', birth_day='$birth_day', astro='$astro', bloodtype='$bloodtype', trade='$trade', live_prov='$live_prov', live_city='$live_city', live_country='$live_country', home_prov='$home_prov', home_city='$home_city', home_country='$home_country', cardtype='$cardtype', cardnum='$cardnum', intro='$intro', email='$email', qqnum='$qqnum', mobile='$mobile', telephone='$telephone', address_prov='$address_prov', address_city='$address_city', address_country='$address_country', address='$address', zipcode='$zipcode' WHERE id='$id' AND `username`='$c_uname'";
	if($dosql->ExecNoneQuery($sql))
	{
		ShowMsg('资料更新成功！','?c=edit');
		exit();
	}
}

//提交考试
else if($a == 'submittest')
{

	$testDtl = $dosql->GetOne("SELECT pass_score FROM `#@__test` WHERE id=$testId");

	$testResult = "";
	$evalBy = "null";
	$evalDate = "null";
	$saveMsg = "试卷提交已提交，请等待老师评卷！";
	$evalQuesCount = $dosql->GetOne("SELECT COUNT(*) AS num FROM `#@__question` WHERE test_id=$testId AND type IN (5,6)");
	if($evalQuesCount['num'] = 0){
		$evalBy = "sys";
		$evalDate = "NOW()";
		$saveMsg = "试卷提交已提交，请查看您的成绩！";
		$calcUserTestSocre = $dosql->GetOne("SELECT SUM(response_score) AS total_score FROM `#@__test_dtl` WHERE test_id=$testId AND user_id=$userId");
		if($calcUserTestSocre['total_score'] >= $testDtl['pass_score']){
			$testResult = "P";
		}else{
			$testResult = "F";
		}
	}
	$updateTestScoreSQL = "
		UPDATE `#@__test_summary` SET
			submit_date = NOW(),
			test_result = '$testResult',
			eval_by = $evalBy,
			eval_date = $evalDate
		WHERE test_id=$testId AND user_id=$userId
	";
	$dosql->ExecNoneQuery($updateTestScoreSQL);
	ShowMsg($saveMsg,'?c=testlist');
	exit();
}

//创建题目
else if($a == 'newques')
{

	$title = trim($title);
	if($type != "2"){
		$answer = trim($answer);
	}
	else{
		$answer = implode(",",$answer);
	}
	$option1 = trim($option_1);
	$option2 = trim($option_2);
	$option3 = trim($option_3);
	$option4 = trim($option_4);
	$optionDesc1 = trim($optionDesc_1);
	$optionDesc2 = trim($optionDesc_2);
	$optionDesc3 = trim($optionDesc_3);
	$optionDesc4 = trim($optionDesc_4);

	$insertQuesSQL = "INSERT INTO `#@__question` (test_id,title,type,score,answer,create_date,modify_date,create_by,description,option_1,option_2,option_3,option_4,option_desc_1,option_desc_2,option_desc_3,option_desc_4) VALUES ($testId,'$title','$type',$score,'$answer',null,null,'$useId','$description','$option1','$option2','$option3','$option4','$optionDesc1','$optionDesc2','$optionDesc3','$optionDesc4')";
	if($dosql->ExecNoneQuery($insertQuesSQL)){
		$calcTotalSocre = $dosql->GetOne("SELECT SUM(score) AS total_score FROM `#@__question` WHERE test_id=$testId");
		$updateTestScoreSQL = "UPDATE `#@__test` SET score=".$calcTotalSocre['total_score']." WHERE id=$testId";
		$dosql->ExecNoneQuery($updateTestScoreSQL);
		ShowMsg('题目添加成功！','?c=testdtl&testid='.$testId);
		exit();
	}
}

//往班级添加学生
else if($a == 'enrollstu')
{
	if(is_array($checkid))
	{
		foreach($checkid as $v)
		{
			//参数过滤
			$v = intval($v);
			$dosql->ExecNoneQuery("
				INSERT INTO `#@__class_stu` (
					class_id, stu_id , enroll_by, enroll_date
				) VALUES (
					$classId, $v, $userId, NOW()
				)
			");
		}
	}

	ShowMsg('添加学生成功！','?c=classstu&classid='.$classId);
	exit();
}

//班级删除学生
else if($a == 'stuout')
{

	if(is_array($checkid))
	{
		foreach($checkid as $v)
		{
			//参数过滤
			$v = intval($v);
			$dosql->ExecNoneQuery("UPDATE `#@__class_stu` SET status='out', out_by=$userId, out_date=NOW() WHERE `class_id`=$classId AND `stu_id`=$v AND status='enroll'");
		}
	}

	header('location:?c=classstu&classid='.$classId);
	exit();
}


//创建试卷
else if($a == 'newtest')
{
	//HTML转义变量
	$name    = trim($name);
	$passScore    = trim($passScore);
	$duration    = trim($duration);
	$status   = trim($status);
	$description     = trim($description);

	$sql = "INSERT INTO `#@__test` (name,description,pass_score,duration,create_by,create_date,modify_date,status) VALUES ('$name','$description','$passScore','$duration','$useId',null,null,'$status')";
	if($dosql->ExecNoneQuery($sql))
	{
		ShowMsg('试卷创建成功，请等待管理员进行审核！','?c=testdtl&testid='.$dosql->GetLastID());
		exit();
	}
}

//创建班级
else if($a == 'newclass')
{
	//HTML转义变量
	$name    = trim($name);
	$description     = trim($description);

	$sql = "INSERT INTO `#@__class` (name,head_teacher,grade,description,create_by) VALUES ('$name',$headTeacher,'$grade','$description',$useId)";
	if($dosql->ExecNoneQuery($sql))
	{
		ShowMsg('班级创建成功！','?c=classlist');
		exit();
	}
}

//获取级联
else if($a == 'getarea')
{

	//初始化参数
	$datagroup = isset($datagroup) ? $datagroup     : '';
	$level     = isset($level)     ? intval($level) : '';
	$v         = isset($areaval)   ? $areaval       : '0';

	if($datagroup == '' or $level == '' or $v == '')
	{
		header('location:?c=default');
		exit();
	}

	$str = '<option value="-1">--</option>';
	$sql = "SELECT * FROM `#@__cascadedata` WHERE `level`=$level And ";

	if($v == 0)
		$sql .= "datagroup='$datagroup'";
	else if($v % 500 == 0)
		$sql .= "`datagroup`='$datagroup' AND `datavalue`>'$v' AND `datavalue`<'".($v + 500)."'";
	else
		$sql .= "`datavalue` LIKE '$v.%%%' AND `datagroup`='$datagroup'";
	
	$sql .= " ORDER BY orderid ASC, datavalue ASC";

	$dosql->Execute($sql);
	while($row = $dosql->GetArray())
	{
		$str .= '<option value="'.$row['datavalue'].'">'.$row['dataname'].'</option>';
	}
	
	if($str == '') $str .= '<option value="-1">--</option>'; 
	echo $str;
	exit();
}


//保存评论
else if($a == 'savecomment')
{
	//是否开去文章评论功能
	if($cfg_comment == 'N') exit();

	//初始化参数
	$aid   = isset($aid)   ? intval($aid)   : '';
	$molds = isset($molds) ? intval($molds) : '';
	$body  = isset($body)  ? htmlspecialchars($body) : '';
	$link  = isset($_SERVER['HTTP_REFERER']) ? htmlspecialchars($_SERVER['HTTP_REFERER'],ENT_QUOTES) : '';

	if($aid == '' or $molds == '' or $body == '')
	{
		header('location:?c=default');
		exit();
	}

	$reply = '';
	
	if(empty($c_uname))
	{
		$uid   = '-1';
		$uname = '游客';
	}
	else
	{
		$r = $dosql->GetOne("SELECT `id`,`expval`,`integral` FROM `#@__member` WHERE `username`='$c_uname'");
		$uid   = $r['id'];
		$uname = $c_uname;
	}


	$time  = time();
	$ip    = GetIP();

	$dosql->ExecNoneQuery("INSERT INTO `#@__usercomment` (aid,molds,uid,uname,body,reply,link,time,ip,isshow) VALUES ('$aid','$molds','$uid','$uname','$body','$reply','$link','$time','$ip','1')");


	$r = $dosql->GetOne("SELECT `id` FROM `#@__usercomment` WHERE `aid`='$aid' AND `molds`='$molds' AND `uid`='$uid'");
	if(empty($r['id']) && !empty($c_uname) && $uid != '-1')
	{
		//评论一条增加1经验值2积分
		$dosql->ExecNoneQuery("UPDATE `#@__member` SET expval='".($r['expval'] + 1)."', integral='".($r['integral'] + 2)."' WHERE `username`='$c_uname'");
	}

	echo json_encode(array('1',$uname,$body,GetDateTime($time)));
	exit();
}


//删除评论
else if($a == 'delcomment')
{
	//是否开去文章评论功能
	if($cfg_comment == 'N') exit();

	if(is_array($checkid))
	{
		foreach($checkid as $v)
		{
			//参数过滤
			$v = intval($v);
			$dosql->ExecNoneQuery("DELETE FROM `#@__usercomment` WHERE `id`=$v AND `uname`='$c_uname'");
		}
	}

	header('location:?c=comment');
	exit();
}

//更新试卷
else if($a == 'editor_ques_new1')
{

	//HTML转义变量
	$title = htmlspecialchars($title);
	if($type != "2"){
		$answer = htmlspecialchars($answer);
	}
	else{
		$answer = implode(",",$answer);
	}
	$option1 = htmlspecialchars($option_1);
	$option2 = htmlspecialchars($option_2);
	$option3 = htmlspecialchars($option_3);
	$option4 = htmlspecialchars($option_4);
	$optionDesc1 = htmlspecialchars($optionDesc_1);
	$optionDesc2 = htmlspecialchars($optionDesc_2);
	$optionDesc3 = htmlspecialchars($optionDesc_3);
	$optionDesc4 = htmlspecialchars($optionDesc_4);
	$modifydate = date('Y-m-d H:i:s',time());
	$updateQuesSQL ="UPDATE `#@__question` SET title='$title',type='$type',score=$score,answer='$answer',create_date=null,modify_date=null,create_by='$useId',description='$description',option_1='$option1',option_2='$option2',option_3='$option3',option_4='$option4',option_desc_1='$optionDesc1',option_desc_2='$optionDesc2',option_desc_3='$optionDesc3',option_desc_4='$optionDesc4' WHERE id=$Id";
	if($dosql->ExecNoneQuery($updateQuesSQL)){
		$calcTotalSocre = $dosql->GetOne("SELECT SUM(score) AS total_score FROM `#@__question` WHERE test_id=$testId AND status = 'active'");
		$updateTestScoreSQL = "UPDATE `#@__test` SET score=".$calcTotalSocre['total_score']." WHERE id=$testId";
		$dosql->ExecNoneQuery($updateTestScoreSQL);
		ShowMsg('试题编辑成功！','?c=testdtl&testid='.$testId);
		exit();
	}
}

//提交批卷
else if($a == 'evaltest')
{
	$testDtl = $dosql->GetOne("SELECT pass_score FROM `#@__test` WHERE id=$testId");
	$calcUserTestSocre = $dosql->GetOne("SELECT SUM(response_score) AS total_score FROM `#@__test_dtl` WHERE test_id=$testId AND user_id=$userId");
	if($calcUserTestSocre['total_score'] >= $testDtl['pass_score']){
		$testResult = "P";
	}else{
		$testResult = "F";
	}
	$updateTestScoreSQL = "UPDATE `#@__test_summary` SET
		test_score=".$calcUserTestSocre['total_score'].",
		test_result='".$testResult."',
		eval_by=".$evalBy.",
		eval_date=NOW()
	WHERE user_id=$userId AND test_id=$testId";
	$dosql->ExecNoneQuery($updateTestScoreSQL);
	ShowMsg('提交成功！','?c=usertest&testid='.$testId);
	exit();
}

//删除题目
else if($a == 'delquestion')
{
	if(is_array($checkid))
	{
		foreach($checkid as $v)
		{
			//参数过滤
			$v = intval($v);
			$dosql->ExecNoneQuery("UPDATE  `#@__question` SET status= 'deleted' WHERE `id`=$v  ");
		}
	}
	$calcTotalSocre = $dosql->GetOne("SELECT SUM(score) AS total_score FROM `#@__question` WHERE test_id=$testId AND status = 'active'");
	$updateTestScoreSQL = "UPDATE `#@__test` SET score=".$calcTotalSocre['total_score']." WHERE id=$testId";
	$dosql->ExecNoneQuery($updateTestScoreSQL);
	header("location:?c=testdtl&testid=$testId");
	exit();
}

//删除试卷
else if($a == 'delpaper')
{
	if(is_array($checkid))
	{
		foreach($checkid as $v)
		{
			//参数过滤
			$v = intval($v);
			$dosql->ExecNoneQuery("UPDATE  `#@__test` SET status= 'deleted' WHERE `id`=$v  ");
		}
	}

	header("location:?c=testlist");
	exit();
}

//删除班级
else if($a == 'delclass')
{
	if(is_array($checkid))
	{
		foreach($checkid as $v)
		{
			//参数过滤
			$v = intval($v);
			$dosql->ExecNoneQuery("UPDATE  `#@__class` SET status= 'deleted' WHERE `id`=$v  ");
		}
	}

	header("location:?c=classlist");
	exit();
}

//上传文件
else if($a == 'uploadfile')
{
	if ($_FILES["file"]["size"] < 200000000)
	{
		if ($_FILES["file"]["error"] > 0)
		{
			echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
		}
		else
		{
			$learningFileDir = "/uploads/learning_file/";
			// echo "Upload: " . $_FILES["file"]["name"] . "<br />";
			// echo "Type: " . $_FILES["file"]["type"] . "<br />";
			// echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
			// echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";

			$filePath=$learningFileDir.$_FILES['file']['name'];
			$upload_path=$_SERVER['DOCUMENT_ROOT'].$learningFileDir;
			$dest_file=$upload_path.$_FILES['file']['name'];
			if (function_exists("iconv"))
			{
				$dest_file=iconv("UTF-8","GB2312",$dest_file);
			}

			move_uploaded_file($_FILES["file"]["tmp_name"],$dest_file);

			$chkFile = $dosql->GetOne("SELECT COUNT(*) i FROM `#@__learning_file` WHERE title='".$_FILES['file']['name']."'");
			if($chkFile['i'] > 0){
				ShowMsg('文件已经存在，请重新命名后上传！','?c=uploadlearningfile');
			}else{
				$dosql->ExecNoneQuery("
					INSERT INTO `#@__learning_file` (
						title,
						points,
						description,
						file_path,
						file_type,
						file_size,
						upload_by,
						upload_date
					) VALUES (
						'".$_FILES['file']['name']."',
						$points,
						'$description',
						'$filePath',
						'".$_FILES["file"]["type"]."',
						'".($_FILES["file"]["size"] / 1024)."',
						'$userId',
						NOW()
					)
				");
				ShowMsg('文件上传成功，请等待管理员审核！','?c=learningfilelist');
			}			
		}
	}
	else
	{
		echo "Invalid file";
	}
	//header("location:?c=testlist");
	exit();
}

//保存收藏
else if($a == 'savefavorite')
{

	$aid   = isset($aid)   ? intval($aid)   : '';
	$molds = isset($molds) ? intval($molds) : '';
	$link  = isset($_SERVER['HTTP_REFERER']) ? htmlspecialchars($_SERVER['HTTP_REFERER'],ENT_QUOTES) : '';

	if($aid == '' or $molds == '' or $link == '')
	{
		header('location:?c=default');
		exit();
	}

	$r = $dosql->GetOne("SELECT `id`,`expval`,`integral` FROM `#@__member` WHERE `username`='$c_uname'");
	$uid   = $r['id'];
	$uname = $c_uname;
	$time  = time();
	$ip    = GetIP();

	$r2 = $dosql->GetOne("SELECT `aid`,`molds` FROM `#@__userfavorite` WHERE `aid`=$aid and `molds`=$molds");
	if(!is_array($r2))
	{
		$dosql->ExecNoneQuery("INSERT INTO `#@__userfavorite` (aid,molds,uid,uname,link,time,ip,isshow) VALUES ('$aid','$molds','$uid','$uname','$link','$time','$ip','1')");

		//收藏一条增加1经验值2积分
		$dosql->ExecNoneQuery("UPDATE `#@__member` SET expval='".($r['expval'] + 1)."', integral='".($r['integral'] + 2)."' WHERE `username`='$c_uname'");
		echo '1';
		exit();
	}
	else
	{
		echo '2';
		exit();
	}
}


//删除收藏
else if($a == 'delfavorite')
{
	if(is_array($checkid))
	{
		foreach($checkid as $v)
		{
			//参数过滤
			$v = intval($v);
			$dosql->ExecNoneQuery("DELETE FROM `#@__userfavorite` WHERE `id`=$v AND `uname`='$c_uname'");
		}
	}

	header('location:?c=favorite');
	exit();
}


//删除留言
else if($a == 'delmsg')
{
	if(is_array($checkid))
	{
		foreach($checkid as $v)
		{
			//参数过滤
			$v = intval($v);
			$dosql->ExecNoneQuery("DELETE FROM `#@__message` WHERE `id`=$v AND `nickname`='$c_uname'");
		}
	}

	header('location:?c=msg');
	exit();
}


//删除订单
else if($a == 'delorder')
{
	if(is_array($checkid))
	{
		foreach($checkid as $v)
		{
			//参数过滤
			$v = intval($v);

			$r = $dosql->GetOne("SELECT `checkinfo` FROM `#@__goodsorder` WHERE `id`=$v");
			$checkinfo = explode(',', $r['checkinfo']);
			if(in_array('overorder',  $checkinfo))
				$dosql->ExecNoneQuery("DELETE FROM `#@__goodsorder` WHERE `id`=$v AND `username`='$c_uname'");
		}
	}

	header('location:?c=order');
	exit();
}


//确认收货
else if($a == 'getgoods')
{
	$r = $dosql->GetOne("SELECT `checkinfo` FROM `#@__goodsorder` WHERE `username`='$c_uname' AND `id`=$id");
	$checkinfo = explode(',',$r['checkinfo']);

	if(!in_array('getgoods', $checkinfo))
	{
		$checkinfo = $r['checkinfo'].',getgoods';
	}

	$dosql->ExecNoneQuery("UPDATE `#@__goodsorder` SET checkinfo='$checkinfo' WHERE `username`='$c_uname' AND `id`=$id");
	header('location:?c=ordershow&id='.$id);
	exit();
}


//申请退款
else if($a == 'applyreturn')
{
	$r = $dosql->GetOne("SELECT `checkinfo` FROM `#@__goodsorder` WHERE `username`='$c_uname' AND `id`=$id");
	$checkinfo = explode(',',$r['checkinfo']);

	if(!in_array('applyreturn', $checkinfo))
	{
		$checkinfo = $r['checkinfo'].',applyreturn';
	}

	$dosql->ExecNoneQuery("UPDATE `#@__goodsorder` SET checkinfo='$checkinfo' WHERE `username`='$c_uname' AND `id`=$id");
	header('location:?c=ordershow&id='.$id);
	exit();
}


//支付余额
else if($a == 'pay')
{
	//
	header('location:orderpay.php');
	exit();
}


//完善账号
else if($a == 'perfect')
{
	//初始化参数
	$username   = empty($username)   ? '' : $username;
	$password   = empty($password)   ? '' : md5(md5($password));
	$repassword = empty($repassword) ? '' : md5(md5($repassword));
	$email      = empty($email)      ? '' : $email;


	//验证输入数据
	if($username == '' or
	   $password == '' or
	   $repassword == '' or
	   $email == '')
	{
		header('location:?c=perfect');
		exit();
	}


	if($password != $repassword)
	{
		header('location:?c=perfect');
		exit();
	}


    $uname_len = strlen($username);
	$upwd_len  = strlen($_POST['password']);
	if($uname_len<6 or $uname_len>16 or $upwd_len<6 or $upwd_len>16)
	{
		header('location:?c=perfect');
		exit();
	}

	if(preg_match("/[^0-9a-zA-Z_@!\.-]/",$username) or
	   preg_match("/[^0-9a-zA-Z_-]/",$password) or
	   !preg_match("/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/", $email))
	{
		header('location:?c=perfect');
		exit();
	}

	$r = $dosql->GetOne("SELECT `id` FROM `#@__member` WHERE `username`='$username'");
	if(isset($r['id']))
	{
		ShowMsg('用户名已存在！','-1');
		exit();
	}

	$r = $dosql->GetOne("SELECT `id` FROM `#@__member` WHERE `email`='$email'");
	if(isset($r['id']))
	{
		ShowMsg('您填写的邮箱已被注册！','-1');
		exit();
	}


	//添加用户数据
	$regtime  = time();
	$regip    = GetIP();

	
	if(check_app_login('qq'))
	{
		$r = $dosql->GetOne("SELECT `id` FROM `#@__member` WHERE `qqid`='".$_SESSION['app']['qq']['uid']."'");
		if(isset($r['id']))
			ShowMsg('该QQ已与其他账号绑定！','-1');
		else
			$sql = "INSERT INTO `#@__member` (username, password, email, expval, regtime, regip, logintime, loginip, qqid) VALUES ('$username', '$password', '$email', '10', '$regtime', '$regip', '$regtime', '$regip', '".$_SESSION['app']['qq']['uid']."')";	
	}

	else if(check_app_login('weibo'))
	{
		$r = $dosql->GetOne("SELECT `id` FROM `#@__member` WHERE `qqid`='".$_SESSION['app']['weibo']['idstr']."'");
		if(isset($r['id']))
			ShowMsg('该微博已与其他账号绑定！','-1');
		else
			$sql = "INSERT INTO `#@__member` (username, password, email, expval, regtime, regip, logintime, loginip, weiboid) VALUES ('$username', '$password', '$email', '10', '$regtime', '$regip', '$regtime', '$regip', '".$_SESSION['app']['weibo']['idstr']."')";	
	}
	
	$dosql->ExecNoneQuery($sql);


	//用绑定账号登录
	$cookie_time = time()+3600;
	setcookie('username',      AuthCode($username ,'ENCODE'), $cookie_time);
	setcookie('lastlogintime', AuthCode($regtime  ,'ENCODE'), $cookie_time);
	setcookie('lastloginip',   AuthCode($regip    ,'ENCODE'), $cookie_time);

	ShowMsg('完善账号成功！','?c=default');
	exit();
	
}


//绑定账号
else if($a == 'binding')
{
	//初始化参数
	$username = empty($username) ? '' : $username;
	$password = empty($password) ? '' : md5(md5($password));


	//验证输入数据
	if($username == '' or $password == '')
	{
		header('location:?c=binding');
		exit();
	}

	$row = $dosql->GetOne("SELECT `id`,`password`,`logintime`,`loginip`,`expval` FROM `#@__member` WHERE `username`='$username'");

	//密码错误
	if(!is_array($row) or $password!=$row['password'])
	{
		ShowMsg('您输入的用户名或密码错误！','-1');
		exit();
	}
	else
	{
		if(check_app_login('qq'))
		{
			$r = $dosql->GetOne("SELECT `id` FROM `#@__member` WHERE `qqid`='".$_SESSION['app']['qq']['uid']."'");
			if(isset($r['id']))
			{
				ShowMsg('该QQ已与其他账号绑定！','-1');
			}
			else
			{
				$qqid = $_SESSION['app']['qq']['uid'];
				$sql = "UPDATE `#@__member` SET `qqid`='$qqid' WHERE `username`='$username'";
			}
		}

		else if(check_app_login('weibo'))
		{
			$r = $dosql->GetOne("SELECT `id` FROM `#@__member` WHERE `qqid`='".$_SESSION['app']['weibo']['idstr']."'");
			if(isset($r['id']))
			{
				ShowMsg('该微博已与其他账号绑定！','-1');
			}
			else
			{
				$weiboid = $_SESSION['app']['weibo']['idstr'];
				$sql = "UPDATE `#@__member` SET `weiboid`='$weiboid' WHERE `username`='$username'";
			}
		}

		$dosql->ExecNoneQuery($sql);

		//用绑定账号登录
		$cookie_time = time()+3600;
		setcookie('username',      AuthCode($username        ,'ENCODE'), $cookie_time);
		setcookie('lastlogintime', AuthCode($row['logintime'],'ENCODE'), $cookie_time);
		setcookie('lastloginip',   AuthCode($row['loginip']  ,'ENCODE'), $cookie_time);

		ShowMsg('绑定账号成功！','?c=default');
		exit();
	}
	
}


//移除绑定QQ
else if($a == 'removeoqq')
{
	$r = $dosql->GetOne("SELECT `id` FROM `#@__member` WHERE `username`='$c_uname' AND `qqid`<>''");
	if(empty($r) && !is_array($r))
	{
		ShowMsg('错误的操作，您没有绑定QQ账号！','-1');
	}
	else
	{
		$dosql->ExecNoneQuery("UPDATE `#@__member` SET `qqid`='' WHERE `username`='$c_uname'");
		ShowMsg('解除QQ绑定成功！','?c=edit');
	}

	exit();
}


//移除绑定微博
else if($a == 'removeoweibo')
{
	$r = $dosql->GetOne("SELECT `id` FROM `#@__member` WHERE `username`='$c_uname' AND `weiboid`<>''");
	if(empty($r) && !is_array($r))
	{
		ShowMsg('错误的操作，您没有绑定微博账号！','-1');
	}
	else
	{
		$dosql->ExecNoneQuery("UPDATE `#@__member` SET `weiboid`='' WHERE `username`='$c_uname'");
		ShowMsg('解除微博绑定成功！','?c=edit');
	}

	exit();
}




//加载模板页面
if($c == 'login')
{
	if(!empty($c_uname))
	{
		$r = $dosql->GetOne("SELECT `id` FROM `#@__member` WHERE `username`='$c_uname'");
		if(is_array($r))
		{
			header('location:?c=default');
			exit();
		}
		else
		{
			setcookie('username',      '', time()-3600);
			setcookie('lastlogintime', '', time()-3600);
			setcookie('lastloginip',   '', time()-3600);
			ShowMsg('该用户已不存在！','?c=login');
			exit();
		}
	}
	else
	{
		require_once(PHPMYWIND_TEMP.'/default/member/login.php');
		exit();
	}
}

if($c=='default'  or $c=='edit'   or $c=='comment' or
   $c=='favorite' or $c=='order'  or $c=='ordershow' or 
   $c=='msg'      or $c=='avatar' or $c=='perfect' or
   $c=='binding' or $c=='applyteacher')
{
	if(!empty($c_uname))
	{
		//guest为同步登录未绑定账号时的临时用户
		if($c_uname != 'guest')
		{
			$r = $dosql->GetOne("SELECT `id`,`expval` FROM `#@__member` WHERE `username`='$c_uname'");
			if(!is_array($r))
			{
				setcookie('username',      '', time()-3600);
				setcookie('lastlogintime', '', time()-3600);
				setcookie('lastloginip',   '', time()-3600);
				ShowMsg('该用户已不存在！','?c=login');
				exit();
			}
			else if($r['expval'] <= 0)
			{
				ShowMsg('抱歉，您的账号被禁止登录！','?c=login');
				exit();
			}
		}
	}
	else
	{
		header('location:?c=login');
		exit();
	}
}



//会员中心
if($c == 'default')
{
	if($c_uname != 'guest')
		require_once(PHPMYWIND_TEMP.'/default/member/default.php');
	else
		require_once(PHPMYWIND_TEMP.'/default/member/defaultguest.php');
	
	exit();
}


//上传头像
else if($c == 'avatar')
{
	require_once(PHPMYWIND_TEMP.'/default/member/avatar.php');
	exit();
}


//上传学习资料
else if($c == 'uploadlearningfile')
{
	require_once(PHPMYWIND_TEMP.'/default/member/upload_file.php');
	exit();
}


//编辑资料
else if($c == 'edit')
{		
	require_once(PHPMYWIND_TEMP.'/default/member/edit.php');
	exit();
}

//编辑试卷
else if($c == 'paperedit')
{		
	require_once(PHPMYWIND_TEMP.'/default/member/paperedit.php');
	exit();
}

//编辑班级
else if($c == 'classedit')
{		
	require_once(PHPMYWIND_TEMP.'/default/member/classedit.php');
	exit();
}
//查看考试成绩
else if($c == 'findscore')
{		
	require_once(PHPMYWIND_TEMP.'/default/member/find_score.php');
	exit();
}
//班级列表
else if($c == 'classlist')
{	
	require_once(PHPMYWIND_TEMP.'/default/member/class_list.php');
	exit();
}

//学习资料
else if($c == 'learningfilelist')
{	
	require_once(PHPMYWIND_TEMP.'/default/member/learning_file_list.php');
	exit();
}

//评论列表
else if($c == 'comment')
{	
	require_once(PHPMYWIND_TEMP.'/default/member/comment.php');
	exit();
}

//收藏列表
else if($c == 'favorite')
{
	require_once(PHPMYWIND_TEMP.'/default/member/favorite.php');
	exit();
}


//订单列表
else if($c == 'order')
{
	require_once(PHPMYWIND_TEMP.'/default/member/order.php');
	exit();
}


//订单详情
else if($c == 'ordershow')
{
	require_once(PHPMYWIND_TEMP.'/default/member/ordershow.php');
	exit();
}


//留言列表
else if($c == 'msg')
{
	require_once(PHPMYWIND_TEMP.'/default/member/msg.php');
	exit();
}

//参加考试
else if($c == 'taketest')
{
	require_once(PHPMYWIND_TEMP.'/default/member/take_test.php');
	exit();
}

//查看学生考试情况
else if($c == 'usertest')
{
	require_once(PHPMYWIND_TEMP.'/default/member/user_test.php');
	exit();
}

//批卷
else if($c == 'evaltest')
{
	require_once(PHPMYWIND_TEMP.'/default/member/eval_test.php');
	exit();
}

//创建题目
else if($c == 'newques')
{
	require_once(PHPMYWIND_TEMP.'/default/member/new_ques.php');
	exit();
}

//创建试卷
else if($c == 'newtest')
{
	require_once(PHPMYWIND_TEMP.'/default/member/new_test.php');
	exit();
}

//创建班级
else if($c == 'newclass')
{
	require_once(PHPMYWIND_TEMP.'/default/member/new_class.php');
	exit();
}

//试卷列表
else if($c == 'testlist')
{
	require_once(PHPMYWIND_TEMP.'/default/member/test_list.php');
	exit();
}

//班级成员
else if($c == 'classstu')
{
	require_once(PHPMYWIND_TEMP.'/default/member/class_stu.php');
	exit();
}

//添加学生
else if($c == 'stulist')
{
	require_once(PHPMYWIND_TEMP.'/default/member/stu_list.php');
	exit();
}

//添加题目
else if($c == 'testdtl')
{
	require_once(PHPMYWIND_TEMP.'/default/member/test_dtl.php');
	exit();
}


//编辑试题
else if($c == 'ques_editor')
{
	require_once(PHPMYWIND_TEMP.'/default/member/ques_editor.php');
	exit();
}


//完善绑定账号
else if($c == 'perfect')
{
	if(isset($c_uname) && $c_uname == 'guest')
		require_once(PHPMYWIND_TEMP.'/default/member/perfect.php');
	else if(isset($c_uname) && $c_uname != 'guest')
		header('location:?c=default');
	else
		header('location:?c=login');

	exit();
}


//绑定已有账号
else if($c == 'binding')
{
	if(isset($c_uname) && $c_uname == 'guest')
		require_once(PHPMYWIND_TEMP.'/default/member/binding.php');
	else if(isset($c_uname) && $c_uname != 'guest')
		header('location:?c=default');
	else
		header('location:?c=login');

	exit();
}


//用户注册
else if($c == 'reg')
{
	require_once(PHPMYWIND_TEMP.'/default/member/reg.php');
	exit();
}


//找回密码
else if($c == 'findpwd')
{
	require_once(PHPMYWIND_TEMP.'/default/member/findpwd.php');
	exit();
}


//找回密码
else if($c == 'findpwd2')
{
	if(!isset($_POST['username']))
		header('location:?c=findpwd');
	else
		require_once(PHPMYWIND_TEMP.'/default/member/findpwd2.php');

	exit();
}


//找回密码
else if($c == 'findpwd3')
{
	if(!isset($_POST['username']) && !isset($_POST['validate']))
		header('location:?c=findpwd');
	else
		//检测验证码
		$r = $dosql->GetOne("SELECT * FROM `#@__sms_log` WHERE `mobile`='$username' AND `status`=0 AND affect='pwd' ORDER BY posttime DESC");
		if(!isset($r['id']))
		{
			ShowMsg('验证码不正确！','-1');
			exit();
		}elseif((time()-$r['posttime'])/60 > 30){
			$dosql->ExecNoneQuery("UPDATE `#@__sms_log` SET `status`=2 WHERE id=".$r['id']);
			ShowMsg('短信验证码已过期！','-1');
			exit();
		}elseif($r['smscode']!=$validate){
			ShowMsg('短信验证码不正确！','-1');
			exit();
		}
		
		$a = 'quesfind';
		$uname = $username;
		
		require_once(PHPMYWIND_TEMP.'/default/member/findpwd3.php');
		
	exit();
}

//申请成为教师
else if($c == 'applyteacher'){
	$r = $dosql->GetOne("SELECT * FROM `#@__member` WHERE `username`='$c_uname'");
	$uid = $r['id'];
	$uname = $c_uname;
	if(isset($action) && $action=='add'){
		$apptime = time();
		$school = strip_tags($school);
		$class = strip_tags($class);
		$teach = strip_tags($teach);
		$sql = "INSERT INTO `#@__applyteacher` (uid, uname, school, `class`, teach, checkinfo, apptime) VALUES('$uid', '$uname', '$school', '$class', '$teach', 0, '$apptime')";
		if($dosql->ExecNoneQuery($sql)){
		    ShowMsg('申请成功，请等待管理员审核！','?c=default');	
		}else{
			ShowMsg('申请失败！','-1');
		}
		exit();
	}
	
	require_once(PHPMYWIND_TEMP.'/default/member/applyteacher.php');
	exit();
}
//课程管理
else if($c == 'course'){
	/*$r = $dosql->GetOne("SELECT * FROM `#@__member` WHERE `username`='$c_uname'");
	$uid = $r['id'];
	$uname = $c_uname;*/
	
	require_once(PHPMYWIND_TEMP.'/default/member/course.php');
	exit();
}
//添加修改课程
else if($c == 'course_edit'){
	require_once(PHPMYWIND_TEMP.'/default/member/course_edit.php');
	exit();
}
//保存
else if($c == 'course_save'){
	$r = $dosql->GetOne("SELECT * FROM `#@__member` WHERE `username`='$c_uname'");
	$uid = $r['id'];
	
	$id = empty($id) ? 0 : intval($id);
	$classid = empty($classid) ? 0 : intval($classid);
	$title = empty($title) ? '' : strip_tags($title);
	$picurl = empty($picurl) ? '' : strip_tags($picurl);
	$price = empty($price) ? 0.00 : strip_tags($price);
	$keywords = empty($keywords) ? '' : strip_tags($keywords);
	$description = empty($description) ? '' : strip_tags($description);
	$content = empty($content) ? '' : strip_tags($content);
	$serialstatus = empty($serialstatus) ? '' : strip_tags($serialstatus);
	$checkinfo = empty($checkinfo) ? 'false' : strip_tags($checkinfo);
	$posttime = time();
	if($id > 0){
		$dosql->ExecNoneQuery("UPDATE `#@__course` SET classid='$classid', uid='$uid', title='$title', picurl='$picurl', price='$price', keywords='$keywords', description='$description', content='$content', checkinfo='$checkinfo', serialstatus='$serialstatus', posttime='$posttime' WHERE `id`='$id' AND uid=$uid");
	}else{
	    $dosql->ExecNoneQuery("INSERT INTO `#@__course` (classid, uid, title, picurl, price, keywords, description, content, checkinfo, serialstatus, posttime) VALUES ('$classid', '$uid', '$title', '$picurl', '$price', '$keywords', '$description', '$content', '$checkinfo', '$serialstatus', '$posttime')");
	}
	ShowMsg('保存成功！','?c=course');
	exit();
}
else if($c == 'delcourse'){
	$r = $dosql->GetOne("SELECT * FROM `#@__member` WHERE `username`='$c_uname'");
	$uid = $r['id'];
	
	if(is_array($checkid))
	{
		foreach($checkid as $v)
		{
			//参数过滤
			$v = intval($v);
			$dosql->ExecNoneQuery("UPDATE `#@__course` SET is_delete='true' WHERE `id`=$v AND `uid`='$uid'");
			$dosql->ExecNoneQuery("UPDATE `#@__course_lesson` SET is_delete='true' WHERE `course_id`=$v AND `uid`='$uid'");
		}
	}

	header('location:?c=course');
	exit();
}
//课程章节管理
else if($c == 'lesson'){
	require_once(PHPMYWIND_TEMP.'/default/member/course_lesson.php');
	exit();
}
//添加修改课程
else if($c == 'course_lesson_edit'){
	require_once(PHPMYWIND_TEMP.'/default/member/course_lesson_edit.php');
	exit();
}
//保存
else if($c == 'course_lesson_save'){
	$r = $dosql->GetOne("SELECT * FROM `#@__member` WHERE `username`='$c_uname'");
	$uid = $r['id'];
	
	$id = empty($id) ? 0 : intval($id);
	$course_id = empty($course_id) ? 0 : intval($course_id);
	$description = empty($description) ? '' : strip_tags($description);
	$videourl = empty($videourl) ? '' : strip_tags($videourl);
	$content = empty($content) ? '' : strip_tags($content);
	$checkinfo = empty($checkinfo) ? 'false' : strip_tags($checkinfo);
	$is_free = empty($is_free) ? 'false' : strip_tags($is_free);
	$posttime = time();
	if($id > 0){
		$dosql->ExecNoneQuery("UPDATE `#@__course_lesson` SET uid='$uid', description='$description', videourl='$videourl', content='$content', checkinfo='$checkinfo', is_free='$is_free', posttime='$posttime' WHERE `id`='$id' AND uid=$uid");
	}else{
	    $dosql->ExecNoneQuery("INSERT INTO `#@__course_lesson` (course_id, uid, description, videourl, content, checkinfo, is_free, posttime) VALUES ('$course_id', '$uid', '$description', '$videourl', '$content', '$checkinfo', '$is_free', '$posttime')");
	}
	ShowMsg('保存成功！','?c=lesson&id='.$course_id);
	exit();
}
else if($c == 'dellesson'){
	$r = $dosql->GetOne("SELECT * FROM `#@__member` WHERE `username`='$c_uname'");
	$uid = $r['id'];
	
	if(is_array($checkid))
	{
		foreach($checkid as $v)
		{
			//参数过滤
			$v = intval($v);
			$dosql->ExecNoneQuery("UPDATE `#@__course_lesson` SET is_delete='true' WHERE `id`=$v AND `uid`='$uid'");
		}
	}

	header('location:?c=lesson&id='.$cid);
	exit();
}
else
{
	header('location:?c=login');
	exit();
}



//验证码获取函数
function GetCkVdValue()
{
	if(!isset($_SESSION)) session_start();
	return isset($_SESSION['ckstr']) ? $_SESSION['ckstr'] : '';
}


//验证码重置函数
function ResetVdValue()
{
	if(!isset($_SESSION)) session_start();
	$_SESSION['ckstr'] = '';
}
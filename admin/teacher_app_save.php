<?php	require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('soft');

/*
**************************
(C)2010-2014 phpMyWind.com
update: 2014-5-30 17:40:45
person: Feng
**************************
*/


//初始化参数
$tbname = '#@__applyteacher';
$gourl  = 'teacher_app.php';
$action = isset($action) ? $action : '';


if($action == 'update')
{
	//栏目权限验证
	//IsCategoryPriv($cid,'update');
	if(!empty($uid)){
		if($checkinfo==1) $is_teacher='true'; else $is_teacher='false';
		$sql = "UPDATE `#@__member` SET is_teacher='$is_teacher' WHERE id=$uid";
		$dosql->ExecNoneQuery($sql);
	}else{
		header("location:$gourl");
		exit();
	}
	$sql = "UPDATE `$tbname` SET checkinfo='$checkinfo', remark='$remark' WHERE id=$id";
	if($dosql->ExecNoneQuery($sql))
	{
		
		header("location:$gourl");
		exit();
	}
}

//无状态返回
else
{
	header("location:$gourl");
	exit();
}
?>
<?php	require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('infoclass');

/*
**************************
(C)2010-2014 phpMyWind.com
update: 2014-5-30 16:53:58
person: Feng
**************************
*/


//初始化参数
$tbname = '#@__course_lesson';
$gourl  = 'course_lesson.php';


//引入操作类
require_once(ADMIN_INC.'/action.class.php');
//修改课程
if($action == 'update')
{
		$time = time();
		$sql = "UPDATE `$tbname` SET description='$description', content='$content', checkinfo='$checkinfo', is_free='$is_free',posttime='$time',hits='$hits' WHERE id=$id";
		if($dosql->ExecNoneQuery($sql))
		{
			header("location:$gourl");
			exit();
		}
}
else if($action == 'dellesson')
{
	//删除栏目
	$dosql->ExecNoneQuery("UPDATE `$tbname` SET is_delete = 'true' WHERE `id`=$id");
	header("location:$gourl");
	exit();
}


//删除全选栏目
else if($action == 'delalllesson')
{
	//删除栏目的单页信息
	foreach($checkid as $k=>$v)
	{
		$dosql->ExecNoneQuery("UPDATE `$tbname` SET is_delete = 'true' WHERE `id`=$v");
		
	}

	header("location:$gourl");
	exit();
}


//无条件返回
else
{
    header("location:$gourl");
	exit();
}
?>
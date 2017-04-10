<?php	require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('infoclass');

/*
**************************
(C)2010-2014 phpMyWind.com
update: 2014-5-30 16:53:58
person: Feng
**************************
*/


//初始化参数
$tbname = '#@__course_type';
$gourl  = 'course_type.php';


//引入操作类
require_once(ADMIN_INC.'/action.class.php');


//添加栏目
if($action == 'add')
{
	//权限验证
	//如果parentid等于0，则是新添加的栏目，不验证权限
	/*if($parentid != 0)
	{
		IsCategoryPriv($parentid,'add');
	}


	$parentstr = $doaction->GetParentStr();*/


	$sql = "INSERT INTO `$tbname` (parentid, classname, orderid, checkinfo) VALUES ('$parentid', '$classname', '$orderid', '$checkinfo')";
	if($dosql->ExecNoneQuery($sql))
	{
		//为非超级管理员增加操作权限
		/*if($cfg_adminlevel != 1)
		{
			$groupid = $cfg_adminlevel;
			$siteid  = $_SESSION['siteid'];
			$classid = $dosql->GetLastID();

			foreach(array('list','add','update','del') as $v)
			{
				$dosql->ExecNoneQuery("INSERT INTO `#@__adminprivacy` (groupid, siteid, model, classid, action) VALUES ('$groupid', '$siteid', 'category', '$classid', '$v')");
			}
		}*/
	}

	header("location:$gourl");
	exit();
}


//修改栏目
else if($action == 'update')
{
	//权限验证
	/*IsCategoryPriv($id,'update');


	$parentstr = $doaction->GetParentStr();*/


	//不允许更新parentid为自己
	if($parentid != $id)
	{
		$sql = "UPDATE `$tbname` SET parentid='$parentid', classname='$classname', orderid='$orderid', checkinfo='$checkinfo' WHERE id=$id";
		if($dosql->ExecNoneQuery($sql))
		{
			header("location:$gourl");
			exit();
		}
	}
	else
	{
		ShowMsg('不允许选择本身作为所属父类！');
		exit();
	}
}

	
//删除栏目
else if($action == 'delclass')
{
	//权限验证
	IsCategoryPriv($id,'del');


	//初始化参数
	$ids = '';
	
	$ids = trim($ids,',');

	//删除栏目
	$dosql->ExecNoneQuery("DELETE FROM `$tbname` WHERE `id`=$id");
	header("location:$gourl");
	exit();
}


//删除全选栏目
else if($action == 'delallclass')
{

	//删除栏目的单页信息
	foreach($checkid as $k=>$v)
	{
		//初始化参数
		$ids = '';

		//获取可删除权限id
		$dosql->Execute("SELECT `id` FROM `$tbname` WHERE `id`=$v");
		while($row = $dosql->GetArray())
		{
			if(IsCategoryPriv($row['id'],'del',1))
			{
				$ids .= $row['id'].',';
			}
		}

		$ids = trim($ids,',');

		//删除栏目
		if(IsCategoryPriv($v,'del',1))
		{
			$dosql->ExecNoneQuery("DELETE FROM `$tbname` WHERE `id`=$v");
		}
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
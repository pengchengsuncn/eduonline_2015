<?php	require_once(dirname(__FILE__).'/inc/config.inc.php');IsModelPriv('infoclass');

/*
**************************
(C)2010-2014 phpMyWind.com
update: 2014-5-30 16:53:58
person: Feng
**************************
*/


//初始化参数
$tbname = '#@__course';
$gourl  = 'course.php';


//引入操作类
require_once(ADMIN_INC.'/action.class.php');

//修改课程
if($action == 'update')
{
		$time = time();
		$flag = implode(',',$flag);
		$sql = "UPDATE `$tbname` SET title='$title', description='$description', keywords='$keywords', price='$price',posttime='$time',checkinfo='$checkinfo',flag='$flag' WHERE id=$id";
		if($dosql->ExecNoneQuery($sql))
		{
			header("location:$gourl");
			exit();
		}
}
	
//删除栏目
else if($action == 'delcourse')
{
	$id = empty($id) ? 0 : intval($id);
	//判断是否有章节
	$r = $dosql->GetOne("SELECT * FROM #@__course_lesson WHERE course_id=$id");
	if(isset($r['id'])){
		ShowMsg('该课程存在章节，请先删除章节！','-1');
		exit();
	}
	
	//删除栏目
	$dosql->ExecNoneQuery("UPDATE `$tbname` SET is_delete = 'true' WHERE `id`=$id AND is_delete != 'true'");
	header("location:$gourl");
	exit();
}


//删除全选栏目
else if($action == 'delallcourse')
{
	//删除栏目的单页信息
	foreach($checkid as $k=>$v)
	{
		
		$r = $dosql->GetOne("SELECT * FROM #@__course_lesson WHERE course_id=$v AND is_delete != 'true'");
		if(isset($r['id'])){
			ShowMsg('ID为'.$v.'的课程存在章节，请先删除章节！','-1');
			exit();
		}
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
<?php require_once(dirname(__FILE__).'/inc/config.inc.php');//IsModelPriv('course_type'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>查看课程</title>
<link href="templates/style/admin.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery.min.js"></script>
<script type="text/javascript" src="templates/js/getuploadify.js"></script>
<script type="text/javascript" src="templates/js/getcatpsize.js"></script>
<script type="text/javascript" src="templates/js/checkf.func.js"></script>
</head>
<body>
<?php
$row = $dosql->GetOne("SELECT * FROM `#@__course` WHERE `id`=$id AND is_delete != 'true'");
if(empty($row['id'])){
	ShowMsg('您访问的信息不存在！','-1');
	exit();
}
?>
<div class="formHeader"> <span class="title">查看课程</span> <a href="javascript:location.reload();" class="reload">刷新</a> </div>
<form name="form" id="form" method="post" action="course_save.php" onsubmit="return cfm_course_type();">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="formTable">
		<tr>
			<td width="25%" height="40" align="right">课程名称：</td>
			<td><input type="text" name="title" id="title"  value="<?php echo $row['title']; ?>" class="input" />
			</td>
		</tr>
		<tr>
			<td height="40" align="right">关键字：</td>
			<td><input type="text" name="keywords" id="keywords"  value="<?php echo $row['keywords']; ?>" class="input" /></td>
		</tr>
        <tr>
			<td height="116" align="right">描述：</td>
			<td>
			 <textarea name="description" id="description" class="textarea"><?php echo $row['description']; ?></textarea>	
            </td>
		</tr>
        <tr>
			<td height="40" align="right">价格：</td>
			<td><input type="text" name="price" id="price" class="input" value="<?php echo $row['price']; ?>" /></td>
		</tr>
        
        <tr>
			<td height="40" align="right">状态连载：</td>
			<td>
                <input type="text" class="input" value="<?php 
                switch($row['serialstatus'])
                {
                    case 1:
                        $serialstatus = '不连载';
                        break;  
                    case 2:
                        $serialstatus = '更新中';
                        break;
                    case 3:
                        $serialstatus = '已完结';
                        break;
                    default:
                        $serialstatus = '没有获取到参数';
                        
                } 
                echo $serialstatus;
                ?>" />
            </td>
		</tr>
        <tr>
			<td height="40" align="right">是否显示：</td>
			<td>
                <input type="radio" name="checkinfo" id="checkinfo" value="true" <?php if($row['checkinfo']=='true') echo 'checked="checked"';?> /> 是
                <input type="radio" name="checkinfo" id="checkinfo" value="false" <?php if($row['checkinfo']=='' || $row['checkinfo']=='false') echo 'checked="checked"';?> /> 否
            </td>
		</tr>
        <tr>
			<td height="40" align="right">属性：</td>
			<td>
                <?php
				$array=array('n'=>'最新','r'=>'推荐','h'=>'最热');
				foreach($array as $k=>$v){
				?>
            	<input type="checkbox" name="flag[]" id="flag" value="<?php echo $k;?>" <?php foreach(explode(',',$row['flag']) as $val){ if($k==$val) echo 'checked="checked"';} ?> /> <?php echo $v;?>
                <?php
				}
				?>
            </td>
		</tr>
        <tr>
			<td height="40" align="right">点击次数：</td>
			<td><input type="text" name="hits" id="hits" value="<?php echo $row['hits']; ?>" class="input" /></td>
		</tr>
	</table>
	<div class="formSubBtn">
		<input type="submit" class="submit" value="提交" />
		<input type="button" class="back" value="返回" onclick="history.go(-1);" />
		<input type="hidden" name="action" id="action" value="update" />
		<input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
	</div>
</form>
</body>
</html>
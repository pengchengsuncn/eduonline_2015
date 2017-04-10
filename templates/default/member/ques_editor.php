<?php if(!defined('IN_MEMBER')) exit('Request Error!'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $cfg_webname; ?> - 会员中心 - 编辑题目</title>
<link href="<?php echo $cfg_webpath; ?>/templates/default/style/member.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo $cfg_webpath; ?>/templates/default/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $cfg_webpath; ?>/templates/default/js/member.js"></script>
<script type="text/javascript" src="<?php echo $cfg_webpath; ?>/admin/editor/kindeditor-min.js"></script>
</head>

<body>
<div class="header">
	<?php require_once(dirname(__FILE__).'/header.php'); ?>
</div>
<div class="mainbody">
	<div class="leftarea">
		<?php require_once(dirname(__FILE__).'/lefter.php'); ?>
	</div>
	<div class="rightarea">
		<?php 
			
			$ques = $dosql->GetOne("SELECT * FROM `#@__question` WHERE id='$id'");
			$type= $dosql->GetOne("SELECT * FROM `#@__subject_type` WHERE id=$ques[type]");
		?>
		<form name="form" id="form" method="post" action="?a=editor_ques_new1">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td colspan="2" height="26"><h3 class="subtitle">编辑试题</h3></td>
				</tr>
				<tr>
					<td width="100px;" height="40" align="right">题目类型：<?php echo $type['type']?></td>
					<input type="hidden" value="<?php echo $ques['type']?>" id="val01"/>
					<td>
						<select name="type" id="type">
							<option value="1">单选题</option>
							<option value="2">多选题</option>
							<option value="3">判断题</option>
							<option value="4">填空题</option>
							<option value="5">问答题</option>
							<option value="6">材料题</option>
						</select>
					</td>
				</tr>
				<tr>
					<td height="40" align="right">题目内容：</td>
					<td><textarea name="title" id="title" class="kindeditor"><?php echo $ques['title']?></textarea>
						<div id="title-demo">
							<span style="color:red;">填空题问题样式：</span>中国风景无限美好，被称为春城的是________市。 
						</div>
						<br />
					</td>
				</tr>
				<tr>
					<td height="40" align="right">选项1：</td>
					<td height="130"><textarea name="option_1" id="option-1" class="class_areatext"><?php echo $ques['option_1']?></textarea></td>
				</tr>
				<tr>
					<td height="40" align="right">备注：</td>
					<td><textarea name="optionDesc_1" id="option-desc-1" class="class_areatext"><?php echo $ques['option_desc_1']?></textarea></td>
				</tr>
				<tr>
					<td height="40" align="right">选项2：</td>
					<td height="130"><textarea name="option_2" id="option-2" class="class_areatext"><?php echo $ques['option_2']?></textarea></td>
				</tr>
				<tr>
					<td height="40" align="right">备注：</td>
					<td><textarea name="optionDesc_2" id="option-desc-2" class="class_areatext"><?php echo $ques['option_desc_2']?></textarea></td>
				</tr>
				<tr>
					<td height="40" align="right">选项3：</td>
					<td height="130"><textarea name="option_3" id="option-3" class="class_areatext"><?php echo $ques['option_3']?></textarea></td>
				</tr>
				<tr>
					<td height="40" align="right">备注：</td>
					<td><textarea name="optionDesc_3" id="option-desc-3" class="class_areatext"><?php echo $ques['option_desc_3']?></textarea></td>
				</tr>
				<tr>
					<td height="40" align="right">选项4：</td>
					<td height="130"><textarea name="option_4" id="option-4" class="class_areatext"><?php echo $ques['option_4']?></textarea></td>
				</tr>
				<tr>
					<td height="40" align="right">备注：</td>
					<td><textarea name="optionDesc_4" id="option-desc-4" class="class_areatext"><?php echo $ques['option_desc_4']?></textarea></td>
				</tr>
				<tr>
					<td height="40" align="right">正确答案：</td>
					<td id="answer-container"></td>
				</tr>
				<tr>
					<td height="40" align="right">题目分值：</td>
					<td><input type="text" name="score" id="score" class="class_input" value="<?php echo $ques['score']?>" /></td>
				</tr>
				<tr>
					<td height="116" align="right">题目解析：</td>
					<td><textarea name="description" id="description" class="class_areatext"><?php echo $ques['description']?></textarea></td>
				</tr>
			</table>
			<div class="btn_area">
				<input type="submit" class="btn" value="保 存" />
				<input type="button" class="btn" value="取 消" onclick="history.go(-1)" />
				<input type="hidden" name="useId" id="user-id" value="<?php echo $r_user['id']; ?>" />
				<input type="hidden" name="Id" id="id" value="<?php echo $ques['id']; ?>" />
				<input type="hidden" name="testId" id="test_id" value="<?php echo $ques['test_id']; ?>" />
			</div>
		</form>
	</div>
	<div class="cl"></div>
</div>
<div class="footer"><?php echo $cfg_copyright; ?></div>

<script type="application/template" class="sc-answer-template">
	<select name="answer" id="answer">
		<option value="1">选项1</option>
		<option value="2">选项2</option>
		<option value="3">选项3</option>
		<option value="4">选项4</option>
	</select>
</script>
<script type="application/template" class="mc-answer-template">
	<label for="answer-1">选项1 <input type="checkbox" name="answer[]" id="answer-1" value="1" checked="checked"></label>
	<label for="answer-2">选项2 <input type="checkbox" name="answer[]" id="answer-2" value="2"></label>
	<label for="answer-3">选项3 <input type="checkbox" name="answer[]" id="answer-3" value="3"></label>
	<label for="answer-4">选项4 <input type="checkbox" name="answer[]" id="answer-4" value="4"></label>
</script>
<script type="application/template" class="tf-answer-template">
	<label for="answer-1">正确 <input type="radio" name="answer" id="answer-1" value="Y" checked="checked"></label>
	<label for="answer-2">错误 <input type="radio" name="answer" id="answer-2" value="N"></label>
</script>
<script type="application/template" class="fb-answer-template">
	<textarea name="answer" id="answer" class="class_areatext"></textarea>
</script>

<script type="text/javascript">
	$(document).ready(function() {

		var editor;
		KindEditor.ready(function(K) {
			editor = K.create('textarea[name="title"]', {
				allowFileManager : true,
				width:'90%',
				height:'180px'
			});
		});
		
		toggleDifferentContent();
		$("#type").change(function(event) {
			toggleDifferentContent();
		});

		$("#form").submit(function(event) {
			if($.trim(editor.text()) == ""){
				alert("题目内容不能为空！");
				return false;
			}

			var type = $("#type").val();
			if(type == "1" || type == "2"){
				if($.trim($("textarea[name='option_1']").val()) == ""){
					alert("请输入选项1的内容！");
					return false;
				}else{
					if($.trim($("textarea[name='option_2']").val()) == "" && ($.trim($("textarea[name='option_3']").val()) != "" || $.trim($("textarea[name='option_4']").val()) != "")){
						alert("请先输入选项2的内容！");
						return false;
					}else{
						if($.trim($("textarea[name='option_3']").val()) == "" &&  $.trim($("textarea[name='option_4']").val()) != ""){
							alert("请先输入选项3的内容！");
							return false;
						}
					}
				}
			}

			if(type == "2"){
				if($("input[type='checkbox']:checked").length == 0){
					alert("请选择题目答案！");
					return false;
				}
			}

			if(type == "4" || type == "5" || type == "6"){
				if($.trim($("#answer").val()) == ""){
					alert("请输入题目答案！");
					return false;
				}
			}

			var cknum = /^[1-9]\d*$/;
			if(!cknum.test($("#score").val())){
				alert("题目分数必须为正整数！");
				return false;
			}
		});
	});
	
	function toggleDifferentContent(){
		var type = $("#type").val();
		var answerTemplate = "";
		switch(type){
			case "1":
				$("#title-demo").hide();
				$("textarea[id^='option-'],textarea[id^='option-desc-'],").closest("tr").show();
				answerTemplate = $($("script.sc-answer-template").html()).clone();
				break;
			case "2":
				$("#title-demo").hide();
				$("textarea[id^='option-'],textarea[id^='option-desc-'],").closest("tr").show();
				answerTemplate = $($("script.mc-answer-template").html()).clone();
				break;
			case "3":
				$("#title-demo").hide();
				$("textarea[id^='option-'],textarea[id^='option-desc-'],").closest("tr").hide();
				answerTemplate = $($("script.tf-answer-template").html()).clone();
				break;
			case "4":
				$("#title-demo").show();
				$("textarea[id^='option-'],textarea[id^='option-desc-'],").closest("tr").hide();
				answerTemplate = $($("script.fb-answer-template").html()).clone();
				break;
			case "5":
				$("#title-demo").hide();
				$("textarea[id^='option-'],textarea[id^='option-desc-'],").closest("tr").hide();
				answerTemplate = $($("script.fb-answer-template").html()).clone();
				break;
			default: // type = mq
				$("#title-demo").hide();
				$("textarea[id^='option-'],textarea[id^='option-desc-'],").closest("tr").hide();
				answerTemplate = $($("script.fb-answer-template").html()).clone();
		}
		$("#answer-container").html(answerTemplate);
	}

</script>
</body>
</html>
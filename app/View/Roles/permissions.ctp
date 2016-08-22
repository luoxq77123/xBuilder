<script type="text/javascript">
$(document).ready(function(e) {
	$("[name='data[Role][template_accesses][]']").each(function() {
        if($(this).attr('checked') == 'checked')
		{
			$('#default_template_id'+$(this).val()).attr('style','');
		}
    });
	
	$("#s1").die().live('click',function(){
		ajaxPermissions($("#sid_user").val(),$("input[name='data[id]']").val(),1);
	});
	$("#s2").die().live('click',function(){
		ajaxPermissions($("#sid_user").val(),$("input[name='data[id]']").val(),2);
	});
	$("#s4").die().live('click',function(){
		ajaxPermissions($("#sid_user").val(),$("input[name='data[id]']").val(),4);
	});
	$("#s5").die().live('click',function(){
		ajaxPermissions($("#sid_user").val(),$("input[name='data[id]']").val(),5);
	});
	$("#s6").die().live('click',function(){
		ajaxPermissions($("#sid_user").val(),$("input[name='data[id]']").val(),6);
	});
});
$("#clickCategoryTrue").live('click',function(event){
	var cId = $('#sid_user').val();
	$("[name='data[Permission][permissions]['"+cId+"'][]']").attr('disabled',false);
	event.preventDefault();
});
$("#clickTemplateTrue").live('click',function(event){
	$(".changeRadio").each(function(){
			$(this).attr('disabled',false);
			if($(this).is(":checked")){
				$(this).parents("td").next().find("input[type='radio']").removeAttr("disabled");
			}
		});
	return false;
});
$('#clickSystemTrue').live('click',function(event){
	$(".operationCheckbox").attr('disabled',false);
	return false;
});



function ajaxPermissions(category_id, role_id, permissions_id)
{
	$.ajax({
		type:'GET',
		url:'Roles/newEditPermissions/'+category_id+'/'+role_id+'/'+permissions_id,
		data:'',
		dataType:'script',
		cache:false,
		success:function(data){
			var statusPermissions = data.split(',');
			if($.inArray(permissions_id.toString(), statusPermissions) == -1)
			{
				$("#s"+permissions_id+"").removeClass("s"+permissions_id);
				$("#s"+permissions_id+"").addClass("move_s"+permissions_id);
			}else
			{
				$("#s"+permissions_id+"").removeClass("move_s"+permissions_id);
				$("#s"+permissions_id+"").addClass("s"+permissions_id);
			}
		},
		error:DWZ.ajaxError
	});
}


/**
 * 普通ajax表单提交
 * @param {Object} form
 * @param {Object} callback
 */
function validateCallback(form, callback) {
	var $form = $(form);
	if (!$form.valid()) {
		return false;
	}

	$.ajax({
		type: form.method || 'POST',
		url:$form.attr("action"),
		data:$form.serializeArray(),
		dataType:"json",
		cache: false,
		success: callback || DWZ.ajaxDone,
		error: DWZ.ajaxError
	});
	return false;
}

 /**
  * dialog上的表单提交回调函数
  * 服务器转回navTabId，可以重新载入指定的navTab. statusCode=DWZ.statusCode.ok表示操作成功, 自动关闭当前dialog
  * 
  * form提交后返回json数据结构,json格式和navTabAjaxDone一致
  */
function dialogAjaxDone(json){
 	DWZ.ajaxDone(json);
 	if (json.statusCode == DWZ.statusCode.ok){
 		if (json.navTabId){
 			navTab.reload(json.forwardUrl, {navTabId: json.navTabId});
 		} else if (json.rel) {
 			navTabPageBreak({}, json.rel);
 		}
 		if ("closeCurrent" == json.callbackType) {
 			$.pdialog.closeCurrent();
 			if(json.reload){
 				setTimeout("window.location.reload()",3000);
 			}
 		}
 		if(json.disable){
 			$(".changeRadio").attr('disabled',true);
 			$(".templateRadio").attr('disabled',true);
 			$(".operationCheckbox").attr('disabled',true);
 		}

 		if(json.rid){
 	 		$("#premissions").loadUrl("<?php echo $this->Html->url(array('controller'=>'roles','action'=>'detailPremission'));?>/"+json.rid, {}, function(){
 	 			$("#premissions").find("[layoutH]").layoutH();
			});
 		}
 	}
}
</script>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="200">
	        <div class="pageContent" layoutH="5">
	            <div class="panel" style="margin:2px 5px 0px 5px;">
	                <h1>角色列表</h1>
	                <div>
		                <ul id="videoTree" class="tree">
		                <?php foreach($roles as $rid => $rname):?>
							<li><a target="ajax" rel="premissions" href="<?php echo $this->Html->url('/roles/detailPremission/'.$rid);?>"><?php echo $rname;?></a></li>
						<?php endforeach;?>
		                </ul>
	                </div>
	            </div>
	        </div>
        </td>
        <td>
        	<div id="premissions" class="pageContent" layoutH="5" style="margin:5px 5px 0px 0px;"></div>
        </td>
    </tr>
</table>

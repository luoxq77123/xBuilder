<?php 
echo $this->Html->css('ui-lightness/jquery-ui-1.8.20.custom');
echo $this->Html->script('jquery-ui-1.8.20.custom.min');
echo $this->Html->script('jquery-spin');
?>
<script type="text/javascript">
// add 20140925
function modifyAttr(obj,id) {
	$('#' + id).removeAttr('disabled').css({'display':'block'});
	$('#' + id).siblings('.title').css({'display':'none'});
	//fix select
	$('#' + id).parent().parent('.select').siblings('.title').css({'display':'none'});
	$(obj).siblings().css({'display':'block'});
	$(obj).css({'display':'none'});
}
function rename () {
	var templateName = $("#templateName").val();
	var templateValue = $("#Type").val();
	var postUrl = null;
	var data = null;
	if(templateName.length > 0){
		if($('#cacheId').val()){
			postUrl = 'TranscodeGroups/edit/' + $('#cacheId').val();
		}else{
			postUrl = 'TranscodeGroups/add/' + templateValue;
		}
		$.ajax({
			type:'POST',
			url:postUrl,
			data:$("#transcodeGroup").serializeArray(),
			dataType:"json",
			cache: false,
			success:function(data){
				$('#cacheId').val(data.value);
				DWZ.ajaxDone(data);
				if (data.navTabId){
					navTab.reloadFlag(data.navTabId);
					$("#templateName").siblings('.title').html(templateName);
					$('span.templateNameCancel').click();
				}
			},
			error: DWZ.ajaxError
		});
	}
}
function renameCategory() {
	var cvalue = $("#category").val();
	var templateName = $("#templateName").val();
	var templateValue = $("#Type").val();
	var ctest = $("#category").find("option:selected").text();
	var postUrl = null;
	var data = null;
	if(templateName == '') {
		alertMsg.info('请填写模板组名称后再保存');
		return false;
	}
	if(cvalue != 0){
		if($('#cacheId').val()){
			postUrl = 'TranscodeGroups/editCategory/' + cvalue;
		}else{
			postUrl = 'TranscodeGroups/add/' + templateValue;
		}
		$.ajax({
			type:'POST',
			url:postUrl,
			data:$("#transcodeGroup").serializeArray(),
			dataType:"json",
			cache: false,
			success:function(data){
				$('#cacheId').val(data.value);
				DWZ.ajaxDone(data);
				if (data.navTabId){
					navTab.reloadFlag(data.navTabId);
					$("#combox_category").parent().parent().siblings('.title').html(ctest);
					$('span.categoryCancel').click();
				}
			},
			error: DWZ.ajaxError
		});
	}else{
		alertMsg.info('请选择分类');
	}
}
$(function() {
	setTimeout(function(){
			$("a.transcodeSelected").parent().addClass("selected");
		var isNew = <?php echo isset($data['TranscodeGroup']['name'])?1:0;?>;
		if(isNew === 0) {
			$('span.modify-action').click();
			$('.panelHeaderContent .adds').click();
		}
	},1);
		/**
		* 失去焦点时，保存模板组
		*
		*/
		$('#templateName-old').focusout(function(){
			var templateName = $("#templateName").val();
			var templateValue = $("#Type").val();
			var postUrl = null;
			var data = null;
			if(templateName.length > 0){
				if($('#cacheId').val()){
					postUrl = 'TranscodeGroups/edit/' + $('#cacheId').val();
				}else{
					postUrl = 'TranscodeGroups/add_video';
				}
				$.ajax({
					type:'POST',
					url:postUrl,
					data:$("#transcodeGroup").serializeArray(),
					dataType:"json",
					cache: false,
					success:function(data){
						$('#cacheId').val(data.value);
						DWZ.ajaxDone(data);
						if (data.navTabId){ 
							navTab.reloadFlag(data.navTabId);
						}
					},
					error: DWZ.ajaxError
				});
			}
		});

		/**
		* 点删除按钮时
		*
		*/
		$('button[type=delete]').die().live('click', function(){
			if($(this).attr("target")){
				url = 'Transcodes/delete/'+$(this).attr("target");
				alertMsg.confirm('您确定要删除子模板?',{
					okCall: function(){
						$.get(url, null, function(json){
							DWZ.ajaxDone(json);
							if(json.statusCode == DWZ.statusCode.ok){
								deleteTab();
							}}, "json");
					}
				});
			}else{
				deleteTab();
			}
			return false;
		});

		/**
		* 更改下拉框时，改变状态
		*
		*/
		$("select").die().live('change',function(){
			changeLiColor();
		});
		
		/**
		* 更改子模板名称时，获取焦点失去焦点进行判断
		*
		*/
		$("input[name*='data[Transcode][sub_name]']").die().live('focusin',function(){
			if($('.tabsHeaderContent').find('li[class*=selected]').find("span").attr('style'))
			{
			}else{
				selectedLi("#snapValue").html($(this).val());
			}
			$(this).focusout(function(){
				if($(this).val() != selectedLi("#snapValue").html()){
					changeLiColor();
				}
			});
		});
		
		/**
		* 更改幅面宽度时，获取焦点失去焦点进行判断
		*
		*/
		$("input[name*='data[Transcode][FormatWidth]']").die().live('focusin',function(){
			if($('.tabsHeaderContent').find('li[class*=selected]').find("span").attr('style'))
			{
			}else{
				selectedLi("#snapValue").html($(this).val());
			}
			$(this).focusout(function(){
				if($(this).val() != selectedLi("#snapValue").html()){
					changeLiColor();
					reset_spin_slider();
				}
			});
		});
		
		/**
		* 更改幅面高度时，获取焦点失去焦点进行判断
		*
		*/
		$("input[name*='data[Transcode][FormatHeight]']").die().live('focusin',function(){
			if($('.tabsHeaderContent').find('li[class*=selected]').find("span").attr('style'))
			{
			}else{
				selectedLi("#snapValue").html($(this).val());
			}
			$(this).focusout(function(){
				if($(this).val() != selectedLi("#FormatHeight").html()){
					changeLiColor();
					reset_spin_slider();
				}
			});
		});
		
		/**
		* 更改码率时，获取焦点失去焦点进行判断
		*
		*/
		$("input[name*='data[Transcode][BitRate]']").die().live('focusin',function(){
			if($('.tabsHeaderContent').find('li[class*=selected]').find("span").attr('style'))
			{
			}else{
				selectedLi("#snapValue").html($(this).val());
			}
			$(this).focusout(function(){
				if($(this).val() != selectedLi("#snapValue").html()){
					changeLiColor();
					reset_spin_slider();
				}
			});
		});
		
		/**
		* 点击删除水印
		*
		*/
		$("#delWater").die().live('click',function(){
			alertMsg.confirm('确定删除水印吗？删除后请保存！',{okCall:function(){
				selectedLi("#pic_s_font").css('display','none');
				selectedLi("#picUrl").html('');
				selectedLi("#pic_c_font").css('display','none');
				selectedLi("#picWH").html('');
				selectedLi("#water_notice").html('');
				selectedLi("#picBox").css('width','150px');
				selectedLi("#waterWidth").html('');
				selectedLi("#waterHeight").html('');
				reset_spin_slider();
				changeLiColor();
				selectedLi("input[name='data[Transcode][water_file]']").val('');
				selectedLi("#delWater").removeClass().attr('id','overDel').html('已删除水印，保存后生效！').addClass('waterPicdelThen');
			}});
		});
		
		/**
		* 点击显示分片时间
		*/
		$("#fpCheck").die().live('click',function(){
			if(selectedLi("#fpCheck").attr('checked') == 'checked')
			{
				changeLiColor();
				selectedLi("#fpTime").css("display","block");
			}else
			{
				changeLiColor();
				selectedLi("#fpTime").css("display","none");
				selectedLi("input[name='data[Transcode][SliceTime]']").val('');
			}
		});
		
		/**
		* 点击显示水印
		*/
		$("#waterCheck").die().live('click',function(){
			if(selectedLi("#waterCheck").attr('checked') == 'checked')
			{
				changeLiColor();
				selectedLi("#waterTime").css("display","block");
			}else
			{
				changeLiColor();
				reset_spin_slider();
				selectedLi("#waterTime").css("display","none");
			}
		});
		
	});



	////////////////////////////////////////////////////////////////////////////////////////////
	/**
	* 更改选中的滑动TAB的样式为未保存状态
	*
	*/
	function changeLiColor()
	{
		$('.tabsHeaderContent').find('li[class*=selected]').find("span").css('color','#ff0000');
	}
	
	/**
	* 重置滑动条，滑动条下拉，缩略水印的值为0
	*
	*/
	function reset_spin_slider()
	{
		selectedLi("input[name='data[Transcode][StartX]']").val( 0 );
		selectedLi("input[name='data[Transcode][StartY]']").val( 0 );
		selectedLi("div[class='ui-slider-range ui-widget-header ui-slider-range-min']").css("width","0%");
		selectedLi("a[class='ui-slider-handle ui-state-default ui-corner-all']").css("left","0%");
		selectedLi("div[class='waterFile']").css({left:'0px',top:'0px'});
	}
	
	/**
	* 删除TAB
	*
	*/
	function deleteTab(){
		var index = $('.tabsHeaderContent').find('li[class*=selected]').index();
		if(index == 0)
		{
			alertMsg.error('<?php echo __("Can not delete initial Sub-template, select other Sub-template and then delete!");?>');
			return false;
		}
		$('.tabsHeaderContent').find('li[class*=selected]').remove();
		$('.tabsContent>div:eq('+index+')').remove();
		
		$("div.tabs").each(function(){
			var $this = $(this);
			var options = {};
			options.currentIndex = $this.attr("currentIndex") || 0;
			options.eventType = $this.attr("eventType") || "click";
			$this.tabs(options);
		});
	}

    /**
    * 自定TAB内容详细选择器
    * selectedLi()
    */
    function selectedLi( str )
    {
    	var index = $('.tabsHeaderContent').find('li[class*=selected]').index();
    	return $('.tabsContent>div:eq('+index+')').find(''+str+'');
    }

	/**
	* 提交form表单时执行的方法
	*
	*/


    /**
    * form执行函数的回调方法
	*
	*/
	function dialogAjaxDone(json)
	{
		DWZ.ajaxDone(json);
		if (json.statusCode == DWZ.statusCode.ok)
		{
			if(json.id)
			{
				selectedLi("#TranscodeId").val(json.id);
				selectedLi("button[type='delete']").attr("target",json.id);
				$('.tabsHeaderContent').find('li[class=selected]').html('<a href="javascript:;"><span>'+json.name+'</span></a>');
			}    		
		}
	}

</script>

<span class="spannone" id="oldSubNameValue"></span>
<form id="transcodeGroup">
	<fieldset class="fieldAbox">
		<div class="legend">

		</div>
		<div class="templateHeader paraList">
			<span class="t" style="padding-left:10px;">模板名称:</span>
			<span>
				<span style="line-height: 28px;font-weight: bold;" class="title"><?php echo $transcodeGroup['TranscodeGroup']['name'];?></span>
				<?php
				echo $this->Form->input('name', array('label'=>false,'div'=>false, 'class'=>'required','disabled'=>'disabled','id'=>'templateName','name'=>'data[TranscodeGroup][name]','value'=>$transcodeGroup['TranscodeGroup']['name']));
				echo $this->Form->input('cacheId',array('type'=>'hidden','id'=>'cacheId','value'=>$transcodeGroup['TranscodeGroup']['id']));
				echo $this->Form->input('type',array('type'=>'hidden','id'=>'Type','value'=>$transcodeGroup['TranscodeGroup']['type']));
				?>
			</span>
			<style type="text/css">#templateName { display: none; }</style>
			<div class="templateOperate">
				<span onclick="modifyAttr(this,'templateName')" class="t modify-action" style="padding-left:10px; cursor:pointer; color:blue; text-decoration: underline; ">修改名称</span>
				<span onclick="rename()" class="t" style="display:none; padding-left:10px; cursor:pointer; color:blue; text-decoration: underline; width:35px;">保存</span>
				<span onclick="$(this).parent().children().hide();$(this).siblings('.modify-action').show();$('#templateName').attr('disabled','disabled').hide();$('#templateName').siblings('.title').show();" class="t templateNameCancel" style="display:none; padding-left:10px; cursor:pointer; color:blue; text-decoration: underline; width:35px;">取消</span>
			</div>
			<span class="t" style="padding-left:10px;">所属分类:</span>
			<span style="line-height: 28px;">
				<?php
				//echo $this->Form->input('name', array('label'=>false,'div'=>false, 'class'=>'required','disabled'=>'disabled','id'=>'TranscodeCategory','name'=>'data[TranscodeCategory][name]','value'=>$data['TranscodeCategory']['name']));
				?>
				<span style="line-height: 28px;font-weight: bold;" class="title"><?php echo isset($viewCategory[$cid])?$viewCategory[$cid]:'请选择分类';?></span>
				<?php
				echo $this->Form->input('category', array('type'=>'select', 'name'=>'data[TranscodeCategory][name]','options'=>$viewCategory, 'value' => @$cid?:'', 'empty'=>array('0'=>'请选择'), 'label'=>false, 'disabled'=>'disabled', 'class'=>'combox uploadComboxWidth'));?>
				<style type="text/css">#combox_category{ display: none; }</style>
				<!-- <span class="inputInfo">（不选为父类）</span> -->
			</span>
			<div class="templateOperate">
				<span onclick="modifyAttr(this,'combox_category')" class="t modify-action" style="padding-left:10px; cursor:pointer; color:blue; text-decoration: underline; ">修改分类</span>
				<span onclick="renameCategory()" class="t" style="display:none; padding-left:10px; cursor:pointer; color:blue; text-decoration: underline; width:35px;">保存</span>
				<span onclick="$(this).parent().children().hide();$(this).siblings('.modify-action').show();$(this).parent().siblings().find('.title').show();$('#combox_category').hide()" class="t categoryCancel" style="display:none; padding-left:10px; cursor:pointer; color:blue; text-decoration: underline; width:35px;">取消</span>
			</div>
		</div>
	</fieldset>
</form>
<div class="clear"></div>
</div>
<div style="float:left;width:230px;">
	<div class="panel" defH="423">
		<h1>子模板列表
			<a onclick="$('#videoTree div.selected').removeClass()" alt="添加子模板" style="float:right;width:14px;height:14px;margin:12px;" class="adds" rel="transcodeContent" href="<?php echo $this->Html->url('/transcodes/add/'.$transcodeGroup['TranscodeGroup']['type']);?>" target="ajax"></a></h1>
			<div>
				<ul id="videoTree" class="tree expand uploadCategory">
					<?php 
					if (isset($transcodeGroup['Transcode'])) {
					foreach($transcodeGroup['Transcode'] as $key => $blist):
					$isSelected = (@$this->params['pass'][1] == $key+1) || ($key== 0 && !isset($this->params['pass'][1]));
					?>
					<li><a href="<?php echo $this->webroot;?>transcodes/editTranscode/<?php echo $this->params['pass'][0] . "/" . $blist['id']; ?>" rel="transcodeContent" target="ajax" class="categorySelect<?php if($isSelected){echo ' transcodeSelected';}?>" categoryid="<?php echo $blist['id']?>"><?php echo  $key+1 . ") " . $blist['title']?></a>
					</li>
				<?php endforeach;}?>
			</ul> 
		</div>
	</div>
</div>
<div style="float:right;width:710px" class="transcodeContent" layoutH="60">
	<?php echo $this->element('transcodeGroup/detail',array('transcode'=>$transcodeGroup['Transcode'][0],'type'=>$transcodeGroup['TranscodeGroup']['type']));?>
</div>

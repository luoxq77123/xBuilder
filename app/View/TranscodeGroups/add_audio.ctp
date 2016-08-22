<?php 
echo $this->Html->css('ui-lightness/jquery-ui-1.8.20.custom');
echo $this->Html->script('jquery-ui-1.8.20.custom.min');
echo $this->Html->script('jquery-spin');
?>
<script type="text/javascript">

    $(function() {
		/**
		* 失去焦点时，保存模板组
		*
		*/
		$('#templateName').focusout(function(){
			var templateName = $("#templateName").val();
			var templateValue = $("#Type").val();
			var postUrl = null;
			var data = null;
			if(templateName.length > 0){
				if($('#cacheId').val()){
					postUrl = 'TranscodeGroups/edit/' + $('#cacheId').val();
				}else{
					postUrl = 'TranscodeGroups/add_audio';
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
    function validateCallbackT(form, callback) {
    	var $form = $(form);
    	var url = null;
    	if (!$form.valid()) {
    		return false;
    	}
		var reg = new RegExp("^[1-9][0-9]*$");
		if(!$('.tabsHeaderContent').find('li[class*=selected]').find("span").attr('style'))
		{
			alertMsg.error('没有数据修改，不用保存!');
			return false;
		}
    	if(selectedLi("select[name='data[Transcode][FileFormat]']").val() == ''){
    		alertMsg.error('请选择文件格式!');
			return false;
    	}
    	
		////////////////////////////////////////////////////////////////////////////////
		if(selectedLi("select[name='data[Transcode][AudioFormat]']").val() == ''){
    		alertMsg.error('请选择音频编码!');
    		return false;
    	}
		if(selectedLi("select[name='data[Transcode][SamplesPerSec]']").val() == ''){
    		alertMsg.error('请选择采样率!');
    		return false;
    	}
		if(selectedLi("select[name='data[Transcode][BitsPerSample]']").val() == ''){
    		alertMsg.error('请选择采样位率!');
    		return false;
    	}
    	
		if(selectedLi("input[name='fpCheck']").attr('checked') == 'checked'){
    		if(selectedLi("input[name='data[Transcode][SliceTime]']").val() == ''){
    		alertMsg.error('请填写分片时间!');
    		return false;
    		}
			if(!reg.test(selectedLi("input[name='data[Transcode][SliceTime]']").val()))
			{
				alertMsg.error('分片时间格式错误，请填写大于0的数字!');
				return false;
			}
    	}
		
    	if(selectedLi("input[name='waterCheck']").attr('checked') == 'checked')
    	{
			var arrWidth = selectedLi("input[name='data[Transcode][FormatWidth]']").val();
			var arrHeight = selectedLi("input[name='data[Transcode][FormatHeight]']").val();
			var uploadImageWidth = selectedLi('#waterWidth').html();
			var uploadImageHeight = selectedLi('#waterHeight').html();
			if(arrWidth-uploadImageWidth<0 || arrHeight-uploadImageHeight<0)
			{
				alertMsg.error("上传的水印尺寸必须小于幅面大小！");
				return false;
			}
    	}

    	var templateName = $("#templateName").val();
		if(templateName.length < 1){
			alertMsg.error('<?php echo __('The template name can not be empty')?>');
			return false;
		}
		var templateId = $form.find('.TranscodeId').val();
		if(!templateId){
			
			url = $form.attr("action")+'/'+$('#cacheId').val();
		}else{
			url = 'Transcodes/edit/'+$('#cacheId').val()+'/'+templateId;
		}
		
    	$.ajax({
    		type: form.method || 'POST',
    		url:url,
    		data:$form.serializeArray(),
    		dataType:"json",
    		cache: false,
    		success: callback || DWZ.ajaxDone,
    		error: DWZ.ajaxError
    	});
    	return false;
    }

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
				navTab.reloadFlag(json.navTabId);
    		}    		
    	}
    }
</script>
<form id="transcodeGroup">
<div class="templateHeader">
<span class="t" style="padding-left:10px;">模板名称</span>
<span>
<?php
echo $this->Form->input('name', array('label'=>false,'div'=>false, 'class'=>'required i','id'=>'templateName','name'=>'data[TranscodeGroup][name]'));
echo $this->Form->input('cacheId',array('type'=>'hidden','id'=>'cacheId'));
?>
</span>
</form>
<div class="clear"></div>
</div>
<div class="tabs" currentIndex="0" eventType="click">
	<div class="tabsHeader">
		<div class="tabsHeaderContent">
			<span class="add_sub"><a class="tabs_add" href="javascript:;" rel="<?php echo $this->webroot;?>transcodes/add_audio/2"></a></span>
			<ul>
				<li><a href="javascript:;"><span style="color:#ff0000;">new</span></a></li>
			</ul>
		</div>
	</div>
	<div class="tabsContent" id="selectType" style="height:278px;">
		<div>
        
        <!--临时存储地址-->
        <span class="spannone" id="snapValue"></span>
        <span class="spannone" id="waterWidth"></span>
        <span class="spannone" id="waterHeight"></span>
		<?php
			echo $this->Form->create('Transcode',array('url' => array('controller' => 'Transcodes', 'action' => 'add_audio'),'onsubmit'=>'return validateCallbackT(this, dialogAjaxDone)'));
			echo $this->Form->input('id',array('type'=>'hidden','id'=>'TranscodeId', 'class'=>'TranscodeId'));
			echo $this->Form->input('time',array('type'=>'hidden','value'=>$time));
			echo $this->Form->input('type',array('type'=>'hidden','value'=>'2'));
			
			$FileFormatOptions = array(''=>'请选择','WAVE'=>'.wav(WAVE)','WMA'=>'.wma(WMA)','MP3'=>'.mp3(MP3)');
			
			$AudioFormatOptions = array(''=>'请选择');
			$SamplesPerSecOptions = array(''=>'请选择');
			$BitsPerSampleOptions = array(''=>'请选择');
		?>
			<ul class="templateContent" style="height:230px;">
				<li>
					<span class="t">子模板名称</span>
					<span><?php echo $this->Form->input('sub_name',array('id'=>'sub_name', 'label'=>false,'div'=>false, 'class'=>'required'));?></span>
				</li>
				<li class="h"><span>基本参数</span></li>
				<li>
					<span class="t">文件格式：</span>
					<span><?php echo $this->Form->input('FileFormat',array('id'=>'FileFormat', 'options'=>$FileFormatOptions, 'fileFormat_3'=>'data[Transcode][AudioFormat]', 'label'=>false, 'class'=>'combox uploadComboxWidth'))?></span>
				</li>
                
                <li>
                     <div class="fieldAudio">
	                    	<fieldset class="fieldAbox">
	                        	<div class="legend">
	                            	<span class="basicPara">
	                                    <table border="0" cellpadding="0" cellspacing="0" width="180">
	                                        <tr>
	                                            <td width="63">音频编码：</td>
	                                            <td>
	                                             <?php echo $this->Form->input('AudioFormat',array('id'=>'AudioFormat', 'options'=>$AudioFormatOptions, 'audioFile'=>'audioFile', 'label'=>false, 'class'=>'combox uploadComboxWidth'))?>
	                                            </td>
	                                        </tr>
	                                    </table>
	                                </span>
	                            </div>
                                <div class="paraList">
	                                <ul>
                                    <li>
                                        <span class="p">采样率：</span>
										<span class="boxInput"><?php echo $this->Form->input('SamplesPerSec',array('id'=>'SamplesPerSec', 'options'=>$SamplesPerSecOptions,'label'=>false, 'class'=>'combox uploadComboxWidth'))?></span>
                    					
                                        <span class="p">采样位率：</span>
                                        <span><?php echo $this->Form->input('BitsPerSample',array('id'=>'BitsPerSample', 'options'=>$BitsPerSampleOptions,'label'=>false, 'class'=>'combox uploadComboxWidth'))?></span>
                                    </li>
                                    
                                    </ul>
	                            </div>
	                        </fieldset>
	                 </div>
                     
                     <!--分片-->
                     <div class="fileFP">
                        <div class="fpTit">
                            <span class="tit">分片设置</span><input type="checkbox" id="fpCheck" name="data[Transcode][fpCheck]"/>
                        </div>
                        <div id="fpTime" class="fpTime" style="display:none">
                        <table border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td width="64"><span class="timeTit">分片时间：</span></td>
                                <td><?php echo $this->Form->input('SliceTime',array('id'=>'SliceTime', 'label'=>false, 'class'=>'input sortInput'))?></td>
                                <td width="30" align="center"><span class="timemiao">秒</span></td>
                            </tr>
                        </table>
                        </div>
                    </div>                    
                </li>
			</ul>
			<div class="buttons">
				<ul>
					<li><div class="buttonActive"><div class="buttonContent"><button type="submit">保存子模版</button></div></div></li>
					<li><div class="button"><div class="buttonContent"><button id="delButton" type="delete" target="">删除子模版</button></div></div></li>
				</ul>
				<div class="clear"></div>
			</div>
			
			<?php
			echo $this->Form->end();
			?>
		</div>
	</div>
	<div class="tabsFooter">
		<div class="tabsFooterContent"></div>
	</div>
</div>

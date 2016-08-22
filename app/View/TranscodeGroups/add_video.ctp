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
    	if(selectedLi("select[name='data[Transcode][VideoFormat]']").val() == ''){
    		alertMsg.error('请选择视频编码!');
    		return false;
    	}
		if(selectedLi("input[name='data[Transcode][FormatWidth]']").val() == ''){
    		alertMsg.error('请填写幅面宽度!');
    		return false;
    	}
		if(!reg.test(selectedLi("input[name='data[Transcode][FormatWidth]']").val()))
		{
			alertMsg.error('格式错误，填写4的倍数且小于等于1920!');
			return false;
		}
		if(selectedLi("input[name='data[Transcode][FormatWidth]']").val()%4!=0)
		{
			alertMsg.error('幅面宽度只能是4的倍数且小于等于1920!');
			return false;
		}
		if(selectedLi("input[name='data[Transcode][FormatHeight]']").val() == ''){
			alertMsg.error('请填写幅面高度!');
    		return false;
		}
		if(!reg.test(selectedLi("input[name='data[Transcode][FormatHeight]']").val()))
		{
			alertMsg.error('格式错误，填写4的倍数且小于等于1080!');
			return false;
		}
		if(selectedLi("input[name='data[Transcode][FormatHeight]']").val()%4!=0)
		{
			alertMsg.error('幅面高度只能是4的倍数且小于等于1080!');
			return false;
		}
		if(selectedLi("select[name='data[Transcode][ConvertModel]']").val() == ''){
    		alertMsg.error('请选择变换模式!');
    		return false;
    	}
		if(selectedLi("input[name='data[Transcode][BitRate]']").val() == ''){
    		alertMsg.error('请填写码率!');
    		return false;
    	}
		if(!reg.test(selectedLi("input[name='data[Transcode][BitRate]']").val()))
		{
			alertMsg.error('码率格式错误，请填写大于0的数字!');
			return false;
		}
		
		//判断码率输入值格式
		//TS-MPEG2_I(MPEG2_IBP)-------------->>3M-50M
		if(selectedLi("input[name='data[Transcode][BitRate]']").attr("fileformat") == "TS" && (selectedLi("input[name='data[Transcode][BitRate]']").attr("videoformat") == "MPEG2-I" || 
		selectedLi("input[name='data[Transcode][BitRate]']").attr("videoformat") == "MPEG2-IBP"))
		{
			if(selectedLi("#TranscodeIsbpsMbps").attr("checked") == "checked")
			{
				if(selectedLi("input[name='data[Transcode][BitRate]']").val()<3 || selectedLi("input[name='data[Transcode][BitRate]']").val()>50)
				{
					alertMsg.error('码率值范围3M-50M!');
					return false;
				}
				if(selectedLi("#TranscodeIsbpsKbps").attr("checked") == "checked")
				{
					alertMsg.error('请选择mbps!');
					return false;
				}
			}
		}
		
		//AVI(MXF)-MPEG2_I---------->>10M-50M
		if((selectedLi("input[name='data[Transcode][BitRate]']").attr("fileformat") == "MATROX-AVI" || selectedLi("input[name='data[Transcode][BitRate]']").attr("fileformat") == "OP1A-MXF") && selectedLi("input[name='data[Transcode][BitRate]']").attr("videoformat") == "MPEG2-I")
		{
			if(selectedLi("input[name='data[Transcode][BitRate]']").val()<10 || selectedLi("input[name='data[Transcode][BitRate]']").val()>50)
			{
				alertMsg.error('码率值范围10M-50M!');
				return false;
			}
			if(selectedLi("#TranscodeIsbpsKbps").attr("checked") == "checked")
			{
				alertMsg.error('请选择mbps!');
				return false;
			}
		}
		
		//AVI-MPEG2_IBP---------->>8M-50M
		if(selectedLi("input[name='data[Transcode][BitRate]']").attr("fileformat") == "MATROX-AVI" && selectedLi("input[name='data[Transcode][BitRate]']").attr("videoformat") == "MPEG2-IBP")
		{
			if(selectedLi("input[name='data[Transcode][BitRate]']").val()<8 || selectedLi("input[name='data[Transcode][BitRate]']").val()>50)
			{
				alertMsg.error('码率值范围8M-50M!');
				return false;
			}
			if(selectedLi("#TranscodeIsbpsKbps").attr("checked") == "checked")
			{
				alertMsg.error('请选择mbps!');
				return false;
			}
		}
		
		//MXF-MPEG2_IBP
		if(selectedLi("input[name='data[Transcode][BitRate]']").attr("fileformat") == "OP1A-MXF" && selectedLi("input[name='data[Transcode][BitRate]']").attr("videoformat") == "MPEG2-IBP")
		{
			if(selectedLi("input[name='data[Transcode][BitRate]']").val()<8 || selectedLi("input[name='data[Transcode][BitRate]']").val()>51)
			{
				alertMsg.error('码率值范围8M-51M!');
				return false;
			}
			if(selectedLi("#TranscodeIsbpsKbps").attr("checked") == "checked")
			{
				alertMsg.error('请选择mbps!');
				return false;
			}
		}
		
		//3GP(FLV)-H263-------------->>10K-5M
		if((selectedLi("input[name='data[Transcode][BitRate]']").attr("fileformat") == "3GP" || selectedLi("input[name='data[Transcode][BitRate]']").attr("fileformat") == "FLV") && selectedLi("input[name='data[Transcode][BitRate]']").attr("videoformat") == "H263")
		{
			if(selectedLi("#TranscodeIsbpsKbps").attr("checked") == "checked")
			{
				if(selectedLi("input[name='data[Transcode][BitRate]']").val()<10 || selectedLi("input[name='data[Transcode][BitRate]']").val()>1024)
				{
					alertMsg.error('码率值范围10K-5M!');
					return false;
				}
			}
			
			if(selectedLi("#TranscodeIsbpsMbps").attr("checked") == "checked")
			{
				if(selectedLi("input[name='data[Transcode][BitRate]']").val()<1 || selectedLi("input[name='data[Transcode][BitRate]']").val()>5)
				{
					alertMsg.error('码率值范围10K-5M!');
					return false;
				}
			}
		}
		
		//3GP(FLV,TS)-H264(MPEG4_XVID)----------->>10K-10M
		if((selectedLi("input[name='data[Transcode][BitRate]']").attr("fileformat") == "3GP" || 
		selectedLi("input[name='data[Transcode][BitRate]']").attr("fileformat") == "TS" || 
		selectedLi("input[name='data[Transcode][BitRate]']").attr("fileformat") == "MP4") &&
		 (selectedLi("input[name='data[Transcode][BitRate]']").attr("videoformat") == "H264" || 
		 selectedLi("input[name='data[Transcode][BitRate]']").attr("videoformat") == "MPEG4-XVID"))
		{
			if(selectedLi("#TranscodeIsbpsKbps").attr("checked") == "checked")
			{
				if(selectedLi("input[name='data[Transcode][BitRate]']").val()<10 || selectedLi("input[name='data[Transcode][BitRate]']").val()>1024)
				{
					alertMsg.error('码率值范围10K-10M!');
					return false;
				}
			}
			
			if(selectedLi("#TranscodeIsbpsMbps").attr("checked") == "checked")
			{
				if(selectedLi("input[name='data[Transcode][BitRate]']").val()<1 || selectedLi("input[name='data[Transcode][BitRate]']").val()>10)
				{
					alertMsg.error('码率值范围10K-10M!');
					return false;
				}
			}
		}
		
		//3GP(FLV)-H264-2PASS-------------->>10K-20M
		if((selectedLi("input[name='data[Transcode][BitRate]']").attr("fileformat") == "3GP" || 
		selectedLi("input[name='data[Transcode][BitRate]']").attr("fileformat") == "FLV" || 
		selectedLi("input[name='data[Transcode][BitRate]']").attr("fileformat") == "F4V" || 
		selectedLi("input[name='data[Transcode][BitRate]']").attr("fileformat") == "MP4") && (selectedLi("input[name='data[Transcode][BitRate]']").attr("videoformat") == "H264-2PASS" || 
		selectedLi("input[name='data[Transcode][BitRate]']").attr("videoformat") == "H264"))
		{
			if(selectedLi("#TranscodeIsbpsKbps").attr("checked") == "checked")
			{
				if(selectedLi("input[name='data[Transcode][BitRate]']").val()<10 || selectedLi("input[name='data[Transcode][BitRate]']").val()>1024)
				{
					alertMsg.error('码率值范围10K-20M!');
					return false;
				}
			}
			
			if(selectedLi("#TranscodeIsbpsMbps").attr("checked") == "checked")
			{
				if(selectedLi("input[name='data[Transcode][BitRate]']").val()<1 || selectedLi("input[name='data[Transcode][BitRate]']").val()>20)
				{
					alertMsg.error('码率值范围10K-20M!');
					return false;
				}
			}
		}
		
		//TS-H264-2PASS-------------->>100K-10M
		if(selectedLi("input[name='data[Transcode][BitRate]']").attr("fileformat") == "TS" && selectedLi("input[name='data[Transcode][BitRate]']").attr("videoformat") == "H264-2PASS")
		{
			if(selectedLi("#TranscodeIsbpsKbps").attr("checked") == "checked")
			{
				if(selectedLi("input[name='data[Transcode][BitRate]']").val()<100 || selectedLi("input[name='data[Transcode][BitRate]']").val()>1024)
				{
					alertMsg.error('码率值范围100K-10M!');
					return false;
				}
			}
			
			if(selectedLi("#TranscodeIsbpsMbps").attr("checked") == "checked")
			{
				if(selectedLi("input[name='data[Transcode][BitRate]']").val()<1 || selectedLi("input[name='data[Transcode][BitRate]']").val()>10)
				{
					alertMsg.error('码率值范围100K-10M!');
					return false;
				}
			}
		}
		
		//TS-H264_XVID-------------->>400K-10M
		if(selectedLi("input[name='data[Transcode][BitRate]']").attr("fileformat") == "TS" && selectedLi("input[name='data[Transcode][BitRate]']").attr("videoformat") == "MPEG4-XVID")
		{
			if(selectedLi("#TranscodeIsbpsKbps").attr("checked") == "checked")
			{
				if(selectedLi("input[name='data[Transcode][BitRate]']").val()<400 || selectedLi("input[name='data[Transcode][BitRate]']").val()>1024)
				{
					alertMsg.error('码率值范围400K-10M!');
					return false;
				}
			}
			
			if(selectedLi("#TranscodeIsbpsMbps").attr("checked") == "checked")
			{
				if(selectedLi("input[name='data[Transcode][BitRate]']").val()<1 || selectedLi("input[name='data[Transcode][BitRate]']").val()>10)
				{
					alertMsg.error('码率值范围400K-10M!');
					return false;
				}
			}
		}
		
		//RMVB-Video Real-------------->>400K-20M
		if(selectedLi("input[name='data[Transcode][BitRate]']").attr("fileformat") == "RMVB" && selectedLi("input[name='data[Transcode][BitRate]']").attr("videoformat") == "VIDEO-REAL")
		{
			if(selectedLi("#TranscodeIsbpsKbps").attr("checked") == "checked")
			{
				if(selectedLi("input[name='data[Transcode][BitRate]']").val()<400 || selectedLi("input[name='data[Transcode][BitRate]']").val()>1024)
				{
					alertMsg.error('码率值范围400K-20M!');
					return false;
				}
			}
			
			if(selectedLi("#TranscodeIsbpsMbps").attr("checked") == "checked")
			{
				if(selectedLi("input[name='data[Transcode][BitRate]']").val()<1 || selectedLi("input[name='data[Transcode][BitRate]']").val()>20)
				{
					alertMsg.error('码率值范围400K-20M!');
					return false;
				}
			}
		}
		
		
		if(selectedLi("select[name='data[Transcode][FrameRate]']").val() == ''){
    		alertMsg.error('请选择帧率!');
    		return false;
    	}
		
		if(selectedLi("input[name='data[Transcode][Gop]']").attr("isValueTwelve") == 'Twelve')
		{
			if(selectedLi("input[name='data[Transcode][Gop]']").val() == ''){
				alertMsg.error('请填写GOP!');
				return false;
			}
			if(!reg.test(selectedLi("input[name='data[Transcode][Gop]']").val()))
			{
				alertMsg.error('Gop格式错误，请填写大于0的数字!');
				return false;
			}
		}
		////////////////////////////////////////////////////////////////////////////////
		if(selectedLi("select[name='data[Transcode][AudioFormat]']").val() == ''){
    		alertMsg.error('请选择音频编码!');
    		return false;
    	}
		
		if(selectedLi("select[name='data[Transcode][SamplesPerSec]']").attr("isNull") == "notNull"){
    		if(selectedLi("select[name='data[Transcode][SamplesPerSec]']").val() == ""){
    			alertMsg.error('请选择采样率!');
    			return false;
    		}
    	}
		
		if(selectedLi("select[name='data[Transcode][BitsPerSample]']").attr("isNull") == "notNull"){
    		if(selectedLi("select[name='data[Transcode][BitsPerSample]']").val() == ""){
    			alertMsg.error('请选择采样位率!');
    			return false;
    		}
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

    	if(selectedLi("#waterCheck").attr('checked') == 'checked')
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
			<span class="add_sub"><a class="tabs_add" href="javascript:;" rel="<?php echo $this->webroot;?>transcodes/add_video/1"></a></span>
			<ul>
				<li><a href="javascript:;"><span style="color:#ff0000;">new</span></a></li>
			</ul>
		</div>
	</div>
	<div class="tabsContent" id="selectType">
		<div>
        
        <!--临时存储地址-->
        <span class="spannone" id="snapValue"></span>
        <span class="spannone" id="waterWidth"></span>
        <span class="spannone" id="waterHeight"></span>
		<?php
			echo $this->Form->create('Transcode',array('url' => array('controller' => 'Transcodes', 'action' => 'add_video'),'onsubmit'=>'return validateCallbackT(this, dialogAjaxDone)'));
			echo $this->Form->input('id',array('type'=>'hidden','id'=>'TranscodeId', 'class'=>'TranscodeId'));
			echo $this->Form->input('water_file',array('type'=>'hidden','class'=>'water_file','id'=>'water'));
			echo $this->Form->input('time',array('type'=>'hidden','value'=>$time));
			echo $this->Form->input('type',array('type'=>'hidden','value'=>'1'));
			
			//$FileFormatOptions = array(''=>'请选择','MATROX-AVI'=>'.avi(MATROX_AVI)','3GP'=>'.3gp(3GP)','TS'=>'.ts(TS)','PS'=>'.mpg(PS)','MP4'=>'.mp4(MP4)','FLV'=>'.flv(FLV)','OP1A-MXF'=>'.mxf(OP1A_MXF)','F4V'=>'.f4v(F4V)','RMVB'=>'.rmvb(RMVB)','MS-WMV'=>'.wmv(MS_WMV)','AVCHD'=>'.m2ts(AVCHD)','QUICKTIME'=>'.mov(QUICKTIME)');
			$FileFormatOptions = array(''=>'请选择','2099'=>'.ts(TS)','2103'=>'.mp4(MP4)','2226'=>'.flv(FLV)');
		?>
			<ul class="templateContent">
				<li>
					<span class="t">子模板名称</span>
					<span><?php echo $this->Form->input('sub_name',array('id'=>'sub_name', 'label'=>false,'div'=>false, 'class'=>'required'));?></span>
				</li>
				<li class="h"><span>基本参数</span></li>
				<li>
					<span class="t">文件格式：</span>
					<span><?php echo $this->Form->input('FileFormat',array('id'=>'FileFormat', 'options'=>$FileFormatOptions, 'label'=>false, 'fileFormat_1'=>'data[Transcode][VideoFormat]', 'fileFormat_2'=>'data[Transcode][AudioFormat]', 'class'=>'combox uploadComboxWidth'))?></span>
				</li>
                
                <li>
                	<div class="fieldVideo">
	                    	<fieldset class="fieldVbox">
	                        	<div class="legend">
	                            	<span class="basicPara">
	                                    <table border="0" cellpadding="0" cellspacing="0" width="180">
	                                        <tr>
	                                            <td width="63">视频编码：</td>
	                                            <td>
	                                             <?php echo $this->Form->input('VideoFormat',array('id'=>'VideoFormat', 'options'=>array(''=>'请选择'), 'video_Allother'=>'allInput', 'label'=>false, 'class'=>'combox uploadComboxWidth'))?>
	                                            </td>
	                                        </tr>
	                                    </table>
	                                </span>
	                            </div>
                                <div class="paraList">
	                                <ul>
                                    <li>
                                        <span class="p">幅面：</span>
										<span class="boxInput">
										<table border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td><?php echo $this->Form->input('FormatWidth',array('id'=>'FormatWidth','label'=>false, 'class'=>'input sortInput'))?></td>
                                            <td><span class="x">X</span></td>
                                            <td><?php echo $this->Form->input('FormatHeight',array('id'=>'FormatHeight', 'label'=>false, 'class'=>'input sortInput'))?></td>
                                            <td><span>(数字)</span></td>
                                          </tr>
                                        </table>
                                        </span>

                                        <span class="p">幅面适配：</span>
                                        <span><?php echo $this->Form->input('WidthRatio',array('id'=>'WidthRatio', 'options'=>array(''=>'请选择','0'=>'自定义高宽比','1'=>'以高为基准','2'=>'保持原面幅'), 'label'=>false, 'class'=>'combox uploadComboxWidth'))?></span>
                    					
                                        <span class="p">变换模式：</span>
                                        <span><?php echo $this->Form->input('ConvertModel',array('id'=>'ConvertModel', 'options'=>array(''=>'请选择'), 'label'=>false, 'class'=>'combox uploadComboxWidth'))?></span>
                                    </li>
                                    <li>
                                    	<span class="p">码率：</span>
                                        <span class="boxInput">
										<table border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td><?php echo $this->Form->input('BitRate',array('id'=>'BitRate', 'label'=>false, 'class'=>'input'))?></td>
                                            <td><span>kbps</span></td>
                                          </tr>
                                        </table>
										</span>

                                        <span class="p">帧率：</span>
                                        <span><?php echo $this->Form->input('FrameRate',array('id'=>'FrameRate', 'options'=>array(''=>'请选择'), 'label'=>false, 'class'=>'combox uploadComboxWidth'))?></span>
                                        
                                        <span class="p">GOP：</span>
                                        <span><?php echo $this->Form->input('Gop',array('id'=>'Gop', 'label'=>false, 'class'=>'input sortInput'))?></span>
                                    </li>
                                    </ul>
	                            </div>
	                        </fieldset>
	                 </div>
                     <div class="fieldAudio">
	                    	<fieldset class="fieldAbox">
	                        	<div class="legend">
	                            	<span class="basicPara">
	                                    <table border="0" cellpadding="0" cellspacing="0" width="180">
	                                        <tr>
	                                            <td width="63">音频编码：</td>
	                                            <td>
	                                             <?php echo $this->Form->input('AudioFormat',array('id'=>'AudioFormat', 'options'=>array('null'=>'请选择'), 'audio_Allother'=>'allInput', 'label'=>false, 'class'=>'combox uploadComboxWidth'))?>
	                                            </td>
	                                        </tr>
	                                    </table>
	                                </span>
	                            </div>
                                <div class="paraList">
	                                <ul>
	                                    <li>
	                                        <span class="p">采样率：</span>
											<span class="boxInput"><?php echo $this->Form->input('SamplesPerSec',array('id'=>'SamplesPerSec', 'options'=>array('null'=>'请选择'), 'label'=>false, 'class'=>'combox uploadComboxWidth'))?></span>
	                    					
	                                        <span class="p">采样位率：</span>
	                                        <span><?php echo $this->Form->input('BitsPerSample',array('id'=>'BitsPerSample', 'options'=>array('null'=>'请选择'), 'label'=>false, 'class'=>'combox uploadComboxWidth'))?></span>
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

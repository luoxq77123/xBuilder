<div class="transcodeDetail">
	<?php if(!$transcode):?>
		没有子模板，请添加子模板！
	<?php else:
		echo $this->Form->create('Transcode',array('class'=>'pageForm required-validate', 'onsubmit'=>'return validateCallbackT(this, dialogAjaxDoneT)','inputDefaults'=>array('div'=>false,'label'=>false)));
		echo $this->Form->input('id',array('type'=>'hidden'));
	?>
	<ul class="templateContent" layoutH="112">
		<li>
			<span class="t">子模板名称</span>
			<span><?php echo $this->Form->input('title');?></span>
		</li>
		<li class="h"><span>基本参数</span></li>
		<li>
			<div class="fieldVideo">
				<fieldset class="fieldVbox">
					<div class="legend">
						<span class="basicPara">
							<table border="0" cellpadding="0" cellspacing="0">
								<tr>
									<th>文件格式：</th>
									<td>
										<?php echo $this->Form->input('FileFormat',array('options'=>$FileFormatOptions, 'class'=>'combox','empty'=>array('0'=>'请选择'),'ref'=>'TranscodeVideoFormat','refUrl'=>$this->Html->url('/xmlAnalysis/getVideoList/{value}'),'target'=>'fileValues','selectUrl'=>$this->Html->url('/XmlAnalysis/getFileParam/{value}')))?>
									</td>
								</tr>
							</table>
						</span>
					</div>
					<div class="paraList" id="fileValues">
					</div>
				</fieldset>
			</div>
		<?php if($type == 1):?>
			<div class="fieldVideo">
				<fieldset class="fieldVbox">
					<div class="legend">
						<span class="basicPara">
							<table border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td>视频编码：</td>
									<td>
										<?php echo $this->Form->input('VideoFormat',array('type'=>'select','empty'=>array('0'=>'请选择'), 'class'=>'combox','ref'=>'TranscodeAudioFormat','refUrl'=>$this->Html->url('/xmlAnalysis/getAudioList/{value}'),'target'=>'videoValues','selectUrl'=>$this->Html->url('/XmlAnalysis/getVideoParam/{value}')))?>
									</td>
								</tr>
							</table>
						</span>
					</div>
					<div class="paraList" id="videoValues">
					</div>
				</fieldset>
			</div>
		<?php endif;?>
			<div class="fieldVideo">
				<fieldset class="fieldVbox">
					<div class="legend">
						<span class="basicPara">
							<table border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td>音频编码：</td>
									<td>
										<?php echo $this->Form->input('AudioFormat',array('type'=>'select','empty'=>array('0'=>'请选择'), 'class'=>'combox','target'=>'audioValues','selectUrl'=>$this->Html->url('/XmlAnalysis/getAudioParam/{value}')))?>
									</td>
								</tr>
							</table>
						</span>
					</div>
					<div class="paraList" id="audioValues">
					</div>
				</fieldset>
			</div>
			<!-- <div class="fileFP">
				<div class="fpTit">
					<span class="tit">分片设置</span><input type="checkbox" id="fpCheck"<?php //if(@$params['Transcode']['fpCheck'] == 'on'){echo "checked";}?> name="data[Transcode][fpCheck]"/>
				</div>
				<div id="fpTime" class="fpTime" style="<?php //if(@$params['Transcode']['fpCheck'] == 'on'){echo 'display:block';}else{echo 'display:none';}?>">
					<table border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td width="64"><span class="timeTit">分片时间：</span></td>
							<td><?php //echo $this->Form->input('SliceTime',array('id'=>'SliceTime', 'value'=>'', 'label'=>false, 'class'=>'input sortInput'))?></td>
							<td width="30" align="center"><span class="timemiao">秒</span></td>
						</tr>
					</table>
				</div>
			</div> -->
		</li>
	</ul>
	<div class="buttons">
		<ul>
			<li><div class="buttonActive"><div class="buttonContent"><button type="submit">保存子模版</button></div></div></li>
			<li><div class="button"><div class="buttonContent"><button id="delButton" type="delete" target="">删除子模版</button></div></div></li>
		</ul>
		<div class="clear"></div>
	</div>
	<?php echo $this->Form->end();?>
	<?php endif;?>
</div>

<script type="text/javascript">
	/*$(function(){
		$("#FileFormat").live('change',function(){
			var file = $("#FileFormat").val();
			if(!file){
				alertMsg.error('请选择文件!');
	    		return false;
			}
			$('#getvideolist').ajaxUrl({'type':'post','url':'XmlAnalysis/getVideoList','data':{file:file}});
			$('#filevalue').ajaxUrl({'type':'post','url':'XmlAnalysis/getFileValue','data':{file:file}});
			$('#getaudiolist').ajaxUrl({'type':'post','url':'XmlAnalysis/getAudioList'});
			$('#getaudiovalue').ajaxUrl({'type':'post','url':'XmlAnalysis/getAudioValue'});
			$('#videovalue.paraList').ajaxUrl({'type':'post','url':'XmlAnalysis/getVideoValue'});
		});
		$("#VideoFormat").live('change',function(){
			var video = $("#VideoFormat").val();
			if(!video){
				alertMsg.error('请选择视频!');
	    		return false;
			}
			$('#videovalue.paraList').ajaxUrl({'type':'post','url':'XmlAnalysis/getVideoValue','data':{video:video}});
	// 		$('#videovalue.paraList').ajaxUrl({'type':'post','url':'XmlAnalysis/getVideoValue','data':{video:video},'callback':function(a){
	// 			$('#VideoParam').ajaxUrl({'type':'post','url':'XmlAnalysis/getParamHtml','data':{video:video}});
	// 		}});
			$('#getaudiolist').ajaxUrl({'type':'post','url':'XmlAnalysis/getAudioList','data':{video:video}});
		});
		$("#AudioFormat").live('change',function(){
			var audio = $("#AudioFormat").val();
			if(!audio){
				alertMsg.error('请选择音频!');
	    		return false;
			}
			$('#getaudiovalue').ajaxUrl({'type':'post','url':'XmlAnalysis/getAudioValue','data':{audio:audio}});
		});
	});*/

	function validateCallbackT(form, callback) {
		var $form = $(form);
		var url = $form.attr('action');
		
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

	function dialogAjaxDoneT(json){
		DWZ.ajaxDone(json);
		if (json.statusCode == DWZ.statusCode.ok){
			if (json.rel){
				$('#'+json.rel).loadUrl('<?php echo $this->Html->url("/transcodes/ajaxlist/");?>'+json.tgId+'/'+json.tid);
			}
			if ("closeCurrent" == json.callbackType) {
				$.pdialog.closeCurrent();
				if(json.reload){
					setTimeout("window.location.reload()",3000);
				}
			}
		}
	}
</script>
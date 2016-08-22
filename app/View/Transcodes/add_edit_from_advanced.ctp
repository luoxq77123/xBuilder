<?php if(!$this->request->data):?>
		没有子模板，请添加子模板！
<?php else:
	echo $this->Form->create('Transcode',array('class'=>'pageForm required-validate', 'onsubmit'=>'return validateCallbackT(this, dialogAjaxDoneT)','inputDefaults'=>array('div'=>false,'label'=>false)));
	echo $this->Form->input('id',array('type'=>'hidden'));
	echo $this->Form->input('transcode_group_id',array('type'=>'hidden','value'=> @$transcodeGroup['TranscodeGroup']['id']?:$this->request->data['TranscodeGroup']['id']));
?>
	<ul class="templateContent" layoutH="112">
		<li>
			<span class="t">子模板名称</span>
			<span><?php echo $this->Form->input('title');?></span>
		</li>
		<li>
			<dl>
				<dt>基本参数</dt>
				<div class="contentMore">
					<dd>
						<table border="0" cellpadding="0" cellspacing="0">
							<tr>
								<th>文件格式:</th>
								<td>
									<?php echo $this->Form->input('FileFormat',array('options'=>$FileFormatOptions, 'class'=>'combox','empty'=>array('0'=>'请选择'),'ref'=>'TranscodeVideoFormat','refUrl'=>$this->Html->url('/xmlAnalysis/getVideoList/{value}'),'target'=>'fileValues','selectUrl'=>$this->Html->url('/XmlAnalysis/getFileParam/{value}')))?>
								</td>
							</tr>
						</table>
					</dd>
					<dd>
						<table border="0" cellpadding="0" cellspacing="0">
							<tr>
								<th>视频编码格式:</th>
								<td>
									<?php echo $this->Form->input('VideoFormat',array('options'=>@$VideoFormatOptions,'type'=>'select','empty'=>array('0'=>'请选择'), 'class'=>'combox','ref'=>'TranscodeAudioFormat','refUrl'=>$this->Html->url('/xmlAnalysis/getAudioList/{value}'),'target'=>'videoValues','selectUrl'=>$this->Html->url('/XmlAnalysis/getVideoParam/{value}/0'),'basicTarget'=>'videoBasicValues','basicSelectUrl'=>$this->Html->url('/XmlAnalysis/getVideoParam/{value}/1')))?>
								</td>
							</tr>
						</table>
					</dd>
					<dd>
						<table border="0" cellpadding="0" cellspacing="0">
							<tr>
								<th>音频编码格式:</th>
								<td>
									<?php echo $this->Form->input('AudioFormat',array('options'=>@$AudioFormatOptions,'type'=>'select','empty'=>array('0'=>'请选择'), 'class'=>'combox','target'=>'audioValues','selectUrl'=>$this->Html->url('/XmlAnalysis/getAudioParam/{value}/0'),'basicTarget'=>'audioBasicValues','basicSelectUrl'=>$this->Html->url('/XmlAnalysis/getAudioParam/{value}/1')))?>
								</td>
							</tr>
						</table>
					</dd>
				</div>
			</dl>
		</li>
		<li>
			<dl>
				<dt>视频参数</dt>
				<div id="videoBasicValues" class="contentMore">
					<?php 
					if(@$this->request->data['video'] && isset($video)):
						$videoKey = array_keys($this->request->data['video']);
				        foreach ($video['Param'] as $i=>$j):
				        	if($j['basic'] == 0) continue;
				            $inputOptions = array('label'=>false,'div'=>false);
				            $inputOptions['type'] = $j['type']?:'text';
				            $inputOptions['name'] = "data[video][".$j['name']."]";
				            $inputOptions['id'] = "video".$j['name'];

				            if($j['type']=='select'){
				                $inputOptions['options'] = json_decode($j['options'],true);
				                $inputOptions['class'] = 'combox';
				            }

				            if(in_array($j['name'], $videoKey)){
				            	$inputOptions['value'] = $this->request->data['video'][$j['name']];
				            }
					?>
					<dd <?php if($j['name'] == 'FormatWidth'):?> class="doble"<?php endif;?>>
						<table border="0" cellpadding="0" cellspacing="0">
							<tr>
								<?php if(@$j['c_name']):?><th><?php echo $j['c_name'];?></th><?php endif;?>
								<td>
									<?php if($j['start_str']):?><span><?php echo $j['start_str'];?></span><?php endif;?>
					            	<?php echo $this->Form->input($j['name'],$inputOptions);?>
					            	<?php if($j['end_str']):?><span class="e"><?php echo $j['end_str'];?></span><?php endif;?>
								</td>
							</tr>
						</table>
					</dd>
					<?php endforeach;endif;?>
				</div>
				
			</dl>
		</li>
		<li>
			<dl>
				<dt>音频参数</dt>
				<div id="audioBasicValues" class="contentMore">
					<?php 
					if(@$this->request->data['audio'] && isset($audio)):
				    	$audioKey = array_keys($this->request->data['audio']);
				        foreach ($audio['Param'] as $i=>$j):
				        	if($j['basic'] == 0) continue;
				            $inputOptions = array('label'=>false,'div'=>false);
				            $inputOptions['type'] = $j['type']?:'text';
				            $inputOptions['name'] = "data[audio][".$j['name']."]";
				            $inputOptions['id'] = "audio".$j['name'];

				            if($j['type']=='select'){
				                $inputOptions['options'] = json_decode($j['options'],true);
				                $inputOptions['class'] = 'combox';
				            }

				            if(in_array($j['name'], $audioKey)){
				            	$inputOptions['value'] = $this->request->data['audio'][$j['name']];
				            }
					?>
				    <dd>
				    	<table border="0" cellpadding="0" cellspacing="0">
							<tr>
								<?php if(@$j['c_name']):?><th><?php echo $j['c_name'];?></th><?php endif;?>
								<td>
									<?php if($j['start_str']):?><span><?php echo $j['start_str'];?></span><?php endif;?>
					            <?php echo $this->Form->input($j['name'],$inputOptions);?>
					            <?php if($j['end_str']):?><span class="e"><?php echo $j['end_str'];?></span><?php endif;?>
								</td>
							</tr>
						</table>
				    </dd>
					<?php endforeach;endif;?>
				</div>
			</dl>
		</li>
		<li>
			<dl>
				<dt class="expand">高级参数</dt>
				<div class="contentMore" style="display:none;">
					<dd class="fieldVideo">
						<fieldset class="fieldVbox">
							<div class="legend">
								<span class="basicPara">文件格式参数:</span>
							</div>
							<div class="paraList" id="fileValues">
								<?php 
									if(@$this->request->data['file'] && isset($file)):
								?>
									<table border="0" cellpadding="0" cellspacing="0">
										<tr>
										<?php 
											$k = 1;
									    	$fileKey = array_keys($this->request->data['file']);
									        foreach ($file['Param'] as $i=>$j):
									        	if($j['basic'] == 1) continue;
									            $inputOptions = array('label'=>false,'div'=>false);
									            $inputOptions['type'] = $j['type']?:'text';
									            $inputOptions['name'] = "data[file][".$j['name']."]";
									            $inputOptions['id'] = "file_".$j['name'];

									            if($j['type']=='select'){
									                $inputOptions['options'] = json_decode($j['options'],true);
									                $inputOptions['class'] = 'combox';
									            }
									            if(in_array($j['name'], $fileKey)){
									            	$inputOptions['value'] = $this->request->data['file'][$j['name']];
									            }
									    ?>
											<?php if(@$j['c_name']):?><th class="file"><?php echo $j['c_name'];?></th><?php endif;?>
											<td>
												<?php if($j['start_str']):?><span><?php echo $j['start_str'];?></span><?php endif;?>
									            <?php echo $this->Form->input($j['name'],$inputOptions);?>
									            <?php if($j['end_str']):?><span class="e"><?php echo $j['end_str'];?></span><?php endif;?>
											</td>
										<?php 
											if($k%6 == 0) echo '</tr><tr>';
											$k++;
											endforeach;
										?>
										</tr>
									</table>
								<?php endif;?>
							</div>
						</fieldset>
					</dd>
					<dd class="fieldVideo">
						<fieldset class="fieldVbox">
							<div class="legend">
								<span class="basicPara">视频格式参数:</span>
							</div>
							<div class="paraList" id="videoValues">
								<?php if(@$this->request->data['video'] && isset($video)):?>
									<table border="0" cellpadding="0" cellspacing="0">
										<tr>
										<?php 
									    	$k = 1;
									        $videoKey = array_keys($this->request->data['video']);
									        foreach ($video['Param'] as $i=>$j):
									        	if($j['basic'] == 1) continue;
									            $inputOptions = array('label'=>false,'div'=>false);
									            $inputOptions['type'] = $j['type']?:'text';
									            $inputOptions['name'] = "data[video][".$j['name']."]";
									            $inputOptions['id'] = "video".$j['name'];

									            if($j['type']=='select'){
									                $inputOptions['options'] = json_decode($j['options'],true);
									                $inputOptions['class'] = 'combox';
									            }

									            if(in_array($j['name'], $videoKey)){
									            	$inputOptions['value'] = $this->request->data['video'][$j['name']];
									            }
									    ?>
											<?php if(@$j['c_name']):?><th class="video"><?php echo $j['c_name'];?></th><?php endif;?>
											<?php if(!in_array($j['name'], array('sar_height','dest_height'))):?><td><?php endif;?>
												<?php if($j['start_str']):?><span><?php echo $j['start_str'];?></span><?php endif;?>
									            <?php echo $this->Form->input($j['name'],$inputOptions);?>
									            <?php if($j['end_str']):?><span class="e"><?php echo $j['end_str'];?></span><?php endif;?>
											<?php if(!in_array($j['name'], array('sar_width','dest_width'))):?></td><?php endif;?>
										<?php 
											if($k%3 == 0) echo '</tr><tr>';
											if(!in_array($j['name'], array('sar_width','dest_width')))$k++;
											endforeach;
										?>
										</tr>
									</table>
								<?php endif;?>
							</div>
						</fieldset>
					</dd>
					<dd class="fieldVideo">
						<fieldset class="fieldVbox">
							<div class="legend">
								<span class="basicPara">音频格式参数:</span>
							</div>
							<div class="paraList" id="audioValues">
								<?php if(@$this->request->data['audio'] && isset($audio)):?>
									<table border="0" cellpadding="0" cellspacing="0">
										<tr>
											<?php 
												$k = 1;
										    	$audioKey = array_keys($this->request->data['audio']);
										        foreach ($audio['Param'] as $i=>$j):
										        	if($j['basic'] == 1) continue;
										            $inputOptions = array('label'=>false,'div'=>false);
										            $inputOptions['type'] = $j['type']?:'text';
										            $inputOptions['name'] = "data[audio][".$j['name']."]";
										            $inputOptions['id'] = "audio".$j['name'];

										            if($j['type']=='select'){
										                $inputOptions['options'] = json_decode($j['options'],true);
										                $inputOptions['class'] = 'combox';
										            }

										            if(in_array($j['name'], $audioKey)){
										            	$inputOptions['value'] = $this->request->data['audio'][$j['name']];
										            }
										    ?>
											    <?php if(@$j['c_name']):?><th class="audio"><?php echo $j['c_name'];?></th><?php endif;?>
												<td>
													<?php if($j['start_str']):?><span><?php echo $j['start_str'];?></span><?php endif;?>
										            <?php echo $this->Form->input($j['name'],$inputOptions);?>
										            <?php if($j['end_str']):?><span class="e"><?php echo $j['end_str'];?></span><?php endif;?>
												</td>
											<?php 
												if($k%3 == 0) echo '</tr><tr>';
												$k++;
												endforeach;
											?>
										</tr>
									</table>
								<?php endif;?>
							</div>
						</fieldset>
					</dd>
				</div>
			</dl>
		</li>
	</ul>
	<div class="buttons">
		<ul>
			<li><div class="buttonActive"><div class="buttonContent"><button type="submit">保存子模版</button></div></div></li>
			<li><div class="button"><div class="buttonContent"><button id="delButton" type="delete" data-id="<?php echo @$this->request->data['Transcode']['id'];?>" data-tgid="<?php echo @$transcodeGroup['TranscodeGroup']['id']?:$this->request->data['TranscodeGroup']['id']?>">删除子模版</button></div></div></li>
		</ul>
		<div class="clear"></div>
	</div>
	<?php echo $this->Form->end();?>
	<?php endif;?>

<script type="text/javascript">	
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
			if (json.tgId && json.tid){
				$('#transcodeTree').loadUrl('<?php echo $this->Html->url("/transcodes/ajaxlist/");?>'+json.tgId+'/'+json.tid);
				$('#delButton').data('id',json.tid);
			}
			if ("closeCurrent" == json.callbackType) {
				$.pdialog.closeCurrent();
				if(json.reload){
					setTimeout("window.location.reload()",3000);
				}
			}
		}
	}

	$(function(){
		$('.templateContent li dl dt').click(function(){
			var $this = $(this);
			if($this.hasClass('expand')){
				$this.removeClass('expand');
				$this.siblings('.contentMore').show();
			}else{
				$this.addClass('expand');
				$this.siblings('.contentMore').hide();
			}
			return false;
		});
	})
</script>
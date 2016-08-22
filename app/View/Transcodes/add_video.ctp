<div style="border-width:1px;" class="tabsContent"<?php if($data['TranscodeGroup']['type'] == 2){echo' style=""';}?>>
		<?php if($data['TranscodeGroup']['type'] == 1){?>
		<?php if(count($data['Transcode'])>0)
		{?>
			<div>
				<!--临时存储地址-->
<!-- 				<span class="spannone" id="snapValue"></span> -->
				<span class="spannone" id="waterWidth"><?php echo $params['Transcode']['ObjWidth'];?></span>
				<span class="spannone" id="waterHeight"><?php echo $params['Transcode']['ObjHeight'];?></span>
				<?php
				echo $this->Form->create('Transcode',array('url' => array('controller' => 'Transcodes', 'action' => 'add_video'), 'class'=>'pageForm required-validate', 'onsubmit'=>'return validateCallbackT(this, dialogAjaxDone,'.$data['Transcode']['id'].')'));
// 				echo $this->Form->input('id',array('type'=>'hidden','id'=>'TranscodeId', 'value'=>$vs['id']));
// 				echo $this->Form->input('time',array('type'=>'hidden','value'=>$time));
// 				echo $this->Form->input('type',array('type'=>'hidden','id'=>'TranscodeType', 'value'=>$params['Transcode']['type']));
// 				echo @$this->Form->input('water_file',array('type'=>'hidden','class'=>'water_file','id'=>'water', 'value'=>$params['Transcode']['water_file']));
// 				echo @$this->Form->input('ObjWidth',array('type'=>'hidden', 'value'=>$params['Transcode']['ObjWidth']));
// 				echo @$this->Form->input('ObjHeight',array('type'=>'hidden', 'value'=>$params['Transcode']['ObjHeight']));

			//$FileFormatOptions = array(''=>'请选择','MATROX-AVI'=>'.avi(MATROX_AVI)','3GP'=>'.3gp(3GP)','TS'=>'.ts(TS)','PS'=>'.mpg(PS)','MP4'=>'.mp4(MP4)','FLV'=>'.flv(FLV)','OP1A-MXF'=>'.mxf(OP1A_MXF)','F4V'=>'.f4v(F4V)','RMVB'=>'.rmvb(RMVB)','MS-WMV'=>'.wmv(MS_WMV)','AVCHD'=>'.m2ts(AVCHD)','QUICKTIME'=>'.mov(QUICKTIME)');
				//$FileFormatOptions = array(''=>'请选择','2099'=>'.ts(TS)','2103'=>'.mp4(MP4)','2226'=>'.flv(FLV)');



				?>

				<ul class="templateContent">
					<li>
						<span class="t">子模板名称</span>
						<span><?php echo $this->Form->input('sub_name',array('id'=>'sub_name', 'label'=>false,'div'=>false, 'class'=>'required','value'=>$data['Transcode']['title']));?></span>
					</li>
					<li class="h"><span>基本参数</span></li>

					<li>
					<div class="fieldVideo">
							<fieldset class="fieldVbox">
								<div class="legend">
									<span class="basicPara">
										<table border="0" cellpadding="0" cellspacing="0" width="180">
											<tr>
												<td width="63">文件格式：</td>
												<td>
													<?php echo $this->Form->input('FileFormat',array('id'=>'FileFormat', 'options'=>$FileFormatOptions, 'value'=>'', 'label'=>false, 'fileFormat_1'=>'data[Transcode][VideoFormat]', 'fileFormat_2'=>'data[Transcode][AudioFormat]', 'class'=>'combox uploadComboxWidth'))?>
												</td>
											</tr>
										</table>
									</span>
								</div>
								<div class="paraList" id="filevalue">
								</div>
							</fieldset>
						</div>
						<div class="fieldVideo">
							<fieldset class="fieldVbox">
								<div class="legend">
									<span class="basicPara">
										<table border="0" cellpadding="0" cellspacing="0" width="180">
											<tr>
												<td width="63">视频编码：</td>
												<td id="getvideolist">
													<?php echo $this->Form->input('VideoFormat',array('id'=>'VideoFormat', 'options'=>array(''=>'请选择'), 'video_Allother'=>'allInput', 'label'=>false, 'class'=>'combox uploadComboxWidth'))?>
												</td>
											</tr>
										</table>
									</span>
								</div>
								<div class="paraList" id="videovalue">
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
												<td id="getaudiolist">
													<?php echo $this->Form->input('AudioFormat',array('id'=>'AudioFormat', 'options'=>array(''=>'请选择'), 'audio_Allother'=>'allInput', 'value'=>'', 'label'=>false, 'class'=>'combox uploadComboxWidth'))?>
												</td>
											</tr>
										</table>
									</span>
								</div>
								<div class="paraList" id="getaudiovalue" style="height:auto">
								</div>
							</fieldset>
						</div>

						<!--分片-->
						<div class="fileFP">
							<div class="fpTit">
								<span class="tit">分片设置</span><input type="checkbox" id="fpCheck"<?php if(@$params['Transcode']['fpCheck'] == 'on'){echo "checked";}?> name="data[Transcode][fpCheck]"/>
							</div>
							<div id="fpTime" class="fpTime" style="<?php if(@$params['Transcode']['fpCheck'] == 'on'){echo 'display:block';}else{echo 'display:none';}?>">
								<table border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td width="64"><span class="timeTit">分片时间：</span></td>
										<td><?php echo $this->Form->input('SliceTime',array('id'=>'SliceTime', 'value'=>'', 'label'=>false, 'class'=>'input sortInput'))?></td>
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
		<?php 
	}else{
		echo '<div style="margin-bottom:13px;">没有子模板，请添加子模板！</div>';
	}?>
	<?php }
	
?>
</div>
<div class="tabsFooter">
	<div class="tabsFooterContent"></div>
</div>

<!-- 详细模板 -->
<div class="title">模板信息：<?php echo $transcodeGroups['TranscodeGroup']['name'];?><span style="margin-left:10px;">分片信息:<?php echo $transcodeGroups['TranscodeGroup']['split']?$transcodeGroups['TranscodeGroup']['split'].'秒':'不分片'?></span><?php if(@is_numeric($transcodeGroups['TranscodeGroup']['policyID'])):?><span style="margin-left:10px;">策略ID:<?php echo $transcodeGroups['TranscodeGroup']['policyID']?></span><?php endif;?><span style="display:none;" class="is_circle" data-mark="<?php echo @$transcodeGroups['TranscodeGroup']['UseTracks']?:0;?>"></span></div>
<?php if(!@is_numeric($transcodeGroups['TranscodeGroup']['policyID'])):?>
<div class="uploadtabs">  
    <ul>
    	<?php
    	if(isset($transcodeGroups['transcodes'])):
    		foreach($transcodeGroups['transcodes'] as $key=>$value):
    	?>
        <li id="uploadtab<?php echo $key;?>" onClick="switchTab(<?php echo $key;?>)"<?php if($key == 0){?> class="active"<?php }?>><?php echo $value['title'];?></li>
		<?php 
			endforeach;
		else:
			echo '<li id="uploadtab0" onClick="switchTab(0)" class="active">'.__('No subtemplates').'</li>';
		endif;
		?> 
    </ul>
    <div class="tabCon">
    	<?php
    	if(isset($transcodeGroups['transcodes'])):
			foreach($transcodeGroups['transcodes'] as $key=>$value):
    			if($value['params']['video']['VideoFormat'] != 0):
    	?>
					<div id="tabCon<?php echo $key;?>"<?php if($key != 0){?> style="display:none;"<?php }?>>
						<dl>
							<dd>文件部分</dd>
							<dd><span>文件格式：<?php echo $formatCode['file'][$value['params']['file']['FileFormat']];?></span></dd>
							<dd>视频部分</dd>
							<dd><span>幅面：<?php echo @$value['params']['video']['FormatWidth'];?>*<?php echo @$value['params']['video']['FormatHeight'];?></span><span>编码格式：<?php echo $formatCode['video'][$value['params']['video']['VideoFormat']];?></span></dd>
							<dd><span>码率：<?php echo $value['params']['video']['BitRate'];?>kbps</span><span>视频帧率：<?php echo $value['params']['video']['FrameRate'];?>fps</span></dd>
							<dd>音频部分</dd>
							<dd><span>编码格式：<?php echo $formatCode['audio'][$value['params']['audio']['AudioFormat']];?></span><span>采样率：<?php echo $value['params']['audio']['SamplesPerSec'];?></span></dd>
							<dd><span>采样位率：<?php echo $value['params']['audio']['BitsPerSample'];?></span></dd>
							<dd>水印部分</dd>
							<dd><div style="white-space:nowrap;height: 23px; width:430px; overflow: hidden;" title="<?php echo @$value['params']['water']['file'];?>"><span>水印文件：<?php echo @$value['params']['water']['file'];?></span></div></dd>
							<dd><span>水印X轴：<?php echo @$value['params']['water']['startX'];?></span>
							<span>水印Y轴：<?php echo @$value['params']['water']['startY'];?></span></dd>
							<dd><span>水印宽：<?php echo @$value['params']['water']['objWidth'];?></span>
							<span>水印高：<?php echo @$value['params']['water']['objHeight'];?></span></dd>
						</dl>
					</div>
					<?php else:?>
					<div id="tabCon<?php echo $key;?>"<?php if($key != 0){?> style="display:none;"<?php }?>>
	                    <dl>
	                        <dd>文件部分</dd>
	                        <dd><span>文件格式：<?php echo $value['params']['file']['FileFormat'];?></span></dd>
	                        <dd>音频部分</dd>
				            <dd><span>音频编码：<?php echo $value['params']['audio']['AudioFormat'];?></span><span>采样率：<?php echo $value['params']['audio']['SamplesPerSec']=="null"?"null":($value['params']['audio']['SamplesPerSec']/1000)."K"?></span></dd>
				            <dd><span>采样位率：<?php echo $value['params']['audio']['BitsPerSample'];?></span></dd>
				            <dd>分片</dd>
				            <dd><span><?php echo @$value['params']['SliceTime']?$value['params']['SliceTime']:0;?></span></dd>
	                    </dl>
                    </div>
        <?php 
        		endif;
			endforeach;
		endif;
		?>
    </div>  
</div>
<?php endif;?>



<?php if(isset($transcodeGroups['transcodes'])):?>
	<script type="text/javascript">
	function switchTab(n){  
		for(var i = 0;i<=<?php echo count($transcodeGroups['transcodes']);?>;i++){  
			$("#uploadtab" + i).removeClass('active');  
			$("#tabCon" + i).hide();  
		}  
		$("#uploadtab" + n).addClass('active');
		$("#tabCon" + n).show();
	} 
	</script>
<?php endif;?>
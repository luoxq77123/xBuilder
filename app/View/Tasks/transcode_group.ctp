<script type="text/javascript">
function G(id){  
return document.getElementById(id);  
}  
function switchTab(n){  
for(var i=1;i<=<?php echo count($allTranscode);?>;i++){  
  G("uploadtab" + i).className = "";  
  G("tabCon" + i).style.display = "none";  
}  
G("uploadtab" + n).className = "active";  
G("tabCon" + n).style.display = "block";  
} 
</script>
<!-- 详细模板 -->

	<div class="title">模板信息：</div>
	<h1><?php echo $transcodeGroupName;?></h1>
    <div class="uploadtabs">  
    <ul>
    	<?php
    	if(isset($allTranscode)):
    	$allTranscodeNum = 0;
    	foreach($allTranscode as $value):
    	$allTranscodeNum++;
    	?>
    	<?php if($value['params']['Transcode']['type'] == 1):?>
        <li id="uploadtab<?php echo $allTranscodeNum;?>" onClick="switchTab(<?php echo $allTranscodeNum;?>)"<?php if($allTranscodeNum == 1){?> class="active"<?php }?>><?php echo $value['title'];?></li>
        <?php else:?>
        <li id="uploadtab<?php echo $allTranscodeNum;?>" onClick="switchTab(<?php echo $allTranscodeNum;?>)"<?php if($allTranscodeNum == 1){?> class="active"<?php }?>><?php echo $value['title'];?></li>
        <?php endif;?>
		<?php endforeach;else:echo '<li id="uploadtab1" onClick="switchTab(1)" class="active">'.__('No subtemplates').'</li>'; endif;?> 
    </ul>  
    <div class="tabCon">
    	<?php
    	if(isset($allTranscode)):
    	$allTranscodeNum = 0;
    	foreach($allTranscode as $value):
    	$allTranscodeNum++;
    	?>
    	<?php if($value['params']['Transcode']['type'] == 1):?>
        <div id="tabCon<?php echo $allTranscodeNum;?>"<?php if($allTranscodeNum != 1){?> style="display:none;"<?php }?>>
			<dl>
				<dt>子模板名称：<?php echo $value['title'];?></dt>
				<dd>视频部分</dd>
				<dd><span>幅面：<?php echo @$value['params']['Transcode']['ImageWidth'];?>*<?php echo @$value['params']['Transcode']['ImageHeight'];?></span><span>编码格式：<?php echo $videoFormat[$value['params']['Transcode']['VideoFormat']];?></span></dd>
				<dd><span>码率：<?php echo $value['params']['Transcode']['BitRate'];?>kbps</span><span>视频帧率：<?php echo $value['params']['Transcode']['FrameRate'];?>fps</span></dd>
				<dd>音频部分</dd>
				<dd><span>音频编码：<?php echo $value['params']['Transcode']['AudioFormat'];?></span></dd>
				<dd><span>采样率：<?php echo $value['params']['Transcode']['SamplesPerSec']=="null"?"0":($value['params']['Transcode']['SamplesPerSec']/1000)."K"?></span><span>采样位率：<?php echo $value['params']['Transcode']['BitsPerSample'];?></span></dd>
				<dd>
					<div class="w">分片</div>
					<div style="clear: both;"></div>
				</dd>
				<dd>
					<div class="w"><span><?php echo @$value['params']['Transcode']['SliceTime']?$value['params']['Transcode']['SliceTime']:0;?></span></div>
					<div style="clear: both;"></div>
				</dd>
				
			</dl>
		</div>
		<?php else:?>
		<div id="tabCon<?php echo $allTranscodeNum;?>"<?php if($allTranscodeNum != 1){?> style="display:none;"<?php }?>>
        <dl>
            <dt style="width:170px;padding:0px;">子模板名称：<?php echo $value['title'];?></dt>
            <dd>音频部分</dd>
            <dd><span>文件格式：<?php echo $value['params']['Transcode']['FileFormat'];?></span></dd>
            <dd><span>音频编码：<?php echo $value['params']['Transcode']['AudioFormat'];?></span><span>采样率：<?php echo $value['params']['Transcode']['SamplesPerSec']=="null"?"null":($value['params']['Transcode']['SamplesPerSec']/1000)."K"?></span></dd>
            <dd><span>采样位率：<?php echo $value['params']['Transcode']['BitsPerSample'];?></span></dd>
            <?php if($value['params']['Transcode']['SliceTime'] != 0 && $splitPermission == 1){?>
            <dd>分片</dd>
            <dd><span><?php echo @$value['params']['Transcode']['SliceTime']?$value['params']['Transcode']['SliceTime']:0;?></span></dd>
            <?php
            }
            ?>
        </dl>
        </div>
        <?php endif;?>
		<?php endforeach;else:echo '<li id="uploadtab1" onClick="switchTab(1)" class="active">'.__('No subtemplates').'</li>'; endif;?>
    </div>  
    </div>

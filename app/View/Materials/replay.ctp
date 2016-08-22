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
<div class="pageContent">
	<form method="POST" action="<?php echo $this->Html->url(array('controller' => 'materials', 'action' => 'replay', @$this->params['pass'][0]))?>" class="pageForm required-validate" name="addform" onsubmit="return validateCallback(this,dialogAjaxDone);">
        <input type="hidden" name="cid" value="<?php echo @$this->params['pass'][0];?>" id="categoryid">
        <input type="hidden" name="replayIds" value="" id="replayIds">
        <div class="pageFormContent" layoutH="58"> 
            <div class="cell">
				<div class="title"><?php echo __('Templates select');?></div>
				<div><?php echo $this->Form->input('templateid', array('type'=>'select','target'=>'subTemplate','selectUrl'=>'materials/transcodeGroup/{value}','options'=>$options,'selected'=>$defaultTemplatesId,'name'=>'templateid','class'=>'combox','label'=>false));?></div>
				<div class="clear"></div>
			</div>
			
			<!-- 详细模板 -->
			<div class="templateDetail" id="subTemplate">
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
							<dd><span>幅面：<?php echo @$value['params']['Transcode']['ImageWidth'];?>*<?php echo @$value['params']['Transcode']['ImageHeight'];?></span><span>编码格式：<?php echo $value['params']['Transcode']['VideoFormat'];?></span></dd>
							<dd><span>码率：<?php echo $value['params']['Transcode']['BitRate'];?>kbps</span><span>视频帧率：<?php echo $value['params']['Transcode']['FrameRate'];?>fps</span></dd>
							<dd>音频部分</dd>
							<dd><span>编码格式：<?php echo $value['params']['Transcode']['AudioFormat'];?></span><span>采样率：<?php echo $value['params']['Transcode']['SamplesPerSec'];?></span></dd>
							<dd><span>采样位率：<?php echo $value['params']['Transcode']['BitsPerSample'];?></span></dd>
							<?php
							//水印权限
							if(in_array(4,explode(',',$userWater['Customer']['limits'])) && $waterPermission == 1)
							{
								$havaWater = 1;//有水印权限
							}else
							{
								$havaWater = 0;
							}
							
							//分片权限
							if(in_array(5,explode(',',$userWater['Customer']['limits'])) && $splitPermission == 1)
							{
								$havaSplit = 1;//有分片权限
							}else
							{
								$havaSplit = 0;
							}
							?>
                            
                            
                            <?php
                            if($havaWater == 1 && $havaSplit == 1)
							{
							?>
							<dd>
                                <div class="w">水印</div>
                                <div class="f">分片</div>
                                <div style="clear: both;"></div>
                            </dd>
							<dd>
                                <div class="w">
                                    <span>X：<?php echo @$value['params']['Transcode']['StartX'];?></span>
                                    <span>Y：<?php echo @$value['params']['Transcode']['StartY'];?></span>
                                </div>
                                <div class="f font"><?php echo @$value['params']['Transcode']['SliceTime']?$value['params']['Transcode']['SliceTime']:0;?></div>
                                <div style="clear: both;"></div>
                            </dd>
                            <?php
							}elseif($havaWater == 0 && $havaSplit == 1)
							{
							?>
                            <dd>
                                <div class="w">分片</div>
                                <div class="f"></div>
                                <div style="clear: both;"></div>
                            </dd>
							<dd>
                                <div class="w"><span><?php echo @$value['params']['Transcode']['SliceTime']?$value['params']['Transcode']['SliceTime']:0;?></span></div>
                                <div class="f font">无水印权限，请联系管理员开通</div>
                                <div style="clear: both;"></div>
                            </dd>
                            <?php
							}elseif($havaWater == 1 && $havaSplit == 0)
							{
							?>
                            <dd>
                                <div class="w">水印</div>
                                <div class="f"></div>
                                <div style="clear: both;"></div>
                            </dd>
							<dd>
                                <div class="w">
                                    <span>X：<?php echo @$value['params']['Transcode']['StartX'];?></span>
                                    <span>Y：<?php echo @$value['params']['Transcode']['StartY'];?></span>
                                </div>
                                <div class="f font">无分片权限，请联系管理员开通</div>
                                <div style="clear: both;"></div>
                            </dd>
                            <?php
							}elseif($havaWater == 0 && $havaSplit == 0)
							{
							?>
                            <dd style="margin:10px 0px 0px 0px;">
                               无水印权限和分片权限，请联系管理员开通
                            </dd>
                            <?php
							}
							?>
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
            </div>
			
        </div>
        
        <div class="formBar">
            <ul>
                <li><div class="buttonActive"><div class="buttonContent"><button type="submit"><?php echo __('submit');?></button></div></div></li>
                <li><div class="button"><div class="buttonContent"><button type="button" class="close"><?php echo __('cancel');?></button></div></div></li>
            </ul>
        </div>
    </form>
    
</div>
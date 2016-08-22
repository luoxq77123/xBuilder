<div class="xxinfo_tit"><?php echo $data['TranscodeGroup']['name']?></div>
<?php if(@$data['TranscodeGroup']['split'] != 0):?>
<div class="split">分片参数: <span><?php echo ($data['TranscodeGroup']['split'] != 0)?$data['TranscodeGroup']['split'].'秒':'不分片'?></span></div>
<?php endif; ?>

<?php if(@is_numeric($data['TranscodeGroup']['policyID'])):?>
<div class="split">策略ID: <span><?php echo $data['TranscodeGroup']['policyID']?></span></div>
<?php endif; ?>

<?php if(count($data['Transcode'])>0 && !@is_numeric($data['TranscodeGroup']['policyID'])):?>
<div id="detailInfo" class="detailInfo">
   <ul>
       <?php
        foreach($data['Transcode'] as $key => $svalue):
        $svalue['params'] = json_decode($svalue['params'],true);
       ?>
       <li>
           <dl>
               <dt>子模板名称：<?php echo $svalue['title']?><span><a target="dialog" href="<?php echo $this->Html->url('/transcodeGroups/edit/'.$data['TranscodeGroup']['id'].'/'.$svalue['id']);?>" rel="edit" mask="true" width="1040" height="580" param="closeSubTemplate" title="编辑模板" maxable="false">编辑</a></span></dt>
               <dd>文件格式：<?php echo $formatCode['file'][$svalue['params']['file']['FileFormat']];?></dd>

               <?php if($data['TranscodeGroup']['type'] == 1):?>
                 <dd>视频部分</dd>
                 <dd><span>幅面：<?php echo @$svalue['params']['video']['FormatWidth']?>*<?php echo @$svalue['params']['video']['FormatHeight']?></span><span>编码格式：<?php echo $formatCode['video'][$svalue['params']['video']['VideoFormat']];?></span></dd>
                 <dd><span>码率：<?php echo @$svalue['params']['video']['BitRate']?> kbps</span><span>帧率：<?php echo @$svalue['params']['video']['FrameRate']?>fps</span></dd>
               <?php endif;?>

               <dd>音频部分</dd>
               <dd><span>编码格式：<?php echo $formatCode['audio'][$svalue['params']['audio']['AudioFormat']];?></span><span>采样率：<?php echo @$svalue['params']['audio']['SamplesPerSec']=='0'?'null':(@$svalue['params']['audio']['SamplesPerSec']/1000)."K"?></span></dd>
               <dd><span>采样位率：<?php echo @$svalue['params']['audio']['BitsPerSample']=='0'?'null':@$svalue['params']['audio']['BitsPerSample'];?></span></dd>
               <?php if(!empty($svalue['params']['Transcode']['water_file']) && $waterPermission == 1):?>
                   <dd>水印</dd>
                   <dd><span>X：<?php echo @$svalue['params']['Transcode']['StartX']?></span><span>Y：<?php echo @$svalue['params']['Transcode']['StartY']?></span></dd>
                <?php endif; ?>
           </dl>
       </li>
       <?php endforeach;?>
   </ul>
</div>
<?php endif;?>
<div>
<!--临时存储地址-->
<span class="spannone" id="snapValue"></span>
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
            <span><?php echo $this->Form->input('sub_name',array('id'=>'sub_name'.$time, 'label'=>false,'div'=>false, 'class'=>'required'));?></span>
        </li>
        <li class="h"><span>基本参数</span></li>
        <li>
            <span class="t">文件格式：</span>
            <span><?php echo $this->Form->input('FileFormat',array('id'=>'FileFormat'.$time, 'options'=>$FileFormatOptions, 'fileFormat_3'=>'data[Transcode][AudioFormat]', 'label'=>false, 'class'=>'combox uploadComboxWidth'))?></span>
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
                                     <?php echo $this->Form->input('AudioFormat',array('id'=>'AudioFormat'.$time, 'options'=>$AudioFormatOptions, 'audioFile'=>'audioFile', 'label'=>false, 'class'=>'combox uploadComboxWidth'))?>
                                    </td>
                                </tr>
                            </table>
                        </span>
                    </div>
                    <div class="paraList">
                        <ul>
                        <li>
                            <span class="p">采样率：</span>
                            <span class="boxInput"><?php echo $this->Form->input('SamplesPerSec',array('id'=>'SamplesPerSec'.$time, 'options'=>$SamplesPerSecOptions,'label'=>false, 'class'=>'combox uploadComboxWidth'))?></span>
                            
                            <span class="p">采样位率：</span>
                            <span><?php echo $this->Form->input('BitsPerSample',array('id'=>'BitsPerSample'.$time, 'options'=>$BitsPerSampleOptions,'label'=>false, 'class'=>'combox uploadComboxWidth'))?></span>
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
                        <td><?php echo $this->Form->input('SliceTime',array('id'=>'SliceTime'.$time, 'label'=>false, 'class'=>'input sortInput'))?></td>
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
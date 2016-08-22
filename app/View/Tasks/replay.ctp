<div class="pageContent">
	<form method="POST" action="<?php echo $this->Html->url('/tasks/replay')?>" class="pageForm required-validate" name="addform" onsubmit="return validateCallback(this,dialogAjaxDone);">
        <input type="hidden" name="replayIds" value="" id="replayIds">
        <div class="pageFormContent" layoutH="58"> 
            <div class="cell">
				<div class="title"><?php echo __('Templates select');?></div>
				<div><?php echo $this->Form->input('templateid', array('type'=>'select','target'=>'subTemplate','selectUrl'=>'transcodeGroups/view_tab/{value}','options'=>$transcodeGroups['selectOptions'],'selected'=>$transcodeGroups['defaultTemplatesId'],'name'=>'templateid','class'=>'combox','label'=>false));?></div>
				<div class="clear"></div>
			</div>
            
            <div class="cell">
                <div class="title">快速分片：</div>
                <div><?php 
                    $value = SPLIT_DEFAULT_VALUE == 0?1:0;
                    echo $this->Form->input('is_split',array('type'=>'checkbox','name'=>'is_split','hiddenField' => false,'value'=>$value,'label'=>false,'style'=>'margin: 6px 0px;'));?></div>
                <div class="clear"></div>
            </div>

            <div class="cell">
               <div class="title">平台选择：</div>
               <ul style="width: 330px;padding-left: 80px;">
                    <?php 
                    $plats = explode('|',PUBLISH_PLATFORMS);
                    foreach($plats as $plat):
                        $tmp = explode(',', $plat);
                    ?>
                    <li><input type="checkbox" value="<?php echo $tmp[0]?>" name="platFormID[]" style="margin: 6px 0px;" ><label><?php echo $tmp[1]?></label></li>
                    <?php endforeach;?>
               </ul>
               <div class="clear"></div>
            </div>

		    <div class="templateDetail" id="subTemplate">	
		      <?php echo $this->element('transcodeGroup/view',array('transcodeGroups'=>$transcodeGroups,'formatCode'=>$formatCode));?>
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
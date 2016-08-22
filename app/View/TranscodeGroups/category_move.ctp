<div class="pageContent">
	<form method="POST" action="<?php echo $this->Html->url(array('controller' => 'Transcodegroups', 'action' => 'category_move', @$this->params['pass'][0]))?>" class="pageForm required-validate" name="addform" onsubmit="return validateCallback(this,dialogAjaxDone);">
        <input type="hidden" name="cid" value="<?php echo @$this->params['pass'][0];?>" id="categoryid">
        <input type="hidden" name="replayIds" value="" id="replayIds">
        <div class="pageFormContent" layoutH="58"> 
            <div class="cell">
				<div><?php echo $this->Form->input(__('category move type'), array('type'=>'radio', 'name'=>'data[Category][move_type]' ,'options'=>array('1'=>'复制并迁移','2'=>'迁移'), 'label'=>false, 'value'=>'1'));?></div>
				<div class="title" style="padding:3px 0px 0px 0px; width:65px;"><?php echo __('category select');?>：</div>
				<div><?php echo $this->Form->input('parent_id', array('type'=>'select', 'name'=>'data[Category][parent_id]' ,'options'=>@$categories, 'value' => '', 'empty'=>array('0'=>'请选择'), 'label'=>false));?></div>
				<div class="clear"></div>
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
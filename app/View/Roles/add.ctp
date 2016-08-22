<div class="pageContent">
	<form method="post" action="<?php echo $this->Html->url(array('controller' => 'roles', 'action' => 'add'))?>" class="pageForm required-validate" name="addform" onsubmit="return validateCallback(this,dialogAjaxDone);">
        <div class="pageFormContent " layoutH="58"> 
            <div class="unit">
                <label for="account"><?php echo __('Role name');?>：</label>
                <input type="text" id="account" name="data[Role][name]" maxlength="24" class="required" />
                <span class="inputInfo"></span>
            </div>
            
            <div class="unit">
                <label for="sort"><?php echo __('sort');?>：</label>
                <input type="text" id="sort" name="data[Role][sort]" maxlength="24" value="0" class="digits" />
                <span class="inputInfo"></span>
            </div>
            
            <div class="unit">
            <label for="operation_accesses">角色系统权限：</label>
                <ul>
                    <?php foreach($systemPermissions as $key=>$value):?>
                    <li style="float:left;">
                        <?php echo $this->Form->checkbox('operation_accesses', array('value'=>$key,'hiddenField'=>false,'name'=>'data[Role][operation_accesses][]')) . $value;?>
                        
                    </li>    
                    <?php endforeach;?>
                </ul>
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


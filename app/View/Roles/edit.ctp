<div class="pageContent">
    <?php echo $this->Form->create('Role',array('class'=>'pageForm','onsubmit'=>'return validateCallback(this, dialogAjaxDone)'));?>
        <div class="pageFormContent " layoutH="58"> 
            <?php 
                echo $this->Form->input('name', array('label' => __('Role name'), 'div' => array('class'=>'unit'),  'size' => '24', 'class' => "required"));
                echo $this->Form->input('sort', array('label' => __('sort'), 'div' => array('class'=>'unit'), 'type'=>'text', 'size' => '24'));
            ?>
            
            <div class="unit">
                <label for="operation_accesses">角色系统权限</label>
                <ul>
                    <?php 
                        foreach($systemPermissions as $key=>$value):
                        $checked = in_array($key, explode(',', $this->request->data['Role']['operation_accesses']))? 'checked':false;
                    ?>
                    <li style="float:left;">
                        <?php echo $this->Form->checkbox('operation_accesses', array('value'=>$key,'hiddenField'=>false,'name'=>'data[Role][operation_accesses][]','checked'=>$checked)) . $value;?>
                        
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
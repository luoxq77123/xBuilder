<div class="pageContent">
    <?php echo $this->Form->create('User',array('class'=>'pageForm','onsubmit'=>'return validateCallback(this, dialogAjaxDone)','inputDefaults'=>array('div'=>false,'label'=>false)));?>
        <?php echo $this->Form->hidden('id', array('value' => $id));?>
        <div class="pageFormContent " layoutH="58"> 
            <div class="unit">
                <label for="account"><?php echo __('Account');?>：</label>
                <?php echo $this->Form->input('email', array( 'size' => '24', 'class' => "textInput readonly", 'readonly'=>"true"));?>
                <span class="inputInfo"></span>
            </div>
            
            <div class="unit">
                <label for="password"><?php echo __('Old password');?>：</label>
                <?php echo $this->Form->input('password', array('value' => '', 'size' => '24'));?>
                <span class="inputInfo"></span>
            </div>
            
            <div class="unit">
                <label for="newpassword"><?php echo __('New password');?>：</label>
                <?php echo $this->Form->input('newpassword', array('type'=>'password', 'value' => '', 'size' => '24'));?>
                <span class="inputInfo"></span>
            </div>
            
            <div class="unit">
                <label for="confirmpassword"><?php echo __('Enter the new password again');?>：</label>
                <?php echo $this->Form->input('confirmpassword', array('type'=>'password', 'size' => '24'));?>
                <span class="inputInfo"></span>
            </div>
            
            <div class="divider"></div>
            <div class="unit">
                <?php echo $this->Form->input('role_id', array('type'=>'select', 'class'=>'combox', 'options'=>$roles, 'label'=>__('Select role')));?>
				<span class="inputInfo"><?php echo __('Select user role');?></span>
            </div>
            <div class="divider"></div>
            
            <div class="unit">
                <label for="name"><?php echo __('Nickname');?>：</label>
                <?php echo $this->Form->input('account', array( 'size' => '24', 'class' => "textInput readonly"));?>
                <span class="inputInfo"></span>
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
<div class="pageContent">
	<form method="post" action="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'add'))?>" class="pageForm required-validate" name="addform" onsubmit="return validateCallback(this,dialogAjaxDone)">
        
        <div class="pageFormContent " layoutH="58"> 
            <div class="unit">
                <label for="email"><?php echo __('Account');?>：</label>
                <input type="hidden" name="data[User][email]" value="">
                <input type="text" id="email" name="data[User][email]" maxlength="24" class="required email">
                <span class="inputInfo">（邮箱格式）</span>
            </div>
            
            <div class="unit">
                <label for="password"><?php echo __('Password');?>：</label>
                <input type="password" id="password" name="data[User][password]" maxlength="24" class="required"/>
                <span class="inputInfo"></span>
            </div>
            
            <div class="divider"></div>
            <div class="unit">
                <?php echo $this->Form->input('role_id', array('type'=>'select', 'class'=>'combox', 'name'=>'data[User][role_id]' ,'options'=>$roles, 'label'=>__('Select role')));?>
				<span class="inputInfo"><?php echo __('Select user role');?></span>
            </div>
            <div class="divider"></div>
            
            <div class="unit">
                <label for="name"><?php echo __('Nickname');?>：</label>
                <input type="text" id="account" name="data[User][account]" maxlength="24" class="required" />
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

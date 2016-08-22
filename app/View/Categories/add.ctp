<div class="pageContent">
	<form method="post" action="<?php echo $this->Html->url(array('controller' => 'categories', 'action' => 'add', $id))?>" class="pageForm required-validate" name="addform" onsubmit="return validateCallback(this,dialogAjaxDone)">
        <div class="pageFormContent " layoutH="58"> 
            <div class="unit">
                <label for="CategoryName">分类名称：</label>
                <input type="text" id="CategoryName" name="data[Category][name]" maxlength="20" class="required" />
                <span class="inputInfo"></span>
            </div>
            <div class="divider"></div>
            <div class="unit">
                <?php echo $this->Form->input('parent_id', array('type'=>'select', 'name'=>'data[Category][parent_id]' ,'options'=>$viewCategory, 'value' => $id, 'empty'=>array('0'=>'请选择'), 'label'=>'选择类别'));?>
				<span class="inputInfo">（不选为父类）</span>
            </div>
            <div class="divider"></div>
        </div>
        <div class="formBar">
            <ul>
                <li><div class="buttonActive"><div class="buttonContent"><button type="submit">提交</button></div></div></li>
                <li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
            </ul>
        </div>
    </form> 
</div>
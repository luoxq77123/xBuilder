<div class="pageContent">
	<form method="post" action="<?php echo $this->Html->url(array('controller' => 'TranscodeGroups', 'action' => 'edit_category', $id))?>" class="pageForm required-validate" name="addform" onsubmit="return validateCallback(this,dialogAjaxDone)">
        <input type="hidden" name="data[TranscodeCategory][id]" value="<?php echo $category['TranscodeCategory']['id']?>" />
        <div class="pageFormContent "> 
            <div class="unit">
                <label for="CategoryName">分类名称：</label>
                <input type="text" id="CategoryName" name="data[TranscodeCategory][name]" maxlength="20" class="required" value="<?php echo $category['TranscodeCategory']['name']?>"/>
                <span class="inputInfo"></span>
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
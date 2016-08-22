<div class="pageContent">
	<form method="post" action="<?php echo $this->Html->url(array('controller' => 'TranscodeGroups', 'action' => 'add_category', $id))?>" class="pageForm required-validate" name="addform" onsubmit="return validateCallback(this,dialogAjaxDone)">
        <div class="pageFormContent "> 
            <div class="unit">
                <label for="TranscodeCategoryName">模版分类名称：</label>
                <input type="text" id="TranscodeCategoryName" name="data[TranscodeCategory][name]" maxlength="20" class="required" />
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
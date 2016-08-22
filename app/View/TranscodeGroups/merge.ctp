<style type="text/css">
.merge .pageFormContent label {
    width: auto;    
}
.merge span.error {
    top:4px;
    left: 225px;
    width: 48px;
} 
</style>
<div class="pageContent merge">
	<form method="post" action="<?php echo $this->Html->url(array('controller' => 'TranscodeGroups'))?>" class="pageForm required-validate" name="addform" onsubmit="return validateCallback(this,dialogAjaxDone);">
        <div class="pageFormContent "> 
            <div class="unit">
                <label for="TranscodeName">模板名称：</label>
                <input type="text" id="TranscodeName" name="data[name]" maxlength="30" class="required" />
                <div class="ids">
                    <input type="hidden" name="replayIds" value="" id="replayIds">
                </div>
            </div>
        </div>
        <div class="formBar">
            <ul>
                <li><div class="buttonActive"><div class="buttonContent"><button type="submit">提交</button></div></div></li>
                <li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
            </ul>
        </div>
    </form> 
</div>
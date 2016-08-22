<style type="text/css">
.split .pageFormContent label {
    width: auto;    
}
.split span.error {
    top:4px;
    left: 385px;
} 
.dialog .split .pageFormContent, .dialog .split .viewInfo {
    border: 0 none;
}
</style>
<div class="layoutBox split">
	<form method="POST" action="<?php echo $this->Html->url(array('controller' => 'Transcodegroups', 'action' => 'split', @$this->params['pass'][0]))?>" class="pageForm required-validate" name="addform" onsubmit="return validateCallback(this,splitAjaxDone);">
    <div class="pageFormContent "> 
            <div class="unit">
                <label for="TranscodeName">拆分模板名称：</label>
                <input style="margin-right: 20px;" type="text" id="TranscodeName" name="data[name]" maxlength="30" class="required" />
                 <ul>
                <li><div class="buttonActive" style="margin-right: 15px;"><div class="buttonContent"><button type="submit">拆分</button></div></div></li>
                <li><div class="button"><div class="buttonContent"><button type="button" class="close"><?php echo __('cancel');?></button></div></div></li>
            </ul>
            </div>
    </div>
     <table class="table" width="100%" layoutH="100%">
        <thead>
            <tr>
                <th>子模板名称</th>
                <th width="120" class="center">拆分模版</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if(count($childrenTrans['Transcode']) > 0):
                foreach($childrenTrans['Transcode'] as $value):
                    ?>
                <tr>
                    <td><?php echo $value['title'];?></td>
                    <td class="center"><?php echo $this->Form->checkbox('template', array('value'=>$value['id'],'hiddenField'=>false,'name'=>'split[]','class'=>'changeRadio'));?>
                    </td>
                </tr>
                <?php
                endforeach;
                else:
                    ?>
                <tr>
                    <td>没有数据！</td>
                </tr>
                <?php
                endif;
                ?>
            </tbody>
        </table>
    </form>
</div>
<script type="text/javascript">
    function splitAjaxDone(json) {
        DWZ.ajaxDone(json);
        if(json.statusCode == '200') {
            var id = json.selectedID;
            $('input[value=' + id + ']').attr('checked',true);
            $('.split').click();
            navTab.reload(json.forwardUrl);
        }
    }
</script>
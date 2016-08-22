<div class="pageContent">
    <?php 
        echo $this->Form->create('Config',array(
                    'class'=>'pageForm required-validate',
                    'onsubmit'=>'return validateCallback(this,dialogAjaxDone)',
                    'inputDefaults'=>array(
                    'div'=>array(
                        'class'=>'unit'
                    ),
                    'legend'=>false
                )
            )
        );
    ?>
        <div class="pageFormContent " layoutH="58">
            <?php 
                echo $this->Form->input('name',array('class'=>'required','type'=>'text','label'=>'配置名称：'));
                echo $this->Form->input('type',array('class'=>'required','type'=>'text','label'=>'配置名称(英文)：'));
                echo $this->Form->input('value',array('class'=>'required','type'=>'textarea','label'=>'配置值：','style'=>'margin: 0px;height: 80px;width: 370px;'));
            ?>
            <div class="divider"></div>
            <?php
                echo $this->Form->input('access',array('class'=>'combox','label'=>'配置类型：','options'=>array('1'=>'基本参数','2'=>'接口参数','3'=>'路径参数','4'=>'隐藏参数')));
                echo $this->Form->input('field_type',array('class'=>'combox','label'=>'配置展示类型：','options'=>array('text'=>'输入框','textarea'=>'文本框')));
                echo $this->Form->input('is_valid',array('class'=>'combox','type'=>'radio','options'=>array('1'=>'是','0'=>'否'),'before'=>'<label>启用：</label>'));
                echo $this->Form->input('description',array('class'=>'xb_textarea','type'=>'textarea','label'=>'配置说明：','style'=>'margin: 0px;height: 80px;width: 370px;'))
            ?>
        </div>
        <div class="formBar">
            <ul>
                <li><div class="buttonActive"><div class="buttonContent"><button type="submit">提交</button></div></div></li>
                <li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
            </ul>
        </div>
    <?php echo $this->Form->end();?>
</div>
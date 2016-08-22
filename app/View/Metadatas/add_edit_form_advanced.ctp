<div class="pageContent">
    <?php 
    echo $this->Form->create('Metadata',array(
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
    <div class="pageFormContent attr-add-from" layoutH="58">
        <!-- 基础参数 -->
        <div style="font-weight: bold; padding: 10px 5px;">基础参数:</div>
        <?php 
        // echo $this->Form->input('name',array('class'=>'required','type'=>'text','label'=>'配置名称(name)：', 'value'=>@$config['Metadata']['name']?:''));
        echo $this->Form->input('title',array('class'=>'','type'=>'text','label'=>'配置名称(title)：', 'value'=>@$config['Metadata']['title']));
        echo $this->Form->input('code',array('class'=>'required','type'=>'text','label'=>'配置名称(code)：', 'value'=>@$config['Metadata']['code']?:''));
        ?>
        <div class="divider"></div>

        <div style="font-weight: bold; padding: 10px 5px;">属性参数:</div>
        <?php
        echo $this->Form->input('is_auto_fill',array('checked'=>@$config['Metadata']['is_auto_fill']?true:false, 'hiddenField'=>false, 'class'=>'','type'=>'checkbox','label'=>'自动填充：', 'value'=>1));
        echo $this->Form->input('type',array('class'=>'combox','label'=>'配置展示类型：','options'=>$type, 'selected'=>@$config['Metadata']['type']));
        echo $this->Form->input('data_type',array('selected'=>@$config['Metadata']['data_type'], 'class'=>'combox','label'=>'配置数据类型：', 'options'=>$datasourceType));
        echo $this->Form->input('data_value',array('value'=>@$config['Metadata']['data_value'], 'class'=>'xb_textarea','type'=>'textarea','label'=>'配置值：','style'=>'margin: 0px;height: 80px;width: 370px;'));
        echo $this->Form->input('desc',array('value'=>@$config['Metadata']['desc'], 'class'=>'xb_textarea','type'=>'textarea','label'=>'配置说明：','style'=>'margin: 0px;height: 80px;width: 370px;'));
        echo $this->Form->input('order', array('default'=>0, 'value'=>@$config['Metadata']['order'], 'class'=>'order', 'type'=>'input', 'label'=>'排序'));
        echo $this->Form->input('id', array('value'=>@$config['Metadata']['id'], 'class'=>'order', 'type'=>'hidden'));
        // echo '<span class="inputInfo">数值越小越靠前显示</span>';
      
        ?>
        
        <div class="diy-attr"></div>
    </div>
    <div class="formBar">
        <ul>
            <li><div class="buttonActive"><div class="buttonContent"><button type="submit">提交</button></div></div></li>
            <li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
        </ul>
    </div>
    <?php echo $this->Form->end();?>
</div>


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
    <div class="pageFormContent attr-add-from small-label" layoutH="58">
        <?php 
        echo $this->element('metadata/metadataList',array('metaData'=>$metaData));      
        ?>
        
        <div class="diy-attr"></div>
    </div>
    <div class="formBar">
        <ul>
            <li><div class="button"><div class="buttonContent"><button type="button" class="close">关闭</button></div></div></li>
        </ul>
    </div>
    <?php echo $this->Form->end();?>
</div>


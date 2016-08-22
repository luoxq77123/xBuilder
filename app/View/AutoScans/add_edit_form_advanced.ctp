<style>
    .ieBox{margin: 6px 0px; float: left; padding: 0; border: 0;+margin: 1px 0px;}
</style> 
<div class="pageContent">
	<form method="post" action="<?php echo $this->Html->url(array('controller' => 'AutoScans', 'action' => 'add_edit'))?>" class="pageForm required-validate" name="addform" onsubmit="return validateCallback(this,dialogAjaxDone)">    
        <div class="pageFormContent " layoutH="58"> 
            <div class="unit">
            <?php echo $this->Form->input('path', array('value'=>@$config['AutoScan']['path'], 'type'=>'text', 'style'=>'width:293px; color:#000', 'class'=>'required uploadIw', 'id'=>'path', 'name'=>'data[AutoScan][path]','label'=>__('AutoScan Path'). ':'));?>
            </div>
            
            <?php 
            if($id) {
                echo $this->Form->input('id', array('value'=>$id, 'type'=>'hidden', 'id'=>'id', 'name'=>'data[AutoScan][id]'));
            }
            ?>
            <div class="unit">
                <?php 
                echo $this->Form->input('suffix',array('label'=>__('Suffix'). ':','class'=>'required textInput','type'=>'textarea','value'=>@$config['AutoScan']['suffix'],'style'=>'margin: 0px;height: 80px;width: 420px;','name'=>'data[AutoScan][suffix]'));?>
                <span class="inputInfo"></span>
            </div>
            <div class="unit">
                <?php
                    echo $this->Form->input('tid', array('target'=>'subTemplate','selectUrl'=>$this->Html->url('/AutoScans/getTrancecodeDetail/{value}'),'selected'=>@$config['AutoScan']['tid'], 'id'=>'template-category','options'=>$transcodeGroupsOptions, 'empty'=>array('请选择'), 'label'=>'模板选择:','class'=>'required combox', 'name'=>'data[AutoScan][tid]'));
                ?>
            </div>
            <div class="unit">
                <label>快速分片：</label>
                <div><?php 
                    $value = SPLIT_DEFAULT_VALUE == 0?1:0;
                    $options = array('type'=>'checkbox','name'=>'data[AutoScan][is_split]','label'=>false,'class'=>'ieBox');
                    if(@$config['AutoScan']['is_split'] == $value){
                        $options['checked'] = 'checked';
                    }
                    echo $this->Form->input('is_split',$options);?></div>
            </div>
            <?php if(defined('PUBLISH_PLATFORMS')):?>
            <div class="unit cell">
               <label>平台选择：</label>
               <ul style="width: 340px;">
                    <?php 
                    $plats = explode('|',PUBLISH_PLATFORMS);
                    foreach($plats as $plat):
                        $tmp = explode(',', $plat);
                    ?>
                    <li><input type="checkbox" value="<?php echo $tmp[0]?>" name="data[AutoScan][platforms][]" class="ieBox" <?php if(@in_array($tmp[0], $config['AutoScan']['platforms'])):?>checked<?php endif;?> ><label><?php echo $tmp[1]?></label></li>
                    <?php endforeach;?>
               </ul>
               <div class="clear"></div>
            </div>
            <?php endif;?>
            <!-- 详细模板 -->
            <div class="templateDetail" id="subTemplate">
                <?php if (isset($transcodeGroups)) {
                    echo $this->element('transcodeGroup/view',array('transcodeGroups'=>$transcodeGroups,'formatCode'=>$formatCode));
                }?>
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

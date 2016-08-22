<?php echo $this->Form->create('Config',array('action'=>$this->Html->url('/index'),'class'=>'pageForm required-validate','onsubmit'=>'return validateCallback(this)','inputDefaults'=>array('div'=>false,'label'=>false)));?>
<div class="pageFormContent nowrap">
	<?php foreach($configs as $config):?>
	<dl>
		<dt><?php echo $config['Config']['name']?>:</dt>
		<dd><?php 
			$style = '';
			if($config['Config']['field_type'] == 'textarea') $style = 'margin: 0px;height: 80px;width: 370px;';
			echo $this->Form->input('value',array('class'=>'required textInput','type'=>$config['Config']['field_type'],'value'=>$config['Config']['value'],'style'=>$style,'name'=>'data['.$config['Config']['id'].'][value]'));?>
			<span class="inputInfo"><?php echo $config['Config']['description']?></span>
		</dd>
	</dl>
	<?php endforeach;?>
</div>
<div class="pageFormFooter">
    <ul>
        <li><div class="buttonActive"><div class="buttonContent"><button type="submit">保存</button></div></div></li>
    </ul>
</div>
<?php echo $this->Form->end();?>
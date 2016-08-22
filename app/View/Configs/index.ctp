<div class="tabs" currentIndex="0" eventType="click">
	<div class="tabsHeader">
		<div class="tabsHeaderContent">
			<ul>
				<li><a href="javascript:;"><span>基本配置</span></a></li>
				<?php if($isAdmin):?>
				<li><a href="<?php echo $this->Html->url('/configs/url');?>" class="j-ajax"><span>接口配置</span></a></li>
				<li><a href="<?php echo $this->Html->url('/configs/path');?>" class="j-ajax"><span>路径配置</span></a></li>
				<?php endif;?>
			</ul>
			<?php if($isAdmin):?>
				<a href="<?php echo $this->Html->url('/configs/superindex');?>" rel="main" target="navTab" style="position: absolute;top: 10px;right: 10px;">配置管理</a>
			<?php endif;?>
		</div>
	</div>
	<div class="tabsContent" layoutH="60">
		<div>
			<?php echo $this->Form->create('Config',array('class'=>'pageForm required-validate','onsubmit'=>'return validateCallback(this)','inputDefaults'=>array('div'=>false,'label'=>false)));?>
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
		</div>
		<?php if($isAdmin):?>
		<div></div>
		<div></div>
		<?php endif;?>
	</div>
	<div class="tabsFooter">
		<div class="tabsFooterContent"></div>
	</div>
</div>
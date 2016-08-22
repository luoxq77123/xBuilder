<div class="topPanelBar">
		<ul class="toolBar">
			<li><a class="web_upload" href="<?php echo $this->Html->url('/materials/upload/'.$id);?>" target="dialog" rel="upload" mask="true" width="800" height="550" title="<?php echo __('web Upload');?>" drawable="true" resizable="true" maxable="false" upload="<?php echo $id;?>" param="closeEven"><span><?php echo __('web Upload');?></span></a></li>
			<li><a class="ftp_upload" href="<?php echo $this->Html->url('/materials/ftp_upload/'.$id);?>" target="dialog" rel="upload" mask="true" width="800" height="550" title="<?php echo __('client Upload');?>" drawable="false" resizable="false" maxable="false" upload="<?php echo $id;?>" param="closeEven"><span><?php echo __('client Upload');?></span></a></li>
			<li><a class="disk_find" href="<?php echo $this->Html->url('/materials/disk_find/'.$id);?>" target="dialog" rel="upload" mask="true" width="800" height="550" title="<?php echo __('disk Find');?>" resizable="false" maxable="false" upload="<?php echo $id;?>" param="closeEven"><span><?php echo __('disk Find');?></span></a></li>
		</ul>
	</div>
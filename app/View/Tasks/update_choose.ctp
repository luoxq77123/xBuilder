<style>
	.topPanelBar p{padding:5px 20px;}
	.topPanelBar p .red{color: #f00;}
</style>
<div class="topPanelBar" style="height: 200px">
<ul class="toolBar">
	<li style="width:110px; height: 140px; margin-left: 5px; margin-right: 4px;"><a style="height: 135px;" class="web_upload" href="<?php echo $this->Html->url('/tasks/upload/'.$id);?>" target="dialog" rel="web_upload" mask="true" width="1100" height="585" title="<?php echo __('web Upload');?>" drawable="true" resizable="true" maxable="false" upload="<?php echo $id;?>" param="closeEven"><span style="padding-top: 80px; height: 36px"><?php echo __('web Upload');?></span></a></li>
	<li style="width:110px; height: 140px; margin-left: 5px; margin-right: 4px;"><a style="height: 135px;" class="ftp_upload" href="<?php echo $this->Html->url('/tasks/ftp_upload/'.$id);?>" target="dialog" rel="ftp_upload" mask="true" width="1100" height="585" title="<?php echo __('client Upload');?>" drawable="false" resizable="false" maxable="false" upload="<?php echo $id;?>" param="closeEven"><span style="padding-top: 80px; height: 36px"><?php echo __('client Upload');?></span></a></li>
	<li style="width:110px; height: 140px; margin-right: 0; margin-left: 5px;"><a style="height: 135px;" class="disk_find" href="<?php echo $this->Html->url('/tasks/disk_find/'.$id);?>" target="dialog" rel="disk_find" mask="true" width="1100" height="585" title="<?php echo __('disk Find');?>" resizable="false" maxable="false" upload="<?php echo $id;?>" param="closeEven"><span style="padding-top: 80px; height: 36px"><?php echo __('disk Find');?></span></a></li>
</ul>
<div class="clear"></div>
<p>1.若单个视频超过<span class="red">500MB</span>,请选择客户端FTP上传方式</p>
<p>2.若本地电脑无FTP客户端,请先<a href="SobeyTransfer_V1.0_Setup.exe" target="_blank" class="red">点击此处</a>下载客户端</p>
</div>
<ul>
	<?php if($path != STORAGE_IP_ADDRESS . WATER_UPLOAD_PREFIX):?>
		<li><a class="folder" href="javascript:;" rel="<?php echo urlencode($upPath);?>">上一级</a></li>
	<?php
		endif;
		if(count(@$folders) > 0):
		foreach ($folders as $folder):
	?>
		<li><a class="folder" href="javascript:;" rel="<?php echo $path.DS.$folder?>"><?php echo StringExpand::cutStr($folder,8,true);?></a></li>
	<?php 
		endforeach;
		endif;
		if(count(@$files) > 0):
		foreach ($files as $file):
	?>
		<li><a class="file" href="javascript:;" rel="<?php echo $path.DS.$file?>"><?php echo StringExpand::cutStr($file,8,true);?></a></li>
	<?php 
		endforeach;
		endif;
	?>
</ul>
<script type="text/javascript">
	$('.panelBar .pathStr').html('<?php echo $now_path;?>');
	$('input[name="file_path"]').val('<?php echo $now_path;?>');
</script>
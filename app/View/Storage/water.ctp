<div class="pageContent" style="border:0;" layoutH="0">
	<div class="panelBar" style="line-height:26px;text-indent:1em;">
		当前路径: <span class="pathStr"><?php echo STORAGE_IP_ADDRESS . WATER_UPLOAD_PREFIX;?></span>
	</div>
	<form id="storage" action="#" onsubmit="return waterStorageCallback(this)">
		<input type="hidden" name="file_path" value="<?php echo STORAGE_IP_ADDRESS . WATER_UPLOAD_PREFIX;?>" />
		<div class="computer" layoutH="90">
			<ul>
				<?php
					if(count(@$folders) > 0):
					foreach ($folders as $folder):
				?>
					<li><a class="folder" href="javascript:;" rel="<?php echo STORAGE_IP_ADDRESS . WATER_UPLOAD_PREFIX . DS . $folder?>"><?php echo StringExpand::cutStr($folder,8,true);?></a></li>
				<?php 
					endforeach;
					endif;
					if(count(@$files) > 0):
					foreach ($files as $file):
				?>
					<li><a class="file" href="javascript:;" rel="<?php echo STORAGE_DISK . WATER_UPLOAD_PREFIX . DS . $file ?>"><?php echo StringExpand::cutStr($file,8,true);?></a></li>
				<?php 
					endforeach;
					endif;
				?>
			</ul>
		</div>
		<div class="formBar" style="border-width:0px 1px 1px 1px;">
	        <ul>
	            <li><div class="buttonActive"><div class="buttonContent"><button type="submit">提交</button></div></div></li>
	            <li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
	        </ul>
	    </div>
	</form>
</div>
<script type="text/javascript">
	$(function(){

		$('.folder').die().live('click',function(){
			var path = $(this).attr('rel');
			$(".computer").loadUrl('<?php echo $this->Html->url("/storage/scan_water");?>','path='+path);
		});

		$('.file').die().live('click',function(){
			var $this = $(this);
			$('.file').removeClass('select');
			$this.addClass('select');

			var filePath = $this.attr('rel');
			$('input[name="file_path"]').val(filePath);
		});
	});

	function waterStorageCallback(form) {
		var $form = $(form);

		var filePath = $('input[name="file_path"]',form).val();

		storageChooseWater(filePath);

		$.pdialog.closeCurrent();
		return false;
	}
	
</script>
<div class="pageContent" style="border:0;" layoutH="0">
	<div class="panelBar" style="line-height:26px;text-indent:1em;">
		当前路径: <span class="pathStr"><?php echo STORAGE_IP_ADDRESS . STORAGE_SELECT_PATH;?></span>
	</div>
	<form id="storage" action="<?php echo $this->Html->url('/materials/choose');?>" onsubmit="return storageValidateCallback(this,<?php echo $callback?:'null'?>)"  data-width="680" data-height="390">
		<input type="hidden" name="file_path" value="<?php echo STORAGE_IP_ADDRESS . STORAGE_SELECT_PATH;?>" />
		<div class="computer" layoutH="90">
			<ul>
				<?php
					if(count(@$folders) > 0):
					foreach ($folders as $folder):
				?>
					<li><a class="folder" href="javascript:;" rel="<?php echo urlencode(STORAGE_IP_ADDRESS . STORAGE_SELECT_PATH . DS . $folder)?>" title="<?php echo $folder;?>"><?php echo StringExpand::cutStr($folder,8,true);?></a></li>
				<?php 
					endforeach;
					endif;
					if(count(@$files) > 0):
					foreach ($files as $file):
				?>
					<li><a class="file" href="javascript:;" rel="<?php echo urlencode(STORAGE_DISK . MPC_STORAGE_SELECT_PATH . DS . $file)?>" title="<?php echo $file;?>"><?php echo StringExpand::cutStr($file,8,true);?></a></li>
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
			$(".computer").loadUrl('<?php echo $this->Html->url("/materials/scan_storage");?>','path='+path);
		});

		$('.file').die().live('click',function(){
			var $this = $(this);
			$('.file').removeClass('select');
			$this.addClass('select');

			var filePath = $this.attr('rel');
			$('input[name="file_path"]').val(filePath);
		});
	});

	function storageValidateCallback(form, callback) {
		var $form = $(form);
		if (!$form.valid()) {
			return false;
		}

		var params = {};
		params.filePath = $('input[name="file_path"]',form).val();

		$.pdialog.closeCurrent();

		if(callback){
			callback($form,params);
		}
		return false;
	}

	function chooseFile(form, params){
		var url = form.attr('action');
		var options = {};
		var w = form.data("width");
		var h = form.data("height");
		if (w) options.width = w;
		if (h) options.height = h;
		options.mask = true;
		options.max = false;
		options.resizable = false;
		options.maxable = false;

		$.pdialog.open(url+'?file_path='+params.filePath, "_blank", "文件选择", options);
	}

	function chooseAudio(form, params){
		var url = form.attr('action')+'/audio?file_path='+params.filePath;
		var id = '<?php echo $id;?>';
		var input = $('#audio_'+id);
		$.getJSON(url,function(json){
			if($(json['list'][0]['a']).size() > 0){
				var html = '<div id="audio_'+id+'"><input type="hidden" name="'+id+'[filename][1]" value="'+json['filename']+'">';
				html += '<select name="'+id+'[audio][]" id="'+id+'_audio" class="combox">';
				$.each(json['list'][0]['a'],function(m){
					html += '<option value="1_0_'+m+'_'+json['list'][0]['a'][m]['llDuration']+'" data-channel="'+json['list'][0]['a'][m]['CH']+'">'+json['list'][0]['a'][m]['name']+'</option>';
				});
				html += '</select></div>';
				input.replaceWith(html);
				$('#clip_'+id).data('channel',json['list'][0]['a'][0]['CH']);

				$('#'+id+'_audio').combox();
				$('#audio_'+id+' select').change(function(){
					$('#clip_'+id).data('channel',$(this).find(':selected').data('channel'));
				});
				
			}else{
				alertMsg.error('所选音频文件无效');
			}
		});
	}

	function chooseSrt(form, params){
		var url = form.attr('action')+'/srt?file_path='+params.filePath;
		var id = '<?php echo $id;?>';
		var input = $('#srt_'+id);
		$.getJSON(url,function(json){
			if($(json['list'][0]['c']).size() > 0){
				var html = '<div id="srt_'+id+'"><input type="hidden" name="'+id+'[filename][2]" value="'+json['filename']+'">';
				html += '<select name="'+id+'[cg][]" id="'+id+'_srt" class="combox">';
				$.each(json['list'][0]['c'],function(m){
					html += '<option value="2_0_'+m+'_'+json['list'][0]['c'][m]['llDuration']+'">'+json['list'][0]['c'][m]['name']+'</option>';
				});
				html += '</select></div>';
				input.replaceWith(html);
				$('#'+id+'_srt').combox();
			}else{
				alertMsg.error('所选字幕文件无效');
			}
		});
	}

	
</script>
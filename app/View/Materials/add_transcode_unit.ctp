<div class="unit" id="clip_<?php echo $id?>" data-id="<?php echo $id?>" data-channel="<?php echo $channel;?>">
	<input type="hidden" name="ids[]" value="<?php echo $id;?>">
	<input type="hidden" name="<?php echo $id?>[filename][0]" value="<?php echo $filename?>" />
	<input type="hidden" name="<?php echo $id?>[isdvd][]" value="<?php echo $isdvd?>" />
	<input type="hidden" name="<?php echo $id?>[pgm][]" value="<?php echo '0_'.$pgm['id']?>" />
	<input type="hidden" name="<?php echo $id?>[video][]" value="<?php echo '0_'.$pgm['id'].'_'.$video['id'].'_'.$video['duration']?>" />
	<input type="hidden" name="<?php echo $id?>[audio][]" value="<?php echo '0_'.$pgm['id'].'_'.$audio['id'].'_'.$audio['duration']?>" />
	<input type="hidden" name="<?php echo $id?>[cg][]" value="<?php echo '0_'.$pgm['id'].'_'.$cg['id'].'_'.$cg['duration']?>" />
	<div class="unitNode expand">
		
	</div>
	<div class="unitBody">
		<input type="text" name="<?php echo $id?>[taskName]" value="<?php echo $pgm['name']?>" class="media_title readonly"/>

		<ul class="media_list">
			<li>
				<span>视频：</span>
				<input type="text" class="middle readonly" name="video" value="<?php echo $video['name']?>" disabled="disabled">
				<div class="clear"></div>
			</li>
			<li>
				<span>音频：</span>
				<input type="text" id="audio_<?php echo $id?>" class="middle readonly" name="audio" value="<?php echo $audio['name']?>" disabled="disabled">
				<div class="buttonActive" style="float: left;margin-left: 3px;">
					<div class="buttonContent">
						<button class="add_audio" href="<?php echo $this->Html->url('/materials/storage/chooseAudio/'.$id);?>" target="dialog" rel="add_stc" mask="true" width="800" height="550">外挂音频</button>
					</div>
				</div>
				<div class="clear"></div>
			</li>
			<li>
				<span>字幕：</span>
				<input type="text" id="srt_<?php echo $id?>" class="middle readonly" name="cg" value="<?php echo $cg['name']?>" readonly="true" >
				<div class="buttonActive" style="float: left;margin-left: 3px;">
					<div class="buttonContent">
						<button class="add_stc" href="<?php echo $this->Html->url('/materials/storage/chooseSrt/'.$id);?>" target="dialog" rel="add_stc" mask="true" width="800" height="550">外挂字幕</button>
					</div>
				</div>
				<div class="clear"></div>
			</li>
		</ul>
	</div>
	<input onclick="checkoutmetaDataForm('<?php echo $id;?>')" title="编辑元数据" style="float: left; margin: 2px 2px 2px -115px;" type="radio" name="metadataRadio" value="<?php echo $id;?>"/>
	<span class="close_unit"></span>
	<div class="clear"></div>
</div>
<script type="text/javascript">
	$('.close_unit').die().live('click',function(){
		var formID = $(this).parent('.unit').attr('data-id');
		$(this).parent('.unit').remove();
		$('#' + formID).remove();
		$('#upload_file input[type=radio]').first().click();
		if($('#upload_file .unit').size() == 0){
			$('#defaultUploadInput').show();
		}
	});
</script>
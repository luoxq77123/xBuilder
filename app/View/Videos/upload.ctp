<?php 
echo $this->Html->css('uploadify');
echo $this->Html->script('jquery-1.7.2.min');
echo $this->Html->script('jquery.uploadify-3.1.min?ver=' . time());
?>
<script type="text/javascript">
var i = 0;

$(function() {
	creatUploadFlash('video_file', 'video_file_hidden', 'upload_state', '<?php echo __('Upload successfully');?>');
});

function creatUploadFlash(objId, hiddenId, noticeId, message){
	$('#' + objId).uploadify({
        'swf'      : '<?php echo $this->webroot;?>files/uploadify.swf',
        'uploader' : '<?php echo $this->webroot;?>uploads/video',
        'auto' : false,
        'onUploadSuccess' : function(file, data, response) {
            $('#'+hiddenId).val(data);
            $('#'+noticeId).html(message);
        } 
    });
}

function add_upload_flie(){
	i++;
	html = creatHtml(i);
	$('#upload_files').append(html);
	creatUploadFlash('video_file'+i, 'video_file_hidden'+i, 'upload_state'+i, '<?php echo __('Upload successfully');?>');
}

function creatHtml(i){
	return html = '<label for="video_name'+i+'"><?php echo __('File name');?>'+i+'：</label><input id="video_name'+i+'" type="text" name="data[name]" /><input type="hidden" name="video_name[]" id="video_name_hidden'+i+'" /><label for="video_file'+i+'"><?php echo __('Video');?>'+i+'：</label><input id="video_file'+i+'" type="file" name="data[file]" /><input type="hidden" name="video_file[]" id="video_file_hidden'+i+'" /><div id="upload_state'+i+'"></div><a href="javascript:$(\'#video_file'+i+'\').uploadify(\'upload\',\'*\')"><?php echo __('Upload file');?></a>'
}
</script>
<style>
form div{margin:0;padding:0;}
</style>
<?php 
echo $this->Form->create('Video');
echo $this->Form->input('video_file',array('type'=>'hidden','name'=>'video_file[]'));
echo $this->Form->input('video_name',array('type'=>'hidden','name'=>'video_name[]'));
?>
<?php
$options = array(
		'model' => 'Category',
		'isCumstomUrl' => false,
		'customUrl' => '#', 
		'param' => 'id',
		'config' => '',
		'selectId' => ''
	);
echo $this->Dwz->getTree($trees, $options);
?>
<h1><a href="javascript:add_upload_flie();"><?php echo __('Add video');?></a></h1>
<div id="upload_files"></div>
<?php
echo $this->Form->input('transcode_group_id', array('options'=>$transcodegroup, 'empty'=>__('Please select transcoding templates'), 'label'=>__('Templates select')));
echo $this->Form->end(_('submit'));
?>
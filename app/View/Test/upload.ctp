<?php 
echo $this->Html->css('uploadify');
echo $this->Html->script('jquery-1.7.2.min');
echo $this->Html->script('jquery.uploadify-3.1.min?ver=' . time());
?>
<script type="text/javascript">
var i = 0;

$(function() {
	creatUploadFlash('video_file', 'video_file_hidden', 'upload_state', '上传成功');
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
	creatUploadFlash('video_file'+i, 'video_file_hidden'+i, 'upload_state'+i, '上传成功');
}

function creatHtml(i){
	return html = '<label for="video_file'+i+'">水印'+i+'：</label><input id="video_file'+i+'" type="file" name="data[file]" /><input type="hidden" name="video_file[]" id="video_file_hidden'+i+'" /><div id="upload_state'+i+'"></div><a href="javascript:$(\'#video_file'+i+'\').uploadify(\'upload\',\'*\')">上传文件</a>'
}
</script>
<style>
form div{margin:0;padding:0;}
</style>
<?php 
echo $this->Form->create('Test');
echo $this->Form->input('video_file',array('type'=>'hidden','name'=>'video_file[]'));
?>
<h1><a href="javascript:add_upload_flie();">添加</a></h1>
<div id="upload_files"></div>
<?php
echo $this->Form->end('提交');
?>
<?php 
echo $this->Form->create('Upload',array('enctype'=>'multipart/form-data'));
echo $this->Form->input('file',array('type'=>'file','name'=>'data[file]'));
echo $this->Form->end('提交');
?>
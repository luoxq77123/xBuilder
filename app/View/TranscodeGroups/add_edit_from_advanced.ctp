<style type="text/css">
	.templateHeader span.template-name,.templateHeader span.template-category,.templateHeader span.template-split{line-height: 28px;font-weight: bold;}
	.templateHeader form.form-template-name,.templateHeader form.form-template-category,.templateHeader form.form-template-split{float: left;display: none;}
	.templateHeader span.t{padding-left: 10px;}
	.templateHeader span.modify-action, .templateHeader span.other-action{ cursor: pointer; color: blue; text-decoration:underline;}
	.templateHeader span.other-action{width: 35px;display: none}
</style>
	<fieldset class="fieldAbox">
		<div class="legend">
		</div>
		<div class="templateHeader paraList">
			<span class="t">模板名称:</span>
			<span class="name">
				<span class="template-name"><?php echo isset($this->request->data['TranscodeGroup']['name'])?$this->request->data['TranscodeGroup']['name']:'请输入名称';?></span>
				<?php 
					echo $this->Form->create('TranscodeGroup',array('inputDefaults'=>array('label'=>false,'div'=>false), 'class'=>'form-template-name'));
					echo $this->Form->input('id',array('type'=>'hidden','class'=>'template-id'));
					echo $this->Form->input('name', array('class'=>'required','id'=>'template-name'));
					echo $this->Form->end();
				?>
			</span>
			<div class="operate-template-name">
				<span onclick="modifyAttr('template-name')" class="t modify-action">修改名称</span>
				<span onclick="rename()" class="t other-action">保存</span>
				<span onclick="modifyCancel('template-name');" class="t other-action">取消</span>
			</div>
			
			<span class="t">所属分类:</span>
			<span class="category">
				<span class="template-category"><?php echo @isset($this->request->data['TranscodeCategory']['name'])?$this->request->data['TranscodeCategory']['name']:'请选择分类';?></span>
				<?php
					echo $this->Form->create('TranscodeGroup',array('inputDefaults'=>array('label'=>false,'div'=>false), 'class'=>'form-template-category'));
					echo $this->Form->input('id',array('type'=>'hidden','class'=>'template-id'));
					echo $this->Form->input('transcode_category_id', array('id'=>'template-category','options'=>$catagories, 'empty'=>array('请选择'), 'class'=>'combox'));
					echo $this->Form->end();
				?>
			</span>
			<div class="operate-template-category">
				<span onclick="modifyAttr('template-category')" class="t modify-action">修改分类</span>
				<span onclick="renameCategory()" class="t other-action">保存</span>
				<span onclick="modifyCancel('template-category');" class="t other-action">取消</span>
			</div>

			<span class="t">分片参数:</span>
			<span class="split">
				<span class="template-split"><?php echo isset($this->request->data['TranscodeGroup']['split'])?$this->request->data['TranscodeGroup']['split'] . '秒':'不分片';?> </span>
				<?php
					echo $this->Form->create('TranscodeGroup',array('inputDefaults'=>array('label'=>false,'div'=>false), 'class'=>'form-template-split'));
					echo $this->Form->input('id',array('type'=>'hidden','class'=>'template-id'));
					echo $this->Form->input('split', array('id'=>'template-split','type'=>'text','after'=>' 秒 （分片时长不能超过24小时）'));
					echo $this->Form->end();
				?>
			</span>
			<div class="operate-template-split">
				<span onclick="modifyAttr('template-split')" class="t modify-action">修改分片</span>
				<span onclick="resplit()" class="t other-action">保存</span>
				<span onclick="modifyCancel('template-split');" class="t other-action">取消</span>
			</div>
		</div>
	</fieldset>
<div class="clear"></div>
<div class="transcodeDetail" data-url="<?php echo $this->Html->url('/transcodes/index/'.@$transcode_group_id.'/'.@$sub_id);?>"></div>

<script type="text/javascript">
	$(function(){
		var $transcodeDetail = $('.transcodeDetail');
		var url = $transcodeDetail.data('url');
		$transcodeDetail.loadUrl(url,{},function(){
 	 			$transcodeDetail.find("[layoutH]").layoutH();
			});
	});

	var issetName = <?php echo (@$this->request->data['TranscodeGroup']['id'])?'true':'false'?>;
	function modifyAttr(id) {
		$('#' + id).removeAttr('disabled');
		$('.' + id).hide();
		$('.form-' + id).show();
		$('.operate-'+id+' .modify-action').hide().siblings('.other-action').show();
	}
	function modifyCancel(id){
		$('#' + id).attr('disabled','disabled');
		$('.' + id).show();
		$('.form-' + id).hide();
		$('.operate-'+id+' .modify-action').show().siblings('.other-action').hide();
	}
	var rename = function() {
		var $form = $('.templateHeader>.name>form');
		var templateName = $form.find("#template-name").val();

		if(templateName.length > 0){
			$.ajax({
				type : 'POST',
				url : $form.attr('action'),
				data : $form.serializeArray(),
				dataType : "json",
				cache: false,
				success:function(data){
					DWZ.ajaxDone(data);
					if (data.navTabId){
						navTab.reloadFlag(data.navTabId);
						$('.template-id').val(data.value);
						$(".template-name").html(templateName);
						modifyCancel('template-name');
						issetName = true;
					}
				},
				error: DWZ.ajaxError
			});
		}
	}

	var renameCategory = function () {
		if(!issetName) {
			alertMsg.info('需要先输入名称保存后，方可修改');
			return false;
		}

		var $form = $('.templateHeader>.category>form');
		var category_id = $form.find('#template-category').val();
		var c_text = $('#template-category').find("option:selected").text();
	
		if(category_id == 0){
			alertMsg.info('请选择分类');
			return false;
		}

		$.ajax({
			type:'POST',
			url:$form.attr('action'),
			data:$form.serializeArray(),
			dataType:"json",
			cache: false,
			success:function(data){
				DWZ.ajaxDone(data);
				if (data.navTabId){
					navTab.reloadFlag(data.navTabId);
					$(".template-category").html(c_text);
					modifyCancel('template-category');
				}
			},
			error: DWZ.ajaxError
		});
	}

	var resplit = function(){
		if(!issetName) {
			alertMsg.info('需要先输入名称保存后，方可修改');
			return false;
		}

		var $form = $('.templateHeader>.split>form');
		var templateSplit = $form.find("#template-split").val();

		if(parseInt(templateSplit) > 86400) {
			alertMsg.info('分片时长不能超过24小时');
			return false;
		}
		if(templateSplit.length > 0){
			$.ajax({
				type : 'POST',
				url : $form.attr('action'),
				data : $form.serializeArray(),
				dataType : "json",
				cache: false,
				success:function(data){
					DWZ.ajaxDone(data);
					if (data.navTabId){
						navTab.reloadFlag(data.navTabId);
						$('.template-id').val(data.value);
						$(".template-split").html(templateSplit+'秒');
						modifyCancel('template-split');
					}
				},
				error: DWZ.ajaxError
			});
		}
	}
</script>
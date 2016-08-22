<style type="text/css">
	.adds{float:right;width:14px;height:14px;margin:12px;}
</style>
<div class="transcodeList">
	<div class="panel" defH="423">
		<h1>子模板列表
			<a alt="添加子模板" class="adds" href="<?php echo $this->Html->url('/transcodes/add/');?>" data-id="<?php echo @$transcodeGroup['TranscodeGroup']['id'];?>" data-rel="transcodeContent"></a>
		</h1>
		<div id="transcodeTree">
			<?php if (isset($transcodeGroup['Transcode'])):?>
			<ul class="tree expand">
				<?php
						foreach($transcodeGroup['Transcode'] as $key => $blist):
					?>
					<li class="<?php if($id == $blist['id']) echo 'selected';?>"><a href="<?php echo $this->Html->url('/transcodes/edit/'.$blist['id']);?>" rel="transcodeContent" target="ajax"  categoryid="<?php echo $blist['id']?>"><?php echo  $key+1 . ") " . $blist['title']?></a>
					</li>
				<?php endforeach;?>
			</ul> 
			<?php endif;?>
		</div>
	</div>
</div>
<div id="transcodeContent" class="transcodeContent" layoutH="70" data-url="<?php echo $this->Html->url('/transcodes/edit/'.@$id);?>"></div>
<script type="text/javascript">
	$(function(){
		var $transcodeContent = $('.transcodeContent');
		var url = $transcodeContent.data('url');
		$transcodeContent.loadUrl(url,{},function(){
 	 			$transcodeContent.find("[layoutH]").layoutH();
			});

		$('.transcodeList .adds').click(function(){
			var $this = $(this);
			var $transcodeGroup_id = $this.data('id') || $('.templateHeader .name .template-id').val(); //页面还是紧耦合姿态
			if(!$transcodeGroup_id) {
				alertMsg.info('需要输入模板名称后，才可以操作！');
				return false;
			}

			$('#transcodeTree div.selected').removeClass();
			var url = $this.attr('href') + $transcodeGroup_id;

			$('.'+$this.data('rel')).loadUrl(url, {}, function(){
				$('.'+$this.data('rel')).find("[layoutH]").layoutH();
			});
			return false;
		});

		$('button[type=delete]').die().live('click', function(){
			var tgid = $(this).data('tgid');
			if($(this).data('id')){
				url = '<?php echo $this->Html->url("/transcodes/delete/");?>'+$(this).data('id');
				alertMsg.confirm('您确定要删除子模板?',{
					okCall: function(){
						$.get(url, null, function(json){
							DWZ.ajaxDone(json);
							if(json.statusCode == DWZ.statusCode.ok){
								resetTranscodeContent(tgid,json.tid);
							}}, "json");
					}
				});
			}else{
				resetTranscodeContent(tgid);
			}
			return false;
		});
	});

	function resetTranscodeContent(tgid,tid){
		var url_params = tgid;
		if(typeof(tid) != 'undefined') url_params += '/'+tid;
		$('#transcodeTree').loadUrl('<?php echo $this->Html->url("/transcodes/ajaxlist/");?>'+url_params,{},function(){
			$('.transcodeContent').html('没有子模板，请添加子模板！');
			if($('#transcodeTree ul li').size() > 0){
				$('#transcodeTree ul li:last a').trigger('click');
			}
		});
		return false;
	}
</script>
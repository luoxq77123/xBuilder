<?php if (!empty($params)) $paginator->options(array('url' => $params));?>
<form id="pagerForm" method="post" action="<?php echo $this->Html->url(array('controller' => 'TranscodeGroups','action' => 'index', @$this->params['pass'][0]))?>">
	<input type="hidden" name="pageNum" value="<?php echo $param['pageNum'];?>" />
	<input type="hidden" name="numPerPage" value="<?php echo $param['numPerPage'];?>" />
    <input type="hidden" name="searchType" value="<?php echo $param['searchType'];?>" />
    <input type="hidden" name="keyword" value="<?php echo $param['keyword'];?>" />
</form>
<div class="pageContent" style="margin:0 312px 0 5px;">
	<div class="topPanelBar">
		<ul class="toolBar">
			<li><a class="add_Tvideo" href="<?php echo $this->Html->url(array('action'=>'add',1));?>" target="dialog" rel="addvideo" param="closeSubTemplate" mask="true" width="1040" height="580" title="创建转码模板" maxable="false"><span>创建</span></a></li>

			<!-- <li><a class="add_Taudio" href="<?php //echo $this->Html->url(array('action'=>'add',2));?>" target="dialog" rel="addaudio" param="closeSubTemplate" mask="true" width="960" height="580" title="创建音频模板" maxable="false"><span>音频</span></a></li> -->

            <li><a class="edit" href="<?php echo $this->Html->url(array('action'=>'edit'));?>" target="selectedTodo" postType="string" datatype="edit" param="closeSubTemplate" mask="true" width="1040" height="580" title="编辑模板" maxable="false" rel="ids[]"><span>编辑</span></a></li>

			<li><a class="merge" href="<?php echo $this->Html->url(array('action'=>'merge'));?>" datatype="replay" target="selectedTodo" mask="true" width="300" height="150" rel="ids[]"><span>合并</span></a></li>

            <li><a class="split" href="<?php echo $this->Html->url(array('action'=>'split'));?>" title="拆分模版组" target="selectedTodo" rel="ids[]" mask="true" datatype="edit"><span>拆分</span></a></li>

            <li class="category_move"><a class="category_move" href="<?php echo $this->Html->url(array('action'=>'category_move',@$this->params['pass'][0]));?>" target="selectedTodo" postType="string" datatype="replay" mask="true" width="350" height="250" rel="ids[]"><span><?php echo __('category move');?></span></a></li>

			<li><a class="delete" rel="ids[]" href="<?php echo $this->Html->url(array('action'=>'del'))?>" target="selectedTodo" title="确定要删除吗?"><span>删除</span></a></li>
		</ul>
	</div>
	<table class="table" width="100%" layoutH="134">
		<thead>
			<tr>
				<th width="22" valign="middle"><input type="checkbox" group="ids[]" class="checkboxCtrl"></th>
				<th>模板名称</th>
				<th align="center" width="150">模板类型</th>
				<th align="center" width="150">模板分类</th>
				<th align="center" width="150">创建时间</th>
				<th align="center" width="100">操作</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($allData as $v):?>
			<tr target="sid_user" rel="<?php echo $v['TranscodeGroup']['id']?>" rewrite="xxinfo" showMore="true" url="<?php echo $this->Html->url(array('action' => 'view', $v['TranscodeGroup']['id']));?>">
				<td><input name="ids[]" value="<?php echo $v['TranscodeGroup']['id']?>" type="checkbox" editHeight="580"></td>
				<td><?php echo $v['TranscodeGroup']['name']?></td>
				<td><?php if($v['TranscodeGroup']['type'] == 1){echo '视频';}else{echo '音频';}?></td>
				<td><?php echo $v['TranscodeCategory']['name']?></td>
				<td><?php echo $v['TranscodeGroup']['created']?></td>
				<td>
					<a target="dialog" href="<?php echo $this->Html->url(array('action' => 'edit', $v['TranscodeGroup']['id']))?>" mask="true" rel="edit" param="closeSubTemplate" width="1040" height="580" title="编辑模板" maxable="false" class="btnEdit"><?php echo __('edit');?></a>
	                <a title="<?php echo __('Delete transcode template will also delete the sub-template, and want to continue?');?>" target="ajaxTodo" href="<?php echo $this->Html->url(array('action' => 'del', $v['TranscodeGroup']['id']))?>" class="btnDel"><?php echo __('delete');?></a>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php $pageParams = $this->Paginator->params();
	?>
	<div class="panelBar">
		<div class="pages">
			<span>共<?php echo $pageParams['count']; ?>条记录，每页<?php echo $pageParams['limit']?>条</span>
		</div>
		
		<div class="pagination" allPage="<?php echo $pageParams['pageCount']?>" targetType="navTab" totalCount="<?php echo $pageParams['count']?>" numPerPage="<?php echo $pageParams['limit']?>" pageNumShown="10" currentPage="<?php echo $param['pageNum'];?>"></div>
	</div>
</div>
<div style="width:302px;position: absolute;top:0;right:5px;" layoutH="1">
	<div class="cmpc_ri_top_tit">模板信息</div>
	<div id="xxinfo" class="xxinfo" layoutH="55">
		
	</div>
</div>
<input type="hidden" name="cid" id="cid" value="<?php echo $cid;?>">
<script>
$(function(){
	$("#FileFormat").live('change',function(){
		var file = $("#FileFormat").val();
		if(!file){
			alertMsg.error('请选择文件!');
    		return false;
		}
		$('#getvideolist').ajaxUrl({'type':'post','url':'XmlAnalysis/getVideoList','data':{file:file}});
		$('#filevalue').ajaxUrl({'type':'post','url':'XmlAnalysis/getFileValue','data':{file:file}});
		$('#getaudiolist').ajaxUrl({'type':'post','url':'XmlAnalysis/getAudioList'});
		$('#getaudiovalue').ajaxUrl({'type':'post','url':'XmlAnalysis/getAudioValue'});
		$('#videovalue.paraList').ajaxUrl({'type':'post','url':'XmlAnalysis/getVideoValue'});
	});
	$("#VideoFormat").live('change',function(){
		var video = $("#VideoFormat").val();
		if(!video){
			alertMsg.error('请选择视频!');
    		return false;
		}
		$('#videovalue.paraList').ajaxUrl({'type':'post','url':'XmlAnalysis/getVideoValue','data':{video:video}});
// 		$('#videovalue.paraList').ajaxUrl({'type':'post','url':'XmlAnalysis/getVideoValue','data':{video:video},'callback':function(a){
// 			$('#VideoParam').ajaxUrl({'type':'post','url':'XmlAnalysis/getParamHtml','data':{video:video}});
// 		}});
		$('#getaudiolist').ajaxUrl({'type':'post','url':'XmlAnalysis/getAudioList','data':{video:video}});
	});
	$("#AudioFormat").live('change',function(){
		var audio = $("#AudioFormat").val();
		if(!audio){
			alertMsg.error('请选择音频!');
    		return false;
		}
		$('#getaudiovalue').ajaxUrl({'type':'post','url':'XmlAnalysis/getAudioValue','data':{audio:audio}});
	});
})
</script>


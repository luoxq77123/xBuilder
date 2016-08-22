<?php if (!empty($params)) $paginator->options(array('url' => $params));?>
<form id="pagerForm" method="post" action="<?php echo $this->Html->url(array('controller' => 'recycle', 'action' => 'index'))?>">
	<input type="hidden" name="pageNum" value="<?php echo $param['pageNum'];?>" />
	<input type="hidden" name="numPerPage" value="<?php echo $param['numPerPage'];?>" />
</form>
<div style="margin:0 312px 0 5px;">
	<div class="topPanelBar">
		<ul class="toolBar">
			<li><a class="reduction" rel="ids[]" href="<?php echo $this->Html->url(array('action'=>'reduction'));?>" target="selectedTodo" title="<?php echo __('Are you reduction the video');?>"><span><?php echo __('Reduction');?></span></a></li>
			<li><a class="remove" href="<?php echo $this->Html->url(array('action' => 'delete'))?>" target="selectedTodo" rel="ids[]" title="<?php echo __('Are you complete delete');?>"><span><?php echo __('Complete delete');?></span></a></li>
		</ul>
	</div>
	<table class="table" width="100%" layoutH="132">
		<thead>
			<tr class="center">
				<th width="22" valign="middle"><input type="checkbox" group="ids[]" class="checkboxCtrl"></th>
				<th align="center" width="60"><span>关键帧</span></th>
				<th><span><?php echo __('title');?></span></th>
				<th width="26" align="center"><span style="text-indent: -1111px;">任务类型</span></th>
                <th align="center" width="150"><?php echo __('with category');?></th>
                <th align="center" width="80"><?php echo __('Upload user');?></th>
				<th align="center" width="170"><?php echo __('Upload time');?></th>
				<th align="center" width="170">删除时间</th>
				<th align="center" width="60"><?php echo __('Length');?></th>
				<th align="center" width="110"><?php echo __('Transcoding template');?></th>
				<th align="center" width="70"><?php echo __('Status');?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($data as $v):?>
			<tr target="sid_user" rel="<?php echo $v['Content']['id'];?>" rewrite="detailInfo" showMore="true" url="<?php echo $this->Html->url(array('controller'=>'tasks','action' => 'detail', $v['Content']['id'], $v['Content']['category_id']));?>">
				<td><input name="ids[]" value="<?php echo $v['Content']['id'];?>" cel="<?php echo $v['Content']['category_id'];?>" type="checkbox"></td>
				<td><?php 
					if($v['Content']['status'] != 1){
						if($v['Content']['type'] == 1){
				?>
					<img src="<?php echo $this->Html->url('/images/index/'.$v['Content']['task_id'].'.png?_'.time());?>" width="50" height="40" title="点击预览视频" />
				<?php 
						}elseif($v['Content']['type'] == 2){
				?>
					<img src="themes/default/images/audio.png" />
				<?php 
						}
					}else{
				?>
					<img src="themes/default/images/trancoding.png" />
				<?php }?></td>
				<td><?php echo $v['Content']['title']?></td>
				<?php 
				 echo $this->element('task/taskType',array('taskType'=>$v['Content']['source']));
				?>
                <td><?php echo $v['Category']['name'];?></td>
                <td><?php echo $v['Content']['user_name'];?></td>
				<td><?php echo $v['Content']['created'];?></td>
				<td><?php echo $v['Content']['delete_time'];?></td>
				<td><?php echo @$v['Video'][0]['duration']?gmstrftime('%H:%M:%S',$v['Video'][0]['duration']/1000):'';?></td>
				<td><?php echo $v['TranscodeGroup']['name'];?></td>
				<td><?php echo __('taskStatus_'.$v['Content']['status']);
				?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php $pageParams = $this->Paginator->params();?>
	<div class="panelBar">
		<div class="pages">
			<span><?php echo __('All');?><?php echo $pageParams['count']; ?><?php echo __('Item record');?>，<?php echo __('Per Page');?><?php echo $pageParams['limit']?><?php echo __('Item');?></span>
		</div>
		
		<div class="pagination" allPage="<?php echo $pageParams['pageCount']?>" targetType="navTab" totalCount="<?php echo $pageParams['count']?>" numPerPage="<?php echo $pageParams['limit']?>" pageNumShown="10" currentPage="<?php echo $param['pageNum'];?>"></div>
	</div>
</div>

<div id="detailInfo" style="width:302px;position: absolute;top:0;right:5px;" layoutH="0">
	<div class="cmpc_ri_top_tit"><?php echo __('Task detail');?></div>
	<div class="xxinfo" layoutH="52">
		<div class="xxinfo_tit"></div>
		<div layoutH="290">
			
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function(){
		$('#searchVideos form').attr('action','<?php echo $this->Html->url('/recycle');?>');
	})
</script>
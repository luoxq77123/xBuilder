<?php if (!empty($params)) $paginator->options(array('url' => $params));?>
<form id="pagerForm" method="post" action="<?php echo $this->Html->url(array('controller' => 'tasks', 'action' => 'index', @$this->params['pass'][0]))?>">
	<input type="hidden" name="pageNum" value="<?php echo $param['pageNum'];?>" />
	<input type="hidden" name="numPerPage" value="<?php echo $param['numPerPage'];?>" />
    <input type="hidden" name="searchType" value="<?php echo @$param['searchType']?:'';?>" />
    <input type="hidden" name="keyword" value="<?php echo @$param['keyword']?:'';?>" />
</form>
<div class="pageContent" style="margin:0 312px 0 5px;">
	<div class="topPanelBar">
		<ul class="toolBar">

			<li><a class="add_task" href="<?php echo $this->Html->url('/tasks/updateChoose/'.$id);?>" target="dialog" rel="upload" mask="true" width="372" height="280" title="添加任务" resizable="false" maxable="false" upload="<?php echo $id;?>" param="closeEven"><span>添加任务</span></a></li>
			<li><a class="task_category_move" href="<?php echo $this->Html->url('/materials/category_move/'.$id);?>" target="selectedTodo" posttype="string" datatype="replay" mask="true" width="350" height="170" rel="ids[]"><span>分类迁移</span></a></li>

			<li><a class="promote" href="<?php echo $this->Html->url('/tasks/priority/promote');?>" target="selectedTodo" title="确认操作" rel="ids[]"><span>提升优先级</span></a></li>
			<li><a class="reduce" href="<?php echo $this->Html->url('/tasks/priority/reduce');?>" target="selectedTodo"title="确认操作" rel="ids[]"><span>降低优先级</span></a></li>
            
            <li><a class="replay" href="<?php echo $this->Html->url('/tasks/replay/');?>" target="selectedTodo" posttype="string" datatype="replay" mask="true"  width="600" height="530" rel="ids[]"><span><?php echo __('Again transcoding');?></span></a></li>

			<li><a class="refresh" href="#" onclick="navTab.reload('<?php echo $this->here;?>')"><span><?php echo __('refresh');?></span></a></li>
			<li><a class="delete" href="<?php echo $this->Html->url(array('action' => 'gotorecycle'))?>" target="selectedTodo" title="<?php echo __('Are you delete');?>" rel="ids[]"><span><?php echo __('delete');?></span></a></li>
		</ul>
	</div>
	<table class="table" width="100%" layoutH="132" border="1">
		<thead>
			<tr class="center">
				<th width="22" valign="middle"><input type="checkbox" group="ids[]" class="checkboxCtrl"></th>
				<th align="center" width="60"><span>关键帧</span></th>
				<th><span><?php echo __('title');?></span></th>
				<th width="26" align="center" e-width="26"><span style="text-indent: -1111px;">任务类型</span></th>
				<th align="center" width="60">优先级</th>
                <th align="center" width="150"><?php echo __('with category');?></th>
                <th align="center" width="80"><?php echo __('Upload user');?></th>
				<th align="center" width="170"><?php echo __('Upload time');?></th>
				<th align="center" width="60"><?php echo __('Length');?></th>
				<th align="center" width="110"><?php echo __('Transcoding template');?></th>
				<th align="center" width="70"><?php echo __('Status');?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($data as $v):?>
			<tr target="sid_user" rel="<?php echo $v['Content']['id'];?>" rewrite="detailInfo" showMore="true" url="<?php echo $this->Html->url(array('action' => 'detail', $v['Content']['id'], $v['Content']['category_id']));?>">
				<td><input name="ids[]" value="<?php echo $v['Content']['id'];?>" cel="<?php echo $v['Content']['category_id'];?>" type="checkbox"></td>
				<td>
				<?php if($v['Content']['type'] == 1){?>
					<img src="<?php echo $this->Html->url('/images/index/'.$v['Content']['task_id'].'.png?_'.time());?>" width="50" height="40" title="点击预览视频" />
				<?php }elseif($v['Content']['type'] == 2){?>
					<img src="themes/default/images/audio.png" />
				<?php }?>
				</td>
				<td><?php echo $v['Content']['title']?></td>
				<?php 
				 echo $this->element('task/taskType',array('taskType'=>$v['Content']['source']));
				?>
				<td><?php echo $v['Content']['priority'] == 100?'高':'低'?></td>
                <td><?php echo $v['Category']['name'];?></td>
                <td><?php echo $v['Content']['user_name'];?></td>
				<td><?php echo $v['Content']['created'];?></td>
				<td><?php echo @$v['Video'][0]['duration']?gmstrftime('%H:%M:%S',$v['Video'][0]['duration']/1000):'';?></td>
				<td><?php echo $v['TranscodeGroup']['name'];?></td>
				<td><?php echo __('taskStatus_'.$v['Content']['status']);?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php $pageParams = $this->Paginator->params();?>
	<div class="panelBar">
		<div class="pages">
			<span><?php echo __('All');?><?php echo $pageParams['count']; ?><?php echo __('Item record');?>，<?php echo __('Per Page');?><?php echo $pageParams['limit']?><?php echo __('Item');?></span>
		</div>
		<div class="pagination" targetType="navTab" totalCount="<?php echo $pageParams['count']?>" numPerPage="<?php echo $pageParams['limit']?>" pageNumShown="10" currentPage="<?php echo $param['pageNum'];?>"></div>
	</div>
</div>
<div id="detailInfo" style="width:302px;position: absolute;top:0;right:5px;">
	<div class="cmpc_ri_top_tit"><?php echo __('Task detail');?></div>
	<div class="xxinfo" layoutH="52">
		<div class="xxinfo_tit"></div>
		<div layoutH="290"></div>
	</div>
</div>
<script type="text/javascript">
	$(function(){
		$('#searchVideos form').attr('action','<?php echo $this->Html->url('/tasks');?>');
	})
</script>
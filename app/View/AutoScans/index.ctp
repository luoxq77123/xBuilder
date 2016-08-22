<?php if (!empty($params)) $paginator->options(array('url' => $params));?>
<form id="pagerForm" method="post" action="<?php echo $this->Html->url(array('controller' => 'AutoScans', 'action' => 'index'))?>">
    <input type="hidden" name="pageNum" value="<?php echo $param['pageNum'];?>" />
    <input type="hidden" name="numPerPage" value="<?php echo $param['numPerPage'];?>" />
    <input type="hidden" name="searchType" value="<?php echo @$param['searchType']?:'';?>" />
    <input type="hidden" name="keyword" value="<?php echo @$param['keyword']?:'';?>" />
</form>
<div class="topPanelBar">
    <ul class="toolBar">
        <li style="margin-left: 10px;"><a class="add_template" href="<?php echo $this->Html->url(array('controller'=>'AutoScans','action'=>'add_edit'));?>" target="dialog" mask="true" rel="add" width="600" height="585"><span><?php echo __('Create AutoScan');?></span></a></li>
        <li><a width="600" height="585" class="edit" href="<?php echo $this->Html->url(array('controller'=>'AutoScans','action'=>'add_edit'));?>" target="selectedTodo" posttype="string" datatype="edit" mask="true" width="600" height="325" title="<?php echo __('Edit AutoScan');?>"><span><?php echo __('Edit AutoScan');?></span></a></li>
        <li><a class="remove" href="<?php echo $this->Html->url(array('controller'=>'AutoScans','action'=>'del'))?>" target="selectedTodo" posttype="string" title="<?php echo __('Are you delete');?>"><span><?php echo __('Delete AutoScan');?></span></a></li>
    </ul>
</div>
<div style="margin: 0 20px 0 10px;">
<table class="table" width="100%" layoutH="155">
	<thead>
		<tr>
			<th width="20"><input type="checkbox" group="ids" class="checkboxCtrl"></th>
			<th>路径</th>
			<th align="center" width="120">模板名称</th>
            <th align="center" width="180">后缀名</th>
            <th align="center" width="180">操作人</th>
			<th align="center" width="120">添加时间</th>
            <th align="center" width="80"><?php echo __('Operating');?></th>
		</tr>
	</thead>
	<tbody>
<?php 
if(count($configs) > 0):
foreach($configs as $key=>$value):
?>
<tr target="sid_user" rel="<?php echo $value['AutoScan']['id']; ?>">
	<td><input name="ids" value="<?php echo $value['AutoScan']['id']; ?>" type="checkbox" editHeight=""></td>
	<td><?php echo $value['AutoScan']['path'];?></td>
	<td><?php echo $value['TranscodeGroup']['name'];?></td>
	<td><?php echo $value['AutoScan']['suffix'];?></td>
	<td><?php echo $value['AutoScan']['user_name'];?></td>
	<td><?php echo $value['AutoScan']['addtime'];?></td>
	<td>
		<a title="<?php echo __('Edit AutoScan');?>" target="dialog" href="<?php echo $this->Html->url(array('action' => 'add_edit', $value['AutoScan']['id']))?>" mask="true" rel="edit" width="600" height="585" class="btnEdit"><?php echo __('edit');?></a>
		<a title="<?php echo __('AutoScan deleted successfully');?>" target="ajaxTodo" href="<?php echo $this->Html->url(array('action' => 'del', $value['AutoScan']['id']))?>" class="btnDel"><?php echo __('delete');?></a>
	</td>
</tr>
<?php 
	endforeach;
	else:
?>
<tr>
	<td><?php echo __('Nothing data');?></td>
</tr>
<?php
	endif;
?>
	</tbody>
</table>
<?php $pageParams = $this->Paginator->params();?>
<div class="panelBar footer">
	<div class="pages">
		<span><?php echo __('All');?><?php echo $pageParams['count']; ?><?php echo __('Item record');?>，<?php echo __('Per Page');?><?php echo $pageParams['limit']?><?php echo __('Item');?></span>
	</div>	
	<div class="pagination" allPage="<?php echo $pageParams['pageCount']?>" targetType="navTab" totalCount="<?php echo $pageParams['count']?>" numPerPage="<?php echo $pageParams['limit']?>" pageNumShown="10" currentPage="<?php echo $param['pageNum'];?>"></div>
</div>
</div>

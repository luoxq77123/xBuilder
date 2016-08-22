<?php if (!empty($params)) $paginator->options(array('url' => $params));?>
<form id="pagerForm" method="post" action="<?php echo $this->Html->url(array('controller' => 'Metadatas', 'action' => 'index'))?>">
    <input type="hidden" name="pageNum" value="<?php echo $param['pageNum'];?>" />
    <input type="hidden" name="numPerPage" value="<?php echo $param['numPerPage'];?>" />
    <input type="hidden" name="searchType" value="<?php echo @$param['searchType']?:'';?>" />
    <input type="hidden" name="keyword" value="<?php echo @$param['keyword']?:'';?>" />
</form>
<div class="topPanelBar">
    <ul class="toolBar">
        <li style="margin-left: 10px;"><a class="add_template" href="<?php echo $this->Html->url(array('controller'=>'Metadatas','action'=>'add_edit'));?>" target="dialog" mask="true" rel="add" width="680" height="610"><span><?php echo __('Create Metadata');?></span></a></li>
        <li><a width="680" height="610" class="edit" href="<?php echo $this->Html->url(array('controller'=>'Metadatas','action'=>'add_edit'));?>" target="selectedTodo" posttype="string" datatype="edit" mask="true" width="600" height="325" title="<?php echo __('Edit Metadata');?>"><span><?php echo __('Edit Metadata');?></span></a></li>
        <li><a class="remove" href="<?php echo $this->Html->url(array('controller'=>'Metadatas','action'=>'del'))?>" target="selectedTodo" posttype="string" title="<?php echo __('Are you delete');?>"><span><?php echo __('Delete Metadata');?></span></a></li>
        <li><a class="refresh" href="<?php echo $this->Html->url(array('controller'=>'Metadatas','action'=>'pre'));?>" target="dialog" mask="true" rel="add" width="359" height="488"><span><?php echo __('Preview');?></span></a></li>
    </ul>
</div>
<div style="margin: 0 20px 0 10px;">
<table class="table" width="100%" layoutH="155">
	<thead>
		<tr>
			<th width="20"><input type="checkbox" group="ids" class="checkboxCtrl"></th>
			<th align="center" width="120">配置名称</th>
            <th align="center" width="180">操作人</th>
			<th align="center" width="120">添加时间</th>
			<th align="center" width="50">排序值</th>
            <th align="center" width="80"><?php echo __('Operating');?></th>
		</tr>
	</thead>
	<tbody>
<?php 
if(count($configs) > 0):
foreach($configs as $key=>$value):
?>
<tr target="sid_user" rel="<?php echo $value['Metadata']['id']; ?>">
	<td><input name="ids" value="<?php echo $value['Metadata']['id']; ?>" type="checkbox" editHeight=""></td>
	<td><?php echo $value['Metadata']['title'];?></td>
	<td><?php echo $value['User']['email'];?></td>
	<td><?php echo date('Y-m-d H:i:s', $value['Metadata']['create_time']);?></td>
	<td><?php echo $value['Metadata']['order'];?></td>
	<td>
		<a title="<?php echo __('Edit Metadata');?>" target="dialog" href="<?php echo $this->Html->url(array('action' => 'add_edit', $value['Metadata']['id']))?>" mask="true" rel="edit" width="680" height="610" class="btnEdit"><?php echo __('edit');?></a>
		<a title="<?php echo __('Metadata deleted successfully');?>" target="ajaxTodo" href="<?php echo $this->Html->url(array('action' => 'del', $value['Metadata']['id']))?>" class="btnDel"><?php echo __('delete');?></a>
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

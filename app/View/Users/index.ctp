<?php if (!empty($params)) $paginator->options(array('url' => $params));?>
<form id="pagerForm" method="post" action="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'index'))?>">
    <input type="hidden" name="pageNum" value="<?php echo $param['pageNum'];?>" />
    <input type="hidden" name="numPerPage" value="<?php echo $param['numPerPage'];?>" />
    <input type="hidden" name="searchType" value="<?php echo @$param['searchType']?:'';?>" />
    <input type="hidden" name="keyword" value="<?php echo @$param['keyword']?:'';?>" />
</form>
<div class="topPanelBar">
    <ul class="toolBar">
        <li style="margin-left: 10px;"><a class="add" href="<?php echo $this->Html->url(array('controller'=>'users','action'=>'add'));?>" target="dialog" mask="true" rel="add"><span><?php echo __('Create User');?></span></a></li>
        <li><a class="edit" href="<?php echo $this->Html->url(array('controller'=>'users','action'=>'user_information_edit'));?>" target="selectedTodo" posttype="string" datatype="edit" mask="true" width="600" height="325" title="<?php echo __('Edit user');?>"><span><?php echo __('Edit user');?></span></a></li>
        <li><a class="user_delete" href="<?php echo $this->Html->url(array('controller'=>'users','action'=>'del'))?>" target="selectedTodo" posttype="string" title="<?php echo __('Are you delete');?>"><span><?php echo __('Delete User');?></span></a></li>
    </ul>
</div>
<div style="margin: 0 20px 0 10px;">
<table class="table" width="100%" layoutH="155">
	<thead>
		<tr>
			<th width="20"><input type="checkbox" group="ids" class="checkboxCtrl"></th>
			<th><?php echo __('Account');?></th>
			<th align="center" width="120"><?php echo __('Nickname');?></th>
			<th align="center" width="120"><?php echo __('Role');?></th>
            <th align="center" width="180"><?php echo __('Create data');?></th>
            <th align="center" width="80"><?php echo __('Operating');?></th>
		</tr>
	</thead>
	<tbody>
<?php 
if(count($users) > 0):
foreach($users as $key=>$value):
?>
<tr target="sid_user" rel="<?php echo $value['User']['id']; ?>">
	<td><input name="ids" value="<?php echo $value['User']['id']; ?>" type="checkbox" editHeight=""></td>
	<td><?php echo $value['User']['email'];?></td>
	<td><?php echo $value['User']['account'];?></td>
	<td><?php echo $value['Role']['name'];?></td>
	<td><?php echo $value['User']['created'];?></td>
	<td>
		<a title="<?php echo __('Edit user');?>" target="dialog" href="<?php echo $this->Html->url(array('action' => 'user_information_edit', $value['User']['id']))?>" mask="true" rel="edit" width="600" height="325" class="btnEdit"><?php echo __('edit');?></a>
		<a title="<?php echo __('User deleted successfully');?>" target="ajaxTodo" href="<?php echo $this->Html->url(array('action' => 'del', $value['User']['id']))?>" class="btnDel"><?php echo __('delete');?></a>
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

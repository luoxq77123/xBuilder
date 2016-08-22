<?php if (!empty($params)) $paginator->options(array('url' => $params));?>
<form id="pagerForm" method="post" action="<?php echo $this->Html->url(array('controller' => 'roles', 'action' => 'index'))?>">
    <input type="hidden" name="pageNum" value="<?php echo $param['pageNum'];?>" />
    <input type="hidden" name="numPerPage" value="<?php echo $param['numPerPage'];?>" />
</form>
<div class="topPanelBar">
    <ul class="toolBar">
        <li style="margin-left: 10px;"><a class="add" href="<?php echo $this->Html->url(array('controller'=>'roles','action'=>'add'));?>" target="dialog" mask="true" width="600" height="350" rel="add"><span><?php echo __('Create').__('Role');?></span></a></li>
        <li><a class="editRole" href="<?php echo $this->Html->url(array('controller'=>'roles','action'=>'edit'));?>" target="selectedTodo" posttype="string" datatype="edit" mask="true" width="600" height="350"><span><?php echo __('edit').__('Role');?></span></a></li>
        <li><a class="deleteRole" href="<?php echo $this->Html->url(array('controller'=>'roles','action'=>'del'))?>" target="selectedTodo" posttype="string" title="<?php echo __('Are you delete');?>"><span><?php echo __('delete').__('Role');?></span></a></li>
        <li><a class="viewRole" href="<?php echo $this->Html->url(array('controller'=>'roles','action'=>'view'))?>" target="selectedTodo" posttype="string" datatype="edit" mask="true" width="600" height="350"><span><?php echo __('Show users');?></span></a></li>
    </ul>
</div>
<div style="margin:0 10px;">
	<table class="table" width="100%" layoutH="195">
		<thead>
			<tr>
				<th width="24"><input type="checkbox" group="ids" class="checkboxCtrl"></th>
				<th><?php echo __('Role name');?></th>
                <th width="120"><?php echo __('Operating');?></th>
			</tr>
		</thead>
		<tbody>
		<?php
		if(count($allroles) > 0){
			foreach($allroles as $value):
		?>
			<tr target="sid_user" rel="<?php echo $value['Role']['id']; ?>">
				<td align="center"><input name="ids" value="<?php echo $value['Role']['id']; ?>" type="checkbox" editHeight=""></td>
				<td><?php echo $value['Role']['name'];?></td>
                <td>
				<a title="<?php echo __('edit').__('Role');?>" target="dialog" href="<?php echo $this->Html->url(array('action' => 'edit', $value['Role']['id']))?>" mask="true" rel="edit" width="600" height="350" class="btnEdit"><?php echo __('edit');?></a>
                <a title="<?php echo __('delete').__('Role');?>" target="ajaxTodo" href="<?php echo $this->Html->url(array('action' => 'del', $value['Role']['id']))?>" class="btnDel"><?php echo __('delete');?></a>
                <a title="<?php echo __('Show users');?>" target="dialog" href="<?php echo $this->Html->url(array('action' => 'view', $value['Role']['id']))?>" mask="true" rel="view" width="600" height="350" class="btnAssign"><?php echo __('Show users');?></a>
                </td>
			</tr>
		<?php 
			endforeach;
		}else{
		?>
        <tr>
			<td><?php echo __('Nothing data');?></td>
		</tr>
        <?php
		}
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
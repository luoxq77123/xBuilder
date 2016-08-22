<?php if (!empty($params)) $paginator->options(array('url' => $params));?>
<form id="pagerForm" method="post" action="<?php echo $this->Html->url(array('controller' => 'roles', 'action' => 'view'))?>">
    <input type="hidden" name="pageNum" value="<?php echo $param['pageNum'];?>" />
    <input type="hidden" name="numPerPage" value="<?php echo $param['numPerPage'];?>" />
</form>
<div style="margin:0 5px;">
	<table class="table" width="100%" layoutH="85">
		<thead>
			<tr>
            	<th><?php echo __('Account');?></th>
				<th width="90"><?php echo __('Nickname');?></th>
                <th width="90"><?php echo __('Role');?></th>
                <th width="150"><?php echo __('Create data');?></th>
			</tr>
		</thead>
		<tbody>
		<?php
		if(count($users) > 0){
		foreach($users as $value)
		{
		?>
			<tr target="sid_user" rel="1">
            	<td><?php echo $value['User']['email'];?></td>
				<td><?php echo $value['User']['account'];?></td>
				<td><?php echo $value['Role']['name'];?></td>
                <td><?php echo $value['User']['created'];?></td>
			</tr>
		<?php 
		}}else{
		?>
        <tr target="sid_user" rel="1">
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
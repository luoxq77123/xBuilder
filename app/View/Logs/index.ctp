<?php if (!empty($params)) $paginator->options(array('url' => $params));?>
<form id="pagerForm" method="post" action="<?php echo $this->Html->url(array('controller' => 'logs', 'action' => 'index'))?>">
	<input type="hidden" name="pageNum" value="<?php echo $param['pageNum'];?>" />
	<input type="hidden" name="numPerPage" value="<?php echo $param['numPerPage'];?>" />
    <input type="hidden" name="province" value="<?php echo $param['province'];?>" />
	<input type="hidden" name="startdate" value="<?php echo $param['startdate'];?>" />
    <input type="hidden" name="enddate" value="<?php echo $param['enddate'];?>" />
    <input type="hidden" name="keywords" value="<?php echo $param['keywords'];?>" />
	<input type="hidden" name="username" value="<?php echo $param['username'];?>" />
</form>
<div class="pageContent" style="margin:0 5px;">
	<div class="searchBar">
    <form onsubmit="return navTabSearch(this);" action="<?php echo $this->Html->url(array('controller' => 'logs', 'action' => 'index'))?>" method="post">
		<table class="searchContent">
			<tbody><tr>
				<td>
					<?php echo __('Type of operation');?>：
				</td>
				<td>
					<select class="combox" name="province" ref="w_combox_city">
						<option value=""><?php echo __('All operations');?></option>
                        <option value="login"<?php if($param['province'] == 'login'){echo "selected";}?>><?php echo __('Login');?></option>
                        <option value="logout"<?php if($param['province'] == 'logout'){echo "selected";}?>><?php echo __('Logout');?></option>
                        <option value="add"<?php if($param['province'] == 'add'){echo "selected";}?>><?php echo __('Add');?></option>
                        <option value="edit"<?php if($param['province'] == 'edit'){echo "selected";}?>><?php echo __('edit');?></option>
                        <option value="del"<?php if($param['province'] == 'del'){echo "selected";}?>><?php echo __('delete');?></option>
						<option value="uploads:video"<?php if($param['province'] == 'uploads:video'){echo "selected";}?>><?php echo __('Upload');?></option>
						<!--<option value="download"<?php if($param['province'] == 'download'){echo "selected";}?>><?php echo __('Download');?></option>-->
					</select>
				</td>
				<td>
					<?php echo __('Start data');?>：<input type="text" readonly="true" format="yyyy-MM-dd HH:mm:ss" class="date textInput readonly valid" name="startdate" value="<?php echo $param['startdate'];?>">
				</td>
				<td>
					<?php echo __('End data');?>：<input type="text" readonly="true" format="yyyy-MM-dd HH:mm:ss" class="date textInput readonly valid" name="enddate" value="<?php echo $param['enddate'];?>">
				</td>
				<td>
					<?php echo __('Nickname');?>：<input type="text" name="username" class="textInput" value="<?php echo $param['username'];?>">
				</td>
				<td>
					<?php echo __('Keyword');?>：<input type="text" name="keywords" class="textInput" value="<?php echo $param['keywords'];?>">
				</td>
				<td>
					<div class="subBar">
						<ul>
							<li><div class="buttonActive"><div class="buttonContent"><button type="submit"><?php echo __('inquire');?></button></div></div></li>
						</ul>
					</div>
				</td>
			</tr>
		</tbody></table>
		</form>
	</div>
	<table class="table" width="100%" layoutH="130">
		<thead>
			<tr>
				<th><?php echo __('History');?></th>
				<th width="120"><?php echo __('Logs type');?></th>
				<th width="120" style="text-align:left;"><?php echo __('Sponsor');?></th>
				<th width="120"><?php echo __('Operating time');?></th>
			</tr>
		</thead>
		<tbody>
		<?php
		if(count($log_Customer) > 0){
		foreach($log_Customer as $value)
		{
		?>
			<tr target="sid_user" rel="1">
				<td><?php echo $value['Log']['LogMessage'];?></td>
				<td>
				<?php
					if(strstr($value['Log']['LogType'], 'add')){echo __('Add');}
					elseif(strstr($value['Log']['LogType'], 'uploads:video')){echo __('Upload');}
					elseif(strstr($value['Log']['LogType'], 'edit') || strstr($value['Log']['LogType'], 'update')){echo __('edit');}
					elseif(strstr($value['Log']['LogType'], 'del') || strstr($value['Log']['LogType'], 'delete') || strstr($value['Log']['LogType'], 'gotorecycle')){echo __('delete');}
					elseif(strstr($value['Log']['LogType'], 'login')){echo __('Login');}
					elseif(strstr($value['Log']['LogType'], 'logout')){echo __('Logout');}
				?>
                </td>
				<td><?php echo $value['Log']['UserName'];?></td>
				<td><?php echo $value['Log']['AddTime'];?></td>
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
			<span><?php echo __('All');?><?php echo $pageParams['count']; ?><?php echo __('Item record');?><?php echo __('Per Page');?><?php echo $pageParams['limit']?><?php echo __('Item');?></span>
		</div>	
		<div class="pagination" allPage="<?php echo $pageParams['pageCount']?>" targetType="navTab" totalCount="<?php echo $pageParams['count']?>" numPerPage="<?php echo $pageParams['limit']?>" pageNumShown="10" currentPage="<?php echo $param['pageNum'];?>"></div>
	</div>
</div>
<div class="tabs" currentIndex="0" eventType="click">
<div class="tabsHeader">
<div class="tabsHeaderContent">
<ul>
	<li><a href="javascript:;"><span>角色用户</span></a></li>
	<li><a href="javascript:;"><span>分类权限</span></a></li>
	<li><a href="javascript:;"><span>模板分配</span></a></li>
	<li><a href="javascript:;"><span>系统权限</span></a></li>
</ul>
</div>
</div>
<div class="tabsContent" layoutH="70" style="padding:10px 10px 0px 10px;">
<!-- 内容1 -->
<div>
<?php if (!empty($params)) $paginator->options(array('url' => $params));?>
<form id="pagerForm" method="post" action="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'index'))?>">
<input type="hidden" name="pageNum"	value="<?php echo $param['pageNum'];?>" />
<input type="hidden" name="numPerPage" value="<?php echo $param['numPerPage'];?>" />
</form>
<?php echo $this->Form->input('id', array('type'=>'hidden', 'value' => $id));?>
<div class="toolButtons">
<ul>
	<li><div class="buttonActive"><div class="buttonContent"><button type="button" target="dialog" mask="true" width="600" height="350" href="<?php echo $this->Html->url(array('controller'=>'roles', 'action'=>'addUserToRoles', $id));?>">添加用户入角色</button></div></div></li>
	<li><div class="button"><div class="buttonContent"><button id="deleteUserOutRole" type="button" target="selectedTodo" posttype="string" callback="dialogAjaxDone"	href="<?php echo $this->Html->url(array('controller'=>'roles', 'action'=>'delUserToRoles', $id));?>">删除用户出角色</button></div></div>
	</li>
</ul>
<div class="clear"></div>
</div>
<div>
<table class="table" layoutH="195" width="100%">
	<thead>
		<tr>
			<th width="24"><input type="checkbox" group="ids" class="checkboxCtrl"></th>
			<th>账号</th>
			<th width="120">昵称</th>
			<th width="120">角色</th>
			<th width="120">电子邮箱</th>
			<th width="200">创建时间</th>
		</tr>
	</thead>
	<tbody>
	<?php
	if(count($users) > 0){
	foreach($users as $value)
	{
	?>
	<tr target="sid_user" rel="<?php echo $value['User']['id']; ?>">
		<td align="center"><input name="ids" value="<?php echo $value['User']['id']; ?>" type="checkbox"></td>
		<td>
		<?php 
		echo $value['User']['email'];
		if($value['User']['is_founder'] == 1):?> <span class="notic">(<?php echo __('Founder can\'t delete role');?>)</span>
		<?php endif;?></td>
		<td><?php echo $value['User']['account'];?></td>
		<td><?php echo $value['Role']['name'];?></td>
		<td><?php echo $value['User']['email'];?></td>
		<td><?php echo $value['User']['created'];?></td>
	</tr>
	<?php
	}}else{
	?>
	<tr>
		<td>没有数据！</td>
	</tr>
	<?php
	}
	?>
	</tbody>
</table>

<?php @$pageParams = $this->Paginator->params();?>
<div class="panelBar footer">
<div class="pages"><span>共<?php echo $pageParams['count']; ?>条记录，每页<?php echo $pageParams['limit']?>条</span></div>
<div class="pagination" allPage="<?php echo $pageParams['pageCount']?>" targetType="navTab"	totalCount="<?php echo $pageParams['count']?>" numPerPage="<?php echo $pageParams['limit']?>" pageNumShown="10"	currentPage="<?php echo $param['pageNum'];?>"></div>
</div>
</div>
</div>
<!-- 内容2 -->
<div>
<form method="post"	action="<?php echo $this->Html->url(array('controller' => 'roles', 'action' => 'editPermissions'))?>" class="pageForm" onsubmit="return validateCallback(this,dialogAjaxDone)"><?php echo $this->Form->input('id', array('type'=>'hidden', 'value' => $id));?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="200">
<div class="panel" defH="400" style="width: 220px; margin:0">
	<h1>任务分类</h1>
	<div>
	<ul id="editCategoryPremission" class="tree expand premissionCategory">
	<?php
	$options = array(
	'model' => 'Category',
	'isCumstomUrl' => false,
	'customUrl' => '#', 
	'controller' => $treeController,
	'action' => $treeAction,
	'param' => array('fix'=>$roles_id, 'key'=>'id'),
	'config' => 'target="sid_user"',
	'selectId' => false
	);
	echo $this->Dwz->genTree($categories, $options);
	?>
	</ul>
	</div>
</div>
</td>
<td valign="top">
<div class="toolButtons" style="margin:5px 5px 6px;">
<ul>
	<li><div style="height:25px;"></div><!--<div class="button"><div class="buttonContent"><button type="submit">保存</button></div></div>--></li>
</ul>
<div class="clear"></div>
</div>
<div id="jbsxBox" class="checkboxList">
<ul id="permissionSubmit" style="display:none; padding-left:25px;">

<div class="role_category_tag_name"></div>
<div class="img"></div>
<li id="s1"></li>
<li id="s2"></li>
<!--<li id="s3"></li>-->
<li id="s4"></li>
<li id="s5"></li>
<li id="s6"></li>
<?php /*?><?php
$allViewCategoryId = null;
foreach($allCategoryId as $valueCategoryId)
{
	$allViewCategoryId.=$valueCategoryId['Permission']['category_id'].',';
}
?>
<?php foreach($categories as $v):?>
<?php
echo '<li>';
foreach($categoryPermissions as $key=>$permissionsValue)
{
	if(in_array($v['Category']['id'],explode(',',$allViewCategoryId))):
	$checked = in_array($key,explode(',',$categoryIdPermissions[$v['Category']['id']]))?'checked':false;
	else:
	$checked = false;
	endif;
	echo $this->Form->checkbox('categoryPermissions', array('value'=>$key, 'statusId'=>'categoryPermissions'.$v['Category']['id'], 'hiddenField'=>false,'name'=>'data[Permission][permissions][]','checked'=>$checked,'disabled'=>'disabled')).$permissionsValue;
}
echo '</li>';
?>
<?php
foreach($v['children'] as $vv):
echo '<li>';
foreach($categoryPermissions as $key=>$permissionsValue)
{
	if(in_array($vv['Category']['id'],explode(',',$allViewCategoryId))):
	$checked = in_array($key,explode(',',$categoryIdPermissions[$vv['Category']['id']]))?'checked':false;
	else:
	$checked = false;
	endif;
	echo $this->Form->checkbox('categoryPermissions'.$vv['Category']['id'], array('value'=>$key, 'statusId'=>'categoryPermissions'.$vv['Category']['id'], 'hiddenField'=>false,'name'=>'data[Permission][permissions][]','checked'=>$checked,'disabled'=>'disabled')).$permissionsValue;
}
echo '</li>';
endforeach;
?>
<?php endforeach;?><?php */?>
</ul>
<div class="clear:both"></div>
</div>

</td>
</tr>
</table>
</form>
</div>

<!-- 内容3 -->
<div>
<form method="post"	action="<?php echo $this->Html->url(array('controller' => 'roles', 'action' => 'editTemplatesPermissions', $id))?>"	onsubmit="return validateCallback(this,dialogAjaxDone)">
<div class="toolButtons">
<ul>
	<li><div class="buttonActive"><div class="buttonContent"><button id="clickTemplateTrue">编辑</button></div></div></li>
	<li><div class="button"><div class="buttonContent"><button type="submit">保存</button></div></div></li>
</ul>
<div class="clear"></div>
</div>

<div>
<table class="table" width="100%" layoutH="195">
	<thead>
		<tr>
			<th>模板名称</th>
			<th>分类名称</th>
			<th width="120">分配权限</th>
			<th width="120">设置默认</th>
		</tr>
	</thead>
	<tbody>
	<?php
	if(count($transcodeGroup) > 0):
	foreach($transcodeGroup as $value):
	@$checked = in_array($value['TranscodeGroup']['id'],explode(',', $this->request->data['Role']['template_accesses']))? 'checked':false;
	@$radio = ($value['TranscodeGroup']['id'] == $this->request->data['Role']['default_template_id'])? 'checked':false;
	?>
    <tr>
        <td><?php echo $value['TranscodeGroup']['name'];?></td>
        <td><?php echo $value['TranscodeCategory']['name'];?></td>
        <td><?php echo $this->Form->checkbox('template_accesses', array('value'=>$value['TranscodeGroup']['id'], 'disabled'=>'disabled', 'hiddenField'=>false,'name'=>'data[Role][template_accesses][]','checked'=>$checked , 'target'=>'default_template_id','class'=>'changeRadio'));?>
        </td>
        <td><input type="radio" name="data[Role][default_template_id]" value="<?php echo $value['TranscodeGroup']['id'];?>"	<?php echo $radio;?> disabled="disabled" class="templateRadio" rel="default_template_id"></td>
    </tr>
    <?php
    endforeach;
    else:
    ?>
    <tr>
        <td>没有数据！</td>
    </tr>
    <?php
    endif;
    ?>
	</tbody>
</table>
</div>
<div class="panelBar footer"></div>
</form>
</div>

<!-- 内容4 -->
<div>
<form method="post"	action="<?php echo $this->Html->url(array('controller' => 'roles', 'action' => 'editSystemPermissions', $id))?>" class="pageForm" onsubmit="return validateCallback(this,dialogAjaxDone)"><?php echo $this->Form->input('id', array('type'=>'hidden', 'value' => $id));?>
<div class="toolButtons">
<ul>
	<li><div class="buttonActive"><div class="buttonContent"><button id="clickSystemTrue">编辑</button></div></div></li>
	<li><div class="button"><div class="buttonContent"><button type="submit">保存</button></div></div></li>
</ul>
<div class="clear"></div>
</div>

<div>
<table class="table" width="100%" layoutH="195">
	<thead>
		<tr>
			<th>系统权限</th>
			<th width="120">分配权限</th>
		</tr>
	</thead>
	<tbody>
	<?php
	foreach($systemPermissions as $key=>$value){
	@$checked = in_array($key,$this->request->data['Role']['operation_accesses'])? 'checked':false;
	?>
	<tr>
		<td><?php echo $value;?></td>
		<td><?php echo $this->Form->checkbox('operation_accesses', array('value'=>$key, 'disabled'=>'disabled', 'hiddenField'=>false,'name'=>'data[Role][operation_accesses][]','checked'=>$checked, 'class'=>'operationCheckbox'));?></td>
	</tr>
	<?php
	}
	?>

	</tbody>
</table>
</div>
<div class="panelBar footer"></div>
</form>
</div>
</div>
<div class="tabsFooter">
<div class="tabsFooterContent"></div>
</div>
</div>


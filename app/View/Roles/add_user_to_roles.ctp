<?php if (!empty($params)) $paginator->options(array('url' => $params));?>
<form id="pagerForm" method="post" action="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'index'))?>">
    <input type="hidden" name="pageNum" value="<?php echo @$param['pageNum'];?>" />
    <input type="hidden" name="numPerPage" value="<?php echo @$param['numPerPage'];?>" />
</form>

<div style="margin:0 5px;">
<form method="post" action="<?php echo $this->Html->url(array('controller' => 'roles', 'action' => 'addUserToRoles'))?>" class="pageForm required-validate" name="addform" onsubmit="return validateCallback(this,dialogAjaxDone)">
<?php echo $this->Form->input('id', array('type'=>'hidden','value'=>''.@$this->params['pass'][0].''));?>
<table class="table" width="100%" layoutH="123">
    <thead>
        <tr>
            <th width="30"></th>
            <th><?php echo __('Account');?></th>
            <th width="100"><?php echo __('Nickname');?></th>
            <th width="80"><?php echo __('Role');?></th>
            <th width="200"><?php echo __('Create data');?></th>
        </tr>
    </thead>
    <tbody>
    <?php
    if(count(@$users) > 0){
    foreach($users as $value)
    {
    ?>
        <tr target="sid_user" rel="<?php echo $value['User']['id']; ?>">
            <td align="center" width="30"><input name="ids[]" value="<?php echo $value['User']['id']; ?>" type="checkbox"></td>
            <td><?php echo $value['User']['email'];?></td>
            <td width="100"><?php echo $value['User']['account'];?></td>
            <td width="80"><?php echo $value['Role']['name'];?></td>
            <td width="200"><?php echo $value['User']['created'];?></td>
        </tr>
    <?php 
    }}else{
    ?>
    <tr>
       <td><?php echo __('Nothing data');?></td>
    </tr>
    <?php
    }
    ?>
    </tbody>
</table>
<div class="formBar">
    <ul>
        <li><div class="buttonActive"><div class="buttonContent"><button type="submit"><?php echo __('submit');?></button></div></div></li>
        <li><div class="button"><div class="buttonContent"><button type="button" class="close"><?php echo __('cancel');?></button></div></div></li>
    </ul>
</div>
</form>
<?php @$pageParams = $this->Paginator->params();?>
<div class="panelBar footer">
    <div class="pages">
        <span><?php echo __('All');?><?php echo $pageParams['count']; ?><?php echo __('Item record');?>,<?php echo __('Per Page');?><?php echo $pageParams['limit']?><?php echo __('Item');?></span>
    </div>	
    <div class="pagination" allPage="<?php echo $pageParams['pageCount']?>" targetType="navTab" totalCount="<?php echo $pageParams['count']?>" numPerPage="<?php echo $pageParams['limit']?>" pageNumShown="10" currentPage="<?php echo $param['pageNum'];?>"></div>
</div>
</div>
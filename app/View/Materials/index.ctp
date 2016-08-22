
<?php if (!empty($params)) $paginator->options(array('url' => $params));?>
<form id="pagerForm" method="post" action="<?php echo $this->Html->url(array('controller' => 'materials', 'action' => 'index', @$this->params['pass'][0]))?>">
	<input type="hidden" name="pageNum" value="<?php echo $param['pageNum'];?>" />
	<input type="hidden" name="numPerPage" value="<?php echo $param['numPerPage'];?>" />
    <input type="hidden" name="searchType" value="<?php echo $param['searchType'];?>" />
    <input type="hidden" name="keyword" value="<?php echo $param['keyword'];?>" />
</form>
<div class="pageContent" style="margin:0 312px 0 5px;">
	<div class="topPanelBar">
		<ul class="toolBar">
			<li><a class="web_upload" href="<?php echo $this->Html->url(array('action'=>'upload',@$this->params['pass'][0]?@$this->params['pass'][0]:$oneCategoryId));?>" target="dialog" rel="upload" mask="true" width="800" height="550" title="<?php echo __('web Upload');?>" drawable="false" resizable="false" maxable="false" upload="<?php echo @$this->params['pass'][0];?>" param="closeEven"><span><?php echo __('web Upload');?></span></a></li>
			<li><a class="ftp_upload" href="<?php echo $this->Html->url(array('action'=>'ftp_upload',@$this->params['pass'][0]?@$this->params['pass'][0]:$oneCategoryId));?>" target="dialog" rel="upload" mask="true" width="800" height="550" title="<?php echo __('client Upload');?>" drawable="false" resizable="false" maxable="false" upload="<?php echo @$this->params['pass'][0];?>" param="closeEven"><span><?php echo __('client Upload');?></span></a></li>
			<li><a class="disk_find" href="<?php echo $this->Html->url(array('action'=>'disk_find',@$this->params['pass'][0]?@$this->params['pass'][0]:$oneCategoryId));?>" target="dialog" rel="upload" mask="true" width="800" height="550" title="<?php echo __('disk Find');?>" resizable="false" maxable="false" upload="<?php echo @$this->params['pass'][0];?>" param="closeEven"><span><?php echo __('disk Find');?></span></a></li>
			<li><a class="category_move" href="<?php echo $this->Html->url(array('action'=>'category_move',@$this->params['pass'][0]));?>" target="selectedTodo" posttype="string" datatype="replay" mask="true" width="350" height="250"><span><?php echo __('category move');?></span></a></li>
            <!-- <li><a class="replay" href="<?php //echo $this->Html->url(array('action'=>'replay',@$this->params['pass'][0]));?>" target="selectedTodo" posttype="string" datatype="replay" mask="true" height="450"><span><?php //echo __('Again transcoding');?></span></a></li> -->
			<li><a class="refresh" href="#" onclick="navTab.reload('<?php echo $this->here;?>')"><span><?php echo __('refresh');?></span></a></li>
			<li><a class="delete" href="<?php echo $this->Html->url(array('action' => 'gotorecycle'))?>" target="selectedTodo" posttype="string" title="<?php echo __('Are you delete');?>"><span><?php echo __('delete');?></span></a></li>
		</ul>
	</div>
	<table class="table" width="100%" layoutH="180" border="1">
		<thead>
			<tr class="left">
				<th width="22" valign="middle"><input type="checkbox" group="ids" class="checkboxCtrl"></th>
				<th align="left" width="60"><span>关键帧</span></th>
				<th align="left"><span><?php echo __('title');?></span></th>
                <th align="left" width="150"><?php echo __('with category');?></th>
                <th align="left" width="80"><?php echo __('Upload user');?></th>
				<th align="left" width="170"><?php echo __('Upload time');?></th>
				<th align="left" width="60"><?php echo __('Length');?></th>
				<th align="left" width="110"><?php echo __('Transcoding template');?></th>
				<th align="left" width="70"><?php echo __('Status');?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($data as $v):?>
			<tr target="sid_user" rel="<?php echo $v['Content']['id'];?>" rewrite="detailInfo" showMore="true" url="<?php echo $this->Html->url(array('action' => 'detail', $v['Content']['id'], $v['Content']['category_id']));?>">
				<td><input name="ids" value="<?php echo $v['Content']['id'];?>" cel="<?php echo $v['Content']['category_id'];?>" type="checkbox"></td>
				<td>
				<?php 
					if($v['Content']['status'] != 1){
						if($v['Content']['type'] == 1){
				?>
					<img src="
					<?php 
						echo $this->Html->url('/images/index/'.$v['Content']['task_id'].'.png?_'.time());
						/*foreach($v['Image'] as $vI){
							if($vI['IsKeyFrame'] == 1){
								echo @VIDEO_IMAGE_URL.$vI['FileUrl'];
							}
						}*/
					?>
					" width="50" height="40" title="点击预览视频" />
				<?php 
						}elseif($v['Content']['type'] == 2){
				?>
					<img src="themes/default/images/audio.png" />
				<?php 
						}
					}else{
				?>
					<img src="themes/default/images/trancoding.png" />
				<?php }?>
				</td>
				<td><?php echo $v['Content']['title']?></td>
                <td><?php echo $v['Category']['name'];?></td>
                <td><?php echo $v['User']['account'];?></td>
				<td><?php echo $v['Content']['created'];?></td>
				<td><?php echo @$v['Video'][0]['Duration']?gmstrftime('%H:%M:%S',$v['Video'][0]['Duration']):'';?></td>
				<td><?php echo $v['TranscodeGroup']['name'];?></td>
				<td><?php
						/*$str = strtolower(UPLOAD_VIDEO_FILE_FORMAT."|".UPLOAD_AUDIO_FILE_FORMAT);
						$arr1 = explode('.',$str);
						$arr2 = join($arr1);
						$arr3 = explode('|',$arr2);
						if(!in_array(strtolower(@$v['Video'][0]['fileFormat']),$arr3)){echo '非法格式';}else{
							if($v['Content']['status']==1):echo __('Is transcoding');elseif($v['Content']['status']==2):echo __('Have been completed');elseif($v['Content']['status']==4):echo __('Have Loading');else: echo '转码失败';endif;
						}*/
						echo __('taskStatus_'.$v['Content']['status']);
					?>
				</td>
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
		<div layoutH="290">
			
			
		</div>
	</div>
</div>

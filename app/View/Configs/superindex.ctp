<?php if (!empty($params)) $paginator->options(array('url' => $params));?>
<form id="pagerForm" method="post" action="<?php echo $this->Html->url(array('controller' => 'configs', 'action' => 'superindex'))?>">
   <input type="hidden" name="pageNum" value="<?php echo $param['pageNum'];?>" />
   <input type="hidden" name="numPerPage" value="<?php echo $param['numPerPage'];?>" />
</form>
<div class="pageContent" style="margin:0 5px;">
   <div class="topPanelBar">
       <ul class="toolBar">
           <li><a class="add_template" href="<?php echo $this->Html->url(array('controller'=>'configs','action'=>'add'));?>" height="460" target="dialog" mask="true" rel="add"><span>添加配置</span></a></li>
           <li><a class="edit" href="<?php echo $this->Html->url(array('controller'=>'configs','action'=>'edit'));?>" target="selectedTodo" posttype="string" datatype="edit" mask="true" width="600" height="460" title="<?php echo __('Edit user');?>"><span>编辑配置</span></a></li>
           <li><a class="remove delete" href="<?php echo $this->Html->url(array('controller'=>'configs','action'=>'del'))?>" target="selectedTodo" posttype="string" title="<?php echo __('Are you delete');?>"><span>删除配置</span></a></li>
           <li><a class="refresh" href="<?php echo $this->Html->url(array('controller'=>'configs','action'=>'flashCache'))?>" target="ajaxTodo"><span>重置</span></a></li>
       </ul>
   </div>
   <table class="table" width="100%" layoutH="200">
       <thead>
           <tr>
               <th><input type="checkbox" group="ids" class="checkboxCtrl"></th>
               <th>配置名称</th>
               <th>配置名称(英文)</th>
               <th>配置值</th>
               <th>配置类型</th>
               <th>是否起效</th>
               <th>操作</th>
           </tr>
       </thead>
       <tbody>
           <?php 
               foreach($configs as $value){
                   echo "<tr target='sid_config' rel='".$value['Config']['id']."'>";
           ?>
           <td><input name="ids" value="<?php echo $value['Config']['id']; ?>" type="checkbox" editHeight=""></td>
           <?php
             echo "<td>".$value['Config']['name']."</td>";
             echo "<td>".$value['Config']['type']."</td>";    
             echo "<td>".String::truncate($value['Config']['value'],40)."</td>";
             echo "<td>".__('ConfigType_'.$value['Config']['access'])."</td>";
             echo "<td>".($value['Config']['is_valid']?"是":"否")."</td>";
             echo "<td>".$this->Html->link('编辑','/configs/edit/'.$value['Config']['id'].'',array("target"=>"dialog","height"=>"460","mask"=>"true","rel"=>"edit"))." " . $this->Html->link('删除', '/configs/del/'.$value['Config']['id'],array( "target"=>"ajaxTodo", "title"=>__('Are you delete')))."</td>";
                   echo "</tr>";
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
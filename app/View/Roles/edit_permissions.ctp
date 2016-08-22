<?php echo $this->Form->input('cid', array('type'=>'hidden', 'value' => @$cid));?>
<?php foreach($categories as $v):?>
<?php
foreach($categoryPermissions as $key=>$permissionsValue)
{
	if($v['Category']['id'] == $cid){$checked = in_array($key,$this->request->data['Permission']['permissions'])? 'checked':false;}else{$checked=false;}
	echo $this->Form->checkbox('categoryPermissions', array('value'=>$key, 'hiddenField'=>false,'name'=>'data[Permission][permissions][]','checked'=>$checked)).$permissionsValue;
}
echo '<br>';
?>
<?php
foreach($v['children'] as $vv)
{
	  foreach($categoryPermissions as $key=>$permissionsValue)
	  {
		  if($vv['Category']['id'] == $cid){$checked = in_array($key,$this->request->data['Permission']['permissions'])? 'checked':false;}else{$checked=false;}
		  echo $this->Form->checkbox('categoryPermissions', array('value'=>$key, 'hiddenField'=>false,'name'=>'data[Permission][permissions][]','checked'=>$checked)).$permissionsValue;
	  }
	  echo '<br>';
}
?>
<?php endforeach;?>
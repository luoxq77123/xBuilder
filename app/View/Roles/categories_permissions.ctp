<div>
<div style="float:left;width:220px;height:400px;overflow:scroll;border:1px solid #003D4C;margin-right:5px;">
<ul>
<?php 
	$options = array(
		'model' => 'Category',
		'isCumstomUrl' => false,
		'customUrl' => '#', 
		'controller' => $treeController,
		'action' => $treeAction,
		'param' => array('fix'=>$roles_id, 'key'=>'id'),
		'config' => 'target="detail"',
		'selectId' => false
	);
	echo $this->Dwz->generateTree($categories, $options);
?>
</ul>
</div>
<iframe id="detail" name="detail" frameborder="0" style="float:left;width:600px;height:400px;overflow-y:scroll;border:1px solid #003D4C"></iframe>
</div>
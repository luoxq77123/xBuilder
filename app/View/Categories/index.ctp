<ul class="tree expand">
<?php 
	echo $this->Html->link('添加分类', 'add');
	$options = array(
		'model' => 'Category',
		'isCumstomUrl' => false,
		'customUrl' => '#', 
		'controller' => $treeController,
		'action' => $treeAction,
		'param' => 'id',
		'config' => '',
		'selectId' => $id
	);
	echo $this->Dwz->generateTree($trees, $options);
?>
</ul>
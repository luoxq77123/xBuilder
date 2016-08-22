<?php 
$items = array();
foreach($audios as $akey=>$audio){
	$items[] = '["'.$akey.'","'.$audio.'"]';
}
?>
[<?php echo implode(',', $items);?>]
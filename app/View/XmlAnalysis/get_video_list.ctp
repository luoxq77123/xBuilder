<?php 
$items = array();
foreach($videos as $vkey=>$video){
	$items[] = '["'.$vkey.'","'.$video.'"]';
}
?>
[<?php echo implode(',', $items);?>]
<?php
class XmlFileformat extends AppModel{
	public $name = 'XmlFileformat';
    
	public $hasMany = array(
	        'XmlVideoType'=>array(
	                'className'=>'XmlVideotype',
	                'foreignKey'=>'fid',
	        ),
	
	);
}
?>
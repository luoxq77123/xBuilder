<?php 
class TranscodeCategory extends AppModel{
	public $name = 'TranscodeCategory';
	public $useTable = 'transcode_categories';
	public $primaryKey = 'id';
	public $actsAs = array('Tree');
	// public $hasMany = array(
	// 	'Video' => array(
	// 		'className' => 'Video',
	// 		'foreignKey' => 'content_id'
	// 	),
	// 	'Image' => array(
	// 		'className' => 'Image',
	// 		'foreignKey' => 'content_id'
	// 	)
	// );
	
	// public $belongsTo = array(
	// 	'TranscodeGroup' => array(
	// 		'className' => 'TranscodeGroup',
	// 		'foreignKey' => 'transcode_group_id',
	// 		'fields' => array('id','name')
	// 	),
	// 	'User' => array(
	// 		'className' => 'User',
	// 		'foreignKey' => 'user_id',
	// 		'fields' => array('id','account')
	// 	),
	// 	'Category' => array(
	// 		'className' => 'Category',
	// 		'foreignKey' => 'category_id',
	// 		'fields' => array('id','name')
	// 	),
	// );
}
?>
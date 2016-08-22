<?php
class Transcode extends AppModel
{
	public $belongsTo = array(
		'TranscodeGroup' => array(
			'className' => 'TranscodeGroup',
			'foreignKey' => 'transcode_group_id'
		)
	);
	
	public $validate = array(
		'title' => array(
			'rule' => 'notEmpty'
		)
	);
}
<?php 
class AutoScan extends AppModel{
	public $name = 'AutoScan';
	public $actsAs = array('Containable');
	public $validate = array(
		'tid'=>array(
			array(
				'rule'=>'notEmpty',
				'required' => true, 
				'allowEmpty' => false, 
				'message'=>'请选择转码组',
				'on' => array('update','create')
				),
			array(
				'rule'=>'notEmpty',
				'message'=>'请选择',
				'on' => array('update','create')
				)
			)
	);
	public function beforeSave($options = array()){
		$this->data['AutoScan']['addtime'] = date('Y-m-d H:i:s', time());
	}

	/**
 	* 链接转码组分类
 	* @return void
 	*/
 	public $belongsTo = array(
 		'TranscodeGroup'=>array(
 			'className'=>'TranscodeGroup',
 			'foreignKey'=>'tid',
 			'fields'=>array('name')
 		)
 	);

 }
 ?>
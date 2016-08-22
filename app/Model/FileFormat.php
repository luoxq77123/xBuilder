<?php
class FileFormat extends AppModel{
//	public $name = 'Fileformat';
	public $useTable = 'fileformats';

	public $hasAndBelongsToMany = array(
		'Param'	=>	array(
				'className' => 'Param',
				'foreignKey' => 'format_id',
				'associationForeignKey' => 'param_id',
				'joinTable' => 'param_formats',
				'with'	=>	'ParamFormat',
				'conditions' => 'ParamFormat.format_type = 1',
				'order' => 'Param.sort desc'
			)
		);

	public function getFileParam($id = null, $base = null){
		if(!$id) return false;

	    return $this->find('first',array('conditions'=>array('FileFormat.id'=>$id)));
	}
}
?>
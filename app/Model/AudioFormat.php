<?php
class AudioFormat extends AppModel{
	public $name = 'AudioFormat';
	public $useTable = 'audioformats';
    
	public $hasAndBelongsToMany = array(
		'Param'	=>	array(
				'className' => 'Param',
				'foreignKey' => 'format_id',
				'associationForeignKey' => 'param_id',
				'joinTable' => 'param_formats',
				'with'	=>	'ParamFormat',
				'conditions' => 'ParamFormat.format_type = 3',
				'order' => 'Param.sort desc'
			)
		);

	public function getAudioList($fid = null){
		if(!$fid) return false;

		return $this->find('list',array('conditions'=>array('AudioFormat.fid'=>$fid)));
	}

	public function getAudioParam($id = null,$basic = null){
		if(!$id) return false;

		if(isset($basic)) {
			$this->hasAndBelongsToMany['Param']['conditions'] = array(
				'ParamFormat.format_type' => 3,
				'Param.basic' => $basic
				);
		}
		$result = $this->find('first',array('conditions'=>array('AudioFormat.id'=>$id)));
		$options = array('0'=>'请选择','1'=>'立体声','2'=>'5.1环绕');

		//在音频为AAC时，屏蔽对5.1声道的选择
		$aac_audio = $this->find('list',array('conditions'=>array('AudioFormat.name'=>'AAC'))); 

		if(in_array($id, array_keys($aac_audio))){
			unset($options[2]);
		}

	    $tracksParam = array(array(
            'c_name' => '声道：',
            'name' => 'UseTracks',
            'type' => 'select',
            'basic' => '1',
            'options' => json_encode($options),
            'start_str' => '',
            'end_str' => '',
            'value' => ''
    	));
	    $result['Param'] = array_merge($result['Param'], $tracksParam);
	    return $result;

	}
}
?>
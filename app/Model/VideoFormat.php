<?php
class VideoFormat extends AppModel{
	public $name = 'VideoFormat';
	public $useTable = 'videoformats';
// 	public $belongsTo = array(
// 	        'Audiotype'=>array(
// 	                'className'=>'XmlAudiotype',
// 	                'foreignKey'=>'default_template_id',
// 	                'fields'=>array('type')
// 	        )
// 	);

	public $hasAndBelongsToMany = array(
		'Param'	=>	array(
				'className' => 'Param',
				'foreignKey' => 'format_id',
				'associationForeignKey' => 'param_id',
				'joinTable' => 'param_formats',
				'with'	=>	'ParamFormat',
				'conditions' => 'ParamFormat.format_type = 2',
				'order' => 'Param.sort desc'
			)
		);


	//获取转码文件对应的视频文件
	public function getVideoList($fid = null){
		if(!$fid) return false;

        return $this->find('list',array('conditions'=>array('VideoFormat.fid'=>$fid,'VideoFormat.is_show'=>1)));

	}
	//获取视频对应的详细value
	public function getVideoParam($id = null, $basic = null){
		if(!$id) return false;

		if(isset($basic)) {
			$this->hasAndBelongsToMany['Param']['conditions'] = array(
				'ParamFormat.format_type' => 2,
				'Param.basic' => $basic
				);
			/*$options['contain'] = array(
				'Param'=>array(
					'conditions'=>array(
						'ParamFormat.format_type' => 2,
						'Param.basic' => $basic
					)
				)
			);*/
		}

	    return $this->find('first',array('conditions'=>array('VideoFormat.id'=>$id)));

	}
}
?>
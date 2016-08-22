<?php
class ParamFormat extends AppModel{
	public $name = 'ParamFormat';
    
//     public $belongsTo = array(
// 		'VideoFormat' => array(
// 			'className' => 'AudioFormat',
// 			'foreignKey' => 'role_id',
// 		),
// 		'AudioFormat' => array(
// 			'className' => 'AudioFormat',
// 			'foreignKey' => 'power_id'
// 		)
// 	);
//获取文件格式的元素
    public function getFileParams($fid){
        $this->belongsTo = array(
                'Param' => array(
                        'className' => 'Param',
                        'foreignKey' => 'param_id'
                )
        );
        $params = $this->find('all',array('order'=>'sort DESC','conditions'=>array('ParamFormat.format_type'=>1,'ParamFormat.format_id'=>$fid)));
        return $params;
    }
    //获取视频格式的元素
    public function getVideoParams($vid){
        $this->belongsTo = array(
                'Param' => array(
                        'className' => 'Param',
                        'foreignKey' => 'param_id'
                )
        ); 
        $params = $this->find('all',array('order'=>'sort DESC','conditions'=>array('ParamFormat.format_type'=>2,'ParamFormat.format_id'=>$vid)));
        return $params;
    }
    //获取音频格式的元素
    public function getAudioParams($aid){
        $this->belongsTo = array(
                'Param' => array(
                        'className' => 'Param',
                        'foreignKey' => 'param_id'
                )
        );
        $params = $this->find('all',array('order'=>'sort DESC','conditions'=>array('ParamFormat.format_type'=>3,'ParamFormat.format_id'=>$aid)));
        return $params;
    }
}
?>
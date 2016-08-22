<?php
class VideoAudio extends AppModel{
	public $name = 'VideoAudio';
    
    //获取音频列表
    public function getAudios($vid){
        $this->belongsTo = array(
                'AudioFormat' => array(
                        'className' => 'AudioFormat',
                        'foreignKey' => 'audio_id',
                        'with'  =>  'AudioFormat',
                        //'conditions' => 'AudioFormat.is_show = 1'
                )
        );
        $audio = $this->find('all',array('conditions'=>array('VideoAudio.video_id'=>$vid)));
        $list = array(''=>'请选择');
        foreach ($audio as $k=>$v){
            if($v['AudioFormat']['is_show'] == 1){
                $list[$v['AudioFormat']['id']] = $v['AudioFormat']['name'];
            }
        }
        return $list;
    }
}
?>
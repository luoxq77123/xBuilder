<?php
class XmlVideotype extends AppModel{
	public $name = 'XmlVideotype';
    
	public $hasMany = array(
	        'Avideorelation'=>array(
	                'className'=>'XmlAvideorelation',
	                'foreignKey'=>'videoid',
	        ),
	);
	
	//获取转码文件对应的视频文件
	public function getVideoList($fid = null){
	    if($fid){
	        $videolist = $this->find('all',array('conditions'=>array('XmlVideotype.fid'=>$fid)));
	        $video[''] = '请选择';
	        foreach ($videolist as $k=>$v){
	            $video[$v['XmlVideotype']['id']] = $v['XmlVideotype']['name'];
	        }
	        $time = isset($this->request->data['time'])?$this->request->data['time']:'';
	        return $video;
	    }else{
	        return false;
	    }
	}
	//获取视频对应的详细value
	public function getVideoValue($vid = null){
	    if($vid){
	        $video = $this->find('first',array('conditions'=>array('XmlVideotype.id'=>$vid)));
	        $video = json_decode($video['XmlVideotype']['value'], TRUE);
	        return $video;
	    }else{
	        return false;
	    }
	}
}
?>
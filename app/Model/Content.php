<?php 
class Content extends AppModel{
	public $name = 'Content';
	public $primaryKey = 'id';

	public $hasMany = array(
		'Video' => array(
			'className' => 'Video',
			'foreignKey' => 'content_id'
		),
		'Image' => array(
			'className' => 'Image',
			'foreignKey' => 'content_id'
		)
	);
	
	public $belongsTo = array(
		'TranscodeGroup' => array(
			'className' => 'TranscodeGroup',
			'foreignKey' => 'transcode_group_id',
			'fields' => array('id','name')
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'fields' => array('id','account')
		),
		'Category' => array(
			'className' => 'Category',
			'foreignKey' => 'category_id',
			'fields' => array('id','name')
		),
	);
/**
	 * 删除物理文件
	 * @param  string $ids [description]
	 * @return [type]      [description]
	 */
	public function deletePhysics($ids = array()) {
		$successTaskID = array();
		foreach ($ids as $value) {	
			if($value) $back = $this->deleteOne($value);
			if($back) $successTaskID[] = $value;
		}
		return $successTaskID;
	}
	/**
	 * 删除某一个物理文件(原始文件和转码后的文件)和抽帧图图片
	 * @param  string $ids [description]
	 * @return [type]      [description]
	 */
	private function deleteOne($ids=''){
		$this->Behaviors->attach('Containable');
		$result = $this->find('first',array('conditions'=>array('Content.id'=>$ids), 'contain'=>array('Video'=>array('fields'=>'Video.filePath'))));
		if($result) {
		//删物理文件(原始文件和转码后的文件)
			foreach($result['Video'] as $v) {
				//编码转换 防止wins下面的中文无法识别 todo fix lunix
				$file = iconv('utf-8','gb2312',$v['filePath']);
				file_exists($file) && unlink($file);
			}
		//删抽帧图
			file_exists(STORAGE_IP_ADDRESS . IMAGE_PATH_PREFIX . DS . $result['Content']['task_id'] . '.png') && unlink(STORAGE_IP_ADDRESS . IMAGE_PATH_PREFIX . DS . $result['Content']['task_id'] . '.png');
			return $ids;
		}
		return false;
	}
}
?>
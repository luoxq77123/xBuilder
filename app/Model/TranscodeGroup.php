<?php 
App::uses('StringExpand', 'Lib');

class TranscodeGroup extends AppModel{
	public $name = 'TranscodeGroup';
	public $actsAs = array('Containable');
	public $validate = array(
		'name'=>array(
			array(
				'rule'=>'notEmpty',
				'message'=>'请输入转码组名称'
			),
			array(
          		'rule' => array('autoCutString'),
          		'message' => '字数过长'
          		//auto cut string length
      		)
		)
	);

/**
 * 自动截取字符串
 * @param  string $data 截取内容
 * @return boolean      截取结果
 */
  	public function autoCutString($data) {
  		$cutStr = StringExpand::cutStr($data['name'], 21);
  		$this->data[$this->alias]['name'] = $cutStr;
  		return (bool)$cutStr;
  	}

/**
 * 链接转码子模板
 * @return void
 */
	public $hasMany = array(
		'Transcode'=>array(
			'className'=>'Transcode',
			'order'=>array('Transcode.id'=>'asc'),
			'foreignKey'=>'transcode_group_id',
			'dependent' => true
		)
	);

/**
 * 链接转码组分类
 * @return void
 */
	public $belongsTo = array(
		'TranscodeCategory'=>array(
			'className'=>'TranscodeCategory',
			'foreignKey'=>'transcode_category_id',
			'fields'=>array('name')
		)
	);

/**
 * 获取转码组详细参数信息
 * @param  array $userInfo   用户信息
 * @param  int 	  $id   	  转码组ID
 * @return array              转码参数信息
 */
	public function getTranscodeParams($userInfo = array(), $id = null){
		if(!$userInfo) return false;

		App::import('Model','Role');
		$this->Role = new Role();

		$role = $this->Role->findById($userInfo['Role']['id']);
		$allow = explode(',',$role['Role']['template_accesses']);

		if($id && !in_array($id, $allow)) return false;
		$conditions['TranscodeGroup.id'] = $id?:$allow;

		$transcodeGroups = $this->find('all',array('conditions'=>$conditions,'contain'=>array('Transcode')));

		foreach ($transcodeGroups as $tkey => $transcodeGroup) {
			$selectOptions[$transcodeGroup['TranscodeGroup']['id']] = (($transcodeGroup['TranscodeGroup']['type'] == 1)?'视频 | ':'音频 | ') . $transcodeGroup['TranscodeGroup']['name'];
			
			if(count($transcodeGroups) == 1 || $role['Role']['default_template_id'] == $transcodeGroup['TranscodeGroup']['id']){
				foreach ($transcodeGroup['Transcode'] as $trkey => $transcode) {
					$transcodes[$trkey] = array(
						'title'		=>	$transcode['title'],
						'params'	=>	json_decode($transcode['params'],true)
					);
				}
				$TranscodeGroup = $transcodeGroup['TranscodeGroup'];
				$defaultTemplatesId = $role['Role']['default_template_id'];
			}

			if(@$transcodes[$trkey]['params']['audio']['UseTracks'] == 2){
				$TranscodeGroup['UseTracks'] = 2;
			}
		}

		return compact('selectOptions','transcodes','TranscodeGroup','defaultTemplatesId');
	}
}
?>
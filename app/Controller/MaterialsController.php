<?php
/**
 * Materials Controller
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('Set','Utility');
App::uses('Folder','Utility');
App::uses('File','Utility');
App::uses('HttpSocket','Network/Http');
App::uses('StringExpand', 'Lib');

class MaterialsController extends AppController{
	public $name = 'Materials';
	public $uses = array('Content','User','Role','TranscodeGroup','Transcode','Permission','Config','Category','Split');
	public $helpers = array('Number');
	
	private $ftpMessage = NULL;

    /**
     * 移动任务所在分类
     * @return void
     */
	public function category_move()
	{
		if($this->request->data)
		{
			if(empty($this->request->data['Category']['parent_id']))
			{
				echo '{"statusCode":"300","message":"'.__('没有选择分类，不能迁移！').'"}';
			}else
			{
				$this->log($this->request->data,'x');
				$Contentids = explode(',', $this->request->data['replayIds']);
				if($this->Content->updateAll(array('Content.category_id'=>$this->request->data['Category']['parent_id']),array('Content.id'=>$Contentids)))
				{
					echo '{"statusCode":"200","message":"'.__('任务迁移成功！').'", "callbackType":"closeCurrent","navTabId":"main"}';
				}
			}
			$this->autoRender = false;
		}else
		{
			$this->set('categories', $this->Category->generateTreeList(array(), null, null, '--'));
		}
	}
	
    /**
     * 视频管理处点击上传渲染视频上传弹出层
     */
	public function upload($cid = null){
		//获取权限
		$this->loadModel('Permission');
		$getPermissions = $this->Permission->getRolesCategoryPermissions($cid, $this->userInfo['Role']['id']);

		if(!in_array(2,explode(',',$getPermissions['Permission']['permissions'])) && !empty($cid)) return $this->jsonToDWZ(array('message'=>__('Not have access'),'callbackType'=>'closeCurrent'),300);

		$this->transcodeGroup();

		$transcodeGroups = $this->TranscodeGroup->getTranscodeParams($this->userInfo);

		$formatCode = Cache::read('formatCode', '_cake_core_');
		$this->set(compact('transcodeGroups','cid','formatCode'));

	}

    /**
     * 磁盘查找
     * @param  int $cid 分类ID
     * @return void
     */
	public function disk_find($cid = null){

		$categories = $this->Category->find('list',array('conditions'=>array('parent_id'=>0)));

		$this->loadModel('TranscodeCategory');
		$transcodeCategories = $this->TranscodeCategory->find('list',array('conditions'=>array('parent_id'=>0)));

		array_unshift($transcodeCategories, '默认分类');

		$transcodeGroups = $this->TranscodeGroup->getTranscodeParams($this->userInfo);
		
		$formatCode = Cache::read('formatCode', '_cake_core_');
		$this->set(compact('categories','transcodeCategories','transcodeGroups','formatCode'));
	}

    /**
     * 查询指定存储目录
     * @param  string $callback 回调函数
     * @param  string id 操作的页面元素ID
     * @return void
     */
	public function storage($callback = null, $id = null){
		$dir = new Folder(STORAGE_IP_ADDRESS . STORAGE_SELECT_PATH);
		$result = $dir->read();

		foreach ($result[0] as $key => $value) {
			$folders[$key] = $this->characet($value);
		}

		$targetCheckFileExt = explode('|', TARGET_CHECK_FILE_EXT);
		foreach ($result[1] as $key => $value) {
			$file = new File(STORAGE_IP_ADDRESS . STORAGE_SELECT_PATH . DS . $value);
			if(in_array('.'.strtolower($file->ext()), $targetCheckFileExt)){
				$files[$key] = $this->characet($value);
			}
		}

		$this->set(compact('folders','files','callback','id'));
	}

    /**
     * 扫描指定目录
     * @return void
     */
	public function scan_storage(){
		$path = urldecode($this->request->query['path'])?:STORAGE_IP_ADDRESS . STORAGE_SELECT_PATH;

		$fileType = Cache::read('storage_characet');

		if($fileType != 'UTF-8'){
			$path = mb_convert_encoding($path , $fileType, 'UTF-8');
		}

		$dir = new Folder($path);
		$result = $dir->read();

		foreach ($result[0] as $key => $value) {
			$folders[$key] = $this->characet($value);
		}
		$targetCheckFileExt = explode('|', TARGET_CHECK_FILE_EXT);
		foreach ($result[1] as $key => $value) {
			$file = new File($path . DS . $value);
			if(in_array('.'.strtolower($file->ext()), $targetCheckFileExt)){
				$files[$key] = $this->characet($value);
			}
		}

		$path = $this->characet($path);
		$now_path = addslashes($path);
		$upPath = substr($path, 0, strrpos($path, DS));
		
		$this->set(compact('path','upPath','now_path','folders','files'));
	}

    /**
     * 检测并转换中文字符集
     * @param  string $data 需要转换的中文字符
     * @return string       转换为UTF-8的字符
     */
	private function characet($data){
	  if( !empty($data) ){
	    $fileType = mb_detect_encoding($data , array('UTF-8','GBK','LATIN1','BIG5')) ;
	    if( $fileType != 'UTF-8'){
	    	Cache::write('storage_characet',$fileType);
	    	$data = mb_convert_encoding($data ,'utf-8' , $fileType);
	    }
	  }
	  return $data;
	}


/**
 * 发起转码请求
 * @param  string $content_id  内容ID
 * @param  int $user_id     用户ID
 * @param  int $template_id 模板组ID
 * @param  int $options     附加参数
 * @return boolean          处理结果
 */
	public function transcode($content_id = null, $user_id = null, $template_id = null, $options = null){
		if(!$content_id) return false;
		$this->autoRender = false;

		$user_id = $user_id?:$this->userInfo['User']['id'];
		$user = $this->User->find('first',array('conditions'=>array('User.id'=>$user_id)));
		
		$content = $this->Content->findById($content_id);
		//$files = Set::combine($content['Video'], '{n}.id','{n}.fileUrl');
		$this->loadModel('Video');
		$files = $this->Video->find('list',array('conditions'=>array('Video.originalFile'=>1,'Video.content_id'=>$content_id),'fields'=>array('id','fileUrl')));

		$transcodes = $this->TranscodeGroup->findById($template_id);

		if(@is_numeric($transcodes['TranscodeGroup']['policyID'])){
			$transParams = array('policyID'=>$transcodes['TranscodeGroup']['policyID']);
		}else{
			foreach ($transcodes['Transcode'] as $k => $transcode) {
				$joblist[$k] = json_decode($transcode['params'] ,true);
				$joblist[$k]['Transcode']['transcodeID'] =$transcode['id'];
			}
			$transParams = array('transType'=>'joblist','joblist' => $joblist,'split'=>@$transcodes['TranscodeGroup']['split']);
		}

		//$transParams = (!$template_id)?array('policyID'=>0):array('transType'=>'joblist','joblist' => $joblist,'split'=>@$transcodes['TranscodeGroup']['split']);
		$settings = array_merge(array('taskName'=>$content['Content']['title']),$transParams);

		if($options['duration'])$settings['OutPoint'] = $options['duration'] * 10000;
		if($options['is_split'])$settings['IsSplit'] = $options['is_split'];
		if($options['platFormID'])$settings['platFormID'] = $options['platFormID'];
		if($options['metaData']){
			$metaData = json_decode($options['metaData'],true);
			$settings['metaData'] = array();
			foreach ($metaData as $meta) {
				$mkey = str_replace(']','',str_replace('data[Metadata][', '', $meta['name']));
				$settings['metaData'][$mkey] = $meta['value'];
			}
		}
		$this->MpcClient = $this->Components->load('MpcClient',array('mpcUrl' => MPC_WEB_SERVICE));
		return $this->MpcClient->AddTask($files, $settings);
	}
	
	
    /**
     * 获取编码组内容
     *
     * @param int $id
     */
	public function transcodeGroup($id = null){
		//点击下拉框时加载
		if($id)
		{
			$transcodeGroup = $this->TranscodeGroup->find('first', array('conditions'=>array('TranscodeGroup.id'=>$id)));
			$transcodeGroupName = $transcodeGroup['TranscodeGroup']['name'];
			foreach($transcodeGroup['Transcode'] as $value)
			{
				$array['title'] = $value['title'];
				$array['params'] = json_decode($value['params'],true);
				$allTranscode[] = $array;
			}
			$this->loadModel('FormateCode');
			$videoFormat = $this->FormateCode->video;
				
			$this->set(compact('transcodeGroupName','allTranscode','videoFormat'));
		}else{
			//获取用户权限
			$roles = $this->Role->findById($this->userInfo['Role']['id']);
			$defaultTemplatesId = $roles['Role']['default_template_id']?$roles['Role']['default_template_id']:"";
			$templateAccesses = explode(',',$roles['Role']['template_accesses']);
			//根据权限加载模板组,下拉控件赋值
			$options = $this->TranscodeGroup->find('all', array('conditions'=>array('TranscodeGroup.id'=>$templateAccesses)));
			foreach($options as $value)
			{
				if($value['TranscodeGroup']['type'] == 1)
				{
					$transType = '视频 | ';
				}else{
					$transType = '音频 | ';
				}
				$arrID[] = $value['TranscodeGroup']['id'];
				$arrName[] = $transType.$value['TranscodeGroup']['name'];
				foreach($value['Transcode'] as $v)
				{
					if(@in_array($defaultTemplatesId, $v))
					{
						$array['title'] = $v['title'];
						$array['params'] = json_decode($v['params'],true);
						$allTranscode[] = $array;
					}
				}
			}
			@$options = array_combine($arrID, $arrName);
			@$transcodeGroupName = str_replace('音频 | ','',str_replace('视频 | ','',$options[$defaultTemplatesId]));
			
			$this->set(compact('options','transcodeGroupName','allTranscode','defaultTemplatesId'));
		}
	}
	
	/**
	 * 删除视频到回收站（废弃）
	 * Materials Single or Batch go to Recycle Bin
	 */
	public function gotorecycle(){
		if($this->request->data)
		{
			$ids = explode(',', $this->request->data['ids']);
			if ($this->Content->updateAll(array('Content.isdelete'=>1),array('Content.id'=>$ids)))
			{
				$this->Session->setFlash(__('Successfully removed the video to the Recycle'));
				$this->_setLogs();
				echo '{"statusCode":"200","message":"'.__('Successfully removed the video to the Recycle').'","callbackType":"closeCurrent","navTabId":"main"}';
			}
		}else
		{
			$this->Session->setFlash(__('The operation failed'));
			echo '{"statusCode":"300","message":"'.__('The operation failed').'"}';
		}
		$this->autoRender = false;
	}
	
	//获取客户上传文件夹大小
	public function getfilesize()
	{
		
	}

    /**
     * 分析选择文件，返回分析结果
     * @return void
     */
	public function choose($type = null){
		if(!$this->request->query['file_path']) return false;

		$HttpSocket = new HttpSocket();

		$filePath = str_replace('+','%2B',base64_encode(str_replace(STORAGE_IP_ADDRESS . STORAGE_SELECT_PATH, STORAGE_DISK . MPC_STORAGE_SELECT_PATH, $this->request->query["file_path"])));

		try {
			$result = $HttpSocket->get(ANALYSE_MEDIA_FILE_URL,'Request=GetMediaInfo(*'.$filePath.'*)');
		} catch (Exception $e) {
			return $this->jsonToDWZ(array('message'=>'无法使用文件分析服务，请检查服务是否开启.','callbackType'=>'closeCurrent'),'300');
		}

		if($result->code == 500){
			$this->autoRender = false;	
			return $this->jsonToDWZ(array('message'=>'该文件或文件夹不符合转码规则！','callbackType'=>'closeCurrent'),'300');
		}

		$fileInfo = Xml::toArray(Xml::build($result->body));
        $this->log($fileInfo,'xml');
		$filename = preg_replace('/^.+[\\\\\\/]/', '', urldecode($this->request->query["file_path"]));
		Cache::write(base64_encode($filename), $fileInfo['FileInfo'],'_cake_file_');
		
		$list = array();

		if(@isset($fileInfo['FileInfo']['MediaData']['PgmInfoList']['PgmInfo']['nPgmID'])){
			$fileInfo['FileInfo']['MediaData']['PgmInfoList']['PgmInfo'] = array($fileInfo['FileInfo']['MediaData']['PgmInfoList']['PgmInfo']);
		}

		$isdvd = (@$fileInfo['FileInfo']['Header']['TYPE'] == 2)?1:0;

		foreach ($fileInfo['FileInfo']['MediaData']['PgmInfoList']['PgmInfo'] as $pgm_key => $pgm) {
			$list[$pgm_key] = array(
					'name' => $pgm['Describe']?:'默认节目'
				);

			if(@isset($pgm['VideoInfoList']['VideoInfo']['nMediaID'])){
				$pgm['VideoInfoList']['VideoInfo'] = array($pgm['VideoInfoList']['VideoInfo']);
			}
			if(@is_array($pgm['VideoInfoList']['VideoInfo'])){
				foreach($pgm['VideoInfoList']['VideoInfo'] as $v_key => $video){
					$list[$pgm_key]['v'][$v_key] = array(
							'name' => $video['szDescrble']?:'默认视频',
							'Format' => $video['Format'],
							'dwDuration' => $video['dwDuration']
						);
				}
			}

			if(@isset($pgm['AudioInfoList']['AudioInfo']['nMediaID'])){
				$pgm['AudioInfoList']['AudioInfo'] = array($pgm['AudioInfoList']['AudioInfo']);
			}
			if(@is_array($pgm['AudioInfoList']['AudioInfo'])){
				foreach($pgm['AudioInfoList']['AudioInfo'] as $a_key => $audio){
					$list[$pgm_key]['a'][$a_key] = array(
							'name' => $audio['szDescrble']?:'默认音频',
							'Format' => $audio['Format'],
							'CH'=>$audio['WAVEFORMATEX']['nChannels'],
							'llDuration'=>$audio['llDuration']
						);
				}
			}

			if(@isset($pgm['CGInfoList']['CGInfo']['nMediaID'])){
				$pgm['CGInfoList']['CGInfo'] = array($pgm['CGInfoList']['CGInfo']);
			}
			if(is_array(@$pgm['CGInfoList']['CGInfo'])){
				foreach($pgm['CGInfoList']['CGInfo'] as $c_key => $cg){
					$list[$pgm_key]['c'][$c_key] = array(
							'name' => @$cg['strLange']?:'默认字幕',
							'Format'=> $cg['Format'],
							'llDuration'=>$cg['llDuration']
						);
				}
			}
		}

		Cache::write(base64_encode($filename).'_simple', $list,'_cake_file_');

		if($type == 'srt' || $type == 'audio'){
			$this->autoRender = false;
			echo json_encode(array('filename'=>$filename,'list'=>$list));
			return false;
		}
		
		$this->set(compact('list','filename','isdvd'));
	}

    /**
     * 添加待转码文件
     */
	public function add_transcode_unit(){
		$filename = $this->request->query['filename'];
		$isdvd = $this->request->query['isdvd'];
		$channel = $this->request->query['channel'];
		$list = Cache::read(base64_encode($filename).'_simple','_cake_file_');

		$pgm = array(
			'id'	=>	$this->request->query['pgm'],
			'name'	=>	$filename
		);

		$video = array(
			'id'	=>	$this->request->query['video'],
			'name'	=>	$list[$pgm['id']]['v'][$this->request->query['video']]['name'],
			'duration' => $list[$pgm['id']]['v'][$this->request->query['video']]['dwDuration']
		);

		$audio = array(
			'id'	=>	$this->request->query['audio'],
			'name'	=>	@$list[$pgm['id']]['a'][$this->request->query['audio']]['name'],
			'duration'	=>	@$list[$pgm['id']]['a'][$this->request->query['audio']]['llDuration']
		);

		$cg = array(
			'id'	=>	$this->request->query['cg'],
			'name'	=>	@$list[$pgm['id']]['c'][$this->request->query['cg']]['name']?:'无',
			'duration'	=>	@$list[$pgm['id']]['c'][$this->request->query['cg']]['llDuration']
		);
		$id = time();
		$this->set(compact('pgm','video','audio','cg','filename','id','isdvd','channel'));
	}

    /**
     * 生成转码目标XML
     * @return void
     */
	public function make_transcode_xml(){
		$this->autoRender = false;
		if(!@$this->request->data['ids']){
			echo '{"statusCode":"300","message":"没有选择文件无法开始转码任务！"}';
			return false;
		}
		$this->loadModel('MpcInterfaceXml');
		$xml = $this->MpcInterfaceXml->ApolloAdapterProjectFile;

		$task_ids = array_unique($this->request->data['ids']);

		$transcodes = $this->TranscodeGroup->findById($this->request->data['templateid']);
		foreach ($transcodes['Transcode'] as $transcode) {
			$joblist[] = json_decode($transcode['params'] ,true);
		}
		$delimiter = 'codeDelimiter';
		foreach ($task_ids as $task_id) {
			$sendXml = $xml['ApolloAdapterProjectFile']['MediaList']['Media'] = $xml['ApolloAdapterProjectFile']['TaskInfoList']['TaskInfo'] = array();
			$taskInfo = $this->request->data[$task_id];
			$taskInfo['metaDataForm'] = str_replace($delimiter, '"', @$taskInfo['metaDataForm']);
			foreach ($taskInfo['filename'] as $key => $filename) {
				$fileInfo = Cache::read(base64_encode($filename),'_cake_file_');
				$media = $this->MpcInterfaceXml->Media;
				$media['MediaIndex'] = $key;
				$media['FileInfo'] = $fileInfo;

				$xml['ApolloAdapterProjectFile']['MediaList']['Media'][] = $media;
			}

			$task = $this->MpcInterfaceXml->TaskInfo;

			foreach ($taskInfo['video'] as $video) {
				$v_tmp = explode('_', $video);
				$task['VideoMedia']['MediaIndex'] = $v_tmp[0];
				$task['VideoMedia']['PgmID'] = $v_tmp[1];
				$task['VideoMedia']['VideoIndex'] = $v_tmp[2];
				$task['VideoMedia']['OutPoint'] = $v_tmp[3];
			}

			foreach ($taskInfo['audio'] as $audio) {
				$a_tmp = explode('_', $audio);
				$task['AudioMedia']['MediaIndex'] = $a_tmp[0];
				$task['AudioMedia']['PgmID'] = $a_tmp[1];
				$task['AudioMedia']['AudioIndex'] = $a_tmp[2];
				$task['AudioMedia']['OutPoint'] = $a_tmp[3];
			}

			foreach ($taskInfo['cg'] as $cg) {
				$c_tmp = explode('_', $cg);
				if(@isset($c_tmp[2]) && @is_numeric($c_tmp[2])){
					$task['CGMedia']['MediaIndex'] = $c_tmp[0];
					$task['CGMedia']['PgmID'] = $c_tmp[1];
					$task['CGMedia']['CGIndex'] = $c_tmp[2];
					$task['CGMedia']['OutPoint'] = $c_tmp[3];
				}
			}

			$isdvd = in_array(1, $taskInfo['isdvd'])?1:0;

			if($isdvd == 1){
				$is_split = 0;
			}else{
				$is_split = @$this->request->data['is_split']?:SPLIT_DEFAULT_VALUE;
			}

			$task['TimeLineOutpoint'] = $v_tmp[3];

			if(!is_numeric($task['CGMedia']['MediaIndex'])){
				unset($task['CGMedia']);
			}

			$xml['ApolloAdapterProjectFile']['TaskInfoList']['TaskInfo'][] = $task;


			$sendXml = Xml::fromArray($xml)->asXML();

			//$xmlName = $this->_random(10) . '.xml';
			$xmlName = strtoupper(String::uuid()) . '.xml';
			$savePath = date('Ymd') . DS . $xmlName;

			$xmlFile = new File(STORAGE_IP_ADDRESS . PROJECT_XML_SAVE_PREFIX . DS . $savePath, true);

			if(!$xmlFile->write($sendXml)){
				echo '{"statusCode":"300","message":"系统错误！"}';
				return false;
			}

			$transcodeType = $this->TranscodeGroup->find('list',array('fields'=>'type', 'conditions'=>array('TranscodeGroup.id'=>$this->request->data['templateid'])));
			$this->Content->id = null;
			$contentData['Content'] = array(
				'id'				=>	strtoupper(String::uuid()),
				'category_id'		=>	$this->request->data['cid'],
				'transcode_group_id'=>	$this->request->data['templateid'],
				'user_id'			=>	$this->userInfo['User']['id'],
				'user_name'			=>	$this->userInfo['User']['account'],
				'title'				=>	$taskInfo['taskName'],
				'type'				=>	$transcodeType[$this->request->data['templateid']],
				'status'			=>	1,
				'meta_data'			=>  $taskInfo['metaDataForm'],
				'is_split'			=>	$is_split,
				'platform_id'		=>	isset($this->request->data['platFormID'])?implode(',', $this->request->data['platFormID']):'',
				'source'            => 3
			);
			$content = $this->Content->save($contentData);


			$saveData['Video'] = array(
				'content_id' => $content['Content']['id'],
				'originalFile' => 1,
				'filePath' => STORAGE_IP_ADDRESS . PROJECT_XML_SAVE_PREFIX . DS . $savePath,
				'fileName' => $xmlName,
				'fileUrl' => STORAGE_DISK . MPC_PROJECT_XML_SAVE_PREFIX . DS . $savePath,
				'fileFormat' => 'xml',
				'fileSize' => 0,
				'duration'	=>	$v_tmp[3],
				'addUser' => $this->userInfo['User']['account']
			);
			$this->loadModel('Video');
			$this->Video->id = null;
			$this->Video->save($saveData);

			$return = $this->transcode($content['Content']['id'], $this->userInfo['User']['id'], $this->request->data['templateid'], array('duration'=>$v_tmp[3],'is_split'=>$is_split,'platFormID'=>@$this->request->data['platFormID']?:null,'metaData'=>$taskInfo['metaDataForm']));
			$this->Content->saveField('task_id',$return['taskGUID']);
		}
		echo '{"statusCode":"200","message":"开始处理！", "callbackType":"closeCurrent", "navTabId":"main"}';		
	}

/**
 * 获得随机字符串
 * @param  int  $length  字符串长度
 * @param  integer $numeric 是否带有字母 默认不带
 * @return string           随机字符串
 */
	private function _random($length, $numeric = 0) {
	    $numeric=1;
	    PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
	    if($numeric) {
	        $hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
	    } else {
	        $hash = '';
	        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
	        $max = strlen($chars) - 1;
	        for($i = 0; $i < $length; $i++) {
	            $hash .= $chars[mt_rand(0, $max)];
	        }
	    }
	    return date('Y').$hash;
	}

/**
 * 上传选择框
 * @param  [type] $id [description]
 * @return [type]     [description]
 */
	public function updateChoose($id=null) {
		$this->set(array('id'=>$id));
	}
}
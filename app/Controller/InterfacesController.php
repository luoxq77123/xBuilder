<?php

App::uses('Xml', 'Utility');
App::uses('HttpSocket','Network/Http');

class InterfacesController extends AppController
{
	//public $uses = array('Category', 'TranscodeGroup', 'Transcode', 'User', 'Role','Permission');
/**
 * 是否开启自动渲染页面
 * @var boolean
 */
	public $autoRender = false;

/**
 * 接口提供的方法数组
 * @var array
 */
	private $allowMethods = array('add','update','GetServerConfig','ReportSearchedMediaFile');

/**
 * 添加状态需要传递的参数
 * @var array
 */
	private $addParamNames = array('token','taskID','clientIP','clientTimestamps','step','type','completeStatus');

/**
 * 更新状态需要传递的参数
 * @var array
 */
	private $updateParamNames = array('token','taskID','clientTimestamps','step','type','completeStatus');

/**
 * 错误代码数组
 * @var array
 */
	private $interfaceErrorCode = array('403'=>'Method is not exists!','404'=>'Method cannot be empty!','500'=>'Param Error!');

/**
 * 接口入口方法
 * @param  string $type 接口请求的方法
 * @return void
 */
	public function mpc_handle(){
		if($this->request->is('get')){
			$this->request->data = $this->request->query;
		}

		if(!$this->request->data && $data = file_get_contents("php://input")){
			$this->request->data = Xml::toArray(Xml::build($data));
			$this->request->data['method'] = $this->request->data['CMEDIAHTTP']['Header']['Command'];
		}

		$method = @$this->request->data['method'];
		if(!$method || !method_exists($this, $method)) return $this->_endParams(array(),404);

		if(!in_array($method, $this->allowMethods)) return $this->_endParams(array(),403);

		$this->{$method}();
	}

/**
 * 添加状态的方法
 * @return  void
 */
	private function add(){
		if(!$this->checkInput(__FUNCTION__)) return $this->_endParams(array(),500);

		$this->callGetTaskSteps();
		return $this->_endParams();
	}

/**
 * 更新状态的方法
 * @return  void
 */
	private function update(){
		if(!$this->checkInput(__FUNCTION__)) return $this->_endParams(array(),500);

		$parameter = json_decode($this->request->data['parameter'],true);
		if($this->Job->check($parameter['taskID'])){
			$this->Job->update($parameter['taskID'],$parameter);
		}else{
			$this->callGetTaskSteps();
		}

		return $this->_endParams();
	}

/**
 * 返回需要自动扫描的文件夹信息
 */
	private function GetServerConfig(){
		$req_data = array(
            'CMEDIAHTTP'=>array(
                'Header'=>array(
                    'Version'=>'',
                    'Command'=>'SetServerConfig'
                    ),
                'DataScope'=>array(
                	'Data' => ''
                	)
                )
            );

		$this->loadModel('AutoScan');
		$paths = $this->AutoScan->find('all',array('fields'=>array('path','suffix')));

		foreach($paths as $path){
            $data[] = array(
                    'SearchPath' => base64_encode(STORAGE_DISK . $path['AutoScan']['path']),
                    'FileExtensions' => str_replace('|', ';', $path['AutoScan']['suffix'])
                );
        }
        $req_data['CMEDIAHTTP']['DataScope']['Data'] = $data;

        echo Xml::fromArray($req_data)->asXML();
        return false;
	}


/**
 * 发送扫描到的新文件
 */
	private function ReportSearchedMediaFile(){
		$filePath = base64_decode($this->request->data['CMEDIAHTTP']['DataScope']['Data']['MediaFile']);	

		$HttpSocket = new HttpSocket();

		$filePath = str_replace('+','%2B',$this->request->data['CMEDIAHTTP']['DataScope']['Data']['MediaFile']);
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

		$isdvd = (@$fileInfo['FileInfo']['Header']['TYPE'] == 2)?1:0;

		$this->loadModel('AutoScan');
		$paths = $this->AutoScan->find('all',array('fields'=>array('path','tid','uid','user_name','is_split','platforms')));

		$this->loadModel('TranscodeGroup');
		$this->loadModel('Content');
		$this->loadModel('Video');

		$filePath = base64_decode($this->request->data['CMEDIAHTTP']['DataScope']['Data']['MediaFile']);
		$file_path_info = explode(DS, $filePath);

		foreach($paths as $path){
			if(strstr($filePath, STORAGE_DISK . $path['AutoScan']['path'] . DS)){
				$is_split = $isdvd == 1?0:$path['AutoScan']['is_split'];

				$transcodeType = $this->TranscodeGroup->find('list',array('fields'=>'type', 'conditions'=>array('TranscodeGroup.id'=>$path['AutoScan']['tid'])));
				$contentData['Content'] = array(
					'id'				=>	strtoupper(String::uuid()),
					'category_id'		=>	1,
					'transcode_group_id'=>	$path['AutoScan']['tid'],
					'user_id'			=>	$path['AutoScan']['uid'],
					'user_name'			=>	$path['AutoScan']['user_name'],
					'title'				=>	$file_path_info[count($file_path_info)-1],
					'type'				=>	$transcodeType[$path['AutoScan']['tid']],
					'status'			=>	1,
					'is_split'			=>	$is_split,
					'source'			=>	4
				);
				$content = $this->Content->save($contentData);

				$file_name = str_replace(STORAGE_DISK . $path['AutoScan']['path'] . DS, '', $filePath);
				$file_ext = array_reverse(explode('.', $file_name));

				$this->Video->id = null;
				$saveData['Video'] = array(
					'content_id' => $content['Content']['id'],
					'originalFile' => 1,
					'filePath' => STORAGE_IP_ADDRESS . $path['AutoScan']['path'] . DS . $file_name,
					'fileName' => $file_name,
					'fileUrl' => STORAGE_DISK . $path['AutoScan']['path'] . DS . $file_name,
					'fileFormat' => $file_ext[0],
					'fileSize' => 'N/A',
					'addUser' => $path['AutoScan']['user_name']
				);
				$this->Video->save($saveData);
				break;
			}
		}

		App::uses('MaterialsController', 'Controller');
		$this->MaterialsController = new MaterialsController();
		if($this->MaterialsController){
			$return = $this->MaterialsController->transcode($content['Content']['id'], $path['AutoScan']['uid'], $path['AutoScan']['tid'], array('is_split'=>$is_split,'platFormID'=>unserialize($path['AutoScan']['platforms'])));
			$this->Content->saveField('task_id',$return['taskGUID']);

			$this->GetMediaFileThumbnailPic($filePath,$return['taskGUID']);
		}else{
			$this->log("MaterialsController did not work!", 'debug');
		}

		return false;
	}

/**
 * 通知客户端抽帧
 * @param string $file_path 文件路径
 * @param string $Guid      抽帧图片文件名
 */
	private function GetMediaFileThumbnailPic($file_path,$Guid){
		$req_data = array(
			'CMEDIAHTTP'=>array(
				'Header'=>array(
					'Version'=>'',
					'Command'=>__FUNCTION__
					),
				'DataScope'=>array(
					'Data'=>array(
						'MediaFile' => base64_encode($file_path),
						'ThumbnailPic' => base64_encode(STORAGE_DISK . IMAGE_PATH_PREFIX . DS . $Guid . '.png')
						)
					)
				)
			);
		$req_xml = Xml::fromArray($req_data)->asXML();

		$HttpSocket = new HttpSocket();
		try {
			$reback = $HttpSocket->post(ANALYSE_MEDIA_FILE_URL,array('parameters'=>$req_xml));
		}catch(Exception $e){
			$this->log( 'Http Error : '.$e->getMessage() );
		}

		return false;
	}

/**
 * 检查传入参数的正确性
 * @param  string $type 调用的方法名
 * @return boolean      检查结果
 */
	private function checkInput($type = null){
		if(!$type) return false;

		$paramNames = array_keys(json_decode($this->request->data['parameter'],true));
		if(array_diff($this->{$type.'ParamNames'}, $paramNames)) return false;

		return true;
	}

/**
 * 获取任务步骤详情
 * @return boolean          获取结果
 */
	public function callGetTaskSteps(){
		$this->MpcClient = $this->Components->load('MpcClient',array('mpcUrl' => MPC_WEB_SERVICE));

		$taskInfo = $this->MpcClient->GetProjectList();

		if(!$taskInfo) return false;

		$this->loadModel('Job');

		$taskList = $taskInfo['MPCWebCmd']['Rtn_GetProjectList']['MPC_Project']['ProjectID']?array($taskInfo['MPCWebCmd']['Rtn_GetProjectList']['MPC_Project']):$taskInfo['MPCWebCmd']['Rtn_GetProjectList']['MPC_Project'];

		foreach($taskList as $task){
			$jobList[$task['TaskGUID']] = $task['MPC_Job']['JobID']?array($task['MPC_Job']):$task['MPC_Job'];
		}
		return $this->Job->storage($jobList);
	}

/**
 * 构建接口调用结果
 * @param Boolean $status	返回状态
 * @param Int $errorCode	错误码
 * @param Array $params		需要返回的数据
 * @return JSONString		JSON编码后的字符串
 */
	private function _endParams($params = array(), $errorCode = null){
		if($errorCode !== null){
			$params['returnCode'] = $errorCode;
			$params['returnDesc'] = $this->interfaceErrorCode[$errorCode];
		}else{
			$params['returnCode'] = 200;
			$params['returnDesc'] = 'success';
		}
		if($this->request->is('get') && @$this->request->query['callback']){
			echo $this->request->query['callback']."(".json_encode($params).")";
		}else{
			echo json_encode($params);
		}
		return $errorCode === null;
	}

	/**
	 * 创建一个默认客户账号
	 * 
	 * @return text
	 */
	public function createUser($email = null){
		if(!$email) return false;

		if(!$category_id = $this->_addCategory()){
			echo '分类创建失败' . '<br />';
		}elseif(!$transcodeGroup_id = $this->_addTranscodeGroup()){
			echo '转码组创建失败' . '<br />';
		}elseif(!$this->_addTranscodes($transcodeGroup_id)){
			echo '转码模板创建失败' . '<br />';
		}elseif(!$role_id = $this->_addRoles($transcodeGroup_id)){
			echo '用户组创建失败' . '<br />';
		}elseif(!$this->_addCategoryPermission($category_id,$role_id)){
			echo '分类权限创建失败' . '<br />';
		}elseif(!$this->_addUser($role_id,$email)){
			echo '用户添加失败' . '<br />';
		}else{
			echo 'good!';
		}

	}
	
	/**
	 * 清除客户账号(在创建客户用户时，出现故障，再次创建前使用，防止留下脏数据)
	 * 
	 * @param int $cid
	 * @return text
	 */
	private function _clearUser($cid = null){
		$tmpCategory = $this->Category->find('list',array('conditions'=>array('customer_id'=>$cid),'fields'=>'id'));
		$this->Category->deleteAll(array('customer_id'=>$cid),false);	
		$this->Permission->deleteAll(array('category_id'=>$tmpCategory),false);
		
		$tmpTranscodeGroup = $this->TranscodeGroup->find('list',array('conditions'=>array('customer_id'=>$cid),'fields'=>'id'));
		//$this->TranscodeGroup->hasMany = array();
		$this->TranscodeGroup->deleteAll(array('customer_id'=>$cid));
		
		/*if($tmpTranscodeGroup){
			$this->Transcode->belongsTo = array();
			$this->Transcode->deleteAll(array('transcode_group_id'=>$tmpTranscodeGroup),false);
		}*/
		
		$tmpRole = $this->Role->find('list', array('conditions'=>array('customer_id'=>$cid),'fields'=>'id'));
		$this->Role->deleteAll(array('Role.id'=>$tmpRole),false);
		
		$tmpUser = $this->User->find('list',array('conditions'=>array('customer_id'=>$cid),'fields'=>'id'));
		$this->User->belongsTo = array();
		$this->User->deleteAll(array('id'=>$tmpUser));
		
		echo 1;
	}
	
	/**
	 * 创建默认分类
	 * 
	 * @param $cid 客户ID
	 * @return int $category_id 分类ID
	 */
	private function _addCategory(){
		$category['Category'] = array(
			'parent_id' => 0,
			'name' => __('default category'),
			'sort' => 0,
		);
		if($this->Category->save($category)){
			return $this->Category->id;
		}else{
			return false;
		}
	}
	
	/**
	 * 创建默认分类权限(写死了初始栏目的权限)
	 * 
	 * @param $category_id 分类ID
	 * @param $rid 角色ID
	 * @return true or false
	 */
	private function _addCategoryPermission($category_id = null, $rid = null ){
		$tmp = $this->Permission->find('all',array('conditions'=>array('Permission.category_id'=>$category_id,'Permission.role_id'=>$rid)));
		if(!$tmp){
			$CategoryPermission['Permission'] = array(
				'category_id' => $category_id,
				'role_id' => $rid,
				'permissions' => '1,2,3,4,5,6'
			);
			if($this->Permission->save($CategoryPermission)){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	/**
	 * 创建默认编码组
	 * 
	 * @param $cid 客户ID
	 * @return int $transcodeGroup_id 转码组ID
	 */
	private function _addTranscodeGroup(){
		$transcodeGroup['TranscodeGroup'] = array(
			'name' => __('default transcode group')
		);
		if($this->TranscodeGroup->save($transcodeGroup)){
			return $this->TranscodeGroup->id;
		}else{
			return false;
		}

	}
	
	/**
	 * 创建默认编码模板
	 * 
	 * @param $tid 转码组ID
	 * @return true or false
	 */
	private function _addTranscodes($tid = null){
		$tmpParams = $this->Transcode->find('all',array('conditions'=>array('Transcode.transcode_group_id'=>$tid)));
		if(!$tmpParams){
			$defaultParams = json_decode(DEFAULT_TRANSCODE_PARAMS,true);
			foreach ($defaultParams as $value){
				$transcodes[]['Transcode'] = array(
					'transcode_group_id' => $tid,
					'title' => __($value['title']),
					'type' => 1,
					'params' => $value['params']
				);
			}
			if($this->Transcode->saveMany($transcodes)){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	/**
	 * 创建客户默认角色（只有管理员角色,写死了管理员的系统功能权限）
	 * 
	 * @param $cid 客户ID
	 * @param $tid 转码组ID
	 * @return int $role_id 角色ID
	 */
	private function _addRoles($tid = null){

		$role['Role'] = array(
			'name' => __('admin'),
			'sort' => 0,
			'default_template_id' => $tid,
			'template_accesses' => $tid,
			'operation_accesses' => '1,2,3,4,5'
		);
		if($this->Role->save($role)){
			return $this->Role->id;
		}else{
			return false;
		}

	}
	
	/**
	 * 创建管理员账号(密码为客户电子邮件 @后面的部分，如电子邮件为wwc@sobey.com 密码为sobey.com)
	 * 
	 * @param $rid 角色ID
	 * @param $email 客户email
	 * @return true or false
	 */
	private function _addUser($rid = null, $email = null){

			$password = explode('@',$email);
			$user['User'] = array(
				'role_id' => $rid,
				'account' => 'admin',
				'password' => md5($password[1]),
				'name' => __('admin'),
				'email' => $email,
				'status' => 1,
				'is_founder' => 1
			);
			if($this->User->save($user)){
				return true;
			}else{
				return false;
			}
	}
}
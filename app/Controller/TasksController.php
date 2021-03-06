<?php
/**
 * Tasks Controller
 *
 * 任务管理类，主要任务是处理MPC任务
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
App::uses('StringExpand', 'Lib');
App::uses('String', 'Utility');

class TasksController extends AppController
{

	public $uses = array('Content', 'User', 'Role', 'TranscodeGroup', 'Transcode', 'Permission', 'Config', 'Category', 'Split');

	/**
	 * 任务列表
	 * @param  int $id 任务分类ID
	 * @return void
	 */
	public function index($id = null)
	{
		$conditions = array();
		if ($id) {
			//获取权限
			$this->loadModel('Permission');
			$getPermissions = $this->Permission->getRolesCategoryPermissions($id, $this->userInfo['Role']['id']);

			if (!in_array(1, explode(',', $getPermissions['Permission']['permissions']))){
                return $this->jsonToDWZ(array('message' => __('Not have access')), 300);
            }
			$conditions['category_id'] = $id;
		}
		$conditions['isdelete'] = 0;
		$conditions = array_merge($this->get_select_conditions(), $conditions);

		$this->paginate = $this->pageHandler($this->request->data, $conditions, 'Content.created DESC');

		$this->loadModel('Content');
		$data = $this->paginate('Content');
		$this->set(compact('data', 'id'));
	}

	/**
	 * 处理搜索参数
	 * @return array 搜索条件数组
	 */
	private function get_select_conditions()
	{
		$conditions = array();
		isset($this->request->data['keyword']) && isset($this->request->data['searchType']) && $this->request->data['searchType'] && $conditions['Content.' . $this->request->data['searchType'] . ' LIKE'] = '%' . $this->request->data['keyword'] . '%';
		return $conditions;
	}

	/**
	 * Materials Detail 视频详细
	 */
	public function detail($id = null, $cid = null)
	{
		if (!$id && !$cid) return $this->jsonToDWZ(array('message' => '非法操作'), 300);
		$this->loadModel('Content');
		$content = $this->Content->find('first', array('conditions' => array('Content.id' => $id)));
		$this->loadModel('FormateCode');
		$file = $this->FormateCode->file;
		$video = $this->FormateCode->video;
		$ftpaddress = FTP_ADDRESS;
		$ftpuser = FTP_NUMBER_ACCOUNT;
		$ftppass = FTP_PASSWORD;
		$this->set(compact('content', 'ftpaddress', 'ftpuser', 'ftppass', 'file', 'video'));
	}

	/**
	 * 显示单条任务步骤信息
	 * @param  string $TaskGUID 任务ID
	 * @return void
	 */
	public function view($TaskGUID = null)
	{
		if ($TaskGUID) {
			//先从MPC获取状态
			$this->MpcClient = $this->Components->load('MpcClient', array('mpcUrl' => MPC_WEB_SERVICE));
			$taskXmlInfo = $this->MpcClient->GetProjectList();
			$taskList = @$taskXmlInfo['MPCWebCmd']['Rtn_GetProjectList']['MPC_Project']['ProjectID'] ? array($taskXmlInfo['MPCWebCmd']['Rtn_GetProjectList']['MPC_Project']) : @$taskXmlInfo['MPCWebCmd']['Rtn_GetProjectList']['MPC_Project'];
			if (isset($taskList)) {
				foreach ($taskList as $task) {
					$jobList[$task['TaskGUID']] = @$task['MPC_Job']['JobID'] ? array($task['MPC_Job']) : $task['MPC_Job'];
				}
			}
			//MPC获取失败，从缓存读取
			if (@!$jobList) {
				$taskInfo = Cache::read($TaskGUID, '_cake_task_');
			} else {
				$this->loadModel('Job');
				if ($this->Job->storage($jobList)) {
					$taskInfo = Cache::read($TaskGUID, '_cake_task_');
				}
			}
			//缓存没有，从数据库读取
			if (@!$taskInfo) {
                $this->loadModel('Content');
				$content = $this->Content->find('first', array('conditions' => array('Content.task_id' => $TaskGUID)));
				if ($content) {
					$taskInfo[0]['MPC_Spore'] = array(
						'JobType' => 'mediatrans',
						'JobName' => '转码',
						'ExecuteGuage' => '0',
						'ExecuteTime' => 'N/A',
						'ExecuteServer' => 'N/A',
						'ExecuteStatus' => $content['Content']['status']
					);
				} else {
					//数据库没有，文件上传未完成
					$taskInfo[0]['MPC_Spore'] = array(
						'JobType' => 'mediatrans',
						'JobName' => '上传',
						'ExecuteGuage' => '0',
						'ExecuteTime' => 'N/A',
						'ExecuteServer' => 'N/A',
						'ExecuteStatus' => '1'
					);
				}
			} else {
                //如果存在则MPC中心还没有处理完，否则表示MPC中心已处理完毕，需要手动更新入ML库状态
                if (isset($jobList[$TaskGUID])) {
                    $taskInfo[0]['MPC_Spore'] = $taskInfo;
                } else {
                    $newTaskInfo = array();
                    $arr = array();
                    foreach ($taskInfo as $val) {
                        if ($val['JobType'] === 'mediatrans') {
                            $arr[] = $val['ExecuteStatus'];
                        }
                    }
                    if (count($arr) == 2 && ($arr[0] == 16 && $arr[1] == 16)) {
                        foreach ($taskInfo as $val) {
                            $val['ExecuteStatus'] = $val['JobType'] === 'NM_ClipToML' ? 16 : $val['ExecuteStatus'];
                            $val['ExecuteGuage'] = $val['JobType'] === 'NM_ClipToML' ? 100 : $val['ExecuteGuage'];
                            $newTaskInfo[] = $val;
                        }
                    }
                    $taskInfo[0]['MPC_Spore'] = $newTaskInfo ? $newTaskInfo : $taskInfo;
                }
            }
		} else {
			//数据库没有，文件上传未完成
			$taskInfo[0]['MPC_Spore'] = array(
				'JobType' => 'mediatrans',
				'JobName' => '上传',
				'ExecuteGuage' => '0',
				'ExecuteTime' => 'N/A',
				'ExecuteServer' => 'N/A',
				'ExecuteStatus' => '1'
			);
		}
		$stepInfo = isset($taskInfo[0]['MPC_Spore'][0]['JobID']) ? $taskInfo[0]['MPC_Spore'] : array($taskInfo[0]['MPC_Spore']);
		$this->set(compact('stepInfo'));
	}


	/**
	 * 获取任务步骤信息（主动抓取）
	 * @param  string $TaskGUID 任务ID
	 * @return boolean          抓取结果
	 */
	public function getTaskSteps($TaskGUID = null)
	{
		if (!$TaskGUID) return false;

		$this->autoRender = false;

		$taskInfo = $this->MpcClient->GetProjectList($TaskGUID);

		$taskList = $taskInfo['MPCWebCmd']['Rtn_GetProjectList']['MPC_Project']['ProjectID'] ? array($taskInfo['MPCWebCmd']['Rtn_GetProjectList']['MPC_Project']) : $taskInfo['MPCWebCmd']['Rtn_GetProjectList']['MPC_Project'];

		foreach ($taskList as $task) {
			$jobList[$task['TaskGUID']] = $task['MPC_Job']['JobID'] ? array($task['MPC_Job']) : $task['MPC_Job'];
		}

		return true;
	}

	/**
	 * 修改任务优先级
	 * @param  string $type 修改方式 promote提升 reduce降低
	 * @return string       处理结果
	 */
	public function priority($type = 'promote')
	{ //reduce
		if (!$this->request->data['ids']) return $this->jsonToDWZ(array('message' => '非法操作'), 300);

		//$this->request->data['ids'] = explode(',', $this->request->data['ids']);

		$priority = ($type == 'promote') ? 100 : 5;

		$this->loadModel('Content');
		$this->Content->hasMany = $this->Content->belongsTo = array();
		$tasks = $this->Content->find('all', array('conditions' => array('Content.id' => $this->request->data['ids'])));

		$this->MpcClient = $this->Components->load('MpcClient', array('mpcUrl' => MPC_WEB_SERVICE));
		foreach ($tasks as $task) {
			if ($task['Content']['task_id'] && $task['Content']['priority'] != $priority) {
				$this->MpcClient->SetProjectPriority($task['Content']['task_id'], $priority);
				$this->Content->updateAll(array('Content.priority' => $priority), array('Content.id' => $task['Content']['id']));
			}
		}
		return $this->jsonToDWZ(array('message' => '操作成功', 'navTabId' => 'main'));
	}

	/**
	 * 重做任务
	 * @return void
	 */
	public function replay()
	{
		if ($this->request->data) {
			if (!$this->request->data['replayIds']) return $this->jsonToDWZ(array('message' => '非法操作'), 300);
			$this->request->data['replayIds'] = explode(',', $this->request->data['replayIds']);
			$this->loadModel('Content');
			$tasks = $this->Content->find('all', array('conditions' => array('Content.id' => $this->request->data['replayIds'], 'Content.status >' => 2)));
			if (count($this->request->data['replayIds']) != count($tasks)) return $this->jsonToDWZ(array('message' => '等待和正在转码的任务，不能发起重新转码！'), 300);
			App::import('Controller', 'Materials');
			$this->MaterialsController = new MaterialsController();

			$this->loadModel('Video');
			foreach ($tasks as $task) {
				if ($task['Content']['is_split'] == 0) {
					$is_split = 0;
				} else {
					$is_split = @$this->request->data['is_split'] ?: SPLIT_DEFAULT_VALUE;
				}

				$video = $this->Video->find('first', array('conditions' => array('Video.originalFile' => 1, 'Video.content_id' => $task['Content']['id'])));
				$return = $this->MaterialsController->transcode($task['Content']['id'], $this->userInfo['User']['id'], $this->request->data['templateid'], array('duration' => $video['Video']['duration'], 'is_split' => $is_split, 'platFormID' => @$this->request->data['platFormID'] ?: null, 'metaData' => $task['Content']['meta_data']));
				$this->Content->updateAll(array(
					'Content.transcode_group_id' => $this->request->data['templateid'],
					'Content.status' => 1,
					'Content.task_id' => '"' . $return['taskGUID'] . '"'
				), array(
					'Content.id' => $task['Content']['id']
				));
			}

			return $this->jsonToDWZ(array(
				'message' => '重新提交转码成功',
				'callbackType' => 'closeCurrent'
			), 200, true);
		}
		$this->loadModel('TranscodeGroup');
		if (!$transcodeGroups = $this->TranscodeGroup->getTranscodeParams($this->userInfo)) return $this->jsonToDWZ(array(
			'message' => '你的权限无法操作',
			'callbackType' => 'closeCurrent'
		), 300);

		$formatCode = Cache::read('formatCode', '_cake_core_');
		$this->set(compact('transcodeGroups', 'formatCode'));
	}

	/**
	 * 删除视频到回收站
	 * Materials Single or Batch go to Recycle Bin
	 */
	public function gotorecycle()
	{
		if (!$this->request->data['ids']) return $this->jsonToDWZ(array('message' => '非法操作'), 300);

		//$this->request->data['ids'] = explode(',', $this->request->data['ids']);

		$successTaskID = array();
		$this->loadModel('Content');
		$this->MpcClient = $this->Components->load('MpcClient', array('mpcUrl' => MPC_WEB_SERVICE));
		foreach ($this->request->data['ids'] as $value) {
			$this->Content->Behaviors->attach('Containable');
			$result = $this->Content->find('first', array('conditions' => array('id' => $value), 'contain' => array(), 'fields' => array('Content.task_id', 'Content.type', 'Content.user_id')));

			if ($result['Content']['user_id'] != $this->userInfo['User']['id']) {
				if (!strstr($this->userInfo['Role']['name'], '管理员') && $this->userInfo['User']['id'] != 1) {
					return $this->jsonToDWZ(array('message' => '你没有删除该任务的权利'), 300);
				}
			}

			//查询状态如果任务status等于2（完成状态）就不用去调MPC删任务了
			if ($result['Content']['type'] != 2) {
				if ($value) $back = $this->MpcClient->DeleteProject($result['Content']['task_id']);
				if ($back['MPCWebCmd']['Rtn_DeleteProject']['MPC_Status']['Status'] == 1) $successTaskID[] = $value;
			}
		}
		if ($this->Content->updateAll(array('Content.isdelete' => 1, 'Content.delete_time' => "'" . date('Y-m-d H:i:s', time()) . "'"), array('Content.id' => $this->request->data['ids']))) {
			return $this->jsonToDWZ(array(
				'message' => __('Successfully removed the video to the Recycle'),
				'callbackType' => 'closeCurrent',
				'navTabId' => 'main'
			), 200, true);
		}
		return $this->jsonToDWZ(array('message' => __('The operation failed')), 300);
	}

	/**
	 * 上传选择框
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function updateChoose($id = null)
	{
		$this->set(array('id' => $id));
	}

	/**
	 * 磁盘查找
	 * @param  int $cid 分类ID
	 * @return void
	 */
	public function disk_find($cid = null)
	{
		$categories = $this->Category->find('list', array('conditions' => array('parent_id' => 0)));
		$this->loadModel('TranscodeCategory');
		$transcodeCategories = $this->TranscodeCategory->find('list', array('conditions' => array('parent_id' => 0)));
		array_unshift($transcodeCategories, '默认分类');
		$transcodeGroups = $this->TranscodeGroup->getTranscodeParams($this->userInfo);
		$this->loadModel('Metadata');
		$metaData = $this->Metadata->find('all', array('order' => 'Metadata.order asc,Metadata.id desc', 'contain' => ''));
		$isAutoFill = array();
		foreach ($metaData as $value) {
			if ($value['Metadata']['is_auto_fill']) {
				$isAutoFill[] = $value['Metadata']['code'];
			}
		}
		$isAutoFill = json_encode($isAutoFill);
		$this->set(compact('isAutoFill', 'metaData'));
		$formatCode = Cache::read('formatCode', '_cake_core_');
		$this->set(compact('categories', 'transcodeCategories', 'transcodeGroups', 'formatCode'));
	}

	/**
	 * FTP上传
	 * @param  int $cid 分类ID
	 * @return void
	 */
	public function ftp_upload($cid = null)
	{
		//获取权限
		$getPermissions = $this->Permission->getRolesCategoryPermissions($cid, $this->userInfo['Role']['id']);
		if (!in_array(2, explode(',', $getPermissions['Permission']['permissions'])) && !empty($cid)) {
			return $this->jsonToDWZ(array('message' => __('Not have access'), 'callbackType' => 'closeCurrent'), 300);
		} else {
			$ftpaddress = FTP_ADDRESS;
			$ftpuser = FTP_NUMBER_ACCOUNT;
			$ftppass = FTP_PASSWORD;
			$this->set(compact('ftpaddress', 'ftpuser', 'ftppass'));
			$this->transcodeGroup();
		}
		$transcodeGroups = $this->TranscodeGroup->getTranscodeParams($this->userInfo);
		$this->loadModel('Metadata');
		$metaData = $this->Metadata->find('all', array('order' => 'Metadata.order asc,Metadata.id desc', 'contain' => ''));
		$isAutoFill = array();
		foreach ($metaData as $value) {
			if ($value['Metadata']['is_auto_fill']) {
				$isAutoFill[] = $value['Metadata']['code'];
			}
		}
		$isAutoFill = json_encode($isAutoFill);
		$this->set(compact('isAutoFill', 'metaData'));
		$formatCode = Cache::read('formatCode', '_cake_core_');
		$this->set(compact('transcodeGroups', 'cid', 'formatCode'));
	}

	/**
	 * 视频管理处点击上传渲染视频上传弹出层
	 */
	public function upload($cid = null)
	{
		//获取权限
		$this->loadModel('Permission');
		$getPermissions = $this->Permission->getRolesCategoryPermissions($cid, $this->userInfo['Role']['id']);
		if (!in_array(2, explode(',', $getPermissions['Permission']['permissions'])) && !empty($cid)){
            return $this->jsonToDWZ(array('message' => __('Not have access'), 'callbackType' => 'closeCurrent'), 300);
        }

		$this->transcodeGroup();
		$transcodeGroups = $this->TranscodeGroup->getTranscodeParams($this->userInfo);
		$this->loadModel('Metadata');
		$metaData = $this->Metadata->find('all', array('order' => 'Metadata.order asc,Metadata.id desc', 'contain' => ''));
		$isAutoFill = array();
		foreach ($metaData as $value) {
			if ($value['Metadata']['is_auto_fill']) {
				$isAutoFill[] = $value['Metadata']['code'];
			}
		}
		$isAutoFill = json_encode($isAutoFill);
		$this->set(compact('isAutoFill', 'metaData'));
		$formatCode = Cache::read('formatCode', '_cake_core_');
		$this->set(compact('transcodeGroups', 'cid', 'formatCode'));

	}

	/**
	 * 获取编码组内容
	 *
	 * @param int $id
	 */
	public function transcodeGroup($id = null)
	{
		//点击下拉框时加载
		if ($id) {
			$transcodeGroup = $this->TranscodeGroup->find('first', array('conditions' => array('TranscodeGroup.id' => $id)));
			$transcodeGroupName = $transcodeGroup['TranscodeGroup']['name'];
			foreach ($transcodeGroup['Transcode'] as $value) {
				$array['title'] = $value['title'];
				$array['params'] = json_decode($value['params'], true);
				$allTranscode[] = $array;
			}
			$this->loadModel('FormateCode');
			$videoFormat = $this->FormateCode->video;
			$this->set(compact('transcodeGroupName', 'allTranscode', 'videoFormat'));
		} else {
			//获取用户权限
			$roles = $this->Role->findById($this->userInfo['Role']['id']);
			$defaultTemplatesId = $roles['Role']['default_template_id'] ? $roles['Role']['default_template_id'] : "";
			$templateAccesses = explode(',', $roles['Role']['template_accesses']);
			//根据权限加载模板组,下拉控件赋值
			$options = $this->TranscodeGroup->find('all', array('conditions' => array('TranscodeGroup.id' => $templateAccesses)));
			foreach ($options as $value) {
				if ($value['TranscodeGroup']['type'] == 1) {
					$transType = '视频 | ';
				} else {
					$transType = '音频 | ';
				}
				$arrID[] = $value['TranscodeGroup']['id'];
				$arrName[] = $transType . $value['TranscodeGroup']['name'];
				foreach ($value['Transcode'] as $v) {
					if (@in_array($defaultTemplatesId, $v)) {
						$array['title'] = $v['title'];
						$array['params'] = json_decode($v['params'], true);
						$allTranscode[] = $array;
					}
				}
			}
			@$options = array_combine($arrID, $arrName);
			@$transcodeGroupName = str_replace('音频 | ', '', str_replace('视频 | ', '', $options[$defaultTemplatesId]));
			$this->set(compact('options', 'transcodeGroupName', 'allTranscode', 'defaultTemplatesId'));
		}
	}

	public function new_guid()
	{
		$guid = strtoupper(String::uuid());
		return $this->jsonToDWZ(array('guid' => $guid));
	}

    public function uploadLog()
    {
        $this->autoRender=false;
        $arr['fguid'] = $this->request->query['fguid'];
        $arr['fileName'] = $this->request->query['fileName'];
        $arr['ftype'] = $this->request->query['ftype'];
        $arr['fsize'] = $this->request->query['fsize'];
        $fileName = LOGS.date("Ymd")."logs.txt";
        $content = "[".date("Y-m-d H:i:s")."]".json_encode($arr);
        $handle = fopen($fileName,"a");
        fwrite($handle,$content."\r\n");
        fclose($handle);
        return true;
    }

	public function ftp_upload_transcode_execute($guid)
	{
		$this->loadModel('Content');
		$task = $this->Content->find('first', array('conditions' => array('Content.id' => $guid)));

		App::import('Controller', 'Materials');
		$this->MaterialsController = new MaterialsController();

		$this->loadModel('Video');

		$video = $this->Video->find('first', array('conditions' => array('Video.originalFile' => 1, 'Video.content_id' => $task['Content']['id'])));
		$this->log('ftp callback guid:'.$guid);
        //发起转码请求
		$return = $this->MaterialsController->transcode($task['Content']['id'], $this->userInfo['User']['id'], $task['Content']['transcode_group_id'], array('duration' => $video['Video']['duration'], 'is_split' => $task['Content']['is_split'], 'platFormID' => $task['Content']['platform_id'] ? explode(',', $task['Content']['platform_id']) : null, 'metaData' => $task['Content']['meta_data']));

        $this->Content->updateAll(array(
			'Content.status' => 1,
			'Content.task_id' => '"' . $return['taskGUID'] . '"'
		), array(
			'Content.id' => $task['Content']['id']
		));

		return $this->jsonToDWZ(array(
			'message' => '开始转码'
		), 200, true);
	}
}
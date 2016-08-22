<?php
/**
 * ReceiveMPCenter Controller
 *
 * receive MPC callback service
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

App::uses('Xml', 'Utility');
App::uses('File', 'Utility');
App::uses('Controller','Controller');

class ReceiveMPCenterController {
	public $state = array(
		0x0001	=>	'wait',		//开始进入执行队列
		0x0002 	=>	'canPlay',	//已经做好播出准备
		0x0004	=>	'sucess',	//处理成功
		0x0008	=>	'fail',		//处理失败
		0x0010	=>	'delete',	//任务删除通知
		0x0020	=>	'pause',	//任务暂停
		0x0040	=>	'lock',		//任务挂起
		0x0080	=>	'recover',	//任务暂停恢复
		0x0100	=>	'finish',	//任务完成
		0x0400	=>	'tryFirst',	//第一次尝试开始
		0x0800	=>	'failLast'	//最后一次失败
	);

/**
 * Say Hello
 *
 * @param string $input Your Name
 * @return string $result 返回信息
 */
	public function sayHello($input){
		return array("result"=>"hello, ".$input);
	}

/**
 * 接收MPC返回
 * @param string $message 返回消息
 */
	public function Commit($input){
		$params = Xml::toArray(Xml::build($input));

		$TaskGUID = $params['MPC']['Header']['RequestID'];
		$taskState = $params['MPC']['Content']['MPCNotify']['NotifyEvent'];

		Controller::loadModel('Content');
		switch ($this->state[$taskState]) {
			case 'wait':
				$nowStatus = 1;
				break;

			case 'tryFirst':
				$nowStatus = 2;
				$this->callGetTaskSteps();
				break;

			case 'finish':
				$nowStatus = 3;
				$this->callSetTaskComplete($TaskGUID);
				$transcodeFiles = $params['MPC']['Content']['MPCNotify']['MediaFile'];
				Controller::loadModel('Video');
				$content = $this->Content->find('first',array('conditions'=>array('task_id'=>$TaskGUID)));

				Controller::loadModel('FileFormat');
				Controller::loadModel('VideoFormat');	

				foreach ($transcodeFiles as $file) {
					if(strstr($file['GroupType'],'out') && strstr($file['MediaType'],'media')){
						//$fileReal = new File($file['FileName']);
						$fileReal = explode(DS, $file['FileName']);
						//add 20140922 通过transcode表中的id查找对应transcode的具体信息
						$transcodeParams = $this->getTranscodeDetail($file['MediaType']);
						$fileFormat =  $this->FileFormat->findByValue($transcodeParams['file']['FileFormat']);
						$videoFormat = $this->VideoFormat->findByValue($transcodeParams['video']['VideoFormat']);

						$data[] = array(
							'content_id'	=>	$content['Content']['id'],
							'fileUrl'		=>	$file['FileName'],
							'filePath'		=>	str_replace(TRANSFORM_FILE_PREFIX, FILE_DOWNLOAD_PREFIX, $file['FileName']),
							'fileFormat'	=>	$fileFormat['FileFormat']['name'],
							'fileName'		=>	$fileReal[count($fileReal) - 1],
							'fileSize'		=>	'N/A',
							'fileRate'		=>	isset($transcodeParams['video']['BitRate'])?$transcodeParams['video']['BitRate']:'0',
							'duration'		=>	$file['OutPoint'] - $file['InPoint'],
							'addUser'		=>	'mpc',
							'pictureWidth'		=>	$transcodeParams['video']['FormatWidth'],
							'pictureHeight'		=>	$transcodeParams['video']['FormatHeight'],
							'mediaType'		=>	$file['MediaType'],
							'videoFormat'	=>	$videoFormat['VideoFormat']['name']
						);
					}
				}
				
				if($data) $this->Video->saveAll($data);
				break;

			case 'failLast':
				$nowStatus = 4;
				break;
		}

		if($nowStatus){
			return $this->Content->updateAll(array('status'=>$nowStatus),array('task_id'=>$TaskGUID));
		}else{
			return true;
		}
	}

/**
 * 获取任务步骤详情
 * @return boolean $result 获取结果
 */
	public function callGetTaskSteps(){
		$this->Components = new ComponentCollection();
		$this->MpcClient = $this->Components->load('MpcClient',array('mpcUrl' => MPC_WEB_SERVICE));

		$taskInfo = $this->MpcClient->GetProjectList();

		if(!$taskInfo) return false;

		Controller::loadModel('Job');

		$taskList = $taskInfo['MPCWebCmd']['Rtn_GetProjectList']['MPC_Project']['ProjectID']?array($taskInfo['MPCWebCmd']['Rtn_GetProjectList']['MPC_Project']):$taskInfo['MPCWebCmd']['Rtn_GetProjectList']['MPC_Project'];

		foreach($taskList as $task){
			$jobList[$task['TaskGUID']] = $task['MPC_Job']['JobID']?array($task['MPC_Job']):$task['MPC_Job'];
		}

		return $this->Job->storage($jobList);
	}

/**
 * 设置步骤为完成状态
 * @param  string $TaskGUID 任务ID
 * @return boolean $result 获取结果
 */
	public function callSetTaskComplete($TaskGUID = null){
		if(!$TaskGUID) return false;

		Controller::loadModel('Job');

		return $this->Job->complete($TaskGUID);
	}

/**
 * 获取子模版详细信息
 * @param  string $mediaID media_+transecodeid
 * @return array           子模板数据
 */
	private function getTranscodeDetail ( $mediaID ) {
		$transeCodeID = explode('_',$mediaID);
		Controller::loadModel('Transcode');
		$data = $this->Transcode->find('first', array('conditions'=>array('Transcode.id'=>$transeCodeID[1]),'fields'=>'params'));
		return json_decode($data['Transcode']['params'], true);
	}

/**
 * 接收FTP服务器完成任务的通知并发起转码任务
 * @param string $input 输入的参数
 */
	public function getXML($input){
		$params = Xml::toArray(Xml::build($input));
		Controller::log($params);
		App::import('Controller','Tasks');

		$this->TasksController = new TasksController();

		return $this->TasksController->ftp_upload_transcode_execute($params['CloudiaTransfer']['ContentID']);
	}
}
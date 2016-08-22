<?php

class UploadsController extends AppController
{
	public $uses = array('Content', 'Video', 'TranscodeGroup', 'Customer');
	public $components = array('Upload');

	/**
	 * 接收水印上传信息
	 * @return void
	 */
	public function water()
	{
		$this->autoRender = false;
		$data = $this->request->params['form'];

		if ($data) {
			$file = $this->Upload->upload($data['Filedata'], STORAGE_IP_ADDRESS . WATER_UPLOAD_PREFIX);
			if (!$file) return $this->jsonToDWZ(array('message' => '上传出错，请检查图片文件格式'), 300);

			if (@!$this->userInfo['User']['email']) {
				$this->userInfo['User']['email'] = $this->request->data['user_name'];
			}

			return $this->jsonToDWZ(array(
				'message' => '水印文件上传成功',
				'imagePath' => $file['filePath'],
				'imageUrl' => STORAGE_DISK . WATER_UPLOAD_PREFIX . DS . $file['fileDir'] . DS . $file['fileName']
			), 200, true);
		} else {
			throw new BadRequestException();
		}
	}

	/**
	 * 接收视频文件网页上传
	 * @return void
	 */
	public function video()
	{
		$this->autoRender = false;
		$data = $this->request->params['form'];
		if ($data) {
			$file = $this->Upload->upload($data['Filedata'], STORAGE_IP_ADDRESS . VIDEO_UPLOAD_PREFIX);
			$transcodeType = $this->TranscodeGroup->find('list', array('fields' => 'type', 'conditions' => array('TranscodeGroup.id' => $this->request->data['templateid'])));
			$contentData['Content'] = array(
				'id' => strtoupper(String::uuid()),
				'category_id' => $this->request->data['categoryid'],
				'transcode_group_id' => $this->request->data['templateid'],
				'user_id' => $this->request->data['user_id'],
				'user_name' => $this->request->data['user_name'],
				'title' => $this->request->data['filename'],
				'type' => $transcodeType[$this->request->data['templateid']],
				'status' => 1,
				'is_split' => $this->request->data['is_split'],
				'platform_id' => $this->request->data['platformId'],
				'source' => 1,
				'meta_data' => $this->request->data['metaData']
			);
			$content = $this->Content->save($contentData);

			$saveData['Video'] = array(
				'content_id' => $content['Content']['id'],
				'originalFile' => 1,
				'filePath' => $file['filePath'],
				'fileName' => $file['fileName'],
				'fileUrl' => STORAGE_DISK . MPC_VIDEO_UPLOAD_PREFIX . DS . $file['fileDir'] . DS . $file['fileName'],
				'fileFormat' => substr($data['Filedata']['name'], strrpos($data['Filedata']['name'], '.') + 1),
				'fileSize' => $data['Filedata']['size'],
				'addUser' => $this->request->data['user_name']
			);
			$this->Video->save($saveData);

			$this->Session->setFlash(__('Upload video success', $this->request->data['filename']));
			$this->_setLogs($this->request->data['user_name']);

			App::uses('MaterialsController', 'Controller');
			$this->MaterialsController = new MaterialsController();
			if ($this->MaterialsController) {
				$return = $this->MaterialsController->transcode($content['Content']['id'], $this->request->data['user_id'], $this->request->data['templateid'], array('is_split' => $this->request->data['is_split'], 'platFormID' => @$this->request->data['platformId'] ? explode(',', $this->request->data['platformId']) : null, 'metaData' => $this->request->data['metaData']));
				$this->Content->saveField('task_id', $return['taskGUID']);
			} else {
				$this->log("MaterialsController did not work!", 'debug');
			}
		} else {
			throw new NotFoundException();
		}
	}

	/**
	 * 接收视频文件FTP上传
	 * @return void
	 */
	public function ftp_upload()
	{
        var_dump($this->request->data);exit;
		$delimiter = 'codeDelimiter';
		foreach ($this->request->data['arr'] as $value) {
			$value['backSendData']['medaData'] = str_replace($delimiter, '"', $value['backSendData']['medaData']);
			$transcodeType = $this->TranscodeGroup->find('list', array('fields' => 'type', 'conditions' => array('TranscodeGroup.id' => $value['backSendData']['templateid'])));
			$this->log('file guid:' . $value['backId']);
			$contentData['Content'] = array(
				'id' => $value['backId'],
				'category_id' => $value['backSendData']['categoryid'],
				'transcode_group_id' => $value['backSendData']['templateid'],
				'user_id' => $value['backSendData']['uid'],
				'user_name' => $value['backSendData']['uname'],
				'title' => $value['backSendData']['filename'],
				'type' => $transcodeType[$value['backSendData']['templateid']],
				'status' => 1,
				'is_split' => $value['backSendData']['is_split'],
				'platform_id' => $value['backSendData']['platFormId'],
				'source' => 2,
				'meta_data' => $value['backSendData']['medaData']
			);
			$cid = $this->Content->save($contentData);

			$this->Video->id = null;
			$saveData['Video'] = array(
				'content_id' => $cid['Content']['id'],
				'originalFile' => 1,
				'filePath' => STORAGE_IP_ADDRESS . FTP_UPLOAD_PREFIX . DS . $value['backUploadPath'] . DS . $value['backUploadUrl'] . '.' . $value['backSendData']['ftype'],
				'fileName' => $value['backUploadUrl'] . '.' . $value['backSendData']['ftype'],
				'fileUrl' => STORAGE_DISK . FTP_UPLOAD_PREFIX . DS . $value['backUploadPath'] . DS . $value['backUploadUrl'] . '.' . $value['backSendData']['ftype'],
				'fileFormat' => $value['backSendData']['ftype'],
				'fileSize' => $value['backSendData']['fsize'],
				'addUser' => $value['backSendData']['uname']
			);
			$this->Video->save($saveData);
		}
		$this->autoRender = false;
	}

}
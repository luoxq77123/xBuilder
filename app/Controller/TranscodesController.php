<?php
class TranscodesController extends AppController
{
	public $name = 'Transcodes';
	public $uses = array('Transcode','TranscodeGroup','User','Config');

/**
 * 显示某转码组下的转码模板
 * 
 * @param int $transcode_group_id	转码组ID
 * @param int $id 	转码子模板ID
 * @return no return
 */
	public function index($transcode_group_id = null, $id = null){

		if($transcode_group_id){
			$transcodeGroup = $this->TranscodeGroup->findById($transcode_group_id);
//pr($transcodeGroup);exit;
			if(count($transcodeGroup['Transcode']) > 0 && !$id) $id = $transcodeGroup['Transcode'][0]['id'];
			$this->set(compact('transcodeGroup','id'));
		}
	}

/**
 * AJAX读取子栏目列表
 * @param  int $transcode_group_id 转码组ID
 * @param  int $id                 转码子模板ID
 * @return void
 */
	public function ajaxlist($transcode_group_id = null, $id = null){
		if(!$transcode_group_id){
			$this->autoRender = false;
			return false;
		}

		$transcodeGroup = $this->TranscodeGroup->findById($transcode_group_id);
		$this->set(compact('transcodeGroup','id'));
	}

/**
 * 添加子模板
 * @param int $transcode_group_id 转码组ID
 */
	public function add($transcode_group_id = null){
		if(!$transcode_group_id) return $this->jsonToDWZ(array('message'=>'非法访问'),300);

		if($this->request->data){
			if(!$this->request->data['Transcode']['title']) return $this->jsonToDWZ(array('message'=>'请输入子模板名称'),300);

			$this->request->data['Transcode']['transcode_group_id'] = $transcode_group_id;
			$this->request->data['Transcode']['x_params'] = serialize($this->request->data);

			$this->request->data['Transcode']['params'] = json_encode($this->_createFieldParams($this->request->data));	

			if($this->Transcode->save($this->request->data)){
				return $this->jsonToDWZ(array('message'=>'操作成功','tgId'=>$transcode_group_id,'tid'=>$this->Transcode->id));
			}
			return $this->jsonToDWZ(array('message'=>'操作失败'), 300);
		}

		$this->request->data = true;
		$transcodeGroup = $this->TranscodeGroup->findById($transcode_group_id);

		$this->loadModel('FileFormat');
        $FileFormatOptions = $this->FileFormat->find ( 'list' , array('order'=>'name asc','conditions'=>array('is_show'=>1)));

		$this->set(compact('transcodeGroup','transcode','FileFormatOptions'));

		$this->render('add_edit_form_advanced');
	}

/**
	 * 编辑转码模板
	 * 
	 * @param int $id	转码模板ID
	 * @return no return
	 */
	public function edit($id = null){

		if($this->request->data){
			if(!$this->request->data['Transcode']['title']) return $this->jsonToDWZ(array('message'=>'请输入子模板名称'),300);

			$this->request->data['Transcode']['x_params'] = serialize($this->request->data);

			$this->request->data['Transcode']['params'] = json_encode($this->_createFieldParams($this->request->data));	

			if($this->Transcode->save($this->request->data)){
				return $this->jsonToDWZ(array('message'=>'操作成功','tgId'=>$this->request->data['Transcode']['transcode_group_id'],'tid'=>$this->Transcode->id));
			}
			return $this->jsonToDWZ(array('message'=>'操作失败'), 300);
		}

		if($id){
			$transcode = $this->Transcode->findById($id);
			$this->request->data = unserialize($transcode['Transcode']['x_params']);
			$this->request->data['Transcode']['id'] = $transcode['Transcode']['id'];
			$this->request->data['TranscodeGroup'] = $transcode['TranscodeGroup'];

	        //读取文件选项
	        if($this->request->data['Transcode']['FileFormat']){
	        	$this->loadModel('FileFormat');
	        	$FileFormatOptions = $this->FileFormat->find ( 'list' , array('order'=>'name asc','conditions'=>array('is_show'=>1)));

	        	$file = $this->FileFormat->getFileParam($this->request->data['Transcode']['FileFormat']);
	        	$this->set(compact('file','FileFormatOptions'));

	        	//读取视频选项
		        if($this->request->data['Transcode']['VideoFormat']){
		        	$this->loadModel('VideoFormat');
		        	$VideoFormatOptions = $this->VideoFormat->getVideoList($this->request->data['Transcode']['FileFormat']);

		        	$video = $this->VideoFormat->getVideoParam($this->request->data['Transcode']['VideoFormat']);
		        	$this->set(compact('video','VideoFormatOptions'));

		        	//读取音频选项
			        if($this->request->data['Transcode']['AudioFormat']){
			        	$this->loadModel('AudioFormat');
			        	$this->loadModel('VideoAudio');

			        	$AudioFormatOptions = $this->VideoAudio->getAudios($this->request->data['Transcode']['VideoFormat']);
			        	//$AudioFormatOptions = $this->AudioFormat->getAudioList($this->request->data['Transcode']['FileFormat']);

			        	$audio = $this->AudioFormat->getAudioParam($this->request->data['Transcode']['AudioFormat']);
			        	$this->set(compact('audio','AudioFormatOptions'));
			        }
		        }
	        }
        }     

		$this->set(compact('id'));
		$this->render('add_edit_form_advanced');
	}

/**
 * 构建可保存的转码参数数组
 * @param  array $data 客户端提交的数组
 * @return array       始于存储的数组
 */
	private function _createFieldParams($data){
		$this->loadModel('FileFormat');
		$file = $this->FileFormat->findById($data['Transcode']['FileFormat']);

		$this->loadModel('VideoFormat');
		$video = $this->VideoFormat->findById($data['Transcode']['VideoFormat']);

		$this->loadModel('AudioFormat');
		$audio = $this->AudioFormat->findById($data['Transcode']['AudioFormat']);

		$result = array(
			'file' => isset($data['file'])?array_merge($data['file'],array('FileFormat'=>$file['FileFormat']['value'])):array('FileFormat'=>$file['FileFormat']['value']),
			'video' => isset($data['video'])?array_merge($data['video'],array('VideoFormat' => $video['VideoFormat']['value'])):array('VideoFormat' => $video['VideoFormat']['value']),
			'audio' => isset($data['audio'])?array_merge($data['audio'],array('AudioFormat' => $audio['AudioFormat']['value'])):array('AudioFormat' => $audio['AudioFormat']['value']),
			'water' => isset($data['water'])?$data['water']:array()
		);

		return $result;
	}


/**
 * 删除转码模板
 * 
 * @param int $id	转码模板ID
 * @return no return
 */
	public function delete($id = null){
		if(!is_numeric($id)) return $this->jsonToDWZ(array('message'=>'非法访问'),300);

		$this->Transcode->id = $id;
		$transcode = $this->Transcode->find('first', array('conditions'=>array('Transcode.id'=>$this->Transcode->id)));
		if(!$transcode) return $this->jsonToDWZ(array('message'=>'输入错误'),300);

		if($this->Transcode->delete($id)) {
			return $this->jsonToDWZ(array('message'=>__('Delete transcode successfully',array($transcode['Transcode']['title']))), 200, true);
		}
		return $this->jsonToDWZ(array('message'=>__('Delete transcode fail',array($transcode['Transcode']['title']))), 300, true);
	}

	/**
	* 重置时根据ID查询子模板的详细信息
	*
	* @return string
	*/
	public function resetBack()
	{
		$this->autoRender = false;
		$Transcodes = $this->Transcode->find('first',array('conditions'=>array('Transcode.id'=>$this->request->data['Transcode']['id'])));
		return '{"tName":"'.$Transcodes['Transcode']['title'].'","Params":'.$Transcodes['Transcode']['params'].'}';
	}
	
	/**
	 * 对模板配置参数编码
	 * 
	 * @param $params
	 * @return string
	 */
	function _encodeParams($params){
		return json_encode($params);
	}
	
	/**
	 * 对模板配置参数解码
	 * 
	 * @param $params
	 * @return array
	 */
	function _decodeParams($params){
		return json_decode($params, true);
	}
	
	/**
	 * 生成转码用的模板(废弃)
	 * 
	 * @param int $transcode_group_id	转码组ID
	 * @return true or false
	 */
	public function bulidXml($transcode_group_id = null,$userLimitsWater = null){
		$transcodes = $this->TranscodeGroup->find('first',array('conditions'=>array('TranscodeGroup.id'=>$transcode_group_id)));
		$config = $this->Config->find('first',array('conditions'=>array('Config.ConfigType'=>'cmpc_notify_addresss')));
		$xmlArray = Xml::toArray(Xml::build($config['Config']['ConfigValue'].'/files/CloudiaTransform.xml'));
		
		foreach($transcodes['Transcode'] as $value){
			$tmpParams = $this->_decodeParams($value['params']);
			if(strstr($tmpParams['Transcode']['water_file'], 'tga'))
			{
				$configWaterUploadPath = $this->Config->find('first',array('conditions'=>array('Config.ConfigType'=>'waterUploadPath')));
				$configReplayUploadWaterPath = $this->Config->find('first',array('conditions'=>array('Config.ConfigType'=>'replayUploadWaterPath')));
				$waterPath = str_replace($configWaterUploadPath['Config']['ConfigValue'], $configReplayUploadWaterPath['Config']['ConfigValue'], $tmpParams['Transcode']['water_file']);
				$WatermarkFlagPermission = 1;
			}else {
				$waterPath = null;
			}
			if(in_array(4,explode(',',$userLimitsWater)) && @$WatermarkFlagPermission == 1 && !empty($tmpParams['Transcode']['water_file']))
			{
				$WatermarkFlagNum = 1;
			}else {
				$WatermarkFlagNum = 0;
			}
			//分片
			@$afterParams['IsSplit'] = $tmpParams['Transcode']['fpCheck'] == 'on'?1:0;
			@$afterParams['SplitTime'] = $tmpParams['Transcode']['SliceTime'];
			@$afterParams['WatermarkFlag'] = $WatermarkFlagNum;
			@$afterParams['StartX'] = $tmpParams['Transcode']['StartX']%2==1?$tmpParams['Transcode']['StartX']-1:$tmpParams['Transcode']['StartX'];
			@$afterParams['StartY'] = $tmpParams['Transcode']['StartY'];
			@$afterParams['ObjWidth'] = $tmpParams['Transcode']['ObjWidth']%2==1?$tmpParams['Transcode']['ObjWidth']-1:$tmpParams['Transcode']['ObjWidth'];
			@$afterParams['ObjHeight'] = $tmpParams['Transcode']['ObjHeight'];
			@$afterParams['PicPath'] = $waterPath;
			@$afterParams['VideoFormat'] = $tmpParams['Transcode']['VideoFormat'];
			@$afterParams['BitRate'] = $tmpParams['Transcode']['isbps']=="mbps"?$tmpParams['Transcode']['BitRate']*1024:$tmpParams['Transcode']['BitRate'];
			@$afterParams['FrameRate'] = $tmpParams['Transcode']['FrameRate'];
			@$afterParams['ImageWidth'] = $tmpParams['Transcode']['ImageWidth'];
			@$afterParams['ImageHeight'] = $tmpParams['Transcode']['ImageHeight'];
			@$afterParams['AudioFormat'] = $tmpParams['Transcode']['AudioFormat'];
			@$afterParams['KeyFrameRate'] = $tmpParams['Transcode']['Gop'];
			@$afterParams['ConvertModel'] = $tmpParams['Transcode']['ConvertModel'];
			@$afterParams['SamplesPerSec'] = $tmpParams['Transcode']['SamplesPerSec'];
			@$afterParams['BitsPerSample'] = $tmpParams['Transcode']['BitsPerSample'];
			@$afterParams['FileFormat'] = $tmpParams['Transcode']['FileFormat'];
			@$afterParams['SpecialParam'] = $tmpParams['Transcode']['SpecialParam'];
			@$arr[] = $afterParams;
		}
		
		$configVideoPreview = $this->Config->find('first',array('conditions'=>array('Config.ConfigType'=>'videoPreviewTranscodingDataConfiguration')));
		$configAudioPreview = $this->Config->find('first',array('conditions'=>array('Config.ConfigType'=>'audioPreviewTranscodingDataConfiguration')));
		$CloudiaTransform = array(
			'CloudiaTransform'=>array(
				'ContentID'=>'',
				'ContentName'=>'',
				'ContentType'=>$transcodes['TranscodeGroup']['type']==1?0:1,//素材类型，0视频，1音频
				'SystemName'=>'CMPC',
				'CPID'=>'',
				'ReTransform'=>'',
				'UserName'=>'',
				'Priority'=> 0,
				'SrcMediaInfo'=>array('PathName'=>''),
				'ObjMediaInfo'=>array(
					'PathFormatPrefix'=>'',
					'CodecParams'=>array(
						'CodecParam'=>$transcodes['TranscodeGroup']['type']==1?array_merge_recursive($arr, array(json_decode($configVideoPreview['Config']['ConfigValue'], true))):array_merge_recursive($arr, array(json_decode($configAudioPreview['Config']['ConfigValue'], true)))
						)
					),
				'KeyFrameFlag'=>$transcodes['TranscodeGroup']['type']==1?1:0, //
				'ObjPicInfo'=>array(
					'PathFormatPrefix'=>'',
					'ImageWidth'=>'',
					'ImageHeight'=>'',
					'ImageType'=>'',
					'Position'=>''
					),
				'FeedBackInfo'=>array(
					'ServiceMethod'=>'',
					'ServiceAddress'=>''
					),
				)
			);
		return $CloudiaTransform;
	}

/**
 * 根据子模板ID值 查找详细信息并渲染(废弃)
 * @param  int $group_id 父模板ID值
 * @param  int $id 子模板ID值
 * @return void
 */
	public function editTranscode($group_id = null, $id = null) {
		// todo by hyh
	    //$this->uses = array_merge($this->uses,array('FileFormat','Param','ParamFormat','AudioFormat','VideoFormat','VideoAudio','TranscodesGroup'));
	    $transcode = $this->Transcode->find('first',array('conditions'=>array('Transcode.id'=>$id)));
	    pr($transcode);
	    $this->set('transcode',$transcode);

	    // add 20141008
	    /*$catagory = $this->TranscodeCategory->generateTreeList ( array (), null, null, '-' ) ?  : array ();
	    $catagories = array ();
	    if ($catagory) {
	        foreach ( $catagory as $key => $v ) {
	            if (strstr ( $v, '---' ))
	                continue;
	            $catagories [$key] = $v;
	        }
	    }
	    $this->set ( 'cid', $data ['TranscodeGroup'] ['transcode_category_id'] );
	    $this->set ( 'viewCategory', $catagories );
	    
	    // ad 20141009
	    $FileFormats = $this->FileFormat->find ( 'all' );
	    $FileFormatOptions = array (
	            '' => '请选择'
	    );
	    foreach ( $FileFormats as $k => $v ) {
	        $FileFormatOptions [$v ['FileFormat'] ['id']] = str_replace ( 'FILE_', '', $v ['FileFormat'] ['name'] );
	    }
	    $this->set ( 'FileFormatOptions', $FileFormatOptions );*/
	}
}


























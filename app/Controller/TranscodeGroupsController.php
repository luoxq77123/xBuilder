<?php 
/**
 * TranscodeGroups Controller
 *
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
class TranscodeGroupsController extends AppController {

/**
 * 查看转码组列表
 * @return void
 */
public function index($id = null) {
	$conditions = array();
	$cid = 0;
	if($id) {
		$conditions = array('transcode_category_id'=>$id);
		$cid = $id;
	}
	$conditions = array_merge($this->get_select_conditions(), $conditions);
	$this->paginate = $this->pageHandler($this->request->data, $conditions, 'TranscodeGroup.id DESC', array('TranscodeCategory'));
	$allData = $this->paginate('TranscodeGroup');
	$this->set(compact('allData','cid'));
}

/**
 * 处理搜索参数
 * @return array 搜索条件数组
 */
private function get_select_conditions(){
	$conditions = array();

	$this->request->data['keyword'] = @$this->request->data['keyword']?:'';
	$this->request->data['searchType'] = @$this->request->data['searchType']?:'';

	if($this->request->data['keyword'] && $this->request->data['searchType'] == 'title'){
		$conditions['TranscodeGroup.name like'] = '%'.$this->request->data['keyword'].'%';
	}
	return $conditions;
}

/**
 * 添加视频转码组
 * @param int $template_type 转码组类型  1视频 2音频
 */

    public function add($template_type = null) {
        if($this->request->data){
            $this->request->data['TranscodeGroup']['type'] = $template_type;
            if($data = $this->TranscodeGroup->save($this->request->data)){
            	if(!@$data['TranscodeGroup']['name']) $data = $this->TranscodeGroup->read();

            	$oper = isset($this->TranscodeGroup->id)?'Edit':'Add';
            	$params = array(
            		'message'=> __($oper . ' transcodegroup successfully', array($data['TranscodeGroup']['name'])),
            		'value'	=>	$data['TranscodeGroup']['id'],
            		'navTabId'	=> 'main'
            	);
            	if(@$this->request->data['TranscodeGroup']['policyID']) $params['callbackType'] = 'closeCurrent';
            	
            	return $this->jsonToDWZ($params,200,true);
            }
        	return $this->jsonToDWZ(array(
        		'message'=> __($oper . ' transcodegroup fail', array($this->request->data['TranscodeGroup']['name'])),
        	),300,true);
        }

        $catagories = $this->getTranscodeCategory();
         
        $this->set(compact('catagories','template_type'));
        $this->render('add_edit_form_advanced');
    }


/**
 * 编辑转码组
 * @param int $id 转码组ID
 * @param int $sub_id 转码子模板ID
 * @return void
 */
	public function edit( $transcode_group_id = null, $sub_id = null ) {
		if(!$transcode_group_id || !is_numeric($transcode_group_id)) return $this->jsonToDWZ(array('message'=>'非法访问'),300);

		$this->TranscodeGroup->id = $transcode_group_id;
		$data = $this->TranscodeGroup->read();

		if($this->request->data){
			if($this->TranscodeGroup->save($this->request->data)){
				if(isset($this->request->data['TranscodeGroup']['name'])) $data['TranscodeGroup']['name'] = $this->request->data['TranscodeGroup']['name'];
				
				$params = array(
					'message'=> __('Edit transcodegroup successfully', array($data['TranscodeGroup']['name'])),
					'value'	=>	$data['TranscodeGroup']['id'],
					'navTabId'	=> 'main'
				);
				if(@$this->request->data['TranscodeGroup']['policyID']) $params['callbackType'] = 'closeCurrent';

				return $this->jsonToDWZ($params,200,true);
			}
			return $this->jsonToDWZ(array(
				'message'=> __('Edit transcodegroup fail', array($data['TranscodeGroup']['name'])),
				),300,true);
		}

		$this->request->data = $data;
		$catagories = $this->getTranscodeCategory();

		$this->set(compact('catagories','transcode_group_id','sub_id'));
		$this->render('add_edit_form_advanced');
	}


/**
 * 获取模板分类联动下拉框数据
 */
	public function getTranscodeCategory($cid = 0){
		$catagory = $this->TranscodeCategory->generateTreeList(array(), null, null, '-')?:array();
		$catagories = array();
		if($catagory){
			foreach($catagory as $key=>$v){
				if(strstr($v,'---')) continue;
				$catagories[$key]=$v;
			}
		}
		$this->set('cid', $cid);
		$this->set('viewCategory', $catagories);
		return $catagories;
	}

/**
 * 
 * 删除转码组
 * @param int $id
 * @throws MethodNotAllowedException
 */
	public function del( $id = null )
	{
		$this->autoRender = false;
		if($id)
		{
			$oldTranscodeGroup = $this->_backSelfMessage($id);
			if($this->TranscodeGroup->delete( $id ))
			{
				$this->Session->setFlash(__('Del transcodegroup successfully', array($oldTranscodeGroup['TranscodeGroup']['name'])));
				$this->_setLogs();
				echo '{"statusCode":"200", "message":"'.__('Del transcodegroup successfully', array($oldTranscodeGroup['TranscodeGroup']['name'])).'"}';
			}
		}elseif($this->request->data['ids'])
		{
			$transcodeGroupNames = $this->TranscodeGroup->find('list',array('conditions'=>array('TranscodeGroup.id'=>$this->request->data['ids'])));
			$writeNames = implode(',', $transcodeGroupNames);
			$this->log($transcodeGroupNames,'data');
			if($this->TranscodeGroup->deleteAll( array('TranscodeGroup.id'=>$this->request->data['ids'])))
			{
				$this->Session->setFlash(__('Del transcodegroup successfully', array($writeNames)));
				$this->_setLogs();
				echo '{"statusCode":"200", "message":"'.__('Del transcodegroup successfully', array($writeNames)).'"}';
			}
		}else
		{
			echo '{"statusCode":"300", "message":"'.__('Bad Request Exception').'"}';
			$this->autoRender = false;
		}
	}

/**
 * 查看转码组中的转码模板
 * 
 * @param int $id
 * @return 
 */
	public function view($id = null){
		if($id){
			$data = $this->TranscodeGroup->find('first',array('conditions'=>array('TranscodeGroup.id'=>$id),'contain'=>'Transcode'));
			$formatCode = Cache::read('formatCode', '_cake_core_');
			$this->set(compact('data','formatCode'));
		}else{
			echo '{"statusCode":"300", "message":"'.__('Bad Request Exception').'"}';
		}
	}

/**
 * 以标签方式显示转码组参数
 * @param  int $id 转码组ID
 * @return void
 */
	public function view_tab($id){
		$formatCode = Cache::read('formatCode', '_cake_core_');
		$transcodeGroups = $this->TranscodeGroup->getTranscodeParams($this->userInfo,$id);
		$this->set(compact('formatCode','transcodeGroups'));
	}


	public function watchTranscode()
	{
		$this->autoRender = false;
		$trans = $this->TranscodeGroup->find('all',array('conditions'=>array('TranscodeGroup.id'=>$this->request->data['id'])));
		if(count($trans[0]['Transcode']) == 0)
		{
			$this->TranscodeGroup->deleteAll(array('TranscodeGroup.id'=>$this->request->data['id']));
			echo '{"statusCode":"300", "message":"没有子模板，模板组将不会保存！", "navTabId":"main"}';
		}else{
			echo '{"statusCode":"200","navTabId":"main"}';
		}
	}

/**
 * 合并模板
 * @return string 合并结果
 */
	public function merge(){
		$isSubmit = isset($this->request->data['replayIds']);
		if($isSubmit){
		$ids = explode( ',', $this->request->data['replayIds'] );		
		$this->autoRender = false;
			if((!$ids || count($ids) < 2)){
				$this->jsonToDWZ(array('message'=>"请至少选择两个模板进行合并操作", "callbackType"=>"closeCurrent"),'300');
				return false;
			}

			$tlist = $this->TranscodeGroup->find('all',array('conditions'=>array('TranscodeGroup.id'=>$ids)));

			$type = null;
			$sub_transcode = array();
			foreach($tlist as $tvalue){
				if(!$type) $type = $tvalue['TranscodeGroup']['type'];
				if($type != $tvalue['TranscodeGroup']['type']){
					$this->jsonToDWZ(array('message'=>"只有同类型的模板才可以合并"),'300');
					return false;
				}
				foreach($tvalue['Transcode'] as $tr){
					$sub_transcode[]= $tr['id'];
				}
				$new_name[] = $tvalue['TranscodeGroup']['name'];
			}

		// $new = array('type'=>$type,'name'=>implode('-', $new_name));
			$newName = isset($this->request->data['name'])?$this->request->data['name']:'';
			if( $newName ) {
				if($this->TranscodeGroup->save(array('name'=>$newName, 'type'=>$type))){
					$this->loadModel('Transcode');
					if($this->Transcode->updateAll(array('transcode_group_id'=>$this->TranscodeGroup->id),array('Transcode.id'=>$sub_transcode))){
						if($this->TranscodeGroup->deleteAll(array('TranscodeGroup.id'=>$ids))){
							$this->jsonToDWZ(array('message'=>"合并成功", "callbackType"=>"closeCurrent","navTabId"=>"main"));
							return false;
						}
					}
				}
				$this->jsonToDWZ(array('message'=>"系统错误执行失败",'300'));
				return false;		
			}
		}
	}

/**
 * 拆分模板
 * @param  int $id 模板ID
 * @return void
 */
	public function split($id = null){
		$this->autoRender = false;
		if($id){
			$this->TranscodeGroup->belongsTo = array();
			$childrenTrans = $this->TranscodeGroup->find('first', array('conditions'=>array('TranscodeGroup.id'=>$id)));
			if($this->request->is('post')) {
				if(isset($this->request->data['split'])){
					$postParams = $this->request->data['split'];
					$newCodeGroupName = $this->request->data['name'];
					//拆分处理逻辑
					$addParams = array('type'=>$childrenTrans['TranscodeGroup']['type'],'name'=>$newCodeGroupName,'created'=>date('y-m-d H:i:s', time()),'transcode_category_id'=>$childrenTrans['TranscodeGroup']['transcode_category_id']);
					$Transcode = $this->TranscodeGroup->save($addParams);
					if($Transcode) {
						$newID = $Transcode['TranscodeGroup']['id'];
						//删除和加入以前的子模版
						$this->loadModel('Transcode');
						$this->Transcode->belongsTo = array();
						$updateResult = $this->Transcode->updateAll(array('transcode_group_id'=>$newID), array('id'=>$postParams));
						echo '{"selectedID":"' . $id . '", "statusCode":"200", "message":"拆分成功，您可以继续拆分", "callbackType":"forward","forwardUrl":"TranscodeGroups/index"}';
						exit;
					}
				}else {
					echo '{"statusCode":"300", "message":"对不起，请至少选择一个可以拆分的子模版", "callbackType":"closeCurrent"}';
					exit;
				}
			}
			if( $childrenTrans ){
				if(count($childrenTrans['Transcode']) > 1){
					$this->set(compact('childrenTrans'));
					$this->autoRender = true;
				}
				else{
					echo '{"statusCode":"300", "message":"对不起，没有或者只有一个子模版无法拆分", "callbackType":"closeCurrent"}';
				}
			} else {
				echo '{"statusCode":"300", "message":"'.__('Bad Request Exception').'", "callbackType":"closeCurrent"}';
			}
		} else {
			{
				echo '{"statusCode":"300", "message":"'.__('Bad Request Exception').'", "callbackType":"closeCurrent"}';
			}
		}
	}

/**
 * 根据ID返回指定转码组
 */
	public function _backSelfMessage( $id = null ) {
		return $this->TranscodeGroup->find('first', array('conditions'=>array('TranscodeGroup.id'=>$id)));
	}

/**
 * 添加分类
 * @param $id
 * @return html or json
 */
	public function add_category ($id = null){

		//获取权限
// 		$getPermissions = $this->_getRolesCategoryPermissions($id);
		// if(!in_array(4,explode(',',$getPermissions['Permission']['permissions'])) && $id)
		// {
		// 	echo '{"statusCode":"300", "message":"'.__('Not have access').'", "callbackType":"closeCurrent"}';
		// 	$this->autoRender = false;
		// }else 
		// {
		//fix public $uses 错误
		$this->loadModel('TranscodeCategory');
		if($this->request->data){
			$check = $this->TranscodeCategory->find('first',array('conditions'=>array(
				'TranscodeCategory.name'=>$this->request->data['TranscodeCategory']['name'],
				)));
			if(!$check){
				if($this->TranscodeCategory->save($this->request->data)){
					$getCategoryID = $this->TranscodeCategory->find('first', array('conditions'=>array('TranscodeCategory.name'=>$this->request->data['TranscodeCategory']['name'])));

					$this->Session->setFlash(__('Add TranscodeCategory', array($this->request->data['TranscodeCategory']['name'])));
					$this->_setLogs();
					echo '{"statusCode":"200", "message":"'.__('Add TranscodeCategory',array($this->request->data['TranscodeCategory']['name'])).'", "callbackType":"closeCurrent", "reload":true}';
				}else{
					$this->Session->setFlash(__('Add TranscodeCategory fail', array($this->request->data['TranscodeCategory']['name'])));
					$this->_setLogs();
					echo '{"statusCode":"300", "message":"'.__('Add TranscodeCategory fail',array($this->request->data['TranscodeCategory']['name'])).'", "callbackType":"closeCurrent"}';
				}
			}else{
				echo '{"statusCode":"300", "message":"'.__('TranscodeCategory name is exist',array($this->request->data['TranscodeCategory']['name'])).'"}';
			}
			$this->autoRender = false;
		}

		if(!is_numeric($id)) $id = '';
		$this->set('id',$id);

		$catagory = $this->TranscodeCategory->generateTreeList(array(), null, null, '-')?:array();
		$catagories = array();
		if($catagory){
			foreach($catagory as $key=>$v){
				if(strstr($v,'---')) continue;
				$catagories[$key]=$v;
			}
		}
		$this->set('viewCategory', $catagories);
			// }

	}


/**
 * 编辑分类
 * Edit Category
 */
	public function edit_category($id = null){
		if($id){
			 //   	$getPermissions = $this->_getRolesCategoryPermissions($id);
				// if(!in_array(6,explode(',',$getPermissions['Permission']['permissions'])))
				// {
				// 	echo '{"statusCode":"300", "message":"'.__('Not have access').'", "callbackType":"closeCurrent"}';
				// 	$this->autoRender = false;
				// }else 
				// {
			$this->loadModel('TranscodeCategory');
			$oldCategory = $this->TranscodeCategory->find('first', array('conditions'=>array('TranscodeCategory.id'=>$id)));
			if($oldCategory)
			{
				if($this->request->data)
				{
					$isfour = false;
					$oneCategory = $this->TranscodeCategory->find('all',array('conditions'=>array('TranscodeCategory.parent_id'=>$this->request->data['TranscodeCategory']['id'])));
					if(count($oneCategory)>0)
					{
						foreach($oneCategory as $oneV)
						{
							$twoCategory = $this->TranscodeCategory->find('all',array('conditions'=>array('TranscodeCategory.parent_id'=>$oneV['TranscodeCategory']['id'])));
							if(count($twoCategory)>0)
							{
								foreach($twoCategory as $twoV)
								{
									$threeCategory = $this->TranscodeCategory->find('all',array('conditions'=>array('TranscodeCategory.parent_id'=>$twoV['TranscodeCategory']['id'])));
									if(count($threeCategory)>0)
									{
										$isfour = true;
									}
								}
							}
						}
					}

					if($isfour == true)
					{
						echo '{"statusCode":"300", "message":"此分类有四级子类，不能再修改到其他类别！", "callbackType":"closeCurrent"}';
						$this->autoRender = false;
						return false;
					}

					$check = $this->TranscodeCategory->find('first',array('conditions'=>array(
						'TranscodeCategory.name'=>$this->request->data['TranscodeCategory']['name'],
						'TranscodeCategory.parent_id'=>@$this->request->data['TranscodeCategory']['parent_id']
						)));
					if(!$check)
					{
						if($this->TranscodeCategory->save($this->request->data))
						{
							$this->Session->setFlash(__('Edit TranscodeCategory', array($oldCategory['TranscodeCategory']['name'], $this->request->data['TranscodeCategory']['name'])));
							$this->_setLogs();
							echo '{"statusCode":"200", "message":"'.__('Edit TranscodeCategory', array($oldCategory['TranscodeCategory']['name'], $this->request->data['TranscodeCategory']['name'])).'", "callbackType":"closeCurrent", "reload":true}';
						}else
						{
							echo '{"statusCode":"300", "message":"'.__('Illegal operation',array($oldCategory['TranscodeCategory']['name'])).'", "callbackType":"closeCurrent"}';
						}
					}else
					{
						echo '{"statusCode":"300", "message":"'.__('Category name is exist',array($this->request->data['TranscodeCategory']['name'])).'"}';
					}
					$this->autoRender = false;

				}else
				{
					$this->set('category', $oldCategory);
				}
				$cate = $this->TranscodeCategory->generateTreeList(array(), null, null, '-');
				foreach($cate as $key=>$v)
				{
					if(strstr($v,'---'))
					{
						continue;
					}
					$catagoryView[$key]=$v;
				}
				$this->set('id', $id);
				$this->set('viewCategory', $catagoryView);

			}else
			{
				echo '{"statusCode":"300", "message":"'.__('Bad Request Exception').'", "callbackType":"closeCurrent"}';
				$this->autoRender = false;
			}
				// }
		}else
		{
			echo '{"statusCode":"300", "message":"'.__('Bad Request Exception').'", "callbackType":"closeCurrent"}';
			$this->autoRender = false;
		}
	}

/**
 * 删除分类支持批处理
 * Delete Category Support Batch
 */
	public function del_category($id = null){
		if(is_numeric($id))
		{
				//获取权限
				// $getPermissions = $this->_getRolesCategoryPermissions($id);
				// if(!in_array(5,explode(',',$getPermissions['Permission']['permissions'])))
				// {
				// 	echo '{"statusCode":"300", "message":"'.__('Not have access').'", "callbackType":"closeCurrent"}';
				// 	$this->autoRender = false;
				// }else 
				// {
			$this->loadModel('TranscodeCategory');
			$content = $this->TranscodeGroup->find('list',array('conditions'=>array('TranscodeGroup.transcode_category_id'=>$id)));
			if(count($content) == 0)
			{
				$category = $this->TranscodeCategory->find('first', array('conditions'=>array('TranscodeCategory.id'=>$id)));
				if($this->TranscodeCategory->delete($id)){
					$this->Session->setFlash(__('Del category', array($category['TranscodeCategory']['name'])));
					$this->_setLogs();
					echo '{"statusCode":"200", "message":"'.__('Del category', array($category['TranscodeCategory']['name'])).'", "reload":true}';
				}else{
					$this->Session->setFlash(__('Del category fail', array($category['TranscodeCategory']['name'])));
					$this->_setLogs();
					echo '{"statusCode":"300", "message":"'.__('Del category fail', array($category['TranscodeCategory']['name'])).'"}';
				}
			}else
			{
				echo '{"statusCode":"300", "message":"该分类下有模版不能删除"}';
			}
				// }
		}else{
			echo '{"statusCode":"300", "message":"'.__('Bad Request Exception').'"}';
		}
		$this->autoRender = false;
	}

/**
 * 分类迁移、复制
 * @return json or html
 */

	public function category_move()
	{
		$this->loadModel('TranscodeCategory');
		$this->loadModel('Transcode');
		if($this->request->data)
		{
			$move_type = isset($this->request->data['Category']['move_type'])?$this->request->data['Category']['move_type']:1;
			$Contentids = explode(',', $this->request->data['replayIds']);

			if($move_type==2){
				    //迁移
				if($this->TranscodeGroup->updateAll(array('TranscodeGroup.transcode_category_id'=>$this->request->data['Category']['parent_id']),array('TranscodeGroup.id'=>$Contentids)))
				{
					echo '{"statusCode":"200","message":"'.__('模板迁移成功！').'", "callbackType":"closeCurrent","navTabId":"main"}';
					$this->autoRender = false;
				}
			}else{
				    //复制并迁移
				foreach ($Contentids as $k=>$v){
					$TranscodeGroup = $this->TranscodeGroup->find('first',array('conditions'=>array('TranscodeGroup.id'=>$v)));
					$TranscodeGroup['TranscodeGroup']['id'] = null;
					$TranscodeGroup['TranscodeGroup']['created'] = date('Y-m-d h:i:s');
					$TranscodeGroup['TranscodeGroup']['name'] .= '(复制)';
					$TranscodeGroup['TranscodeGroup']['transcode_category_id'] = $this->request->data['Category']['parent_id'];
					$NewTG = $this->TranscodeGroup->save($TranscodeGroup);
					$transcode = $this->Transcode->find('all',array('conditions'=>array('Transcode.transcode_group_id'=>$v)));
					foreach ($transcode as $k=>$v){
						$v['Transcode']['id'] = null;
						$v['Transcode']['transcode_group_id'] = $NewTG['TranscodeGroup']['id'];
						$this->Transcode->save($v);
					}
				}
				echo '{"statusCode":"200","message":"'.__('模板复制、迁移成功！').'", "callbackType":"closeCurrent","navTabId":"main"}';
				$this->autoRender = false;
			}
		}else{
			$this->set('categories', $this->TranscodeCategory->generateTreeList(array(), null, null, '--'));
		}
	}

/**
 * 组装模板分类JS数据
 * @param  int $id 模板分类ID
 * @return string  JS模板分类树
 */
	public function getNodeTree($id = null){
		$this->autoRender = false;

		if(!isset($id)){
			echo '[["0","无"]]';
		} else {
			$this->loadModel('TranscodeGroup');
			$nodeTree = $this->TranscodeGroup->find('list',array('conditions'=>array('transcode_category_id'=>$id)));

			if(!$nodeTree){
				echo '[["0","无"]]';
			}else{
				$jsArray[] = '["0","选择模板"]';
				foreach ($nodeTree as $key => $value) {
					$jsArray[]= '["'.$key.'","'.$value.'"]';
				}
				echo '['.implode(',', $jsArray).']';
			}
		}
	}
	
/**
 * 编辑分类
 * @param  integer $id [转码组分类ID]
 * @return json      result msg
 */
	public function editCategory ($id = 0) {
		$this->autoRender = false;
		if($id) {
			$this->TranscodeGroup->id = $this->request->data['cacheId'];
			if($this->TranscodeGroup->save(array('transcode_category_id'=>$id))) {
				echo '{"statusCode":"200", "message":"编辑分类成功","navTabId":"main"}';
			}else {
				echo '{"statusCode":"300", "message":"编辑分类失败"}';
			}
		}else{
			echo '{"statusCode":"300", "message":"请选择分类后再修改"}';
		}
	}
}
?>
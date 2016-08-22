<?php
/**
 * Categories Controller
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

class CategoriesController extends AppController{
	//public $name = 'Categories';
	//public $helpers = array('Html', 'Form', 'Js', 'Dwz');
	//public $layout = 'ajax';
	//public $uses = array('Category','Permission','Content');
/**
 * 分类列表
 * Categories list
 */
/*	public function index($id=null) {
		$trees = $this->Category->find('threaded', array('order' => array('sort ASC'),'conditions'=>array('customer_id'=>$this->cookieCustomerId)));
		$treeController = 'categories';
		$treeAction = 'edit';
		$this->set(compact('trees', 'treeController', 'treeAction','id'));
    }*/
	
	/**
	 * 添加分类
	 * @param $id
	 * @return html or json
	 */
	public function add($id = null){

		//获取权限数组
		$this->loadModel('Permission');
		$getPermissions = $this->Permission->getRolesCategoryPermissions($id,$this->userInfo['Role']['id']);

		if(!in_array(4,explode(',',$getPermissions['Permission']['permissions'])) && $id) return $this->jsonToDWZ(array('message'=>__('Not have access'),'callbackType'=>'closeCurrent'),300);

		if($this->request->data){
			if($this->Category->findByName($this->request->data['Category']['name'])) return $this->jsonToDWZ(array(
				'message'=>__('Category name is exist',array($this->request->data['Category']['name'])),
				),300);

			if($addCategory = $this->Category->save($this->request->data)){
				$data = array(
					'category_id'	=>	$addCategory['Category']['id'],
					'role_id'		=>	$this->userInfo['Role']['id'],
					'permissions'	=>	'1,2,3,4,5,6'
					);
				if($this->Permission->save($data)){
					return $this->jsonToDWZ(array('message'=>__('Add category', array($this->request->data['Category']['name'])),'callbackType'=>'closeCurrent','reload'=>'true'),200,true);
				}
			}
			return $this->jsonToDWZ(array('message'=>__('Add category fail', array($this->request->data['Category']['name'])),'callbackType'=>'closeCurrent'),300);
		}

		if(!is_numeric($id)) $id = '';
		$this->set('id',$id);

		$catagory = $this->Category->generateTreeList(array(), null, null, '-')?:array();
		$catagories = array();
		if($catagory){
			foreach($catagory as $key=>$v){
				if(strstr($v,'---')) continue;
				$catagories[$key]=$v;
			}
		}
		$this->set('viewCategory', $catagories);
	}

	/**
	 * 编辑分类
	 * Edit Category
	 */
	public function edit($id = null){
	   if($id){
		   	$this->loadModel('Permission');
			$getPermissions = $this->Permission->getRolesCategoryPermissions($id,$this->userInfo['Role']['id']);

			if(!in_array(6,explode(',',$getPermissions['Permission']['permissions'])))
			{
				echo '{"statusCode":"300", "message":"'.__('Not have access').'", "callbackType":"closeCurrent"}';
				$this->autoRender = false;
			}else 
			{
				$oldCategory = $this->Category->find('first', array('conditions'=>array('Category.id'=>$id)));
				if($oldCategory)
				{
					if($this->request->data)
					{
						$isfour = false;
						$oneCategory = $this->Category->find('all',array('conditions'=>array('Category.parent_id'=>$this->request->data['Category']['id'])));
						if(count($oneCategory)>0)
						{
							foreach($oneCategory as $oneV)
							{
								$twoCategory = $this->Category->find('all',array('conditions'=>array('Category.parent_id'=>$oneV['Category']['id'])));
								if(count($twoCategory)>0)
								{
									foreach($twoCategory as $twoV)
									{
										$threeCategory = $this->Category->find('all',array('conditions'=>array('Category.parent_id'=>$twoV['Category']['id'])));
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
						
						$check = $this->Category->find('first',array('conditions'=>array(
							'Category.name'=>$this->request->data['Category']['name'],
							'Category.parent_id'=>@$this->request->data['Category']['parent_id']
						)));
						if(!$check)
						{
							if($this->Category->save($this->request->data))
							{
								$this->Session->setFlash(__('Edit category', array($oldCategory['Category']['name'], $this->request->data['Category']['name'])));
								$this->_setLogs();
								echo '{"statusCode":"200", "message":"'.__('Edit category', array($oldCategory['Category']['name'], $this->request->data['Category']['name'])).'", "callbackType":"closeCurrent", "reload":true}';
							}else
							{
								echo '{"statusCode":"300", "message":"'.__('Illegal operation',array($oldCategory['Category']['name'])).'", "callbackType":"closeCurrent"}';
							}
						}else
						{
							echo '{"statusCode":"300", "message":"'.__('Category name is exist',array($this->request->data['Category']['name'])).'"}';
						}
						$this->autoRender = false;
						
					}else
					{
						$this->set('category', $oldCategory);
					}
					$cate = $this->Category->generateTreeList(array(), null, null, '-');
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
			}
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
	public function del($id = null){
		if(is_numeric($id))
		{
			//获取权限
			$this->loadModel('Permission');
			$getPermissions = $this->Permission->getRolesCategoryPermissions($id,$this->userInfo['Role']['id']);
			if(!in_array(5,explode(',',$getPermissions['Permission']['permissions'])))
			{
				echo '{"statusCode":"300", "message":"'.__('Not have access').'", "callbackType":"closeCurrent"}';
				$this->autoRender = false;
			}else 
			{
				$this->loadModel('Content');
				$content = $this->Content->find('list',array('conditions'=>array('Content.category_id'=>$id)));
				if(count($content) == 0)
				{
					$category = $this->Category->find('first', array('conditions'=>array('Category.id'=>$id)));
					if($this->Category->delete($id)){
						$this->Session->setFlash(__('Del category', array($category['Category']['name'])));
						$this->_setLogs();
						echo '{"statusCode":"200", "message":"'.__('Del category', array($category['Category']['name'])).'", "reload":true}';
					}else{
						$this->Session->setFlash(__('Del category fail', array($category['Category']['name'])));
						$this->_setLogs();
						echo '{"statusCode":"300", "message":"'.__('Del category fail', array($category['Category']['name'])).'"}';
					}
				}else
				{
					echo '{"statusCode":"300", "message":"'.__('Have content can not delete').'"}';
				}
			}
		}else{
			echo '{"statusCode":"300", "message":"'.__('Bad Request Exception').'"}';
		}
		$this->autoRender = false;
	}

	public function getNodeTree($id = null){
		$this->autoRender = false;

		if(!$id){
			echo '[["0","无"]]';
		} else {
			$nodeTree = $this->Category->find('list',array('conditions'=>array('parent_id'=>$id)));

			if(!$nodeTree){
				echo '[["0","无"]]';
			}else{
				$jsArray[] = '["0","选择子类"]';
				foreach ($nodeTree as $key => $value) {
					$jsArray[]= '["'.$key.'","'.$value.'"]';
				}
				echo '['.implode(',', $jsArray).']';
			}
		}
	}
}
<?php
/**
 * Roles Controller
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

class RolesController extends AppController{
	
	//public $helpers = array('Html', 'Form', 'Js', 'Dwz');
	//public $uses = array('User','Role','TranscodeGroup','Permission');
	
	
/**
 * 角色列表
 * @return void
 */
	public function index(){
	    $this->paginate = $this->pageHandler($this->request->data, array(), 'Role.sort ASC');

	    $this->loadModel('User');
	    $this->User->linkRole();

	    $allroles = $this->paginate('Role');
	    $this->set(compact('allroles'));
	}
	
	
/**
 * 添加角色
 * @return void
 */
	public function add()
	{
		if($this->request->data) {	
			isset($this->request->data['Role']['operation_accesses']) && $this->request->data['Role']['operation_accesses'] = implode(',',$this->request->data['Role']['operation_accesses']);
			
			if ($this->Role->findByName($this->request->data['Role']['name'])) 
				return $this->jsonToDWZ(array(
					'message'=>__('Role already exists', array($this->request->data['Role']['name'])),
				), 300);

			if($this->Role->save($this->request->data)) 
				return $this->jsonToDWZ(array(
					'message'=>__('Add roles', array($this->request->data['Role']['name'])),
					'callbackType'=>'closeCurrent',
					'navTabId'=>'main'
				), 200, true);
		}

		$this->_getSystemPermissions();
	}
	
/**
 * 编辑角色
 * @param  int $id 角色ID
 * @return void
 */
	public function edit($id = null)
	{
		if(!$id || !is_numeric($id)) return $this->jsonToDWZ(array('message'=>'非法访问'),300);

		$this->Role->id = $id;
		if($this->request->data) {
			is_array($this->request->data['Role']['operation_accesses']) && $this->request->data['Role']['operation_accesses'] = implode(',', $this->request->data['Role']['operation_accesses']);

			if($this->Role->save($this->request->data)) return $this->jsonToDWZ(array(
					'message'=>__('Edit roles', array($this->request->data['Role']['name'])),
					'callbackType'=>'closeCurrent',
					'navTabId'=>'main'
				), 200, true);
		}

		$this->request->data = $this->Role->read();
		$this->_getSystemPermissions();
	}
	
	
/**
 * 删除角色支持批量
 * 如果是批量删除，需要POST ids数组参数
 * @param  int $id 角色ID
 * @return void
 */
	public function del( $id = null )
	{
		if($id) $this->request->data['ids'] = $id;

		if(!$this->request->data['ids']) return $this->jsonToDWZ(array('message'=>'非法访问'),300);

		$this->request->data['ids'] = explode(',', $this->request->data['ids']);

		if($this->Role->deleteAll(array('Role.id' => $this->request->data['ids']))) return $this->jsonToDWZ(array(
			'message'	=>	__('Del roles'),
			'navTabId'	=>	'main',
			'rel'		=>	'main'
			), 200, true);

		return $this->jsonToDWZ(array('message'=>__('The operation failed')),300);
	}

/**
 * 显示制定角色用户列表
 * @param  int $id 角色ID
 * @return void
 */
	public function view($id)
	{
		$this->loadModel('User');

		$conditions['User.role_id'] = $id;
		!$this->User->isAdmin($this->userInfo['User']['id']) && $conditions['User.id !='] = '1';

	    $this->paginate = $this->pageHandler($this->request->data, $conditions, 'User.id DESC');
	    
		$this->User->linkRole();

	    $this->set('users',$this->paginate('User'));
	}
	
	
/**
*  获取角色列表
*/	
	public function permissions()
	{
		$this->set('roles',$this->Role->find('list'));
	}
	
/**
 * 角色权限详情
 * 点击角色，渲染右侧的数据，角色用户，分类权限，模板分配，系统权限
 * 
 * @param  int $id 角色ID
 * @return void
 */
	public function detailPremission($id = null){
		if(is_numeric($id)){

			/**
			*  传角色ID显示详细权限
			*  渲染用户信息
			*/
			//add 20141117 超级管理员才在列表页显示
			$this->loadModel('User');
			$conditions = array('User.role_id'=>$id);
			!$this->User->isAdmin($this->userInfo['User']['id']) && $conditions['User.id !='] = '1';

		    $this->paginate = $this->pageHandler($this->request->data, $conditions, 'User.id DESC');

		    $this->loadModel('User');
		    $this->User->linkRole();
		    $users = $this->paginate('User');
		    
		    /**
			*  渲染分类权限
			*/
			$allCategoryId = $this->Permission->find('all', array('conditions'=>array('Permission.role_id'=>$id)));
			$categoryIdPermissions = $this->Permission->find('list', array('fields'=>array('Permission.category_id','Permission.permissions'),'conditions'=>array('Permission.role_id'=>$id)));
		    $this->categoriesPermissions($id);
			$this->_getCategoryPermissions();

		    /**
			*  渲染模板权限
			*/
			$this->loadModel('TranscodeGroup');
		    $transcodeGroup = $this->TranscodeGroup->find('all',array('contain'=>array('TranscodeCategory'=>array('fields'=>array('name'))),'order'=>'TranscodeGroup.id DESC'));
		    /**
			*  渲染系统权限
			*/
		    $this->Role->id = $id;
		    $this->request->data = $this->Role->read();
			$this->request->data['Role']['operation_accesses'] = explode(',',$this->request->data['Role']['operation_accesses']);
		    $this->_getSystemPermissions();

		    $this->helpers[] = 'Dwz';
		    $this->set(compact('users','allCategoryId','categoryIdPermissions','transcodeGroup','id'));
		}
	}
	
/**
*  添加用户入角色
*
*/
	public function addUserToRoles()
	{
		if($this->request->data)
		{	
			if(!isset($this->request->data['ids']))
			{
				$this->Session->setFlash(__('Please check and after submit'));
				echo '{"statusCode":"300", "message":"'.__('Please check and after submit').'"}';
			}
			elseif(empty($this->request->data['id']))
			{
				$this->Session->setFlash(__('Please check roles and after submit'));
				echo '{"statusCode":"300", "message":"'.__('Please check roles and after submit').'"}';
			}else
			{
				$this->Role->id = $this->request->data['id'];
				$data = $this->Role->read();
				
				$this->loadModel('User');

				if($this->User->updateAll(array('role_id'=>$this->request->data['id']), array('User.id'=>$this->request->data['ids'])))
				{
					$this->Session->setFlash(__('Add user to role', array($data['Role']['name'])));
					$this->_setLogs();
					echo '{"statusCode":"200", "message":"'.__('Add user to role', array($data['Role']['name'])).'", "callbackType":"closeCurrent", "rid":"'.$this->request->data['id'].'"}';
				}
			}
			$this->autoRender = false;
		}
		
		/**
		*  获取没有角色的用户
		*/
		$this->loadModel('User');

		$conditions = array('User.role_id'=>0);
		!$this->User->isAdmin($this->userInfo['User']['id']) && $conditions['User.id !='] = '1';

		$this->paginate = $this->pageHandler($this->request->data, $conditions, 'User.id DESC');

		$this->User->linkRole();

		$users = $this->paginate('User');
		$this->set(compact('users'));
	}
	
	
/**
*  删除用户出角色
*
*/
	public function delUserToRoles($rid=null)
	{
		if(!isset($rid))
		{
			return $this->jsonToDWZ(array('message'=>__('Please check roles and after submit')),300);
		}else
		{
			if($rid)
			{
				if($this->request->data)
				{
					$this->Role->id = $rid;
					$data = $this->Role->read();
					
					$ids = explode(',', $this->request->data['ids']);
					if(in_array($this->userInfo['User']['id'], $ids))
					{
						echo '{"statusCode":"300","message":"'.__("Can't delete youself out role").'"}';
					}else{
						$this->loadModel('User');
						if($this->User->updateAll(array('role_id'=>0), array('User.id'=>$ids, 'User.is_founder <>' => 1)))
						{
							$this->Session->setFlash(__('Delete user role',array($data['Role']['name'])));
							$this->_setLogs();
							echo '{"statusCode":"200", "message":"'.__('Delete user role',array($data['Role']['name'])).'", "rid":"'.$rid.'"}';
						}
					}
				}
			}
		}
		$this->autoRender = false;
	}
	
	
/**
*  修改模板权限
*
*/
	public function editTemplatesPermissions($id = null)
	{
		if(!isset($id))
		{
			$this->Session->setFlash(__('Please check roles and after submit'));
			echo '{"statusCode":"300", "message":"'.__('Please check roles and after submit').'"}';
		}else{
			$this->Role->id = $id;
			if($this->request->data)
			{
				$this->request->data['Role']['template_accesses'] = @implode(',',$this->request->data['Role']['template_accesses']);
				if(@$this->request->data['Role']['default_template_id']){
					$data = $this->Role->read();
					if($this->Role->save($this->request->data))
					{
						$this->Session->setFlash(__('Edit roles template distribution',array($data['Role']['name'])));
						echo '{"statusCode":"200","message":"'.__('Edit roles template distribution',array($data['Role']['name'])).'","disable":true}';
					}else{
						$this->Session->setFlash(__('Edit roles template distribution fail',array($data['Role']['name'])));
						echo '{"statusCode":"200","message":"'.__('Edit roles template distribution fail',array($data['Role']['name'])).'"}';
					}
				}else{
					echo '{"statusCode":"300","message":"'.__('Please choose default template').'"}';
				}
			}else{
				echo '{"statusCode":"300","message":"'.__('No changes').'"}';
			}
		}
		$this->autoRender = false;
	}
	
	
/**
*  配置分类权限
*
*/
	public function categoriesPermissions($roles_id = null)
	{
		$treeController = 'roles';
		$treeAction = 'editPermissions';
		
		$this->loadModel('Category');
		$categories = $this->Category->find('threaded',array('order'=>'Category.id ASC'));
		$this->set(compact('categories', 'treeController', 'treeAction', 'roles_id'));
	}
	
/**
* 获取分类权限
*/
	public function getCategoryPermissions($str = null, $role_id = null)
	{
		$this->autoRender = false;
		if($str && $role_id)
		{
			$categories = $this->Permission->find('first',array('conditions'=>array('Permission.category_id'=>$str,'Permission.role_id'=>$role_id)));
			return $categories['Permission']['permissions'];
		}else
		{
			return false;
		}
	}
	
/**
* 编辑分类权限(新)
*/
	public function newEditPermissions($category_id = null, $role_id = null, $permissions_id = null)
	{
		$this->autoRender = false;
		if(empty($role_id) || empty($category_id) || empty($permissions_id))
		{
			echo '{"statusCode":"300","message":"'.__('Please select category and then save').'"}';
		}else
		{
			$backPermissions = $this->Permission->find('first', array('conditions'=>array('Permission.role_id'=>$role_id, 'Permission.category_id'=>$category_id)));
			$oldPermissions = explode(',',$backPermissions['Permission']['permissions']);
			if($backPermissions)
			{
				if(in_array($permissions_id, $oldPermissions))
				{
					unset($oldPermissions[array_search($permissions_id, $oldPermissions)]);
					$this->Permission->updateAll(array('Permission.permissions'=>'"'.implode(',',$oldPermissions).'"'), array('Permission.role_id'=>$role_id, 'Permission.category_id'=>$category_id));
					$Permissions = $this->Permission->find('first', array('conditions'=>array('Permission.role_id'=>$role_id, 'Permission.category_id'=>$category_id)));
					return $Permissions['Permission']['permissions'];
				}elseif(empty($backPermissions['Permission']['permissions']))
				{
					$this->Permission->updateAll(array('Permission.permissions'=>$permissions_id), array('Permission.role_id'=>$role_id, 'Permission.category_id'=>$category_id));
					$Permissions = $this->Permission->find('first', array('conditions'=>array('Permission.role_id'=>$role_id, 'Permission.category_id'=>$category_id)));
					return $Permissions['Permission']['permissions'];
				}else
				{
					array_push($oldPermissions, $permissions_id);
					$this->Permission->updateAll(array('Permission.permissions'=>'"'.implode(',',$oldPermissions).'"'), array('Permission.role_id'=>$role_id, 'Permission.category_id'=>$category_id));
					$Permissions = $this->Permission->find('first', array('conditions'=>array('Permission.role_id'=>$role_id, 'Permission.category_id'=>$category_id)));
					return $Permissions['Permission']['permissions'];
				}
			}else {
				$savePermiss['Permission'] = array('role_id'=>$role_id, 'category_id'=>$category_id, 'permissions'=>$permissions_id);
				$Permissions = $this->Permission->save($savePermiss);
				return $Permissions['Permission']['permissions'];
			}
		}
	}
	
/**
*  编辑分类权限(旧)
*
*/
	public function editPermissions()
	{
		$this->loadModel('Permission');
		@$id = $this->request->data['id'];
		@$cid = $this->request->data['cid'];
		if(empty($id) || empty($cid))
		{
			echo '{"statusCode":"300","message":"'.__('Please select category and then save').'"}';
		}else
		{
			$backPermissions = $this->Permission->find('first', array('conditions'=>array('Permission.role_id'=>$this->request->data['id'], 'Permission.category_id'=>$this->request->data['cid'])));
			if($backPermissions)
			{
				@$this->request->data['Permission']['permissions'] = '"'.implode(',',$this->request->data['Permission']['permissions']).'"';
				if($this->Permission->updateAll(array('Permission.permissions'=>$this->request->data['Permission']['permissions']), array('Permission.role_id'=>$this->request->data['id'], 'Permission.category_id'=>$this->request->data['cid'])))
				{
					$this->Session->setFlash(__('Edit category permissions successflly'));
					$this->_setLogs();
					echo '{"statusCode":"200","message":"'.__('Edit category permissions successflly').'"}';
				}else
				{
					$this->Session->setFlash(__('Edit category permissions unsuccessflly'));
					$this->_setLogs();
					echo '{"statusCode":"300","message":"'.__('Edit category permissions unsuccessflly').'"}';
				}
			}else
			{
				$this->request->data['Permission']['permissions'] = @implode(',',$this->request->data['Permission']['permissions']);
				$this->request->data['Permission']['role_id'] = $this->request->data['id'];
				$this->request->data['Permission']['category_id'] = $this->request->data['cid'];
				if($this->Permission->save($this->request->data))
				{
					$this->Session->setFlash(__('Edit category permissions successflly'));
					$this->_setLogs();
					echo '{"statusCode":"200","message":"'.__('Edit category permissions successflly').'"}';
				}else
				{
					$this->Session->setFlash(__('Edit category permissions unsuccessflly'));
					$this->_setLogs();
					echo '{"statusCode":"300","message":"'.__('Edit category permissions unsuccessflly').'"}';
				}
			}
			
		}
		$this->autoRender = false;
	}
	
	
/**
*  修改系统权限
*
*/
	public function editSystemPermissions($id = null)
	{
		if(!isset($id)){
			$this->Session->setFlash(__('Please check roles and after submit'));
			echo '{"statusCode":"300", "message":"'.__('Please check roles and after submit').'"}';
		}else{
			$this->Role->id = $id;
			if($this->request->data)
			{
				$data = $this->Role->read();
				@$this->request->data['Role']['operation_accesses'] = implode(',',$this->request->data['Role']['operation_accesses']);
				if($this->Role->save($this->request->data))
				{
					$this->Session->setFlash(__('Edit roles system permission', array($data['Role']['name'])));
					echo '{"statusCode":"200", "message":"'.__('Edit roles system permission', array($data['Role']['name'])).'","disable":true}';
				}else{
					$this->Session->setFlash(__('Edit roles system permission fail', array($data['Role']['name'])));
					echo '{"statusCode":"300", "message":"'.__('Edit roles system permission fail', array($data['Role']['name'])).'"}';
				}
				$this->_setLogs();
			}else{
				echo '{"statusCode":"300", "message":"'.__('No changes').'"}';
			}
		}
		$this->autoRender = false;
	}
	
/**
 * 获取分类配置
 * 
 */
	function _getCategoryPermissions()
	{
		$categoryPermissions = json_decode(CATEGORY_PERMISSIONS,true);
		$this->set(compact('categoryPermissions'));
	}
	
	
/**
*  获取系统功能配置
*
*/
	private function _getSystemPermissions(){
		$systemPermissions = json_decode(SYSTEM_PERMISSIONS,true);
		$this->set(compact('systemPermissions'));
	}
}
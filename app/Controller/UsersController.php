<?php
/**
 * User Controller
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

class UsersController extends AppController{

/**
 * 用户列表
 * @return void
 */
	public function index(){
		$conditions = array();

		//if($searchType = $this->request->data['searchType']) $conditions['User.role_id'] = $searchType;
		isset($this->request->data['searchType']) && $this->request->data['searchType'] && $conditions['User.role_id'] = $this->request->data['searchType'];
		if(isset($this->request->data['keyword']) && $this->request->data['keyword']) $conditions['User.email LIKE'] = '%'.$this->request->data['keyword'].'%';  //两种写法，不知道哪种效率要高些

		//add 20141117 超级管理员才在列表页显示
		!$this->User->isAdmin($this->userInfo['User']['id']) && $conditions['User.id !='] = '1';

		$this->paginate = $this->pageHandler($this->request->data, $conditions, 'User.id DESC');
	    $this->User->linkRole();
	    $users = $this->paginate('User');
	    $this->set(compact('users'));
	}
	
	/**
	 * 用户添加
	 */
	public function add(){
		
		if($this->request->data){

			if ($this->User->findByEmail($this->request->data['User']['email'])){
				return $this->jsonToDWZ(array(
						'message'=>__('User already exists', array($this->request->data['User']['email']))
					),300);
			} elseif ($this->User->save($this->request->data)) {
				return $this->jsonToDWZ(array(
						'message'		=>	__('Add successful', array($this->request->data['User']['email'])),
						'callbackType'	=>	'closeCurrent',
						'navTabId'		=>	'main'
					), 200, true);
			}else{
				return $this->jsonToDWZ(array('message'=>'操作失败'),300);
			}
		}

		$roles = $this->Role->find('list', array('order' => 'sort ASC'));
		$this->set(compact('roles'));
	}
	
	/**
	 * 用户修改
	 * 
	 * @param int $id
	 * @throws MethodNotAllowedException
	 */
	public function user_information_edit($id = null){
		if(!$id || !is_numeric($id)) return $this->jsonToDWZ(array('message'=>'非法访问'),300);

		$this->User->id = $id;

		if($this->request->data){
			$user = $this->User->read();

			$this->request->data['User'] = array_filter($this->request->data['User'],function($var){
				return $var;
			});

			$tmp = array_flip($user['User']);
			$tmp = array_filter($tmp, function($var){
				return in_array($var, array('email','account','role_id','password'));
			});
			
			$user['User'] = array_flip($tmp);

			if($user == $this->request->data){
				return $this->jsonToDWZ(array('message'=>__('Nothing edit user information')),300);
			}

			if(isset($this->request->data['User']['newpassword'])){
				if(!isset($this->request->data['User']['password']) || md5($this->request->data['User']['password']) != $user['User']['password']){
					return $this->jsonToDWZ(array('message'=>__('The old password wrong')),300);
				}
				$this->request->data['User']['password'] = $this->request->data['User']['newpassword'];
				if($this->request->data['User']['newpassword'] != @$this->request->data['User']['confirmpassword']){
					return $this->jsonToDWZ(array('message'=>__('The two entered passwords do not match, please re-enter')),300);
				}
			}

			$this->User->data = array();
			if($this->User->save($this->request->data)){
				return $this->jsonToDWZ(array(
						'message'=>__('The user information successfully modified'),
						'callbackType'	=>	'closeCurrent',
						'navTabId'		=>	'main'
					), 200, true);
			}else{
				return $this->jsonToDWZ(array('message'=>'系统错误'),300);
			}
		}
		
		$this->request->data = $this->User->read();
		$roles = $this->Role->find('list', array('order' => 'sort ASC'));

		$this->set(compact('roles','id'));
	}

/**
 * 用户删除
 * @param int $id
 */
	public function del($id = null) {
		$ids = isset($this->request->data['ids'])?explode(',', $this->request->data['ids']):Set::filter(array($id));

		if(!$ids) return $this->jsonToDWZ(array('message'=>__("The operation failed")), 300); 
		
		if(in_array($this->userInfo['User']['id'], $ids)) {//不能删除自己
			return $this->jsonToDWZ(array(
					'message'	=>	__("You can't delete yourself"),
					'navTabId'	=>	'main'
				), 300);
		} else {
			if($this->User->deleteAll(array('User.id'=>$ids))) {
				return $this->jsonToDWZ(array(
					'message'	=>	__("User deleted successfully"),
					'navTabId'	=>	'main',
					'rel'		=>	'main'
				), 200, true);
			} else {
				return $this->jsonToDWZ(array(
					'message'	=>	__("The operation failed")
				), 300);
			}
		}

		$this->autoRender = false;
	}
	
/**
 * 用户登录
 * @return void
 */
	public function login(){
		$this->layout = false;
		if($this->request->is('post') && $this->request->data){
			if ($this->Session->read('Users.authCode') != $this->request->data['User']['authCode']) {
				$this->Session->SetFlash(__('authCode is incorrect'));
			}else{
				$this->User->linkRole();
				$backData = $this->User->find('first', array(
						'conditions'	=>	array(
							'User.email'	=>	$this->request->data['User']['email'],
							'User.password'	=>	md5($this->request->data['User']['password']
							)
						),
						'fields'	=> 	array('User.id','User.email','User.account','Role.id','Role.name')
					)
				);

				if($backData){
					$this->Cookie->name = COOKIE_NAME;

					if(empty($backData['Role']['id'])) {
						$this->Session->SetFlash(__('Your account have not user role, can not login'));
						$this->redirect(array('action'=>'login'),true);
					} else {
						$userLoginInfo = Cache::read($backData['User']['id'],'_cake_user_');
						if($userLoginInfo['is_login'] != 1 || time() - $userLoginInfo['logintime'] > 1800){
							$backData['User']['loginTime'] = time();
							
							$userLoginInfo['is_login'] = 1;
							$userLoginInfo['logintime'] = $backData['User']['loginTime'];
							Cache::write($backData['User']['id'],$userLoginInfo,'_cake_user_');

							$this->Cookie->write('User',$backData,true,'1 month');

							$this->userInfo = $backData;
							
							$this->Session->SetFlash(__('Login successfully', array($backData['User']['email'])));
							$this->_setLogs();
							$this->redirect(array('controller' => 'console', 'action'=>'index'), true);
						}else{
							$this->Session->SetFlash('当前用户还在登录状态，无法在异地登录。');
						}
					}
				}else{
					$this->Session->SetFlash(__('E-mail or password is incorrect'));
				}
			}
		}
	}
	
	/**
	 * 用户登出
	 */
	public function logout() {
		$this->Session->setFlash(__('Logout successfully', array($this->userInfo['User']['email'])));
		$this->_setLogs();

		//$this->User->id = $this->userInfo['User']['id'];
		//$this->User->saveField('User.is_login', 0);
		Cache::write($this->userInfo['User']['id'],array('is_login'=>0),'_cake_user_');

		$this->Cookie->name = COOKIE_NAME;
		$this->Cookie->delete('User');
		$this->Session->delete('Message.flash');

		$this->redirect(array('controller'=>'users','action'=>'login'),true);
	}
	
	/**
	 * 生成验证码
	 * 
	 * @return file image
	 */
	public function captcha() {
		$this->autoRender = false;
		$this->Captcha = $this->Components->load('Captcha');
		$this->Captcha->create($this->Components);
	}
}
?>
<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	public $layout = false;
	public $systemCacheName = 'systemConfig'; 	//系统配置缓存文件名称
	
	public function beforeFilter(){
		/**
    	 * 读取并设置系统配置
    	 */
		$this->loadModel('Config');
		$systemConfigs = Cache::read($this->systemCacheName);
		if (!$systemConfigs) {
			$systemConfigs = $this->Config->getAll();
			Cache::write($this->systemCacheName, $systemConfigs);
		}

		$systemConfigs = json_decode($systemConfigs, true); 
		
		foreach($systemConfigs AS $systemConfig) {
			$constant = strtoupper($this->_caseSwitchToSpaces($systemConfig['type']));
			!defined($constant)?define($constant,$systemConfig['value']):false;
		}

		$formatCode = Cache::read('formatCode', '_cake_core_');
		if (!$formatCode) {
			$this->loadModel('FileFormat');
			$this->loadModel('VideoFormat');
			$this->loadModel('AudioFormat');
			$format = array(
				'file'  =>  $this->FileFormat->find('list',array('fields' => array('FileFormat.value', 'FileFormat.name'),'conditions'=>array('FileFormat.is_show'=>1))),
				'video' =>  $this->VideoFormat->find('list',array('fields' => array('VideoFormat.value', 'VideoFormat.name'),'conditions'=>array('VideoFormat.is_show'=>1))),
				'audio' =>  $this->AudioFormat->find('list',array('fields' => array('AudioFormat.value', 'AudioFormat.name'),'conditions'=>array('AudioFormat.is_show'=>1)))
				);
			Cache::write('formatCode', $format, '_cake_core_');
		}
		
		/**
		 * 初始化cookie数组
		 */
		$this->Cookie = $this->Components->load('Cookie');
		$this->Cookie->name = COOKIE_NAME;
		$this->userInfo = $this->Cookie->read('User');

		$this->set('userInfo',$this->userInfo);
		
		/**
		 * 操作权限判断
		 */
		$allowController = array('users','uploads','interfaces','configs','webservices');
		$allowAction = array('login','water','createUser','clearUser','video','captcha','flashCache','callback','handle','mpc_handle');
		if (in_array($this->params['controller'],$allowController) && in_array($this->params['action'],$allowAction)){

		} else {
			if(empty($this->userInfo['User']['id'])) {
				$this->redirect(array('controller' => 'users', 'action' => 'login'));
			}

			if(time() - $this->userInfo['User']['loginTime'] > 1800) {
				header("Content-type:text/html;charset=utf-8");
				echo '<script>';
				echo 'alert("操作超时，请重新登陆！");';
				echo 'location.href="'.FULL_BASE_URL.'/users/login";';
				echo '</script>';
				exit;
			}
			
			//$this->set('waterPermission',$this->_getWaterPermissions());
			//$this->set('splitPermission',$this->_getSplitPermissions());
			
			$this->loadModel('Category');
			$this->set('tree', $this->_getCategoriesTree());

			$this->loadModel('TranscodeCategory');
			$this->set('template_tree', $this->_getTemplateCategoriesTree());

			$this->loadModel('Role');
			$this->set('roles', $this->_getRoles());

			$this->loadModel('Permission');
			$this->set('rolesSystemPermissions', $this->_getRolesSystemPermissions());

			//$this->userInfo['User']['loginTime'] = time();

			//Cache::write($this->userInfo['User']['id'],array('is_login'=>1,'logintime'=>$this->userInfo['User']['loginTime']),'_cake_user_');

			$this->Cookie->write('User', $this->userInfo, true, '1 month');
		}
	}
	
/**
 * 记录日志公用方法
 * @param string $UserName 用户名
 */
public function _setLogs($UserName = null){
	$logsMessage = CakeSession::read('Message.flash');

	$this->RequestHandler = $this->Components->load('RequestHandler');
	$logsArray = array(
		'UserName'	=>	$UserName?$UserName:$this->userInfo['User']['email'],
		'IP'		=>	$this->RequestHandler->getClientIP(),
		'LogType'	=>	$this->params['controller'].':'.$this->params['action'],
		'LogMessage'	=>	$logsMessage['message'],
		'AddTime'	=>	date('Y-m-d H:i:s')
		);

	$this->loadModel('Log');
	$this->Log->save($logsArray);

	return $this->Session->delete('Message.flash');
}

/**
 * 系统配置常量转换
 * @e.m cookieName to COOKIE_NAME
 * @param string $string
 * @return string
 */
private function _caseSwitchToSpaces( $string ) { 
	$pattern = '/([A-Z])/'; 
	$replacement = '_${1}'; 
	return preg_replace( $pattern, $replacement, $string ); 
}

	//获取水印权限
	/*public function _getWaterPermissions()
	{
		$users = $this->User->find('first',array('conditions'=>array('User.customer_id'=>$this->cookieCustomerId)));
		if(in_array(4,explode(',',$users['Customer']['limits'])))
		{
			return 1;
		}else
		{
			return 0;
		}
	}*/
	
/*	//获取分片权限
	public function _getSplitPermissions()
	{
		$users = $this->User->find('first',array('conditions'=>array('User.customer_id'=>$this->cookieCustomerId)));
		if(in_array(5,explode(',',$users['Customer']['limits'])))
		{
			return 1;
		}else
		{
			return 0;
		}
	}*/
	
/**
 * 获取当前客户分类树
 * @return array
 */
protected function _getCategoriesTree(){
	return $this->Category->find('threaded',array('order'=>'Category.id ASC'));
}
/**
 * 获取当前客户模版分类树
 * @return array
 */
public function _getTemplateCategoriesTree(){
	return $this->TranscodeCategory->find('threaded',array('order'=>'TranscodeCategory.id ASC'));
}

/**
 * 获取当前客户下的所有角色
 * @return array
 */
private function _getRoles(){
	return $this->Role->find('list');
}

/**
 * 获取当前用户角色的系统权限
 * @return array
 */
private function _getRolesSystemPermissions(){
	return $this->Role->find('first',array('conditions'=>array('Role.id'=>$this->userInfo['Role']['id'])));
}

/**
 * 获取当前用户角色的分类权限
 * @return array
 */
private function _getRolesCategoryPermissions($id = null){
	return $this->Permission->find('first',array('conditions'=>array('Permission.category_id'=>$id, 'Permission.role_id'=>$this->userInfo['Role']['id'])));
}

	/**
     * 获取当前用户的水印权限
     * @return array
     */
    /*public function _getUserWaterPermissions($id = null){
    	return $this->User->find('first',array('conditions'=>array('User.id'=>$id, 'User.customer_id'=>$this->cookieCustomerId)));
    }*/
    
/**
 * 获取tga图片文件的宽，高
 * @return array
 */
protected function _imageCreateFromtga ( $filename, $return_array = 0 )
{
	$handle = fopen ( $filename, 'rb' );
	$data = fread ( $handle, filesize( $filename ) );
	fclose ( $handle );
	$pointer = 18;
	$x = 0;
	$y = 0;
	$w = base_convert ( bin2hex ( strrev ( substr ( $data, 12, 2 ) ) ), 16, 10 );
	$h = base_convert ( bin2hex ( strrev ( substr ( $data, 14, 2 ) ) ), 16, 10 );
	$img = imagecreatetruecolor( $w, $h );
	while ( $pointer < strlen ( $data ) )
	{
		imagesetpixel ( $img, $x, $y, base_convert ( bin2hex ( strrev ( substr ( $data, $pointer, 3 ) ) ), 16, 10 ) );
		$x++;
		if ($x == $w)
		{
			$y++;
			$x=0;
		}
		$pointer += 3;
	}
	if ( $return_array )
		return array ( $img, $w, $h );
	else
		return $img;
}

/**
 * 获取文件后缀
 * @return array
 */
protected function _getFileSuffix( $file_name )
{
	$extend = pathinfo($file_name);
	$extend = strtolower($extend["extension"]);
	return $extend;
}

/**
 * DWZ分页处理函数
 * @param  array  $data       提交数据
 * @param  array  $conditions 条件数组
 * @param  array  $order      排序数组
 * @param  array  $containble      取消多余关联行为
 * @return array 			  分页条件
 */
protected function pageHandler($data = array(), $conditions = array(), $order = array(), $containble = array()){

	$data['numPerPage'] = isset($data['numPerPage']) ? $data['numPerPage'] : PAGE_SIZE;
	$data['pageNum'] = isset($data['pageNum']) ? $data['pageNum'] : 1;
	$this->set('param', $data);

	$page_params = array('limit' => $data['numPerPage'], 'page' => $data['pageNum']);

	if($conditions){
		$page_params = array_merge($page_params, array('conditions'=>$conditions));
	}
	if($order){
		$page_params = array_merge($page_params, array('order'=>$order));
	}
	if($containble) {
		$page_params = array_merge($page_params, array('contain'=>$containble));
	} 
	return $page_params;
}


/**
 * 针对DWZ的json返回
 * @param  array   $params 		传递的参数
 * @param  string  $statusCode  状态码
 * @param  boolean $recode 		是否写入日志
 * @return void
 */

protected function jsonToDWZ($params = array(), $statusCode = '200', $recode = false){
	$this->autoRender = false;

	if($recode == true){
			$this->Session->setFlash($params['message']);//结果写入SESSION,以便于生成日志
			$this->_setLogs();
		}
		
		$params = array_merge($params,array('statusCode'=>$statusCode));

		echo new CakeResponse(array(
			'body' => json_encode($params),
			'type' => "application/json"
			)
		);
		
		return false;
	}
}


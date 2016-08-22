<?php
/**
 * Configs Controller
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

class ConfigsController extends AppController {

    public $isAdmin = false;

    public function beforeFilter(){
        parent::beforeFilter();
        $this->isAdmin = $this->Config->isAdmin($this->userInfo['User']['email']);
        $this->set('isAdmin', $this->isAdmin);
    }

/**
 * 配置列表-基本配置
 * @return void
 */
    public function index() {
        if($this->request->data && $this->request->is('post')){
            $params = array();
            foreach($this->request->data as $key => $param){
                $params[] = array(
                    'id' => $key,
                    'value' => $param['value']
                    );
            }

            if($this->Config->saveAll($params)){
                return $this->jsonToDWZ(array('message'=>'配置项修改成功'), 200, true);
            }
            return $this->jsonToDWZ(array('message'=>'操作失败'), 300);
        }
        
        $configs = $this->Config->find('all',array(
            'conditions'=>array('access' => 1,'is_valid'=>1),
            'order' => 'type asc'
            )
        );

        $this->set(compact('configs'));
    }

/**
 * 配置列表-接口/路径配置
 * @return void
 */
    public function more($type = null){
        if(!$type || !$this->isAdmin || $type == 1) return $this->jsonToDWZ(array('message'=>'错误请求'), 300);

        $configs = $this->Config->find('all',array(
            'conditions'=>array('access' => $type,'is_valid'=>1),
            'order'=>'type asc'
            )
        );

        $this->set(compact('configs'));
    }

/**
 * 超级管理员配置列表
 * @return void
 */
    public function superindex() {
        if(!$this->isAdmin) return $this->jsonToDWZ(array('message'=>'错误请求'), 300);
        $this->paginate = $this->pageHandler($this->request->data, array(), 'access asc,type asc');
        $configs = $this->paginate('Config');

        $isAdmin = $this->isAdmin;
        $this->set(compact('configs','isAdmin'));
    }

/**
 * 添加配置
 * @return void
 */
    public function add(){
        if(!$this->isAdmin) return $this->jsonToDWZ(array('message'=>'错误请求','callbackType'=>'closeCurrent'), 300);

        if($this->request->data && $this->request->is('post')){
        	if ($this->Config->save($this->request->data)){
                return $this->jsonToDWZ(array(
                        'message'       =>  '添加成功',
                        'callbackType'  =>  'closeCurrent',
                        'navTabId'      =>  'main'
                    ));
            }else{
                return $this->jsonToDWZ(array('message'=>'添加失败'), 300);
            }
        }

        $this->render('add_edit_form_advanced');
    }
    
/**
 * 编辑配置项
 * @param  int $id 配置项ID
 * @return void
 */
    public function edit($id = null) {
        if(!$id || !$this->isAdmin) return $this->jsonToDWZ(array('message'=>'错误请求','callbackType'=>'closeCurrent'), 300);

        $isAccess = $this->Config->isAccess($id,$this->userInfo['User']['email']);
        if(!$isAccess['access']){           //权限判断 只能编辑用户设置
            return $this->jsonToDWZ(array(
                    'message'       =>  $isAccess['msg'],
                    'callbackType'  =>  'closeCurrent'
                ), 300);
        }

        $this->Config->id = $id;
        if($this->request->data){
            if($this->Config->save($this->request->data)){
                return $this->jsonToDWZ(array(
                    'message'       =>  '编辑成功',
                    'callbackType'  =>  'closeCurrent',
                    'navTabId'      =>  'main'
                ));
            } else {
                return $this->jsonToDWZ(array('message'=>'编辑失败'), 300);
            }
        }

        $this->request->data = $this->Config->read();
        $this->render('add_edit_form_advanced');
    }

/**
 * 删除配置项
 * @param  int $id 配置项ID
 * @return void
 */
    public function del($id = null) {
    	if($id) $this->request->data['ids'] = $id;

    	if(!$this->request->data['ids'] || !$this->isAdmin) return $this->jsonToDWZ(array('message'=>'错误请求','callbackType'=>'closeCurrent'), 300);
            
        $ids = explode(',', $this->request->data['ids']);

        if($this->Config->deleteAll(array('id' => $ids))){
            return $this->jsonToDWZ(array(
                    'message'       =>  '删除成功',
                    'navTabId'      =>  'main'
                ));
        }else {
            return $this->jsonToDWZ(array('message'=>'删除失败'), 300);
        }
    }

/**
 * 更新系统缓存
 * @return void
 */
    public function flashCache(){
        try {
            $data = $this->Config->getAll();
            Cache::write($this->systemCacheName, $data);

            $this->loadModel('FileFormat');
            $this->loadModel('VideoFormat');
            $this->loadModel('AudioFormat');
            $format = array(
                'file'  =>  $this->FileFormat->find('list',array('fields' => array('FileFormat.value', 'FileFormat.name'),'conditions'=>array('FileFormat.is_show'=>1))),
                'video' =>  $this->VideoFormat->find('list',array('fields' => array('VideoFormat.value', 'VideoFormat.name'),'conditions'=>array('VideoFormat.is_show'=>1))),
                'audio' =>  $this->AudioFormat->find('list',array('fields' => array('AudioFormat.value', 'AudioFormat.name'),'conditions'=>array('AudioFormat.is_show'=>1)))
                );
            Cache::write('formatCode', $format, '_cake_core_');

            return $this->jsonToDWZ(array('message'=>'更新系统缓存成功'));

        } catch (Exception $e) {
            return $this->jsonToDWZ(array(
                'message'=>'更新系统缓存错误 ' . $e->getMessage(),
            ),300,true);
        }
    }
}
?>


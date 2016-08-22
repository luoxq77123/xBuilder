<?php
/**
 * AutoScans Controller
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

// App::uses('Xml', 'Utility');

class MetadatasController extends AppController {
    public function beforeFilter(){
        parent::beforeFilter();
        $this->set(array(
            'type'=>$this->Metadata->type,
            'datasourceType'=>$this->Metadata->datasourceType,
            ));
    }

/**
 * 配置列表
 * @return void
 */
    public function index() {

        $this->paginate = $this->pageHandler($this->request->data, array(), 'Metadata.order ASC, Metadata.id DESC');
        $configs = $this->paginate('Metadata');
        $this->set(compact('configs'));

    }

/**
 * 添加、编辑配置
 * @return void
 */
    public function add_edit($id = null){
        if($this->request->data && $this->request->is('post')){
          $msg = '添加';
          if( @$this->request->data['Metadata']['id'] ) {
            $msg = '编辑';
          }
          $this->request->data['Metadata']['uid'] = $this->userInfo['User']['id'];
            if ( $this->Metadata->save($this->request->data['Metadata']) ) {
                return $this->jsonToDWZ(array(
                        'message'       =>  $msg . '成功',
                        'callbackType'  =>  'closeCurrent',
                        'navTabId'      =>  'main'
                    ));
            } else {
                return $this->jsonToDWZ(array('message'=>$msg . '失败'), 300);
            }
        }

        if( $id ) {
            //edit
            $config = $this->Metadata->find('first',array('conditions'=>array('Metadata.id'=>$id)));
        }
        $this->set(compact('config', 'id'));
        $this->render('add_edit_form_advanced');
    }
    

/**
 * 删除配置项
 * @param  int $id 配置项ID
 * @return void
 */
    public function del($id = null) {
    	if($id) $this->request->data['ids'] = $id;

    	if(!$this->request->data['ids']) return $this->jsonToDWZ(array('message'=>'错误请求','callbackType'=>'closeCurrent'), 300);
            
        $ids = explode(',', $this->request->data['ids']);
        if($this->Metadata->deleteAll(array('Metadata.id' => $ids))){
            return $this->jsonToDWZ(array(
                    'message'       =>  '删除成功',
                    'navTabId'      =>  'main'
                ));
        }else {
            return $this->jsonToDWZ(array('message'=>'删除失败'), 300);
        }
    }
/**
 * 元数据预览
 * @return html
 */
    public function pre() {
        $metaData = $this->Metadata->find('all',array('order'=>'Metadata.order ASC, Metadata.id DESC', 'contain'=>''));
        $this->set(compact('metaData'));
    }
}
?>

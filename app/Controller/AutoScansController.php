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

App::uses('Xml', 'Utility');
App::uses('HttpSocket','Network/Http');

class AutoScansController extends AppController {

    public function beforeFilter(){
        parent::beforeFilter();
    }

/**
 * 配置列表
 * @return void
 */
    public function index() {

        $this->paginate = $this->pageHandler($this->request->data, array(), 'AutoScan.addtime DESC');
        $configs = $this->paginate('AutoScan');
        $this->set(compact('configs'));

    }

/**
 * 添加、编辑配置
 * @return void
 */
    public function add_edit($id = null){
        if($this->request->data && $this->request->is('post')){
          $msg = @$this->request->data['AutoScan']['id']?'编辑':'添加';
          $this->request->data['AutoScan']['uid'] = $this->userInfo['User']['id'];
          $this->request->data['AutoScan']['user_name'] = $this->userInfo['User']['account'];
          $this->request->data['AutoScan']['platforms'] = serialize($this->request->data['AutoScan']['platforms']);

          if(!$this->request->data['AutoScan']['tid']) return $this->jsonToDWZ(array('message'=>'模板不能为空'),300);
            if ( $this->AutoScan->save($this->request->data) ) {
                return $this->jsonToDWZ(array(
                        'message'       =>  $msg . '成功',
                        'callbackType'  =>  'closeCurrent',
                        'navTabId'      =>  'main'
                    ));
            }else{
                return $this->jsonToDWZ(array('message'=>$msg . '失败'), 300);
            }
        }

        //查找模板组
        $this->loadModel('Role');
        $role = $this->Role->findById($this->userInfo['Role']['id']);
        $allow = explode(',',$role['Role']['template_accesses']);
        $this->loadModel('TranscodeGroup');
        $conditions= array('conditions'=>array('TranscodeGroup.id'=>$allow), 'order'=>'TranscodeGroup.id desc','fields'=>'TranscodeGroup.name,TranscodeGroup.id','contain'=>'');
        $transcodeGroups = $this->TranscodeGroup->find('all', $conditions);
        $transcodeGroupsOptions = $this->formatOptions($transcodeGroups,'id','name','TranscodeGroup');

        if($id) {
            //edit
            $config = $this->AutoScan->find('first',array('conditions'=>array('AutoScan.id'=>$id),'contain'=>array()));
            $this->getTrancecodeFromGroup( $config['AutoScan']['tid'] );
            $config['AutoScan']['platforms'] = unserialize($config['AutoScan']['platforms']);
        }
        $this->set(compact('transcodeGroupsOptions','currentScan','config', 'id'));
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
        if($this->AutoScan->deleteAll(array('AutoScan.id' => $ids))){
            return $this->jsonToDWZ(array(
                    'message'       =>  '删除成功',
                    'navTabId'      =>  'main'
                ));
        }else {
            return $this->jsonToDWZ(array('message'=>'删除失败'), 300);
        }
    }
/**
 * 根据模板组ID获取对应子模板并渲染
 * @param  string $id 模板组ID
 * @return [type]     [description]
 */
    public function getTrancecodeDetail($id = '') {
        if ( $id ) {
            $this->getTrancecodeFromGroup( $id );
            $this->render('/Elements/TranscodeGroup/view');
        } else {
            $this->autoRender = false;
            return false;
        }
    }
/**
 * 根据模板组ID获取对应子模板数据
 * @param  string $id 模板组ID
 * @return [type]     [description]
 */
    public function getTrancecodeFromGroup ( $id = '' ) {
        $this->loadModel('TranscodeGroup');
        $transcodeGroups = $this->TranscodeGroup->getTranscodeParams($this->userInfo, $id);
        $formatCode = Cache::read('formatCode', '_cake_core_');
        $this->set(compact('transcodeGroups','formatCode'));
    }
/**
 * 格式化选项值for select option
 * @param  array  $array 格式化之前的数组
 * @param  string $key   转换过后的key
 * @param  string $value 转化过后的value
 * @param  string $model 获取的模型名称
 * @return array        转换过后的数组
 */
    public function formatOptions ( $array = array(), $key = '', $value = '', $model='' ) {
        if($array){
            $newArray = array();
            foreach( $array as $v ) {
                $newArray[$v[$model][$key]] = $v[$model][$value];
            }
            return $newArray;
        }
        return $array;
    }

/**
 * 发送服务器配置和扫描的目录
 */
    private function SetServerConfig(){
        $req_data = array(
            'CMEDIAHTTP'=>array(
                'Header'=>array(
                    'Version'=>'',
                    'Command'=>__FUNCTION__
                    ),
                'DataScope'=>array(
                    'Data'=>array(
                        '0'=>array(
                            'ServerUrl' => FULL_BASE_URL . '/interfaces/mpc_handle'
                            )
                        )
                    )
                )
            );
        $paths = $this->AutoScan->find('all',array('fields'=>array('path','suffix')));

        foreach($paths as $path){
            $data[] = array(
                    'SearchPath' => STORAGE_DISK . $path['AutoScan']['path'],
                    'FileExtensions' => str_replace('|', ';', $path['AutoScan']['suffix'])
                );
        }
        $req_data['CMEDIAHTTP']['DataScope']['Data'] = array_merge($req_data['CMEDIAHTTP']['DataScope']['Data'],$data);

        $req_xml = Xml::fromArray($req_data)->asXML();
        $HttpSocket = new HttpSocket();

        try {
            $reback = $HttpSocket->post(ANALYSE_MEDIA_FILE_URL,array('parameters'=>$req_xml));
        }catch(Exception $e){
            $this->log( 'Http Error : '.$e->getMessage() );
        }
    }

}
?>

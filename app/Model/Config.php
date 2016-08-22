<?php
/**
 * Config model for Cake.
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
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class Config extends AppModel {
    public $cacheName = 'systemConfig';
    
    public $validate = array(
    	'name' => array(
    		'rule' => 'isUnique',
    		'allowEmpty' => false,
            'message' => '配置名必填，并不能重复!'
        ),
    	'value' => array(
    		'rule' => 'notEmpty',
            'message' => '配置值不能为空!'
        )
    );

/**
 * 获取单个配置项的值
 * @param  string $key      配置项名
 * @return mixed  $results  配置项值
 */
    public function getValue($key){
        $results = $this->find('first',array('conditions' => array('type' =>$key)));  
        return $results;
    }

/**
 * 返回全部配置项
 * @return array $datas
 */
    public function getAll(){
        $results = $this->find('all',array('conditions' => array('is_valid' => 1)));
        foreach($results as $result){
            $data[$result['Config']['id']]['type'] = $result['Config']['type'];
            $data[$result['Config']['id']]['value'] = $result['Config']['value'];
        }
        $datas = json_encode($data);
        return $datas;
    }

/**
 * 保存后更新缓存
 * @param  boolean $created 是否新建记录
 * @return void
 */
    public function afterSave($created){
        $data = $this->getAll();
        Cache::write($this->cacheName, $data);
    }

/**
 * 删除成功后更新缓存
 * @return void
 */
    public function afterDelete(){
        $data = $this->getAll();
        Cache::write($this->cacheName, $data);
    }

/**
 * 判断当前用户是否有操作该配置权限
 * @param  array  $ids 配置项ID
 * @return array       判断结果数组
 */
    public function isAccess($ids,$account){
        $isAdmin = $this->isAdmin($account);
        // todo 如果是超级管理员【有配置系统参数的权限】就不需要下面的判断
        if( !$isAdmin ) {
            $data = $this->find('all',array('fields'=>array('access','name'),'conditions'=>array('id'=>$ids)));
            //如果IDS组中包含系统配置，那么这次所有配置都不能修改
            foreach ($data as $value) {
               if($value['Config']['access'] == 2){
                return array('access'=>false, 'msg'=>'配置项:[' . $value['Config']['name']  . ']为系统配置,您没有权限操作');
                }
            }
        }
        return array('access'=>true);
    }

/**
 * 判断是否具有操作系统配置参数的权限
 * @return boolean [description]
 */
    public function isAdmin($account = null) {
        // todo 如果是超级管理员【有配置系统参数的权限】就不需要下面的判断
        if(!$account) return false;

        return in_array($account, array('sobey@sobey.com'));
    }
}
?>
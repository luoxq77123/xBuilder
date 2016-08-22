<?php
class Role extends AppModel{
	public $useTable = 'roles';

	public $validate = array(
		'name' => array(
			'rule' => 'notEmpty',
			'message' => '角色名不能为空，请填写!'
		),
		'sort' => array(
			'rule' => 'notEmpty',
			'message' => '排序值不能为空!'
		)
	);
	
	public $belongsTo = array(
		'TranscodeGroup'=>array(
			'className'=>'TranscodeGroup',
			'foreignKey'=>'default_template_id',
			'fields'=>array('type')
		)
	);

	public $hasAndBelongsToMany = array(
		'Category' => array(
			'className' => 'Category',
			'joinTable' => 'permissions',
			'foreignKey' => 'role_id',
			'associationForeignKey' => 'category_id',
			'unique' => true,
			'fields' => array('id','name')
		)
	);
	
	/**
	 * 获取当前用户角色的分类权限
	 * @return array
	 */
	public function _getRolesCategoryPermissions($id = null){
	    return $this->Permission->find('first',array('conditions'=>array('Permission.category_id'=>$id, 'Permission.role_id'=>$this->userInfo['Role']['id'])));
	}
	 
}
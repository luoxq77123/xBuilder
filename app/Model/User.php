<?php
class User extends AppModel
{
	public $useTable = 'user';
	
	/*public $belongsTo = array(
        'Role' => array(
            'className'    => 'Role',
            'foreignKey'   => 'role_id',
			'fields'	   => array('id','name','default_template_id','template_accesses','operation_accesses')
        )
    );*/
	
	public $validate = array(
		'account' => array(
			'rule' => 'notEmpty',
			'message' => '用户名不能为空!'
		),	
		'password' => array(
			'rule' => 'notEmpty',
			'message' => '密码不能为空!'
		),
		'email' => array(
			'rule' => 'email',
			'message' => '电子邮件格式不正确!'
		),
		'newpassword' => array(
		'rule' => 'notEmpty',
		'message' => '新密码不能为空!'
		),
		
		'confirmpassword' => array(
		'rule' => 'notEmpty',
		'message' => '再次输入的新密码也不能为空!'
		)
	);

	public function linkRole(){
		$this->belongsTo = array(
			'Role' => array(
	            'className'    => 'Role',
	            'foreignKey'   => 'role_id',
				'fields'	   => array('id','name','default_template_id','template_accesses','operation_accesses')
	        )
        );
	}

	public function beforeSave($options = array()){
		if(isset($this->data['User']['password'])){
			$this->data['User']['password'] = md5($this->data['User']['password']);
		}
	}
	/**
	 * 判断当前用户是否为超级管理员
	 * @param  str  $uid 当前用户ID
	 * @return boolean 
	 */
	public function isAdmin( $uid = null ) {
		//现在暂时判断条件：用户user.id为1就是超级管理员
		return '1' === $uid;
	}
}
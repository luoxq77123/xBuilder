<?php 
class Metadata extends AppModel{
	public $name = 'Metadata';
	public $actsAs = array('Containable');
	public function beforeSave($options = array()){
		$this->data['Metadata']['update_time'] = time();
		//add的时候才更新createtime字段
		!$this->data[$this->alias]['id'] ? ( $this->data[$this->alias]['create_time'] = time() ) : '';
		isset($this->data[$this->alias]['is_auto_fill']) ? : ( $this->data[$this->alias]['is_auto_fill'] = 0 );
	}

	/**
 	* 链接转码组分类
 	* @return void
 	*/
 	public $belongsTo = array(
 		'User'=>array(
 			'className'=>'User',
 			'foreignKey'=>'uid',
 			'fields'=>array('email')
 		)
 	);
 	/*
 	空间属性
 	 */
 	public $type = array(
 		0=>'文本',
 		1=>'按钮',
 		2=>'时间',
 		// 3=>'日期',
 		4=>'下拉框',
 		5=>'单选框',
 		6=>'复选框',
 		7=>'文本域'
 	);
  	/*
 	空间属性
 	 */
 	public $datasourceType = array(
 		0=>'自定义',
 		1=>'sql',
 	);
 }
 ?>
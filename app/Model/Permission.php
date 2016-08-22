<?php
class Permission extends AppModel{

	public function getRolesCategoryPermissions($id = null, $role_id = null){
    	return $this->find('first',array(
            'conditions'=>array(
                'Permission.category_id'=>$id,
                'Permission.role_id'=>$role_id
            )
        ));
    }
}
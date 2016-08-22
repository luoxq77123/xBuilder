<?php
class RecycleController extends AppController
{
	public $uses = array('Content','Video');
	/**
	 * 回收站视频内容列表
	 * 
	 * @return 显示回收站主界面
	 */
	public function index(){
		$conditions['isdelete'] = 1;
		$conditions = array_merge($this->get_select_conditions(), $conditions);
		$this->paginate = $this->pageHandler($this->request->data, $conditions, 'Content.created DESC');

		$this->loadModel('Content');
		$data = $this->paginate('Content');
        $this->set(compact('data'));
    }
	/**
 	* 处理搜索参数
 	* @return array 搜索条件数组
 	*/
	private function get_select_conditions(){
		$conditions = array();

		isset($this->request->data['keyword']) && isset($this->request->data['searchType']) && $this->request->data['searchType'] && $conditions['Content.'.$this->request->data['searchType'].' LIKE'] = '%'.$this->request->data['keyword'].'%';
		return $conditions;
	}
	/**
	 * 还原视频内容
	 * 
	 * @param int $id
	 * @return 返回到回收站主界面
	 */
	public function reduction(){
		if($this->request->data)
		{
			$ids = $this->request->data['ids'];
			if ($this->Content->updateAll(array('Content.isdelete'=>0),array('Content.id'=>$ids)))
			{
				$this->Session->setFlash(__('Successfully reduction the video to the category'));
				$this->_setLogs();
				echo '{"statusCode":"200","message":"'.__('Successfully reduction the video to the category').'","callbackType":"closeCurrent","navTabId":"main"}';
			}
		}else
		{
			$this->Session->setFlash(__('The operation failed'));
			echo '{"statusCode":"300","message":"'.__('The operation failed').'"}';
		}
		$this->autoRender = false;
	}
	
	/**
	 * 预览视频内容
	 * 
	 * @param int $id
	 * @return 显示预览页面
	 */
	public function preview($id = null){
		
	}
	
	/**
	 * 查看视频内容详细信息
	 * 
	 * @param int $id
	 * @return 显示视频内容详细信息页面
	 */
	public function detail($id = null){
		
	}
	
	/**
	 * 删除视频内容，彻底删除
	 * 这里需要做额外工作，是否删除上传、转码文件？
	 * 
	 * @param int $id
	 * @return 返回到回收站主界面
	 */
	public function delete(){
		$this->autoRender = false;
		if($this->request->data)
		{
			
			// add 20141020 删除物理文件和图片
			$successTaskID = $this->Content->deletePhysics($this->request->data['ids']);
			//删除成功
			if($successTaskID)
			{
				//删除 物理文件删除成功后数据库的数据
				$this->Content->hasMany['Video']['dependent'] = $this->Content->hasMany['Image']['dependent'] = true;
				$result = $this->Content->deleteAll(array('Content.id'=>$successTaskID));
				$this->jsonToDWZ(array("callbackType"=>"closeCurrent","navTabId"=>"main","message"=>__('Successfully del the video')), '200',true);
			}else
			{
				$this->jsonToDWZ(array("callbackType"=>"closeCurrent","navTabId"=>"main","message"=>__('The operation failed')), '300');
			}
			
		}else
		{
			$this->jsonToDWZ(array("message"=>__('The operation failed')), '300');
		}
	}
}
<?php 
class VideosController extends AppController{
	public $name = 'Videos';
	public $uses = array('Video', 'TranscodeGroup', 'Category', 'Content');
	public $helpers = array('Html', 'Form', 'Js', 'Dwz');
	public $layout = 'ajax';
	
	/**
	 * 视频上传
	 * 
	 */
	public function upload() {
		
		//获取转码模板相关信息
		$transcodegroup = $this->TranscodeGroup->find('all');
		$transcodegroup = Set::combine($transcodegroup,'{n}.TranscodeGroup.id','{n}.TranscodeGroup.name');
		$this->set(compact('transcodegroup'));
		
		//获取视频分类相关信息
		$trees = $this->Category->find('threaded', array('order' => array('sort ASC')));
		$this->set(compact('trees'));
	}
	
	
	/**
	 * 直接下载
	 * @param int $id
	 */
	public function download( $id = null ) {
		if($id)
		{
			$files = $this->Video->find('first',array('conditions'=>array('Video.id'=>$id, 'OriginalFile'=>0)));
			$file = $files['Video']['FileUrl'];
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Accept-Ranges: bytes'); 
			header('Content-Disposition: attachment; filename='.basename(VIDEO_PLAY_URL_PRE.$file));
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Length: ' . filesize($file));
			ob_clean();
			flush();
			readfile($file);
			exit;
		}else 
		{
			$this->Session->setFlash(__('The operation failed'));
			echo '{"statusCode":"300","message":"'.__('The operation failed').'"}';
		}
		$this->autoRender = false;
	}
}
?>
<?php 

App::uses('File','Utility');
/**
* 图片文件显示处理类
*/
class ImagesController extends AppController
{
	
	public function index($filename = null)
	{
		$this->autoRender = false;
		if(!$filename) return false;

		$file = new File(STORAGE_IP_ADDRESS . IMAGE_PATH_PREFIX . DS . $filename);

		if(!$image = @$file->read()){
			$default = new File(WWW_ROOT . 'themes/default/images/trancoding.png');
			return $default->read();
		}

		return $image;

	}
}

?>
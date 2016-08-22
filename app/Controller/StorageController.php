<?php
/**
* 存储控制器
*/
App::uses('Folder','Utility');
App::uses('File','Utility');

class StorageController extends AppController{
	
/**
 * 获取存储水印目录
 * @return void
 */
	public function water(){
		$dir = new Folder(STORAGE_IP_ADDRESS . WATER_UPLOAD_PREFIX);
		$result = $dir->read();

		foreach ($result[0] as $key => $value) {
			$folders[$key] = $this->characet($value);
		}

		$targetCheckFileExt = explode('|', UPLOAD_IMAGE);
		foreach ($result[1] as $key => $value) {
			$file = new File(STORAGE_IP_ADDRESS . WATER_UPLOAD_PREFIX . DS . $value);
			if(in_array('.'.strtolower($file->ext()), $targetCheckFileExt)){
				$files[$key] = $this->characet($value);
			}
		}

		$this->set(compact('folders','files'));
	}

/**
 * 扫描水印下级文件夹
 * @return void
 */
	public function scan_water(){
		$path = urldecode($this->request->query['path'])?:STORAGE_IP_ADDRESS . WATER_UPLOAD_PREFIX;

		$fileType = Cache::read('storage_characet');

		if($fileType != 'UTF-8'){
			$path = mb_convert_encoding($path , $fileType, 'UTF-8');
		}

		$dir = new Folder($path);
		$result = $dir->read();

		foreach ($result[0] as $key => $value) {
			$folders[$key] = $this->characet($value);
		}
		$targetCheckFileExt = explode('|', UPLOAD_IMAGE);
		foreach ($result[1] as $key => $value) {
			$file = new File($path . DS . $value);
			if(in_array('.'.strtolower($file->ext()), $targetCheckFileExt)){
				$files[$key] = $this->characet($value);
			}
		}

		$path = $this->characet($path);
		$now_path = addslashes($path);
		$upPath = substr($path, 0, strrpos($path, DS));
		
		$this->set(compact('path','upPath','now_path','folders','files'));
	}

/**
 * 检测并转换中文字符集
 * @param  string $data 需要转换的中文字符
 * @return string       转换为UTF-8的字符
 */
	private function characet($data){
	  if( !empty($data) ){
	    $fileType = mb_detect_encoding($data , array('UTF-8','GBK','LATIN1','BIG5')) ;
	    if( $fileType != 'UTF-8'){
	    	Cache::write('storage_characet',$fileType);
	    	$data = mb_convert_encoding($data ,'utf-8' , $fileType);
	    }
	  }
	  return $data;
	}
}
?>
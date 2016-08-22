<?php
App::uses('Validation', 'Utility');
App::uses('Folder','Utility');
App::uses('File','Utility');

class UploadComponent extends Component{
	var $name = 'Upload';

	// Upload an image
	public function upload($data = null, $path = null) {
        if(!$data || !$path) return false;
        $date = date('Ymd');
        $uploadDir = new Folder($path . DS . $date, true, 0777);

        if(!$uploadDir) throw new InternalErrorException();

        $ext = $this->_validFile($data);

        if(!$ext) return false;
        
        $fileName = $this->_random(10) . '.' . $ext;
        $savePath = $uploadDir->pwd(). DS . $fileName;

        if(@move_uploaded_file($data['tmp_name'], $savePath)){
        	$savePath = str_replace('\\','/',$savePath);
	        return array('filePath'=>$savePath,'fileName'=>$fileName,'fileDir'=>$date);
        }else{
        	return false;
        }
    }
	
	private function _random($length, $numeric = 0) {
	    $numeric=1;
	    PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
	    if($numeric) {
	        $hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
	    } else {
	        $hash = '';
	        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
	        $max = strlen($chars) - 1;
	        for($i = 0; $i < $length; $i++) {
	            $hash .= $chars[mt_rand(0, $max)];
	        }
	    }
	    return date('Y').$hash;
	} 
	
    private function _validFile($check, $settings = array()) {
		$str = strtolower(UPLOAD_VIDEO_FILE_FORMAT."|".UPLOAD_AUDIO_FILE_FORMAT."|".UPLOAD_IMAGE);
		$arr1 = explode('.',$str);
		$arr2 = join($arr1);
		$arr3 = explode('|',$arr2);
        $_default = array(
            'required' => false,
            'extensions' => $arr3
        );

        $_settings = array_merge(
            $_default,is_array($settings)?$settings:array()            
        );

        // Remove first level of Array
        //$_check = array_shift($check);
        $_check = $check;

        if($_settings['required'] == false && $_check['size'] == 0) {
            return false;
        }

        // No file uploaded.
        if($_settings['required'] && $_check['size'] == 0) {
            return false;
        }

        // Check for Basic PHP file errors.
        if($_check['error'] !== 0) {
            return false;
        }

        // Use PHPs own file validation method.
        if(is_uploaded_file($_check['tmp_name']) == false) {
            return false;
        }

        // Valid extension
        if(Validation::extension($_check,$_settings['extensions'])){
        	//$extnsions = strtolower(array_pop(explode('.', $_check['name'])));
        	$filename = explode('.', $_check['name']);
        	$extnsions = strtolower(array_pop($filename));
        	return $extnsions;
        }else{
        	return false;
        }
        
    }
}
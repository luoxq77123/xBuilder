<?php
App::uses('File', 'Utility');

class DownloadController extends AppController{

	public function web(){
		$filePath = $this->request->query['filePath'];
		if(!$filePath) return false;
		
		header("Content-type:text/html;charset=utf-8");
		$filePath=iconv("UTF-8","GBK",$filePath);
		
		$filename = preg_replace('/^.+[\\\\\\/]/', '', $filePath);
		
		$fp=fopen($filePath,"r"); 
		$file_size=filesize($filePath); 

		//下载文件需要用到的头 
		Header("Content-type: application/octet-stream"); 
		Header("Accept-Ranges: bytes"); 
		Header("Accept-Length:".$file_size); 
		Header("Content-Disposition: attachment; filename=".$filename); 

		$buffer=1024; 
		$file_count=0; 
		//向浏览器返回数据 
		while(!feof($fp) && $file_count<$file_size){ 
			$file_con=fread($fp,$buffer); 
			$file_count+=$buffer; 
			echo $file_con; 
		} 
		fclose($fp);

		$this->autoRender = false;
	}
}

?>
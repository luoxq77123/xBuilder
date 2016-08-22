<?php
App::uses('Folder','Utility');
App::uses('File','Utility');
App::uses('String', 'Utility');
App::uses('HttpSocket','Network/Http');
class TestController extends AppController
{
	public $layout = 'default';
	/*public $components = array(
		'MpcClient' => array(
				'mpcUrl' => 'http://172.16.146.28:8088',
				//'wsdl' => 'http://172.16.146.28:8088/mpcinterface.wsdl'
			)
		);*/

	public function beforeFilter(){
	}

	public function afterFilter(){
	}

	public function mpc_request(){

	}
	public function index(){
		$HttpSocket = new HttpSocket();
		$result = $HttpSocket->get('http://172.16.146.21:9395/','Request=GetMediaInfo(F:\\sobey资料\\ebu.mp4)');
		pr($result->body);exit;
		//system("net use X: \\\\Reddrake\\Chenmeng130/user:eagleftf123@gmail.com /persistent:no>nul 2>&1");
		//$a = 'F:'.DIRECTORY_SEPARATOR.'xBuilder'.DIRECTORY_SEPARATOR.'source';
		//$a = realpath('../webroot/files');
		//var_dump($a);
		//$a = '\\\Reddrake\\MPC存储盘'.DIRECTORY_SEPARATOR.'xBuilder'.DIRECTORY_SEPARATOR.'source';
		//$a = realpath('../webroot/files').DIRECTORY_SEPARATOR.'mpc.lnk'.DIRECTORY_SEPARATOR.'xBuilder'.DIRECTORY_SEPARATOR.'source';
		//$a = 'D:\wwwroot\product\cmpc\app\webroot\files\xxx';
		
		//var_dump(is_dir("F:/xBuilder"));
//exit;

		//$dir = new Folder( $a, true);
		//echo $dir->pwd();
		//$file = new File($a . '/abc.txt', true, 0644);
		//pr($file);
		//$this->Mpc = $this->Components->load('Mpc');
		//var_dump($this->MpcClient->QueryGuage('53D236BD-A31C-4DFB-AF4A-14C46C7875F8'));
		//var_dump($this->MpcClient->GetProjectList(16));
		/*var_dump($this->MpcClient->AddTask(array(
												array('name'=>'D:\1.mp4')
											), 0
										));*/
		
	}
	/*public $uses = array();
	public $layout = false;
	public function test($id)
	{
		$this->log($id,'x');
	}
	public function xml()
	{
		
		App::import('Vendor', 'nusoap');
		$NAMESPACE = 'http://localhost/front/Test/xml?wsdl';
		$server = @new soap_server;
		$server->configureWSDL('CmpcService', $NAMESPACE, 'http://localhost/front/Test/xml');
		$server->register('getXML',array('params'=>'xsd:string'), array('return'=>'xsd:string'), $NAMESPACE);
		
		$server->soap_defencoding = 'UTF-8';
		$server->decode_utf8 = false;
		$server->xml_encoding = 'UTF-8';
		
		$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
		$server->service($HTTP_RAW_POST_DATA);
		$this->autoRender = false;
	}

	
	
	
	public function upload(){
		$a = '{"1":"\u89c6\u9891\u7ba1\u7406","2":"\u56de\u6536\u7ad9","3":"\u6a21\u677f\u7ba1\u7406","4":"\u7528\u6237\u7ba1\u7406"}';
		$b = json_decode($a,true);
		$b[] = '系统日志';
		pr($b);
		echo json_encode($b);
		exit;
	}
	public function mkdir()
	{
		mkdir("x:\\info", 777);
		exit;	
	}
	public function password($p = null){
		echo md5($p);
	}
	*/
	public function make(){
		$a = array(
			array(
				'title'=>'流畅',
				'params'=>'{"Transcode":{"type":"1","ImageWidth":"1024","ImageHeight":"768","ConvertModel":"Stretch","VideoFormat":"H264","BitRate":"100000","FileFormat":"MP4","FrameRate":"25","KeyFrameRate":"25","AudioFormat":"AAC","SamplesPerSec":"48000","BitsPerSample":"0","fpCheck":"on","SliceTime":"3000"}}'
			),
			array(
				'title'=>'流畅ios',
				'params'=>'{"Transcode":{"type":"1","ImageWidth":"1024","ImageHeight":"768","ConvertModel":"Stretch","VideoFormat":"H264","BitRate":"200000","FileFormat":"MP4","FrameRate":"25","KeyFrameRate":"25","AudioFormat":"AAC","SamplesPerSec":"48000","BitsPerSample":"0","fpCheck":"on","SliceTime":"3000"}}'
			),
			array(
				'title'=>'标清',
				'params'=>'{"Transcode":{"type":"1","ImageWidth":"1024","ImageHeight":"768","ConvertModel":"Stretch","VideoFormat":"H264","BitRate":"450000","FileFormat":"MP4","FrameRate":"25","KeyFrameRate":"25","AudioFormat":"AAC","SamplesPerSec":"48000","BitsPerSample":"0","fpCheck":"on","SliceTime":"3000"}}'
			),
			array(
				'title'=>'标清ios',
				'params'=>'{"Transcode":{"type":"1","ImageWidth":"1024","ImageHeight":"768","ConvertModel":"Stretch","VideoFormat":"H264","BitRate":"800000","FileFormat":"MP4","FrameRate":"25","KeyFrameRate":"25","AudioFormat":"AAC","SamplesPerSec":"48000","BitsPerSample":"0","fpCheck":"on","SliceTime":"3000"}}'
			),
			array(
				'title'=>'高清',
				'params'=>'{"Transcode":{"type":"1","ImageWidth":"1024","ImageHeight":"768","ConvertModel":"Stretch","VideoFormat":"H264","BitRate":"1000000","FileFormat":"MP4","FrameRate":"25","KeyFrameRate":"25","AudioFormat":"AAC","SamplesPerSec":"48000","BitsPerSample":"0","fpCheck":"on","SliceTime":"3000"}}'
			)
		);
		
		echo json_encode($a);exit;
	}
}
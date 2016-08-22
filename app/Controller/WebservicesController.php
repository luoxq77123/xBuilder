<?php
/**
 * Webservices Controller
 *
 * MPC callback service
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Controller', 'Controller');
App::uses('ReceiveMPCenterController', 'Controller');
App::uses('PhpWSDL', 'Lib');


class WebservicesController extends AppController 
{
    public $components = array('RequestHandler');

	public $className = "ReceiveMPCenterController";
    public $location;   //本地webservice接口地址

/**
 * 返回WSDL或调用处理类对请求予以响应
 * @return string WSDL内容或处理结果
 */
	public function callback() {
        $this->location = FULL_BASE_URL.'/webservices/callback';
        
		if (isset($this->request->query['wsdl']) && $this->request->is('get')) {
			$wsdl = Cache::read('webservice');   //读取缓存

            if (!$wsdl) {
                $namespace = "http://www.sobey.com/newmedia";
                
                //生成wsdl文件
                $wsdl = sprintf("%s", PhpWSDL::genWSDL(
                                                $this->className, 
                                                $this->location, 
                                                $namespace,
                                                'rpc',
                                                true
                                            )); 
            }
            Cache::write('webservice',$wsdl);   //写入缓存
            $this->set(compact('wsdl'));           
        } else {
            $this->soapHandle();    //调用处理类
        }
	}

/**
 * 实例化webservice对象处理第三方对接口的方法调用
 * @return string 处理结果
 */
	public function soapHandle(){
        $this->autoRender = false;
        //实例化webservice服务对象
		$server = new SoapServer($this->location.'.xml?wsdl',
                            array(
                                'exceptions' => true,
                                'encoding' => 'UTF-8',
                                'soap_version' => SOAP_1_2
                            ));

        //post raw content
        /*$data = file_get_contents("php://input");
        $this->log($data);*/

        $server->setClass($this->className);
        $server->handle();  //执行接口请求(调用)的方法
	}
}
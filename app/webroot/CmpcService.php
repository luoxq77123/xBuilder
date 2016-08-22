<?php
/**
 * EovService
 *
 * The Front Controller for handling every request
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
 * @package       app.webroot
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/**
 * Use the DS to separate the directories in other defines
 */
	if (!defined('DS')) {
		define('DS', DIRECTORY_SEPARATOR);
	}
/**
 * These defines should only be edited if you have cake installed in
 * a directory layout other than the way it is distributed.
 * When using custom settings be sure to use the DS and do not add a trailing DS.
 */

/**
 * The full path to the directory which holds "app", WITHOUT a trailing DS.
 *
 */
	if (!defined('ROOT')) {
		define('ROOT', dirname(dirname(dirname(__FILE__))));
	}
/**
 * The actual directory name for the "app".
 *
 */
	if (!defined('APP_DIR')) {
		define('APP_DIR', basename(dirname(dirname(__FILE__))));
	}

/**
 * The absolute path to the "cake" directory, WITHOUT a trailing DS.
 *
 * Un-comment this line to specify a fixed path to CakePHP.
 * This should point at the directory containing `Cake`.
 *
 * For ease of development CakePHP uses PHP's include_path.  If you
 * cannot modify your include_path set this value.
 *
 * Leaving this constant undefined will result in it being defined in Cake/bootstrap.php
 */
	//define('CAKE_CORE_INCLUDE_PATH', ROOT . DS . 'lib');

/**
 * Editing below this line should NOT be necessary.
 * Change at your own risk.
 *
 */
	if (!defined('WEBROOT_DIR')) {
		define('WEBROOT_DIR', basename(dirname(__FILE__)));
	}
	if (!defined('WWW_ROOT')) {
		define('WWW_ROOT', dirname(__FILE__) . DS);
	}

	if (!defined('CAKE_CORE_INCLUDE_PATH')) {
		if (function_exists('ini_set')) {
			ini_set('include_path', ROOT . DS . 'lib' . PATH_SEPARATOR . ini_get('include_path'));
		}
		if (!include('Cake' . DS . 'bootstrap.php')) {
			$failed = true;
		}
	} else {
		if (!include(CAKE_CORE_INCLUDE_PATH . DS . 'Cake' . DS . 'bootstrap.php')) {
			$failed = true;
		}
	}
	if (!empty($failed)) {
		trigger_error("CakePHP core could not be found.  Check the value of CAKE_CORE_INCLUDE_PATH in APP/webroot/index.php.  It should point to the directory containing your " . DS . "cake core directory and your " . DS . "vendors root directory.", E_USER_ERROR);
	}
	
	/**
	 * 加载外调文件
	 * 数据库配置文件 database.php
	 * Nusoap库文件 nusoap.php
	 * 解析XML文件 xml_array.php
	 * 模拟http异步请求的方法文件 curlHttp.php
	 */
	
	require_once(ROOT . DS . APP_DIR . DS . 'Config' . DS . 'database.php');
	require_once(ROOT . DS . APP_DIR . DS . 'Vendor' . DS . 'nusoap.php');
	require_once(ROOT . DS . APP_DIR . DS . 'Controller' . DS . 'MaterialsController.php');

	require_once(WWW_ROOT . 'xml_array.php');
	require_once(WWW_ROOT . 'curlHttp.php');

	//实例化数据库配置
	$serviceMysql = new DATABASE_CONFIG();
	$link = mysql_connect($serviceMysql->default['host'], $serviceMysql->default['login'], $serviceMysql->default['password']);
	$db = mysql_select_db($serviceMysql->default['database'], $link);
	$dbprefix = $serviceMysql->default['prefix'];
	
	mysql_query('SET NAMES UTF8');
	
	//读取系统配置表，需要服务器网址前缀urlPrefixes
	$cmpcConfigWebUrlSql = "SELECT * FROM `{$dbprefix}config` WHERE `ConfigType` = 'cmpc_notify_addresss'";
	$cmpcConfigWebUrlQuery = mysql_query($cmpcConfigWebUrlSql,$link);
	$cmpcConfigWebUrlView = mysql_fetch_array($cmpcConfigWebUrlQuery);
	$NAMESPACE = 'http://new-media.sobey.com';
	$server = new soap_server;
	$server->configureWSDL('CmpcService', $NAMESPACE, $cmpcConfigWebUrlView['ConfigValue'].'/CmpcService.php');
	$server->register('getXML',array('params'=>'xsd:string'), array('return'=>'xsd:string'), $NAMESPACE);
	$server->soap_defencoding = 'UTF-8';
	$server->decode_utf8 = false;
	$server->xml_encoding = 'UTF-8';
	
	$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
	$server->service($HTTP_RAW_POST_DATA);
	
	function getXML($params)
	{
		global $link, $db, $dbprefix, $eovConfigWebUrlView;
		/*
		 * 判断接收到的值是不是xml格式
		 */
		if(!xml_parser($params))
		{	
			$file = fopen('..'.DS.'tmp'.DS.'logs'.DS.'notXML'.DS.''.date("YmdHis").'.log','w');
			@fwrite($file, $params);
			@fclose($file);
			return $params;
		}else {
			$xml = xml_to_array($params, 1, 'attribute');
			$file = fopen('..'.DS.'tmp'.DS.'logs'.DS.'yesXML'.DS.''.date("YmdHis").'.xml','w');
			@fwrite($file, "UPDATE `{$dbprefix}contents` SET `status` = 1 WHERE `id` = '".$xml['CloudiaTransfer']['ContentID']['value']."'");
			@fclose($file);
			
			if($xml['CloudiaTransfer']['TransferState']['value'] == 1)
			{
				$MaterialsController = new MaterialsController();
				if($MaterialsController)
				{
					mysql_query("UPDATE `{$dbprefix}contents` SET `status` = 1 WHERE `id` = '".$xml['CloudiaTransfer']['ContentID']['value']."'");
					$contents = mysql_query("SELECT * FROM `{$dbprefix}contents` WHERE `id` = '".$xml['CloudiaTransfer']['ContentID']['value']."'");
					$contentsView = mysql_fetch_array($contents);
					$MaterialsController->transcode($contentsView['id'], $contentsView['user_id'], $contentsView['transcode_group_id']);
				}else
				{
					mysql_query("UPDATE `{$dbprefix}contents` SET `status` = 3 WHERE `id` = '".$xml['CloudiaTransfer']['ContentID']['value']."'");
				}
			}
			return $params;
		}
	}
?>

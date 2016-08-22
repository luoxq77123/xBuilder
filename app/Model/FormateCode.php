<?php
/**
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
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('AppModel', 'Model');
/**
 *
 * @package       app.Model
 */
class FormateCode extends AppModel {
	public $useTable = false;
	
	public $video = array(
		'37'	=>	'H264',
		'39936'	=>	'H264-INTEL',
		'1444176017'	=>	'H265'
	);

	public $file = array(
		'2099'	=>	'TS',
		'2103'	=>	'MP4',
		'2226'	=>	'FLV'
	);
}
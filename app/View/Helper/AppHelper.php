<?php
/**
 * Application level View Helper
 *
 * This file is application-wide helper file. You can put all
 * application-wide helper-related methods here.
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
 * @package       app.View.Helper
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Helper', 'View');

/**
 * Application helper
 *
 * Add your application-wide methods in the class below, your helpers
 * will inherit them.
 *
 * @package       app.View.Helper
 */
class AppHelper extends Helper {
	
	public function Byte_Change($size){
	    if($size<=1024){
			$num=floor($size*100)/100;
			$ext="K";
		}elseif($size<=1048576 and $size>1024){
			$num=floor(($size/1024)*100)/100;
			$ext="KB";
		}elseif($size<=1073741824 and $size>1048576){
			$num=floor(($size/1048576)*100)/100;
			$ext="MB";
		}elseif($size<=1099511627776 and $size>1073741824){
			$num=floor(($size/1073741824)*100)/100;
			$ext="GB";
		}
		return $num." ".$ext;
	}
}

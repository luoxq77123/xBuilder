<?php
/**
 * String handling methods expand.
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
 * @package       app.Lib
 * @since         CakePHP(tm) v 1.2.0.5551
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * String handling methods expand.
 *
 *
 * @package       app.Lib
 */
class StringExpand {
/**
 * utf-8字符截取
 * @param  str $sourcestr 是要处理的字符串
 * @param  int $cutlength 为截取的长度(即字数)
 * @param  bool $isSuffix 是否需要'...'后缀
 * @return [type]            [description]
 */
	public static function cutStr($sourcestr, $cutlength, $isSuffix=false)  
	{  
		$returnstr='';  
		$i=0;  
		$n=0;  
		$str_length=strlen($sourcestr);//字符串的字节数  
		while (($n<$cutlength) and ($i<=$str_length))  
		{  
			$temp_str=substr($sourcestr,$i,1);  
			$ascnum=Ord($temp_str);//得到字符串中第$i位字符的ascii码  
			if ($ascnum>=224) //如果ASCII位高与224，  
			{  
				$returnstr=$returnstr.substr($sourcestr,$i,3); 
				//根据UTF-8编码规范，将3个连续的字符计为单个字符  
				$i=$i+3; //实际Byte计为3  
				$n++; //字串长度计1  
			}  
			elseif ($ascnum>=192) //如果ASCII位高与192，  
			{  
				$returnstr=$returnstr.substr($sourcestr,$i,2);
 				//根据UTF-8编码规范，将2个连续的字符计为单个字符  
				$i=$i+2; //实际Byte计为2  
				$n++; //字串长度计1  
			}  
			elseif ($ascnum>=65 && $ascnum<=90) 
			//如果是大写字母，  
			{  
				$returnstr=$returnstr.substr($sourcestr,$i,1);  
				$i=$i+1; //实际的Byte数仍计1个  
				$n++; //但考虑整体美观，大写字母计成一个高位字符  
			}  
			else //其他情况下，包括小写字母和半角标点符号，  
			{  
				$returnstr=$returnstr.substr($sourcestr,$i,1);  
				$i=$i+1; //实际的Byte数计1个  
				$n=$n+0.5; //小写字母和半角标点等与半个高位字符宽…  
			}  
		}  
		if ($str_length > $cutlength && $isSuffix){  
			$returnstr = $returnstr . '…';
			//超过长度时在尾处加上省略号  
			}  
		return $returnstr;  
	} 
}
?>
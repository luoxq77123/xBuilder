<?php
/**
 * MPC job Model.
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
class Job extends AppModel {
	
	public $useTable = false;

	public $status = array('1'=>'2','2'=>'10','3'=>'32','4'=>'264');

/**
 * 检查任务信息是否存在
 * @param  string  $TaskGUID 任务ID
 * @return boolean           检查结果
 */
	public function is_exist($TaskGUID = null){
		if(!$TaskGUID) return false;

		return Cache::read($TaskGUID, '_cake_task_');
	}

/**
 * 任务信息存储
 * @param  array  $joblist 任务步骤列表
 * @return boolean         保存结果
 */
	public function storage($joblist = array()){
		if(!$joblist) return false;
		foreach ($joblist as $TaskGUID => $value) {
			Cache::write($TaskGUID, $value, '_cake_task_');
		}
		return true;
	}

/**
 * 更新任务信息
 * @param  string $TaskGUID 任务ID
 * @param  array  $stepInfo  任务步骤数据
 * @return boolean 更新结果
 */
	public function update($TaskGUID = null, $stepInfo = array()){
		if(!$TaskGUID || !$stepInfo) return false;

		if($taskInfo = Cache::read($TaskGUID, '_cake_task_')){
			foreach ($taskInfo as $key => $value) {
				if( $value['JobType'] == $stepInfo['step'] ){
					$taskInfo[$key]['ExecuteStatus'] = $this->status[$stepInfo['completeStatus']];
					$taskInfo[$key]['ExecuteGuage'] = $stepInfo['progress'];
				}
			}
			return Cache::write($TaskGUID, $taskInfo, '_cake_task_');
		}

		return false;
	}

/**
 * 任务完成处理
 * @param  string $TaskGUID 任务ID
 * @return boolean          处理结果
 */
	public function complete($TaskGUID){
		if(!$TaskGUID) return false;

		$taskInfo = Cache::read($TaskGUID, '_cake_task_');

		$stepInfo = isset($taskInfo[0]['MPC_Spore'][0]['JobID'])?$taskInfo[0]['MPC_Spore']:array($taskInfo[0]['MPC_Spore']);

		if(count($stepInfo)>1){
			foreach ($stepInfo as $key => $value) {
				$taskInfo[0]['MPC_Spore'][$key]['ExecuteGuage'] = 100;
				$taskInfo[0]['MPC_Spore'][$key]['ExecuteStatus'] = 10;
				if($taskInfo[0]['MPC_Spore'][$key]['FinishTime'] = 'N/A'){
					$taskInfo[0]['MPC_Spore'][$key]['FinishTime'] = date('Y-m-d H:i:s');
				}
			}
		}else{
			$taskInfo[0]['MPC_Spore']['ExecuteGuage'] = 100;
			$taskInfo[0]['MPC_Spore']['ExecuteStatus'] = 10;
			if($taskInfo[0]['MPC_Spore']['FinishTime'] = 'N/A'){
				$taskInfo[0]['MPC_Spore']['FinishTime'] = date('Y-m-d H:i:s');
			}
		}

		return Cache::write($TaskGUID, $taskInfo, '_cake_task_');
	}
}

<?php
class DwzHelper extends AppHelper {
    /**
     * 
     * 根据find('threaded')方法返回的树的数组数据生成DWZ界面所需的html结构
     * @param array $trees 树的数组数据
     * @param array $options
     * options说明：
     * model 使用find方法的数据模型
     * isCumstomUrl 是否使用自定义url
     * customUrl 如果isCumstomUrl == true, <a href="customUrl"></a>
     * controller 要自动生成的url中的控制器名称
     * action 要自动生成的url中的action名称
     * param 要自动生成的url中的一个参数，仅限一个
     * config <a>标签的属性配置，例如target="ajax" rel="jbsxBox"
	 * selectId 默认选中的节点
	 * checkedIds 勾选的节点ids
     */
	function generateTree($trees, $options) {
		if (empty($options)) return false;
		$html = '';
		$options['checkedIds'] = (empty($options['checkedIds']))?array():$options['checkedIds'];
		foreach ($trees AS $tree){
			foreach ($tree AS $k => $v) {
				$selected = '';
				if ($k == $options['model']) {
					if ($options['isCumstomUrl']) $url = $options['customUrl'];
					else {
						$param = '';
						$tmp = '';
						if(is_array($options['param'])){
							foreach($options['param'] as $pk => $pv){
								switch($pk){
									case 'key':
										$tmp[$pk] = $v[$pv];
										break;
									case 'fix':
										$tmp[$pk] = $pv;
										break;
									case defalut:
										break;
								}
							}
							$param = implode("/",$tmp);
						}else{
							$param =$v[$options['param']];
						}
						$url = Router::url(array('controller' =>$options['controller'], 'action' => $options['action'])).'/'.$param;
					}
					if ($v['id'] == $options['selectId']) $selected = 'class="picked"';
					$html .= '<li ' . $selected . '><a href="'.$url.'" '.$options['config'].' tvalue="'.$v['id'].'"'.((in_array($v['id'], $options['checkedIds']))? ' checked="true"':'').'>'.$v['name'].'</a>';
				}elseif ($k == 'children' && !empty($v)) {
					$html .= '<ul>';
					$html .= $this->generateTree($v, $options);
					$html .= '</ul></li>';
				} elseif ($k == 'children' && empty($v)) {
					$html .= '</li>';
				}
			}
		}
		return $html;
	}
	
	
	function genTree($trees, $options) {
		if (empty($options)) return false;
		$html = '';
		$options['checkedIds'] = (empty($options['checkedIds']))?array():$options['checkedIds'];
		foreach ($trees AS $tree){
			foreach ($tree AS $k => $v) {
				$selected = '';
				if ($k == $options['model']) {
					if ($options['isCumstomUrl']) $url = $options['customUrl'];
					else {
						$param = '';
						$tmp = '';
						if(is_array($options['param'])){
							foreach($options['param'] as $pk => $pv){
								switch($pk){
									case 'key':
										$tmp[$pk] = $v[$pv];
										break;
									case 'fix':
										$tmp[$pk] = $pv;
										break;
									case defalut:
										break;
								}
							}
							$param = implode("/",$tmp);
						}else{
							$param =$v[$options['param']];
						}
						$url = Router::url(array('controller' =>$options['controller'], 'action' => $options['action'])).'/'.$param;
					}
					if ($v['id'] == $options['selectId']) $selected = 'class="picked"';
					$html .= '<li ' . $selected . '><span url="'.$url.'" '.$options['config'].' tvalue="'.$v['id'].'"'.((in_array($v['id'], $options['checkedIds']))? ' checked="true"':'').'>'.$v['name'].'</span>';
				}elseif ($k == 'children' && !empty($v)) {
					$html .= '<ul>';
					$html .= $this->genTree($v, $options);
					$html .= '</ul></li>';
				} elseif ($k == 'children' && empty($v)) {
					$html .= '</li>';
				}
			}
		}
		return $html;
	}	
}
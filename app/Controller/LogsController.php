<?php
class LogsController extends AppController{
	public $name = 'Logs';
	public function index(){
		$this->layout = 'ajax';
		
		$numPerPage = isset($this->request->data['numPerPage']) ? $this->request->data['numPerPage'] : PAGE_SIZE;
        $pageNum = isset($this->request->data['pageNum']) ? $this->request->data['pageNum'] :1;
		$province = isset($this->request->data['province']) ? $this->request->data['province'] : '';
        $username = isset($this->request->data['username']) ? $this->request->data['username'] : '';
        $keywords = isset($this->request->data['keywords']) ? $this->request->data['keywords'] : '';
        $start = isset($this->request->data['startdate']) ? $this->request->data['startdate'] : '';
        $end = isset($this->request->data['enddate']) ? $this->request->data['enddate'] : '';
        $page_params = array('limit' => $numPerPage, 'page' => $pageNum);
        /*
        *  set 入dwz分页 参数 pageNum numPerPage keywords
        */
        $this->set('param', array('pageNum' => $pageNum, 'numPerPage' => $numPerPage, 'username' => $username, 'keywords' => $keywords, 'province' => $province, 'startdate' => $start, 'enddate' => $end));
        
		if(!empty($province)){
			$page_params['conditions'] = array_merge($page_params['conditions'],array('Log.LogType LIKE' => '%'.$province));
		}
		
		if(!empty($username)){
			$page_params['conditions'] = array_merge($page_params['conditions'],array('Log.UserName LIKE' => '%'.$username.'%'));
		}
		
		if(!empty($keywords)){
			$page_params['conditions'] = array_merge($page_params['conditions'],array('Log.LogMessage LIKE' => '%'.$keywords.'%'));
		}
		
		if(!empty($start)&& !empty($end)){
			if($start <= $end){
				$page_params['conditions'] = array_merge($page_params['conditions'], array('Log.AddTime >=' => $start,'Log.AddTime <=' => $end));
			}
		}elseif(empty($start)&& !empty($end)){
			$page_params['conditions'] = array_merge($page_params['conditions'], array('Log.AddTime <=' => $end));
		}elseif(!empty($start)&& empty($end)) {
			$page_params['conditions'] = array_merge($page_params['conditions'], array('Log.AddTime >=' => $start));
		}
		
        $page_params['order'] =  'Log.AddTime DESC';
        $this->paginate = $page_params;
        $log_Customer = $this->paginate('Log');
		$this->set(compact('log_Customer'));
	}	
}
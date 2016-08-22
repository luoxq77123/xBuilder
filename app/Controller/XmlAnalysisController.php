<?php
class XmlAnalysisController extends AppController{
	public $uses = array('FileFormat','Param','ParamFormat','AudioFormat','VideoFormat','VideoAudio');
	public $filetype = 1,$videotype = 2,$audiotype = 3;//类型字段
	/**
	 * 上传xml文件
	 * */
	public function index(){
	     
	}
	/**
	 * 解析xml文件功能
	 */
	public function analysisXml(){
// 	    echo '{"statusCode":"200","navTabId":"main"}';
// 	    exit;
	    ini_set('max_execution_time',0);
	    App::import('Utility', 'Xml');
	    App::import('Vendor', 'simple_html_dom');
	    $this->autoRender = false;
	    $file = WWW_ROOT.'videoxml/mpccodecparam.xml';
	    $xmlString = file_get_contents($file);
	    if(!$xmlString){
	        echo '{"statusCode":"300", "message":"'.$file.'文件不存在或者为空！", "navTabId":"main"}';
	        exit;
	    }
	    $xmlArray = Xml::toArray(Xml::build($xmlString));
	    
	    //解析ParamHTML数据
	    if(!isset($xmlArray['CodecParam']['ParamHTML'])){
	        echo '{"statusCode":"300", "message":"ParamHTML参数不存在！", "navTabId":"main"}';
	        exit;
	    }else{
	        // 获取html代码对象
	        foreach ($xmlArray['CodecParam']['ParamHTML'] as $k => $html){
	            $html = str_get_html($html);
	            $this->analysisHtml($html,$k);
	            $html->clear();
	        }
	    }
	    //解析fileformat数据
	    if(!isset($xmlArray['CodecParam']['FileFormat'])){
	        echo '{"statusCode":"300", "message":"FileFormat参数不存在！", "navTabId":"main"}';
	        exit;
	    }else{
	        foreach ($xmlArray['CodecParam']['FileFormat'] as $k => $ftype){
	            $data = array();
	            $data['FileFormat']['name'] = $k;
	            $data['FileFormat']['value'] = $ftype['@value'];
	            $data['FileFormat']['is_show'] = (isset($ftype['@is_show'])?$ftype['@is_show']:0);
	            $re = $this->FileFormat->save($data);
	            $fileid = $re['FileFormat']['id'];//添加fileformat后的id
	            if(isset($ftype['@param'])){
	                $params = $this->Param->find('all',array('conditions'=>array('Param.p_name'=>$ftype['@param']),'fields'=>array('id')));
	                $paramformat =array();
	                foreach ($params as $p=>$param){
	                    $paramformat['ParamFormat']['param_id'] = $param['Param']['id'];
	                    $paramformat['ParamFormat']['format_id'] = $fileid;
	                    $paramformat['ParamFormat']['format_type'] = $this->filetype;
	                    $this->ParamFormat->save($paramformat);
	                    $this->ParamFormat->id = null;
	                }
	            }
	            $this->FileFormat->id = null;
	            foreach ($ftype as $key => $avideo){
	                if($key == '@value'){
	                }elseif($key == '@param'){
	                }elseif($key == 'VideoType'){
	                }elseif($key == '@is_show'){
	                }else{//音频处理
	                    $this->xmlAudio($avideo,$fileid,$key);
	                }
	            }
// 	            //视频 处理
	            $this->xmlVideo($ftype['VideoType'],$fileid);
	            
	        }
	    }
	    //解析完成
	    echo '{"statusCode":"200","message":"解析成功"}';
	}
	/**
	 * 解析html功能
	 */
	private function analysisHtml($html = null, $p_name = null, $param_id = null){
	    if($html->find('table')){
	        $temptable = array();
	        $temptr = array();
	        foreach ($html->find('table', 0)->find('tr') as $tr => $trv){
	            $temptd = array();
	            $i = 0;
	            foreach ($trv->find('td') as $td => $tdv){
	                if($tdv->innertext&&$tdv->innertext!='|'&&$tdv->innertext!='&nbsp;'){
	                    $temptd[$i] = $this->htmlTd($tdv);
	                    if(is_array($temptd[$i])){
	                        if(count($temptd[$i])>1){
	                            foreach ($temptd[$i] as $key=>$val){
	                                $val['c_name'] = ($key?'':$temptd[$i-1]);
	                                $val['start_str'] = ($key?':':'');
	                                $val['p_name'] = $p_name;
	                                $val['parent_param'] = $param_id;
	                                
	                                //保存param到表中
	                                $this->Param->save($val);
	                                $this->Param->id=null;
	                            }
	                        }else{
	                            $temptd[$i][0]['c_name'] = $temptd[$i-1];
	                            $temptd[$i][0]['p_name'] = $p_name;
	                            $temptd[$i][0]['parent_param'] = $param_id;
	                            if(isset($temptd[$i][0]['onclick'])){
	                                $id = 1;
	                                unset($temptd[$i][0]['onclick']);
	                            }
	                            //保存param到表中
	                            $param = $this->Param->save($temptd[$i][0]);
	                            if(isset($id)){
	                                $param_id = $this->Param->id;
	                            }
	                            $this->Param->id=null;
	                        }
	                    }
	                    $i++;
	                }
	            }
	        }
	    }
	    if($html->find('div')){
	        $divid = $html->find('div',0)->id;
	       	$obj = $html->find('div',0)->find('div');
	        foreach ($obj as &$v){
	            $v->tag = 'span';
	        }
	        $this->analysisHtml($html->find('div',0),$p_name,$param_id);
	    }
	}
// 	private function analysisHtml($html = null, $k = null){
// 	    if($html->find('table')){
// 	        $temptable = array();
// 	        $j = 0;
// 	        foreach ($html->find('table', 0)->find('tr') as $tr => $trv){
// 	            $temptr = array();
// 	            $i = 0;
// 	            foreach ($trv->find('td') as $td => $tdv){
//     	            if($tdv->innertext&&$tdv->innertext!='|'&&$tdv->innertext!='&nbsp;'){
//     	                $temptr[$i] = $this->htmlTd($tdv);
//     	                $i++;
//     	            }
// 	            }
// 	            if($temptr){
//     	            $temptable[$j] = $temptr;
//     	            $j++;
// 	            }
// 	        }
// 	    }
// 	    if($html->find('div')){
// 	        $divid = $html->find('div',0)->id;
// 	        foreach ($html->find('div',0)->find('div') as &$v){
// 	            $v->tag = 'span';
// 	        }
// 	        $tempdiv = $this->analysisHtml($html->find('div',0));
// 	        $temptable['div'] = $tempdiv;
// 	        $temptable['div']['id'] = $divid;
// 	    }
// 	    return $temptable;
// 	}
	/**
	 * xml视频数据处理
	 */
	private function xmlVideo($avideo=array(),$fileid=0){
	     
	    if(!$fileid)return false;
	     
	    foreach ($avideo as $j => $video){
	        $temp = array();
	        foreach ($video as $p => $param){
	            if($p != '@param'){
	                $temp[str_replace('@', '', $p)] = $param;
	            }else{
	                $video['param'] = $param;
	            }
	        }
	        $data = array();
	        $data['VideoFormat']['name'] = $j;
	        $data['VideoFormat']['value'] = $temp['value'];
	        $data['VideoFormat']['fid'] = $fileid;
	        $data['VideoFormat']['is_show'] = (isset($temp['is_show'])?$temp['is_show']:0);
	        @$temp['brmin'] = str_replace('k','',$temp['brmin']);
	        $temp['brmin'] = str_replace('K','',$temp['brmin']);
	        $temp['brmin'] = str_replace('m','000',$temp['brmin']);
	        @$temp['brmax'] = str_replace('k','',$temp['brmax']);
	        $temp['brmax'] = str_replace('m','000',$temp['brmax']);
	        
	        $this->VideoFormat->save($data);
	        
	        if(isset($video['param'])){
	            $params = $this->Param->find('all',array('conditions'=>array('Param.p_name'=>$video['param']),'fields'=>array('id')));
	            $paramformat =array();
	            foreach ($params as $p=>$param){
	                $paramformat['ParamFormat']['param_id'] = $param['Param']['id'];
	                $paramformat['ParamFormat']['format_id'] = $this->VideoFormat->id;
	                $paramformat['ParamFormat']['format_type'] = $this->videotype;
	                $this->ParamFormat->save($paramformat);
	                $this->ParamFormat->id = null;
	            }
	        }
	        
	        if($data['VideoFormat']['value']){
	            $basic = array();
	            $basic[] = array('basic'=>1,'p_name'=>$j.'.'.$fileid,'name'=>'FormatWidth','c_name'=>'幅面:','type'=>'text','end_str'=>'','sort'=>'1','verify'=>json_encode(array('is_number'=>1)),'value'=>$temp['width']);
	            $basic[] = array('basic'=>1,'p_name'=>$j.'.'.$fileid,'name'=>'FormatHeight','start_str'=>'X','type'=>'text','sort'=>'1','verify'=>json_encode(array('is_number'=>1)),'value'=>$temp['height']);
	            $basic[] = array('basic'=>1,'p_name'=>$j.'.'.$fileid,'name'=>'WidthRatio','c_name'=>'幅面自适配:','type'=>'select','options'=>json_encode(array(''=>'请选择','0'=>'自定义高宽比','1'=>'以高为基准','2'=>'保持原面幅')),'value'=>'0','sort'=>'1');
	            $basic[] = array('basic'=>1,'p_name'=>$j.'.'.$fileid,'name'=>'ConvertModel','c_name'=>'变换模式:','type'=>'select','options'=>json_encode(array(''=>'请选择','Stretch'=>'完全填充','X-fit'=>'水平填充','Y-fit'=>'垂直填充','Auto-fit'=>'自动填充',)),'sort'=>'1','value'=>'Stretch');
	            $basic[] = array('basic'=>1,'p_name'=>$j.'.'.$fileid,'name'=>'BitRate','c_name'=>'码率:','type'=>'text','sort'=>'1','end_str'=>'kbps','value'=>$temp['brmin']);
	            $basic[] = array('basic'=>1,'p_name'=>$j.'.'.$fileid,'name'=>'FrameRate','c_name'=>'帧率:','type'=>'select','options'=>json_encode(array(''=>'请选择','25'=>'25','29.97'=>'29.97','15'=>'15','28'=>'28','30'=>'30','5'=>'5','6'=>'6','8'=>'8','10'=>'10','12'=>'12','23.97'=>'23.97','0'=>'0',)),'value'=>'25','sort'=>'1');
	            $basic[] = array('basic'=>1,'p_name'=>$j.'.'.$fileid,'name'=>'Gop','c_name'=>'GOP:','type'=>'text','value'=>12,'sort'=>'1');
	            //这几个参数需要存入param
	            $paramformat =array();
	            foreach ($basic as $k=>$v){
	                $temp_basic['Param'] = $v;
	                $this->Param->save($v);
	                //存入param和format的关系
	                $paramformat['ParamFormat']['param_id'] = $this->Param->id;
	                $paramformat['ParamFormat']['format_id'] = $this->VideoFormat->id;
	                $paramformat['ParamFormat']['format_type'] = $this->videotype;
	                $this->ParamFormat->save($paramformat);
	                $this->ParamFormat->id = null;
	                 
	                $this->Param->id = null;
	            }
	        }
	        if(isset($temp['audio'])){
	            $audio = $this->AudioFormat->find('all',array('conditions'=>array('AudioFormat.team_name LIKE'=>$temp['audio'],'fid'=>$fileid)));
	            if($audio){
    	            foreach ($audio as $a=>$au){
    	                $relationdata = array('VideoAudio'=>array('video_id'=>$this->VideoFormat->id,'audio_id'=>$au['AudioFormat']['id']));
    	                $this->VideoAudio->save($relationdata);
    	                $this->VideoAudio->id = null;
    	            }
	            }
	        }
	        $this->VideoFormat->id = null;
	    }
	}
// 	private function xmlVideo($avideo=array(),$fileid=0){
	     
// 	    if(!$fileid)return false;
	     
// 	    foreach ($avideo as $j => $video){
// 	        $temp = array();
// 	        foreach ($video as $p => $param){
// 	            if($p != '@param'){
// 	                $temp[str_replace('@', '', $p)] = $param;
// 	            }else{
// 	                $video['param'] = $param;
// 	            }
// 	        }
// 	        $data = array();
// 	        $data['XmlVideotype']['name'] = $j;
// 	        $data['XmlVideotype']['value'] = json_encode($temp);
// 	        $data['XmlVideotype']['param'] = (isset($video['param'])?$video['param']:0);
// 	        $data['XmlVideotype']['fid'] = $fileid;
	        
// 	        $this->XmlVideotype->save($data);
	        
// 	        if(isset($temp['audio'])){
// 	            $audio = $this->XmlAudiotype->find('first',array('conditions'=>array('XmlAudiotype.name LIKE'=>$temp['audio'],'fid'=>$fileid)));
// 	            if($audio){
//     	            $relationdata = array('XmlAvideorelation'=>array('videoid'=>$this->XmlVideotype->id,'audioid'=>$audio['XmlAudiotype']['id']));
//     	            $this->XmlAvideorelation->save($relationdata);
//     	            $this->XmlAvideorelation->id = null;
// 	            }
// 	        }
	        
// 	        $this->XmlVideotype->id = null;
// 	    }
// 	}
	/**
	 * xml音频数据处理
	 */
	private function xmlAudio($avideo=array(),$fileid=0,$key=0){
	    foreach ($avideo as $j => $aideo){
	        $temp = array();
	        foreach ($aideo as $p => $param){
	            $temp[str_replace('@', '', $p)] = $param;
	        }
	        $data = array();
	        $data['AudioFormat']['team_name'] = $key;
	        $data['AudioFormat']['name'] = $j;
	        $data['AudioFormat']['value'] = $temp['value'];
	        $data['AudioFormat']['fid'] = $fileid;
	        $data['AudioFormat']['is_show'] = (isset($temp['is_show'])?$temp['is_show']:0);
	        $this->AudioFormat->save($data);
	        if(isset($temp['param'])){
	            $params = $this->Param->find('all',array('conditions'=>array('Param.p_name'=>$temp['param']),'fields'=>array('id')));
	            $paramformat =array();
	            foreach ($params as $p=>$param){
	                $paramformat['ParamFormat']['param_id'] = $param['Param']['id'];
	                $paramformat['ParamFormat']['format_id'] = $this->AudioFormat->id;
	                $paramformat['ParamFormat']['format_type'] = $this->audiotype;
	                $this->ParamFormat->save($paramformat);
	                $this->ParamFormat->id = null;
	            }
	        }
	        
	        //音频基础元素入库
	        if($data['AudioFormat']['value']){
	            $basic = array();
	            $basic[] = array('basic'=>1,'p_name'=>$j.'.'.$fileid,'name'=>'SamplesPerSec','c_name'=>'采样率:','type'=>'select','options'=>json_encode(array(''=>'请选择','48000'=>'48000','44100'=>'44100',)),'value'=>'48000','sort'=>'1');
	            $basic[] = array('basic'=>1,'p_name'=>$j.'.'.$fileid,'name'=>'BitsPerSample','c_name'=>'采样位率:','type'=>'select','sort'=>'1','options'=>json_encode(array(''=>'请选择','16'=>'16','8'=>'8',)),'value'=>'16');
	            //这几个参数需要存入param
	            $paramformat =array();
	            foreach ($basic as $k=>$v){
	                $temp_basic['Param'] = $v;
	                $this->Param->save($v);
	                //存入param和format的关系
	                $paramformat['ParamFormat']['param_id'] = $this->Param->id;
	                $paramformat['ParamFormat']['format_id'] = $this->AudioFormat->id;
	                $paramformat['ParamFormat']['format_type'] = $this->audiotype;
	                $this->ParamFormat->save($paramformat);
	                $this->ParamFormat->id = null;
	        
	                $this->Param->id = null;
	            }
	        }
	        
	        $this->AudioFormat->id = null;
	    }
	    
// 	    $this->AudioFormat->save($data);
// 	    $this->AudioFormat->id = null;
	}
	/**
	 * 解析html中td内的代码
	 */
	private function htmlTd($td){
        if($td->children()){
        	$child = $td->children();
            if($child[0]->tag=='input'){
                return $this->htmlInput($td);
            }elseif($child[0]->tag=='select'){
                return $this->htmlSelect($td);
            }
        }elseif($td->innertext!='|'&&$td->innertext){
            return $td->innertext;
        }
	    return false;
	}
	/**
	 * 解析html中td内的input代码
	 */
	private function htmlInput($input){
	    $tempv = array();
	    if(count($input->children())==1){
	        $v = $input->children(0);
	        $tempv[0] = array(
	                'value' => (isset($v->value)?$v->value:null),
	                'type' => (isset($v->type)?$v->type:null),
	                'name'  => (isset($v->name)?$v->name:null),
	        );
	        if(isset($v->onclick)){
	            $tempv[0]['onclick'] = $v->onclick;
	        }
	        if(isset($input->children(0)->find('text',0)->_)){
	            if($input->children(0)->find('text',0)->prev_sibling()){
	                $tempv[0]['start_str'] = '';
	                foreach ($input->children(0)->find('text',0)->_ as $text){
	                    $tempv[0]['end_str'] = trim($text);
	                }
	            }else{
	                foreach ($input->children(0)->find('text',0)->_ as $text){
	                    $tempv[0]['start_str'] = trim($text);
	                }
	                $tempv[0]['end_str'] = '';
	            }
	        }else{
	            $tempv[0]['start_str'] = '';
	            $tempv[0]['end_str'] = '';
	        }
	        unset($v->attr['value']);
	        unset($v->attr['type']);
	        unset($v->attr['name']);
	        $tempv[0]['verify'] = json_encode($v->attr);
	    }else{
	        foreach ($input->children() as $k => $v){
	            $tempv[$k] = array(
	                    'value' => isset($v->value)?$v->value:null,
	                    'type' => (isset($v->type)?$v->type:null),
	                    'name'  => (isset($v->name)?$v->name:null),
	            );
	            $tempv[$k]['start_str'] = '';
	            $tempv[$k]['end_str'] = '';
	            unset($v->attr['value']);
	            unset($v->attr['type']);
	            unset($v->attr['name']);
	            $tempv[$k]['verify'] = json_encode($v->attr);
	        }
	    }
	    return $tempv;
	}
	/**
	 * 解析html中td内的select代码
	 */
	private function htmlSelect($select){
// 	     目前只能读取一个select的数据
         $select = $select->find('select',0);
	     $name = $select->name;
	     $selected = '';
	     foreach ($select->children() as $k => $option){
	         $tempoption[$option->value] = $option->plaintext;
	         if(isset($option->selected)){
	             $selected = $option->value;
	         }
// 	         $selected = (isset($option->selected)?$option->value:null);
	     }
	     unset($select->attr['name']);
	     if(isset($select->find('text',0)->_)){
	         if($select->find('text',0)->prev_sibling()){
	             $tempv['start_str'] = '';
	             foreach ($select->find('text',0)->_ as $text){
	                 $tempv['end_str'] = trim($text);
	             }
	         }else{
	             foreach ($select->find('text',0)->_ as $text){
	                 $tempv['start_str'] = trim($text);
	             }
	             $tempv['end_str'] = '';
	         }
	     }else{
	         $tempv['start_str'] = '';
	         $tempv['end_str'] = '';
	     }
	     return array(array('name'=>$name, 'value'=>$selected, 'type'=>'select', 'options'=>json_encode($tempoption), 'start_str'=>$tempv['start_str'], 'end_str'=>$tempv['end_str'], 'verify'=>json_encode($select->attr)));
	}


//获取文件对应元素
	public function getFileParam($id = null){
		if(!$id) return $this->jsonToDWZ(array('message'=>'非法访问'),300);

        $file = $this->FileFormat->getFileParam($id);
        $this->set(compact('file'));
	}

//获取转码文件对应的视频文件
	public function getVideoList($id = null){
		if(!$id) return $this->jsonToDWZ(array('message'=>'非法访问'),300);

	    $videos = $this->VideoFormat->getVideoList($id);
	    $this->set(compact('videos'));
	}

//获取视频对应的元素
    public function getVideoParam($id = null,$basic = null){
    	if(!$id){
			$this->autoRender = false;
			return false;
		}

        $video = $this->VideoFormat->getVideoParam($id, $basic);
        $this->set(compact('video','basic'));
	}

//获取视频对应的音频列表
	public function getAudioList($id = null){
		if(!$id) return $this->jsonToDWZ(array('message'=>'非法访问'),300);

	    $audios = $this->VideoAudio->getAudios($id);
	    $this->set(compact('audios'));
	}

//获取音频参数
	public function getAudioParam($id = null,$basic = null){
		if(!$id || $basic == null){
			$this->autoRender = false;
			return false;
		}

        $audio = $this->AudioFormat->getAudioParam($id,$basic);
        $this->set(compact('audio','basic'));

	}

//获取特殊参数html
	public function getParamHtml(){
	    $time = (isset($this->request->data['time'])?$this->request->data['time']:'');
	    if(isset($this->request->data['file']) && $this->request->is('post')){
	        //file特殊参数
	        $fid = $this->request->data['file'];
	        $param = $this->XmlFileformat->find('first',array('conditions'=>array('XmlFileformat.id'=>$fid)));
	        if(isset($param['XmlFileformat']['param'])&&$param['XmlFileformat']['param']){
	            $html = $this->getHtml($param['XmlFileformat']['param'],'file',$time);
	            $this->set('htmlparam',$html);
	        }else{
	            $this->autoRender = false;
	        }
	    }elseif(isset($this->request->data['video'])&&$this->request->data['video']){
	        //video特殊参数
	        $vid = $this->request->data['video'];
	        $param = $this->XmlVideotype->find('first',array('conditions'=>array('XmlVideotype.id'=>$vid)));
	        if(isset($param['XmlVideotype']['param'])&&$param['XmlVideotype']['param']){
	            $html = $this->getHtml($param['XmlVideotype']['param'],'video',$time);
	            $this->set('htmlparam',$html);
	            $this->render('get_video_param');
	        }else{
	            $this->autoRender = false;
	        }
	    }elseif(isset($this->request->data['audio'])&&$this->request->data['audio']){
	        //audio特殊参数
	        $aid = $this->request->data['audio'];
	        $avid = $this->request->data['avideo'];
	        $audioteam = $this->XmlAvideorelation->find('first',array('conditions'=>array('XmlAvideorelation.videoid'=>$avid)));
	        $audiolist = json_decode($audioteam['Audiotype']['value'],true);
	        foreach ($audiolist as $k => $v){
	            if($v['value']==$aid){
	                $paramname = $v['param'];
	            }
	        }
	        if(isset($paramname)&&$paramname){
	            $html = $this->getHtml($paramname,'audio',$time);
	            $this->set('htmlparam',$html);
	            $this->render('get_audio_param');
	        }else{
	            $this->autoRender = false;
	        }
	    }else{
	        $this->autoRender = false;
	    }              
	}
	//
	public function getHtml($param=null,$type='video',$time=''){
	    $paramone = $this->XmlParam->find('first',array('conditions'=>array('XmlParam.name'=>$param)));
	    if($paramone){
	        $htmlparam = json_decode($paramone['XmlParam']['documents'],TRUE);
	        $html = array();
	        $div = array();
	        foreach ($htmlparam as $k=>$v){
	            if($k=='div'&&$k){
	                foreach ($htmlparam['div'] as $key=>$val){
	                    if($key=='id'&&$key){
	                        $div[$key] = $val.$time;
	                    }else{
	                        foreach ($val as $i=>$j){
	                            if(is_array($j)){
	                                foreach ($j as $ii=>$jj){
	                                    if($jj['type']=='select'){
	                                        $div[$key][$i][$ii]['name'] = $type.']['.$jj['name'];
	                                        $div[$key][$i][$ii]['type'] = $jj['type'];
	                                        $div[$key][$i][$ii]['start_str'] = $jj['start_str'];
	                                        $div[$key][$i][$ii]['end_str'] = $jj['end_str'];
	                                        $div[$key][$i][$ii]['options']['id'] = $jj['name'].$time;
	                                        $div[$key][$i][$ii]['options']['class'] = 'combox uploadComboxWidth';
	                                        $div[$key][$i][$ii]['options']['label'] = false;
	                                        foreach ($jj['options'] as $iii=>$jjj){
	                                            $div[$key][$i][$ii]['options']['options'][$jjj['value']] = $jjj['text'];
	                                        }
	                                    }elseif($jj['type']=='text'){
	                                        $div[$key][$i][$ii] = $jj;
	                                        $div[$key][$i][$ii]['id'] = $jj['name'].$time;
	                                        $div[$key][$i][$ii]['name'] = 'data['.$type.']['.$jj['name'].']';
	                                        $div[$key][$i][$ii]['label'] = false;
	                                    }elseif($jj['type']=='checkbox'){
	                                        $div[$key][$i][$ii] = $jj;
	                                        $div[$key][$i][$ii]['name'] = 'data['.$type.']['.$jj['name'].']';
	                                        $div[$key][$i][$ii]['id'] = $jj['name'].$time;
	                                        $div[$key][$i][$ii]['label'] = false;
	                                    }
	                                }
	                            }else{
	                                $div[$key][$i] = $j;
	                            }
	                        }
	                         
	                    }
	                }
	            }else{
	                foreach ($v as $key=>$val){
	                    if(is_array($val)){
	                        foreach ($val as $i=>$j){
	                            if($j['type']=='select'){
	                                $html[$k][$key][$i]['name'] = $type.']['.$j['name'];
	                                $html[$k][$key][$i]['type'] = $j['type'];
	                                $html[$k][$key][$i]['start_str'] = $j['start_str'];
	                                $html[$k][$key][$i]['end_str'] = $j['end_str'];
	                                $html[$k][$key][$i]['options']['id'] = $j['name'].$time;
	                                $html[$k][$key][$i]['options']['class'] = 'combox uploadComboxWidth';
	                                $html[$k][$key][$i]['options']['label'] = false;
	                                foreach ($j['options'] as $ii=>$jj){
	                                    $html[$k][$key][$i]['options']['options'][$jj['value']] = $jj['text'];
	                                }
	                            }elseif($j['type']=='text'){
	                                $html[$k][$key][$i] = $j;
	                                $html[$k][$key][$i]['id'] = $j['name'].$time;
	                                $html[$k][$key][$i]['name'] = 'data['.$type.']['.$j['name'].']';
	                                $html[$k][$key][$i]['label'] = false;
	                            }elseif($j['type']=='checkbox'){
	                                $html[$k][$key][$i] = $j;
	                                $html[$k][$key][$i]['id'] = $j['name'].$time;
	                                $html[$k][$key][$i]['name'] = 'data['.$type.']['.$j['name'].']';
	                                $html[$k][$key][$i]['label'] = false;
	                                if(isset($j['onclick'])){
	                                    $html[$k][$key][$i]['onclick'] = str_replace('vbrset','vbrset'.$time,$j['onclick']);
	                                }
	                            }
	                        }
	                    }else{
	                        $html[$k][$key] = $val;
	                    }
	                }
	            }
	        }
	        return array('html'=>$html,'div'=>$div);
	    }
	}
	
	public function test(){
// 	    ?>
<!-- 	    <script type="text/javascript" src="/js/jquery-1.7.1.js"></script> -->
<!-- 	    <script type="text/javascript" id="script-data"> -->
// 	    <param name="movie" 

// 	    	value="/Flvplayer.swf" />
// 	    	        <param name="quality" value="high" />
// 	    	        <param name="allowFullScreen" value="true" />
// 	    	        <param name="wmode" value="opaque" />
// 	    	        <param name="FlashVars" 

// 	    	value="vcastr_file=/DSCF0044.flv&BufferTim

// 	    	e=3" />
// 	    	        <embed src="/Flvplayer.swf" 

// 	    	allowfullscreen="true" 

// 	    	flashvars="vcastr_file=/DSCF0044.flv" 

// 	    	quality="high"
	    	            

// 	    	pluginspage="http://www.macromedia.com/go/getflashplayer" 

// 	    	type="application/x-shockwave-flash"
// 	    	            width="308" height="206" wmode="opaque"></embed>
	    		    
<!-- </script> -->
<!-- 	    <object id='a' classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0"><param name="scale" value="showall" /><param name="wmode" value="window" /><param name="play" value="false" /><param name="quality" value="high" /><param name="movie" value="/DSCF0044.flv" /><embed play="false" pluginspage="http://www.macromedia.com/go/getflashplayer" quality="high" scale="showall" src="/DSCF0044.flv" type="application/x-shockwave-flash" wmode="window"></embed></object> -->
<!-- 	    <script type="text/javascript"> -->
// 	    //var src = $("object").find("[name=movie]").val();
// 	    var o = $("#script-data").html();
// 	    var s = document.createElement('div');
// 	    s.innerHTML = o;
// 	    b = $(s);

// 	    $("object").each(function(){
// 		    var c = $(this).find("[name=movie]").val();
// 	    	if(c.indexOf('.flv')>0){
// 	    		var src = 'vcastr_file='+c;
// 		    	var play = $(this).find("[name=play]").val();
// 		    	b.find("[name=FlashVars]").val(src+'&BufferTime=3');
// 		    	b.find("[name=embed]").attr('flashvars',src);
// 		    	$(this).html(b.html());
// 			}
// 		});
<!-- </script> -->
	    
	    <?php
	    $this->autoRender = false;
	}
}
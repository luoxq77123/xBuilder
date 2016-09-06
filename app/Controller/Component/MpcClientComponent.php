<?php
/**
 * MpcClient component
 *
 * Call Mpc Interface by Webservice.
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
 * @package       Cake.Controller.Component
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */


App::uses('Component', 'Controller');
App::uses('String', 'Utility');
App::uses('Xml', 'Utility');
App::uses('Debugger', 'Utility');


class MpcClientComponent extends Component {

    /**
     * $wsdl
     * @var mixed
     */
    private $wsdl = null;

    /**
     * soap 客户端实例化对象
     * @var object
     */
    private $soapClient = null;

    /**
     * 特殊参数全键值
     * @var array
     */
    private $specialParamKey = array(
        'video' => array('sar_width','sar_height','dest_width','dest_height','speed','delay_frames','profile','level','definterlace','interlace','ref_frames','bframes','vbv_buffer','b_aud','vpp_definterlace','slice_frames','decode_threads','sps_id','OptimizeMode'),
        'audio' => array('Bitrate','encoderversion','profile','bitstreamoutputformat'),
        'file' => array('HintTrackValue','CreateStreamIndex')
    );

    /**
     * 与MPC特殊参数节点对应关系
     * @var array
     */
    private $MpcSpecialParamKey = array(
        'video' => 'VideoParam',
        'audio' => 'AudioParam',
        'file' => 'FileParam'
    );

    /**
     * Constructor 构造函数
     * @param ComponentCollection $collection A ComponentCollection this component can use to lazy load its components
     * @param array $settings Array of configuration settings.
     */
    public function __construct(ComponentCollection $collection, $settings = array())
    {
        $this->wsdl = @$settings['wsdl'] ? : FULL_BASE_URL . "/mpcinterface.wsdl";
        try {
            $this->soapClient = new SoapClient($this->wsdl, array(
                "connection_timeout" => 30,
                "encoding" => "utf-8"
            ));
            if (!@$settings['wsdl']) {
                $this->soapClient->__setLocation($settings['mpcUrl']);
            }
        }
        catch (Exception $e) {
            $this->setError("SOAP Error: " . $e->getMessage() . " (" . $this->wsdl . ")");
            return false;
        }

        parent::__construct($collection, $settings);
    }

    /**
     * 析构函数
     * 释放soap客户端对象
     */
    public function __destruct()
    {
        $this->soapClient = null;
    }

    /**
     * 查询任务详情
     * @param string  $ProjectID 任务ID
     */
    public function GetProjectList($projectID = null)
    {
        return $this->docommit(__FUNCTION__, array(
            "ProjectID" => $projectID
        ));
    }

    /**
     * 查询工位详情
     * @param int $JobID 工位ID
     */
    public function GetJobList($JobID = null)
    {
        return $this->docommit(__FUNCTION__, array(
            "JobID" => $JobID
        ));
    }

    /**
     * 删除任务
     * @param string $ProjectID 任务ID
     */
    public function DeleteProject($ProjectID = null)
    {
        return $this->docommit(__FUNCTION__, array(
            "TaskGUID" => $ProjectID
        ));
    }

    /**
     * 暂停指定工位
     * @param int $JobID 工位ID
     */
    public function PauseJob($JobID = null)
    {
        return $this->docommit(__FUNCTION__, array(
            "JobID" => $JobID
        ));
    }

    /**
     * 恢复指定工位
     * @param int $JobID 工位ID
     */
    public function ResumeJob($JobID = null)
    {
        return $this->docommit(__FUNCTION__, array(
            "JobID" => $JobID
        ));
    }

    /**
     * 取消指定工位
     * @param int $JobID 工位ID
     */
    public function CancelJob($JobID = null)
    {
        return $this->docommit(__FUNCTION__, array(
            "JobID" => $JobID
        ));
    }

    /**
     * 设置指定任务优先级
     * @param string $ProjectID 任务ID
     * @param int    $Priority  优先级值
     */
    public function SetProjectPriority($ProjectID, $Priority)
    {
        return $this->docommit(__FUNCTION__, array(
            "TaskGUID" => $ProjectID,
            "Priority" => $Priority
        ));
    }

    /**
     * 重置工位
     * @param string $JobID 步骤ID
     */
    public function ResetJob($JobID)
    {
        return $this->docommit(__FUNCTION__, array(
            "JobID" => $JobID
        ));
    }

    /**
     * 添加任务
     * @param array files 待转码文件参数
     * @param array settings 附加设置
     */
    public function AddTask($files = array(), $settings = array()) {
        $taskGUID = strtoupper(String::uuid()); //生成任务ID
        $publicTime = date('Y-m-d H:i:s'); //任务产生时间

        //审核默认栏目 AUDIT_DEFAULT_COLUMN=>"1400,xBuilder入库"
        $columnInfo = explode(',',AUDIT_DEFAULT_COLUMN);

        $req_data = array(
            'MPCWebCmd' => array(
                'CommandType' => __FUNCTION__,
                __FUNCTION__  =>array(
                    'MPC'=>  array(
                            'Header' =>  array(
                                'Version'       =>  '1.0',
                                'RequestID'     =>  $taskGUID,
                                'RequestTime'   =>  $publicTime,
                                'RequestMQ'     =>  ''
                        ),
                        'Content'   =>  array(
                            'MPCType'       =>  __FUNCTION__,
                            __FUNCTION__    =>  array(
                                    'BaseInfo'  =>  array(
                                        'TaskGUID'      =>  $taskGUID,
                                        'TaskName'      =>  $settings['taskName']?:$taskGUID,
                                        'ColumnCode'    =>  $columnInfo[0],
                                        'ColumnName'    =>  $columnInfo[1],
                                        'TaskLength'    =>  'n/a',
                                        'TaskPriority'  =>  0,
                                        'DispatchType'  =>  'auto',
                                        'Controllable'  =>  1,
                                        'PutinTime'     =>  $publicTime
                                    ),
                                    'MediaFile'     =>  array(),
                                    'SourceInfo'    =>  array(
                                        'HostName'  =>  'Reddrake-PC',
                                        'IpAddress' =>  '172.16.146.37',
                                        'SubSystem' =>  'xBuilder'
                                    ),
                                    'TaskInfo'      =>  array(
                                        'Scope'     =>  'SoapTargetInfo',
                                        'Schema'    =>  '',
                                        'Data'      =>  array(
                                            'SoapTargetInfo'    =>  array(
                                                'SoapTargetUri' =>  FULL_BASE_URL . '/webservices/callback'
                                            ),
                                            'IsSplit'   =>  ''
                                        )
                                    ),
                                    'NotifyTarget'  =>  'mpc_response',
                                    'NotifyEvent'   =>  '4095'
                            )
                        )
                    )
                )
            ));
        
        if(isset($settings['policyID'])){//走策略方式
            $req_data['MPCWebCmd'][__FUNCTION__]['MPC']['Content'][__FUNCTION__]['PolicyID'] = $settings['policyID'];
        }elseif ($settings['transType'] == 'joblist') {
            foreach ($settings['joblist'] as $key=>$value) {
                $obj[$key] = array(
                    'GroupType'     =>  'out_'.$value['Transcode']['transcodeID'],
                    'MediaType'     =>  'media_'.$value['Transcode']['transcodeID'],
                    'PathFormat'    =>  PATH_FORMAT,
                    'UseTracks'     =>  @$value['audio']['UseTracks'] == 2?'1,2,3,4,5,6':'1+3+4+5,2+3+4+6',
                    'CodecParam'    =>  array(
                        'FileFormat'    =>  $value['file']['FileFormat'],
                        'VideoFormat'   =>  $value['video']['VideoFormat'],
                        'BitRate'       =>  $value['video']['BitRate'] * 1000,
                        'FrameRate'     =>  $value['video']['FrameRate'],
                        'ImageWidth'    =>  $value['video']['FormatWidth'],
                        'ImageHeight'   =>  $value['video']['FormatHeight'],
                        'WidthRatio'    =>  $value['video']['WidthRatio'],
                        'KeyFrameRate'  =>  $value['video']['Gop'],
                        'Transform'     =>  array(
                            'Mode'  =>  $value['video']['ConvertModel']
                        ),
                        'AudioFormat'   =>  $value['audio']['AudioFormat'],
                        'SamplesPerSec' =>  $value['audio']['SamplesPerSec'],
                        'BitsPerSample' =>  $value['audio']['BitsPerSample'],
                        'ReplaceByMainFormat'   =>  0,
                        'AssistFormat'  =>  0,
                        'SpecialParam'  =>  array(
                            'CDATA' =>  '',
                            'SpecialParam'  =>  array(
                                /*'VideoParam'    =>  array(
                                    'sar_width'     =>  @$value['video']['sar_width']?:1,
                                    'sar_height'    =>  @$value['video']['sar_height']?:1,
                                    'speed'         =>  @$value['video']['speed']?:6,
                                    'delay_frames'  =>  @$value['video']['delay_frames']?:60,
                                    'profile'       =>  @$value['video']['profile']?:4,
                                    'level'         =>  @$value['video']['level']?:30,
                                    'definterlace'  =>  @$value['video']['definterlace']?:0,
                                    'interlace'     =>  @$value['video']['interlace']?:0,
                                    'ref_frames'    =>  @$value['video']['ref_frames']?:0,
                                    'bframes'       =>  @$value['video']['bframes']?:-1,
                                    'vbv_buffer'    =>  @$value['video']['vbv_buffer']?:10,
                                    'b_aud'         =>  @$value['video']['b_aud']?:0,
                                    'slice_frames'  =>  @$value['video']['slice_frames']?:-1,
                                    'decode_threads'=>  @$value['video']['decode_threads']?:8,
                                    'sps_id'        =>  @$value['video']['sps_id']?:-1,
                                    'OptimizeMode'  =>  @$value['video']['OptimizeMode']?:0,
                                    'spsid_mode'    =>  @$value['video']['spsid_mode']?:0
                                ),
                                'AudioParam'    =>  '',
                                'FileParam' =>  array(
                                    'HintTrackValue'    =>  @$value['file']['HintTrackValue']?:0,
                                    'CreateStreamIndex' =>  @$value['file']['CreateStreamIndex']?:0
                                )*/
                            ),
                            'ECDATA' => ''
                        )
                    )
                );
                if(@$value['water']['file']){
                    $obj[$key]['CodecParam']['FilterParam'] = array(
                        'FilterItem'    => array(
                            'FilterType'    => '水印叠加',
                            'FilterSummary' => '叠加图片路径:'.$value['water']['imageUrl'].' 叠加横坐标:'.$value['water']['startX'].' 叠加纵坐标:'.$value['water']['startY'].' 水印宽度:'.$value['water']['objWidth'].' 水印高度:'.$value['water']['objHeight'],
                            'FilterItemParam'   => array(
                                'PicOverParam'      => array(
                                    'PicPath'           => $value['water']['imageUrl'],
                                    'StartX'            => $value['water']['startX'],
                                    'StartY'            => $value['water']['startY'],
                                    'ObjWidth'          => $value['water']['objWidth'],
                                    'ObjHeight'         => $value['water']['objHeight'],
                                    )
                                )
                            )
                        );
                }
                foreach($this->specialParamKey as $sKey => $sValue){
                    foreach($sValue as $sonValue){
                        if(isset($value[$sKey][$sonValue])){
                            $specialParam[$this->MpcSpecialParamKey[$sKey]][$sonValue] = $value[$sKey][$sonValue];
                        }
                    }
                }
                $obj[$key]['CodecParam']['SpecialParam']['SpecialParam'] = $specialParam;
            }

            $req_data['MPCWebCmd'][__FUNCTION__]['MPC']['Content'][__FUNCTION__]['JobList'] = array(
                'StepID'    =>  0,
                'JobType'   =>  'nmfastsplit',
                'JobInfo'   =>  array(
                    'Scope'     =>  'tv_nmfastsplitjobparam2',
                    'Schema'    =>  'sbnmfastsplitjobparam2.xsd',
                    'Data'      =>  array(
                        'NMFastSplitParam2' =>  array(
                            'Src'   =>  array(
                                'GroupType'     =>  'S',
                                'VideoMedia'    =>  'V',
                                'AudioMedia'    =>  array(
                                    'MediaType'     =>  'V',
                                    'UseChannels'   =>  0,
                                    'MapAsTracks'   =>  0
                                )
                            ),
                            'Option'    =>  array(
                                'DeleteSource'  =>  0,
                                'OnTargetExist' =>  1,
                                'AddCleanInfo'  =>  0,
                                'AnalyzeTarget' =>  0,
                                'CreateOtcFile' =>  -1,
                                'UseSDKType'    =>  3
                            ),
                            'Obj'   =>  $obj,
                            'SplitRule' =>  array(
                                'TmpFilePath'   =>  TMP_FILE_PATH,
                                'SplitTime'     =>  @$settings['split']?:10,
                                'MinSplitTime'  =>  5,
                                'MediaFileType' =>  @$settings['split']?1:0,
                                'IsSplit'       =>  @$settings['IsSplit']?:0       //标示素材类型是不是DVD 1是 0否
                            )
                        )
                    )
                ),
                'NotifyMask'    =>  268435455,
                'PRI'           =>  0,
                'Bookto'        =>  '*',
                'AtomJob'       =>  'No'
            );
        }

        foreach($files as $file){
            $OutPoint = (@$settings['OutPoint'])?:-1;
            $req_data['MPCWebCmd'][__FUNCTION__]['MPC']['Content'][__FUNCTION__]['MediaFile'][] = array(
                    'GroupType' =>  'S',
                    'MediaType' =>  'V',
                    'FileName'  =>  $file,
                    'InPoint'   =>  0,
                    'OutPoint'  =>  $OutPoint
                );
        }

        if(isset($settings['platFormID']) || isset($settings['metaData'])){
            $req_data['MPCWebCmd'][__FUNCTION__]['MPC']['Content'][__FUNCTION__]['DocumentInfo'] = '';
            $req_data['MPCWebCmd'][__FUNCTION__]['MPC']['Content'][__FUNCTION__]['AddMediaInfo'] = '';

            $req_xml = Xml::fromArray($req_data)->asXML();

            $pgmId = strtoupper(String::uuid());
            $documentInfo = $this->documentXML($pgmId, $settings);
            $result = str_replace('<DocumentInfo/>', $documentInfo, $req_xml);

            if(isset($settings['platFormID'])){
                $platformInfo = $this->platformInfoXML($settings['platFormID']);
                $result = str_replace('<NewMediaBaseInfo/>', $platformInfo, $result);
                $mediaInfo = $this->addMediaInfo($settings);
                $result = str_replace('<AddMediaInfo/>', $mediaInfo, $result);
            }

            if(isset($settings['metaData'])){
                $editcatalog = $this->editcatalogXML($settings['metaData'],$pgmId, $settings);
                $result = str_replace('<EDITCATALOG/>', $editcatalog, $result);
            }

        }else{
            $result = Xml::fromArray($req_data)->asXML();
        }

        return array('taskGUID'=>$taskGUID,'result'=>$this->docommit(__FUNCTION__, array(), $result));
    }

    public function GetSvcList()
    {
        return $this->docommit(__FUNCTION__);
    }

    /**
     * 扩充taskinfo节点内容
     * @param  array $pgmId 平台ID
     * @param  array $settings 转码配置
     * @return void
     */
    private function documentXML($pgmId, $settings){
        $columnInfo = explode(',',AUDIT_DEFAULT_COLUMN);

        $xml = array('TaskInfo'=>array(
            'Scope'     =>  'DocumentInfo',
            'Schema'    =>  '',
            'Data'      =>  array(
                'DocumentInfo'  =>  array(
                    'PGMID' =>  $pgmId,
                    'PGMNAME' => $settings['taskName']?:$pgmId,
                    'PGMFILE' => '',
                    'PGMLENGTH' => '',
                    'CLIPIN' => 0,
                    'USERID' => '',
                    'GUIDELENGTH' => '',
                    'GUIDETAIL' => '',
                    'DOCTAIL' => '',
                    'DOCID' => '',
                    'DOCLENGTH' => '',
                    'IPADDR' => '',
                    'COLUMNID' => $columnInfo[0],
                    'COLUMNNAME' => $columnInfo[1],
                    'CATALOGNAME' => '',
                    'CATALOGID' => '',
                    'PUBLISHTYPE' => 'xBuilderSystem',
                    'PUBLISHTERNIMER' => '',
                    'SrcMediaType'  =>  'HighCode,',
                    'NewMediaBaseInfo'  =>  '',
                    'EDITCATALOG'   =>  ''
                )
            )));

        return str_replace("\n","",str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', Xml::fromArray($xml)->asXML()));
    }

    /**
     * 生成下发平台相关XML
     * @param  array  $platFormID 下发平台ID
     * @return string             生成的XML
     */
    private function platformInfoXML($platFormID = array()){
        if(!$platFormID) return '';

        $platArray = explode('|',PUBLISH_PLATFORMS);
        foreach ($platArray as $key=>$plat) {
            $tmp = explode(',', $plat);

            $platform[$key] = array('PlatFormID'=>$tmp[0],'PlatFormName'=>$tmp[1]);

            if(in_array($tmp[0], $platFormID)){
                $platform[$key]['IsSelected'] = 1;
            }else{
                $platform[$key]['IsSelected'] = 0;
            }
        }
        $xml = array('NewMediaBaseInfo'=>array('PlatFormInfos'=>array('PlatFormInfo'=>$platform)));

        return str_replace("\n","",str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', Xml::fromArray($xml)->asXML()));
    }

    /**
     * 生成编目数据相关XML
     * @param  array  $metaData 编目数据
     * @param  string $pgmId    平台ID
     * @param  array  $settings 任务配置信息
     * @return string           生成的XML
     */
    private function editcatalogXML($metaData = array(), $pgmId, $settings){
        if(!$metaData) return '';

        $xml = array(
            'EDITCATALOG'=>array(
                'SYSTEM' => 'xBuilder',
                'PGMGUID' => $pgmId,
                'AttributeItem' => array()
            )
        );

        $publicTime = date('Y-m-d H:i:s');
        $attribute = array(
            '0' =>  array(
                'ItemCode'  => 'Title',
                'ItemName'  => '节目名称',
                'Value'     =>  $settings['taskName']?:$pgmId
            ),
            '1' =>  array(
                'ItemCode'  => 'PgmLength',
                'ItemName'  => '节目长度',
                'Value'     =>  '1500'
            ),
            '2' =>  array(
                'ItemCode'  => 'ColumnName',
                'ItemName'  => '栏目名称',
                'Value'     =>  '测试栏目1'
            ),
            '3' =>  array(
                'ItemCode'  => 'Name',
                'ItemName'  => '素材标题',
                'Value'     =>  ''
            ),
            '4' =>  array(
                'ItemCode'  => 'SearchName',
                'ItemName'  => '搜索名称',
                'Value'     =>  ''
            ),
            '5' =>  array(
                'ItemCode'  => 'KeyWords',
                'ItemName'  => '关键词',
                'Value'     =>  ''
            ),
            '6' =>  array(
                'ItemCode'  => 'PgmNote',
                'ItemName'  => '节目描述',
                'Value'     =>  '1234567908'
            ),
            '7' =>  array(
                'ItemCode'  => 'Description',
                'ItemName'  => '节目描述',
                'Value'     =>  ''
            ),
            '8' =>  array(
                'ItemCode'  => 'PgmType',
                'ItemName'  => '节目类型',
                'Value'     =>  '其他节目'
            ),
            '9' =>  array(
                'ItemCode'  => 'CatalogType',
                'ItemName'  => '分类',
                'Value'     =>  '索贝视频@东北戏曲'
            ),
            '10' =>  array(
                'ItemCode'  => 'VoiceTimeCode',
                'ItemName'  => '语音识别',
                'Value'     =>  array('CDATA'=>'','ECDATA'=>'')
            ),
            '11' =>  array(
                'ItemCode'  => 'EmergencyWorkFlow',
                'ItemName'  => '紧急流程',
                'Value'     =>  '0'
            ),
            '12' =>  array(
                'ItemCode'  => 'PgmSource',
                'ItemName'  => '来源渠道',
                'Value'     =>  'xBuilder'
            ),
            '13' =>  array(
                'ItemCode'  => 'ProduceTime',
                'ItemName'  => '拆编时间',
                'Value'     =>  $publicTime
            ),
            '14' =>  array(
                'ItemCode'  => 'Producer',
                'ItemName'  => '拆编人员',
                'Value'     =>  'xBuilder'
            ),
            '15' =>  array(
                'ItemCode'  => 'ProducerUserCode',
                'ItemName'  => '拆编人员Code',
                'Value'     =>  'xBuilder'
            ),
            '16' =>  array(
                'ItemCode'  => 'OperationLog',
                'ItemName'  => '操作日志',
                'Value'     =>  '人员:xBuilder '.$publicTime.' 完成拆条编目操作'
            ),
            '17' =>  array(
                'ItemCode'  => 'ActorDisplay',
                'ItemName'  => '演职人员',
                'Value'     =>  ''
            ),
            '18' =>  array(
                'ItemCode'  => 'Director',
                'ItemName'  => '导演',
                'Value'     =>  ''
            ),
            '19' =>  array(
                'ItemCode'  => 'SubTitle',
                'ItemName'  => '副标题',
                'Value'     =>  ''
            ),
            '20' =>  array(
                'ItemCode'  => 'Source',
                'ItemName'  => '来源',
                'Value'     =>  ''
            ),
            '21' =>  array(
                'ItemCode'  => 'Author',
                'ItemName'  => '作者',
                'Value'     =>  ''
            ),
            '22' =>  array(
                'ItemCode'  => 'SourceFrom',
                'ItemName'  => '节目来源',
                'Value'     =>  '详细信息'
            ),
            '23' =>  array(
                'ItemCode'  => 'Series',
                'ItemName'  => '剧集',
                'Value'     =>  '0'
            )
        );

        $metaDataKey = array_keys($metaData);
        foreach($attribute as $key=>$attr){
            if(in_array($attr['ItemCode'], $metaDataKey)){
                $attribute[$key]['Value'] = $metaData[$attr['ItemCode']];
            }
        }

        $xml['EDITCATALOG']['AttributeItem'] = $attribute;
        return str_replace("\n","",str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', Xml::fromArray($xml)->asXML()));
    }

    /**
     * 添加媒资入库信息
     * @param array $settings 任务设置
     */
    public function addMediaInfo($settings = array()){
        $clipGuid = strtolower(str_replace('-', '', String::uuid()));
        $publicTime = date('Y-m-d H:i:s');
        $xml = array(
            'TaskInfo'=>array(
                'Scope'=>'tv_SobeyExchangeProtocal',
                'Schema'=>'',
                'Data'=>array(
                    'UnifiedContentDefine'=>array(
                        'Header'=>array(
                            'TaskGUID'=>'',
                            'TaskCurrentGUID'=>'',
                            'TaskPreStepGUID'=>'',
                            'UserToken'=>'',
                            'SourceSystemID'=>''
                            ),
                        'SourceInfo'=>array(
                            'MSB-UnifiedContentImport-1.0'=>'',
                            'SourceSystem'=>'xBuilder',
                            'SourceAction'=>'AddTask',
                            'SourceIP'=>'',
                            'SourceMachineName'=>'xBuilder'
                            ),
                        'ContentInfo'=>array(
                            'ContentID'=>'',
                            'ContentData'=>array(
                                'ContentFile'=>'',
                                'EntityData'=>array(
                                    'TypeID'=>'Clip',
                                    'TypeName'=>'素材',
                                    'AttributeItem'=>array(
                                        '0'=>array(
                                            'ItemCode'=>'ClipGuid',
                                            'ItemName'=>'素材Guid',
                                            'Value'=>$clipGuid
                                        ),
                                        '1'=>array(
                                            'ItemCode'=>'ClipName',
                                            'ItemName'=>'素材名称',
                                            'Value'=>$settings['taskName']?:$clipGuid
                                        ),
                                        '2'=>array(
                                            'ItemCode'=>'CreatorCode',
                                            'ItemName'=>'创建人编码',
                                            'Value'=>'xBuilder'
                                        ),
                                        '3'=>array(
                                            'ItemCode'=>'CreatorName',
                                            'ItemName'=>'创建人姓名',
                                            'Value'=>'xBuilder'
                                        ),
                                        '4'=>array(
                                            'ItemCode'=>'CreateDate',
                                            'ItemName'=>'创建日期',
                                            'Value'=>$publicTime
                                        ),
                                        '5'=>array(
                                            'ItemCode'=>'Inpoint',
                                            'ItemName'=>'入点',
                                            'Value'=>'0'
                                        ),
                                        '6'=>array(
                                            'ItemCode'=>'OutPoint',
                                            'ItemName'=>'出点',
                                            'Value'=>(@$settings['OutPoint'])?:-1
                                        ),
                                        '7'=>array(
                                            'ItemCode'=>'TotalLength',
                                            'ItemName'=>'长度',
                                            'Value'=>(@$settings['OutPoint'])?:-1
                                        ),
                                        '8'=>array(
                                            'ItemCode'=>'ClassificationName',
                                            'ItemName'=>'分类名称',
                                            'Value'=>''
                                        ),
                                        '9'=>array(
                                            'ItemCode'=>'CREATEORDES',
                                            'ItemName'=>'创建者描述',
                                            'Value'=>''
                                        ),
                                        '10'=>array(
                                            'ItemCode'=>'TYPEIN',
                                            'ItemName'=>'磁带入点',
                                            'Value'=>''
                                        ),
                                        '11'=>array(
                                            'ItemCode'=>'TYPEOUT',
                                            'ItemName'=>'磁带出点',
                                            'Value'=>(@$settings['OutPoint'])?:-1
                                        ),
                                        '12'=>array(
                                            'ItemCode'=>'TYPENAME',
                                            'ItemName'=>'磁带名称',
                                            'Value'=>''
                                        ),
                                        '13'=>array(
                                            'ItemCode'=>'INFODOCID',
                                            'ItemName'=>'文稿ID',
                                            'Value'=>''
                                        )
                                    )
                                )
                            )
                        ),
                        'ProcessInfo'=>array(
                            'ProcessID'=>'MAM4S6Import',
                            'ProcessName'=>'DCMImport'
                        )
                    )
                )
            )
        );
        return str_replace("\n","",str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', Xml::fromArray($xml)->asXML()));
    }

    /**
     * 构造发送的XML
     * @param  string $CommandType 要执行的操作/函数
     * @param  array  $CommandArgs 操作附带的参数
     * @return string              协议XML字符串
     */
    public function buildXml($CommandType, $CommandArgs=array()) {
        if(!$CommandType) return false;

        $req_data = array(
            'MPCWebCmd' =>  array(
                'CommandType'   =>  $CommandType
            )
        );

        if($CommandArgs){
            $req_data['MPCWebCmd'][$CommandType] = $CommandArgs;
        }

        return Xml::fromArray($req_data)->asXML();
    }

    /**
     * 执行发送和返回状态检测
     * @param  string $CommandType 要执行的操作/函数
     * @param  array  $CommandArgs 操作附带的参数
     * @return boolean             执行操作结果
     */
    public function docommit($CommandType, $CommandArgs=array(), $req_data = null) {
        if(!$CommandType) return false;

        if(!$req_data){
            $req_data = $this->buildXml($CommandType, $CommandArgs);
        }
        $req_data = str_replace('<CDATA/>', '<![CDATA[', $req_data);
        $req_data = str_replace('<ECDATA/>', ']]>', $req_data);
        //$file = fopen('Baowen.xml', 'w');
        //fwrite($file, $req_data);
        //fclose($file);
        //$this->log($req_data,'MpcClient');
        try {
            $returnXmlContent = $this->soapClient->mpccommit($req_data);
            //$this->log($returnXmlContent, 'MpcClient');
            $xmlObject = Xml::build($returnXmlContent);

            //检测错误
            if ($xmlObject->Rtn_Unknow && (int)$xmlObject->Rtn_Unknow->MPC_Status->Status) {
                $this->setError((string)$xmlObject->Rtn_Unknow->MPC_Status->Remark);
                return false;
            } 
            elseif (intval($xmlObject->{"Rtn_".$CommandType}->MPC_Status->Status) == 2) {
                $this->setError((string)$xmlObject->{"Rtn_".$CommandType}->MPC_Status->Remark);
                return false;
            }
            return Xml::toArray($xmlObject);
        }
        catch (SoapFault $e) {
            $this->setError("SOAP Error: ".$e->getMessage()." (".$this->wsdl.")");
            return false;
        }
    }

    /**
     * 错误处理函数
     * @param string  $info 错误描述
     * @param integer $code 错误代码
     */
    private function setError($info, $code = 1)
    {
        $this->log($info, 'MpcClient');
    }
}
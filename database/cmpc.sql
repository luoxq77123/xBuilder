/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50616
Source Host           : localhost:3306
Source Database       : cmpc

Target Server Type    : MYSQL
Target Server Version : 50616
File Encoding         : 65001

Date: 2014-08-07 16:53:29
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `cmpc_categories`
-- ----------------------------
DROP TABLE IF EXISTS `cmpc_categories`;
CREATE TABLE `cmpc_categories` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '素材分类ID',
  `parent_id` int(20) NOT NULL DEFAULT '0' COMMENT '父级id',
  `name` varchar(125) NOT NULL COMMENT '分类名称',
  `sort` smallint(6) NOT NULL DEFAULT '0' COMMENT '栏目排序',
  `lft` int(10) DEFAULT NULL COMMENT '树形结构特殊字段',
  `rght` int(10) DEFAULT NULL COMMENT '树形结构特殊字段',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='素材分类表';

-- ----------------------------
-- Records of cmpc_categories
-- ----------------------------
INSERT INTO `cmpc_categories` VALUES ('1', '0', '默认分类', '0', '1', '2');

-- ----------------------------
-- Table structure for `cmpc_configs`
-- ----------------------------
DROP TABLE IF EXISTS `cmpc_configs`;
CREATE TABLE `cmpc_configs` (
  `id` int(20) NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `type` varchar(256) NOT NULL COMMENT '配置项名称(英文)',
  `name` varchar(256) NOT NULL COMMENT '配置项名称(中文)',
  `value` text NOT NULL COMMENT '配置项值',
  `created` datetime DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COMMENT='参数配置表';

-- ----------------------------
-- Records of cmpc_configs
-- ----------------------------
INSERT INTO `cmpc_configs` VALUES ('3', 'systemPermissions', '系统功能选项', '{\"1\":\"\\u89c6\\u9891\\u7ba1\\u7406\",\"2\":\"\\u56de\\u6536\\u7ad9\",\"3\":\"\\u6a21\\u677f\\u7ba1\\u7406\",\"4\":\"\\u7528\\u6237\\u7ba1\\u7406\",\"5\":\"\\u7cfb\\u7edf\\u65e5\\u5fd7\"}', '2012-05-28 14:29:18');
INSERT INTO `cmpc_configs` VALUES ('5', 'categoryPermissions', '栏目功能选项', '{\"1\":\"\\u6d4f\\u89c8\",\"2\":\"\\u89c6\\u9891\\u5904\\u7406\",\"3\":\"\\u5bf9\\u5916\\u4e0b\\u8f7d\",\"4\":\"\\u521b\\u5efa\\u5b50\\u5206\\u7c7b\",\"5\":\"\\u5220\\u9664\\u5206\\u7c7b\",\"6\":\"\\u7f16\\u8f91\\u5206\\u7c7b\"}', '2012-05-07 14:34:50');
INSERT INTO `cmpc_configs` VALUES ('6', 'cookieName', '前台COOKIE前缀', 'cmpc_sobey', '2012-05-10 15:23:51');
INSERT INTO `cmpc_configs` VALUES ('7', 'waterUploadPath', '水印文件上传目录', 'files\\source', '2012-05-15 15:37:01');
INSERT INTO `cmpc_configs` VALUES ('8', 'videoUploadPath', '视频文件上传目录', 'E:\\Clips', '2012-05-18 16:25:21');
INSERT INTO `cmpc_configs` VALUES ('9', 'defaultTranscodeParams', '默认编码模板', '[{\"title\":\"\\u6d41\\u7545\",\"params\":\"{\\\"Transcode\\\":{\\\"type\\\":\\\"1\\\",\\\"ImageWidth\\\":\\\"1024\\\",\\\"ImageHeight\\\":\\\"768\\\",\\\"ConvertModel\\\":\\\"Stretch\\\",\\\"VideoFormat\\\":\\\"H264\\\",\\\"BitRate\\\":\\\"100000\\\",\\\"FileFormat\\\":\\\"MP4\\\",\\\"FrameRate\\\":\\\"25\\\",\\\"KeyFrameRate\\\":\\\"25\\\",\\\"AudioFormat\\\":\\\"AAC\\\",\\\"SamplesPerSec\\\":\\\"48000\\\",\\\"BitsPerSample\\\":\\\"0\\\",\\\"fpCheck\\\":\\\"on\\\",\\\"SliceTime\\\":\\\"3000\\\"}}\"},{\"title\":\"\\u6d41\\u7545ios\",\"params\":\"{\\\"Transcode\\\":{\\\"type\\\":\\\"1\\\",\\\"ImageWidth\\\":\\\"1024\\\",\\\"ImageHeight\\\":\\\"768\\\",\\\"ConvertModel\\\":\\\"Stretch\\\",\\\"VideoFormat\\\":\\\"H264\\\",\\\"BitRate\\\":\\\"200000\\\",\\\"FileFormat\\\":\\\"MP4\\\",\\\"FrameRate\\\":\\\"25\\\",\\\"KeyFrameRate\\\":\\\"25\\\",\\\"AudioFormat\\\":\\\"AAC\\\",\\\"SamplesPerSec\\\":\\\"48000\\\",\\\"BitsPerSample\\\":\\\"0\\\",\\\"fpCheck\\\":\\\"on\\\",\\\"SliceTime\\\":\\\"3000\\\"}}\"},{\"title\":\"\\u6807\\u6e05\",\"params\":\"{\\\"Transcode\\\":{\\\"type\\\":\\\"1\\\",\\\"ImageWidth\\\":\\\"1024\\\",\\\"ImageHeight\\\":\\\"768\\\",\\\"ConvertModel\\\":\\\"Stretch\\\",\\\"VideoFormat\\\":\\\"H264\\\",\\\"BitRate\\\":\\\"450000\\\",\\\"FileFormat\\\":\\\"MP4\\\",\\\"FrameRate\\\":\\\"25\\\",\\\"KeyFrameRate\\\":\\\"25\\\",\\\"AudioFormat\\\":\\\"AAC\\\",\\\"SamplesPerSec\\\":\\\"48000\\\",\\\"BitsPerSample\\\":\\\"0\\\",\\\"fpCheck\\\":\\\"on\\\",\\\"SliceTime\\\":\\\"3000\\\"}}\"},{\"title\":\"\\u6807\\u6e05ios\",\"params\":\"{\\\"Transcode\\\":{\\\"type\\\":\\\"1\\\",\\\"ImageWidth\\\":\\\"1024\\\",\\\"ImageHeight\\\":\\\"768\\\",\\\"ConvertModel\\\":\\\"Stretch\\\",\\\"VideoFormat\\\":\\\"H264\\\",\\\"BitRate\\\":\\\"800000\\\",\\\"FileFormat\\\":\\\"MP4\\\",\\\"FrameRate\\\":\\\"25\\\",\\\"KeyFrameRate\\\":\\\"25\\\",\\\"AudioFormat\\\":\\\"AAC\\\",\\\"SamplesPerSec\\\":\\\"48000\\\",\\\"BitsPerSample\\\":\\\"0\\\",\\\"fpCheck\\\":\\\"on\\\",\\\"SliceTime\\\":\\\"3000\\\"}}\"},{\"title\":\"\\u9ad8\\u6e05\",\"params\":\"{\\\"Transcode\\\":{\\\"type\\\":\\\"1\\\",\\\"ImageWidth\\\":\\\"1024\\\",\\\"ImageHeight\\\":\\\"768\\\",\\\"ConvertModel\\\":\\\"Stretch\\\",\\\"VideoFormat\\\":\\\"H264\\\",\\\"BitRate\\\":\\\"1000000\\\",\\\"FileFormat\\\":\\\"MP4\\\",\\\"FrameRate\\\":\\\"25\\\",\\\"KeyFrameRate\\\":\\\"25\\\",\\\"AudioFormat\\\":\\\"AAC\\\",\\\"SamplesPerSec\\\":\\\"48000\\\",\\\"BitsPerSample\\\":\\\"0\\\",\\\"fpCheck\\\":\\\"on\\\",\\\"SliceTime\\\":\\\"3000\\\"}}\"}]', '2012-05-15 15:26:16');
INSERT INTO `cmpc_configs` VALUES ('11', 'pageSize', '翻页单页量', '25', '2012-05-15 10:09:56');
INSERT INTO `cmpc_configs` VALUES ('12', 'imagePathFormatPrefix', '转码生成图片路径前缀', 'V:\\\\cmpc\\\\desc\\\\', '2012-05-28 14:29:18');
INSERT INTO `cmpc_configs` VALUES ('13', 'filePathFormatPrefix', '转码生成目标文件路径前缀', 'E:\\\\', '2012-05-28 14:29:18');
INSERT INTO `cmpc_configs` VALUES ('14', 'videoPlayUrlPre', '视频前台播放前缀', 'http://113.142.30.179/desc/cmpc/desc', '2012-05-28 14:29:18');
INSERT INTO `cmpc_configs` VALUES ('15', 'cmpc_notify_addresss', 'CMPC前台地址（后台更改用户、配置通知使用）', 'http://www.cmpc.dev/', '2012-06-14 19:37:50');
INSERT INTO `cmpc_configs` VALUES ('16', 'transFormService', 'CMPC后台接口地址', 'http://172.28.28.190:8080/services/CMPCService?wsdl', '2012-05-28 14:29:18');
INSERT INTO `cmpc_configs` VALUES ('17', 'videoImageUrl', '前台视频缩略图访问前缀', 'http://113.142.30.179/desc/cmpc/desc', '2012-05-28 14:29:18');
INSERT INTO `cmpc_configs` VALUES ('18', 'videoPreviewTranscodingDataConfiguration', '视频预览转码数据配置', '{\"WatermarkFlag\":\"0\",\"StartX\":\"121\",                                            \"StartY\":\"105\",                                            \"ObjWidth\":\"20\",                                            \"ObjHeight\":\"20\",                                            \"PicPath\":\"C:\\\\1.tga\",                                            \"VideoFormat\":\"H264\",                                            \"BitRate\":\"750\",                                            \"FrameRate\":\"25\",                                            \"ImageWidth\":\"640\",                                            \"ImageHeight\":\"360\",                                            \"AudioFormat\":\"AAC\",                                            \"KeyFrameRate\":\"12\",                                            \"ReplaceByMainFormat\":\"48000\",                              \"BitsPerSample\":\"16\",              \"FileFormat\":\"MP4\",                                            \"SpecialParam\":\"<![CDATA[<SpecialParam><VideoParam><sar_width>1<\\/sar_width><sar_height>1<\\/sar_height><speed>6<\\/speed><delay_frames>60<\\/delay_frames><profile>0<\\/profile><level>30<\\/level><definterlace>1<\\/definterlace><interlace>0<\\/interlace><ref_frames>0<\\/ref_frames><bframes>-1<\\/bframes><vbv_buffer>40<\\/vbv_buffer><b_aud>0<\\/b_aud><slice_frames>-1<\\/slice_frames><decode_threads>8<\\/decode_threads><sps_id>-1<\\/sps_id><OptimizeMode>0<\\/OptimizeMode><fieldresmple>0<\\/fieldresmple><\\/VideoParam><AudioParam><Bitrate>128000<\\/Bitrate><encoderversion>0<\\/encoderversion><profile>2<\\/profile><bitstreamoutputformat>1<\\/bitstreamoutputformat><\\/AudioParam><FileParam><HintTrackValue>0<\\/HintTrackValue><CreateStreamIndex>1<\\/CreateStreamIndex><\\/FileParam><\\/SpecialParam>]]>\"}', '2012-05-28 14:29:18');
INSERT INTO `cmpc_configs` VALUES ('19', 'videoDownload', '视频下载前缀', 'http://113.142.30.179/desc/cmpc/desc', '2012-05-28 14:29:18');
INSERT INTO `cmpc_configs` VALUES ('20', 'replayUploadVideoPath', '提交CMPC视频文件上传替换路径', 'F:', '2012-05-28 14:29:18');
INSERT INTO `cmpc_configs` VALUES ('21', 'replayUploadWaterPath', '提交CMPC水印文件上传替换路径', 'V:\\cmpc\\source', '2012-05-28 14:29:18');
INSERT INTO `cmpc_configs` VALUES ('22', 'audioPreviewTranscodingDataConfiguration', '音频预览转码数据配置', '{\"WatermarkFlag\":\"0\",\"StartX\":\"\",\"StartY\":\"\",\"ObjWidth\":\"\",\"ObjHeight\":\"\",\"PicPath\":\"\",\"VideoFormat\":\"\",\"BitRate\":\"120\",\"FrameRate\":\"25\",\"ImageWidth\":\"\",\"ImageHeight\":\"\",\"AudioFormat\":\"MP3\",\"KeyFrameRate\":\"12\",\"ReplaceByMainFormat\":\"48000\",\"FileFormat\":\"MP3\",\"SpecialParam\":\"<![CDATA[<SpecialParam><VideoParam\\/><AudioParam><Bitrate>48000<\\/Bitrate><\\/AudioParam><FileParam\\/><\\/SpecialParam>]]>\"}', '2012-05-28 14:29:18');
INSERT INTO `cmpc_configs` VALUES ('23', 'uploadVideoFileFormat', '上传视频文件格式', '.mp4|.avi|.flv|.mxf|.tp|.ts|.rm|.wmv|.dat|.vob|.mts|.3gp|.mov|.m4v|.mkv|.mpg|.swf|.m2p|.f4v|.rmvb|.m2ts|.mpeg', '2012-05-28 14:29:18');
INSERT INTO `cmpc_configs` VALUES ('24', 'uploadAudioFileFormat', '上传音频文件格式', '.mp3|.mp2|.wma|.aac', '2012-05-28 14:29:18');
INSERT INTO `cmpc_configs` VALUES ('25', 'uploadImage', '水印上传格式', '.tga', '2012-11-28 07:46:14');
INSERT INTO `cmpc_configs` VALUES ('26', 'ftpMessage', 'FTP服务器信息', '{\"ftpaddress\":\"172.20.13.90\",\"ftpuser\":\"cmpc\",\"ftppass\":\"cmpc\"}', '2012-12-19 02:50:55');
INSERT INTO `cmpc_configs` VALUES ('27', 'transformVideoPath', '转码后视频文件本地映射路径', 'F:', '2012-12-24 10:18:44');
INSERT INTO `cmpc_configs` VALUES ('28', 'transformImagePath', '转码后图片文件本地映射路径', 'F:', '2012-12-24 10:19:19');
INSERT INTO `cmpc_configs` VALUES ('29', 'mpcWebService', 'MPC接口地址', 'http://172.16.146.28:8088', '2014-08-01 15:16:18');
INSERT INTO `cmpc_configs` VALUES ('30', 'mpcCallback', 'MPC回调地址', 'http://www.cmpc.dev/webservices/callback', '2014-08-01 15:16:50');
INSERT INTO `cmpc_configs` VALUES ('31', 'pathFormat', '文件生成路径规则', 'E:\\?filetype_?mediatype\\?taskguid\\?taskname_?putin.?ext', '2014-08-07 15:21:38');
INSERT INTO `cmpc_configs` VALUES ('32', 'tmpFilePath', '分片临时文件路径', 'E:\\?filetype_?mediatype\\Tmp\\', '2014-08-07 15:23:15');

-- ----------------------------
-- Table structure for `cmpc_contents`
-- ----------------------------
DROP TABLE IF EXISTS `cmpc_contents`;
CREATE TABLE `cmpc_contents` (
  `id` varchar(40) NOT NULL COMMENT '视频ID',
  `category_id` int(20) NOT NULL COMMENT '视频分类ID',
  `transcode_group_id` int(20) NOT NULL COMMENT '转码组ID',
  `task_id` varchar(40) DEFAULT NULL COMMENT 'MPC任务ID',
  `user_id` int(20) NOT NULL COMMENT '添加用户ID',
  `title` varchar(256) NOT NULL COMMENT '视频名称',
  `descriptions` varchar(3000) DEFAULT NULL COMMENT '资源描述',
  `type` smallint(6) DEFAULT '1' COMMENT '素材类型 1为视频 2为音频',
  `status` smallint(6) NOT NULL DEFAULT '1' COMMENT '内容状态 1待转码 2转码完成 3转码失败',
  `isdelete` smallint(6) NOT NULL DEFAULT '0' COMMENT '回收站状态 0原始状态 1回收站 2彻底删除',
  `created` datetime DEFAULT NULL COMMENT '创建时间',
  `modified` datetime DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='素材表';

-- ----------------------------
-- Records of cmpc_contents
-- ----------------------------
INSERT INTO `cmpc_contents` VALUES ('53E33C1A-8668-454E-B7A6-0C486C7875F8', '1', '1', '53E33C1B-E614-4714-B6BA-0C486C7875F8', '1', '1', null, '1', '1', '0', '2014-08-07 16:43:07', '2014-08-07 16:43:28');

-- ----------------------------
-- Table structure for `cmpc_images`
-- ----------------------------
DROP TABLE IF EXISTS `cmpc_images`;
CREATE TABLE `cmpc_images` (
  `Id` int(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '关键帧ID',
  `content_id` varchar(40) NOT NULL COMMENT '素材ID',
  `fileName` varchar(256) DEFAULT NULL COMMENT '文件名',
  `fileUrl` varchar(256) NOT NULL COMMENT '文件访问地址',
  `filePath` varchar(256) NOT NULL COMMENT '文件物理地址',
  `created` datetime DEFAULT NULL COMMENT '创建时间',
  `modified` datetime DEFAULT NULL COMMENT '修改时间',
  `is_keyFrame` int(1) DEFAULT NULL COMMENT '是否关键帧（1：是；0：否）',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='素材关键帧表';

-- ----------------------------
-- Records of cmpc_images
-- ----------------------------

-- ----------------------------
-- Table structure for `cmpc_operations`
-- ----------------------------
DROP TABLE IF EXISTS `cmpc_operations`;
CREATE TABLE `cmpc_operations` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `parent_id` int(11) DEFAULT NULL COMMENT '父级ID',
  `controller` varchar(255) DEFAULT NULL COMMENT '控制器名',
  `action` varchar(255) DEFAULT NULL COMMENT '方法名',
  `name` varchar(255) DEFAULT NULL COMMENT '权限名称',
  `created` datetime DEFAULT NULL COMMENT '创建时间',
  `modified` datetime DEFAULT NULL COMMENT '修改时间',
  `lft` int(11) DEFAULT NULL COMMENT '拓扑图左路径',
  `rght` int(11) DEFAULT NULL COMMENT '拓扑图右路径',
  `sort` int(11) DEFAULT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='操作权限表';

-- ----------------------------
-- Records of cmpc_operations
-- ----------------------------

-- ----------------------------
-- Table structure for `cmpc_permissions`
-- ----------------------------
DROP TABLE IF EXISTS `cmpc_permissions`;
CREATE TABLE `cmpc_permissions` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `category_id` int(20) unsigned NOT NULL COMMENT '栏目ID',
  `role_id` int(20) unsigned NOT NULL COMMENT '角色ID',
  `permissions` varchar(2400) DEFAULT NULL COMMENT '权限数据（JSON编码）',
  PRIMARY KEY (`id`,`category_id`,`role_id`),
  KEY `fk_role_a` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='栏目权限表';

-- ----------------------------
-- Records of cmpc_permissions
-- ----------------------------
INSERT INTO `cmpc_permissions` VALUES ('1', '1', '1', '1,2,3,4,5,6');

-- ----------------------------
-- Table structure for `cmpc_roles`
-- ----------------------------
DROP TABLE IF EXISTS `cmpc_roles`;
CREATE TABLE `cmpc_roles` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '角色ID',
  `name` varchar(60) NOT NULL COMMENT '角色名称',
  `sort` int(4) NOT NULL DEFAULT '0' COMMENT '排序字段',
  `default_template_id` int(3) DEFAULT NULL COMMENT '默认模板组ID',
  `template_accesses` varchar(2400) DEFAULT NULL COMMENT '模板权限',
  `operation_accesses` varchar(2400) DEFAULT NULL COMMENT '系统权限',
  `created` datetime DEFAULT NULL COMMENT '创建时间',
  `modified` datetime DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `sort` (`sort`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='角色表';

-- ----------------------------
-- Records of cmpc_roles
-- ----------------------------
INSERT INTO `cmpc_roles` VALUES ('1', '管理员', '0', '1', '1', '1,2,3,4,5', '2014-08-07 16:42:38', '2014-08-07 16:42:38');

-- ----------------------------
-- Table structure for `cmpc_splits`
-- ----------------------------
DROP TABLE IF EXISTS `cmpc_splits`;
CREATE TABLE `cmpc_splits` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `videoId` bigint(20) NOT NULL COMMENT '所属视频文件ID',
  `fileName` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '分片文件名称',
  `filePath` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '文件存放物理地址',
  `fileIndex` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT '分片文件索引号',
  `fileUrl` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '终端用户访问地址',
  `fileSize` varchar(32) CHARACTER SET utf8 NOT NULL COMMENT '文件大小',
  `fileDuration` varchar(32) CHARACTER SET utf8 NOT NULL COMMENT '文件时长',
  `addTime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='视频文件分片表';

-- ----------------------------
-- Records of cmpc_splits
-- ----------------------------

-- ----------------------------
-- Table structure for `cmpc_transcodes`
-- ----------------------------
DROP TABLE IF EXISTS `cmpc_transcodes`;
CREATE TABLE `cmpc_transcodes` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '转码ID',
  `transcode_group_id` int(20) NOT NULL COMMENT '转码组ID',
  `title` varchar(125) NOT NULL COMMENT '格式名',
  `params` text COMMENT '转码参数以JSON形式保存',
  `policy_id` varchar(125) DEFAULT NULL COMMENT '策略ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='转码配置表';

-- ----------------------------
-- Records of cmpc_transcodes
-- ----------------------------
INSERT INTO `cmpc_transcodes` VALUES ('1', '1', '流畅', '{\"Transcode\":{\"type\":\"1\",\"ImageWidth\":\"1024\",\"ImageHeight\":\"768\",\"ConvertModel\":\"Stretch\",\"VideoFormat\":\"H264\",\"BitRate\":\"100000\",\"FileFormat\":\"MP4\",\"FrameRate\":\"25\",\"KeyFrameRate\":\"25\",\"AudioFormat\":\"AAC\",\"SamplesPerSec\":\"48000\",\"BitsPerSample\":\"0\",\"fpCheck\":\"on\",\"SliceTime\":\"3000\"}}', null);
INSERT INTO `cmpc_transcodes` VALUES ('2', '1', '流畅ios', '{\"Transcode\":{\"type\":\"1\",\"ImageWidth\":\"1024\",\"ImageHeight\":\"768\",\"ConvertModel\":\"Stretch\",\"VideoFormat\":\"H264\",\"BitRate\":\"200000\",\"FileFormat\":\"MP4\",\"FrameRate\":\"25\",\"KeyFrameRate\":\"25\",\"AudioFormat\":\"AAC\",\"SamplesPerSec\":\"48000\",\"BitsPerSample\":\"0\",\"fpCheck\":\"on\",\"SliceTime\":\"3000\"}}', null);
INSERT INTO `cmpc_transcodes` VALUES ('3', '1', '标清', '{\"Transcode\":{\"type\":\"1\",\"ImageWidth\":\"1024\",\"ImageHeight\":\"768\",\"ConvertModel\":\"Stretch\",\"VideoFormat\":\"H264\",\"BitRate\":\"450000\",\"FileFormat\":\"MP4\",\"FrameRate\":\"25\",\"KeyFrameRate\":\"25\",\"AudioFormat\":\"AAC\",\"SamplesPerSec\":\"48000\",\"BitsPerSample\":\"0\",\"fpCheck\":\"on\",\"SliceTime\":\"3000\"}}', null);
INSERT INTO `cmpc_transcodes` VALUES ('4', '1', '标清ios', '{\"Transcode\":{\"type\":\"1\",\"ImageWidth\":\"1024\",\"ImageHeight\":\"768\",\"ConvertModel\":\"Stretch\",\"VideoFormat\":\"H264\",\"BitRate\":\"800000\",\"FileFormat\":\"MP4\",\"FrameRate\":\"25\",\"KeyFrameRate\":\"25\",\"AudioFormat\":\"AAC\",\"SamplesPerSec\":\"48000\",\"BitsPerSample\":\"0\",\"fpCheck\":\"on\",\"SliceTime\":\"3000\"}}', null);
INSERT INTO `cmpc_transcodes` VALUES ('5', '1', '高清', '{\"Transcode\":{\"type\":\"1\",\"ImageWidth\":\"1024\",\"ImageHeight\":\"768\",\"ConvertModel\":\"Stretch\",\"VideoFormat\":\"H264\",\"BitRate\":\"1000000\",\"FileFormat\":\"MP4\",\"FrameRate\":\"25\",\"KeyFrameRate\":\"25\",\"AudioFormat\":\"AAC\",\"SamplesPerSec\":\"48000\",\"BitsPerSample\":\"0\",\"fpCheck\":\"on\",\"SliceTime\":\"3000\"}}', null);

-- ----------------------------
-- Table structure for `cmpc_transcode_groups`
-- ----------------------------
DROP TABLE IF EXISTS `cmpc_transcode_groups`;
CREATE TABLE `cmpc_transcode_groups` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '转码组ID',
  `type` smallint(6) NOT NULL DEFAULT '1' COMMENT '模板类型 1:视频 2:音频',
  `name` varchar(45) NOT NULL COMMENT '转码组名称',
  `created` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='转码组表';

-- ----------------------------
-- Records of cmpc_transcode_groups
-- ----------------------------
INSERT INTO `cmpc_transcode_groups` VALUES ('1', '1', '默认转码组', '2014-08-07 16:42:38');

-- ----------------------------
-- Table structure for `cmpc_user`
-- ----------------------------
DROP TABLE IF EXISTS `cmpc_user`;
CREATE TABLE `cmpc_user` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `role_id` varchar(20) NOT NULL COMMENT '角色ID',
  `account` varchar(128) NOT NULL COMMENT '账号',
  `password` varchar(128) NOT NULL COMMENT '密码',
  `name` varchar(128) DEFAULT NULL COMMENT '昵称',
  `email` varchar(255) DEFAULT NULL COMMENT '电子邮件',
  `created` datetime DEFAULT NULL COMMENT '创建时间',
  `modified` datetime DEFAULT NULL COMMENT '修改时间',
  `status` int(20) DEFAULT '0' COMMENT '用户状态 (1.使用 2.正式)',
  `is_founder` smallint(3) NOT NULL DEFAULT '0' COMMENT '是否是创始人 1是，0否',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Records of cmpc_user
-- ----------------------------
INSERT INTO `cmpc_user` VALUES ('1', '1', 'admin', '0a1a0fe51220d42db105279a6c97cc22', '管理员', 'sobey@sobey.com', '2014-08-07 16:42:38', '2014-08-07 16:42:38', '1', '1');

-- ----------------------------
-- Table structure for `cmpc_userlog`
-- ----------------------------
DROP TABLE IF EXISTS `cmpc_userlog`;
CREATE TABLE `cmpc_userlog` (
  `LogId` int(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '日志ID',
  `UserName` varchar(256) NOT NULL COMMENT '用户名称',
  `IP` varchar(32) NOT NULL COMMENT '任务GUID',
  `LogType` varchar(256) NOT NULL COMMENT '日志类型',
  `LogMessage` text NOT NULL COMMENT '日志详细信息',
  `AddTime` datetime NOT NULL COMMENT '日志添加时间',
  PRIMARY KEY (`LogId`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='用户日志表';

-- ----------------------------
-- Records of cmpc_userlog
-- ----------------------------
INSERT INTO `cmpc_userlog` VALUES ('1', 'admin', '127.0.0.1', 'users:login', '【admin】登录成功', '2014-08-07 16:42:46');
INSERT INTO `cmpc_userlog` VALUES ('2', 'admin', '127.0.0.1', 'uploads:video', '上传素材【1】', '2014-08-07 16:43:07');

-- ----------------------------
-- Table structure for `cmpc_videos`
-- ----------------------------
DROP TABLE IF EXISTS `cmpc_videos`;
CREATE TABLE `cmpc_videos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `content_id` varchar(40) NOT NULL COMMENT '素材ID',
  `fileName` varchar(256) NOT NULL COMMENT '文件名称',
  `fileUrl` varchar(256) NOT NULL COMMENT '文件访问地址',
  `filePath` varchar(256) DEFAULT NULL COMMENT '文件物理地址',
  `fileFormat` varchar(32) NOT NULL COMMENT '文件格式',
  `fileSize` varchar(32) NOT NULL COMMENT '文件大小',
  `pictureHeight` int(11) DEFAULT NULL COMMENT '画面高度',
  `pictureWidth` int(11) DEFAULT NULL COMMENT '画面宽度',
  `fileRate` int(11) NOT NULL COMMENT '文件码率',
  `duration` varchar(32) NOT NULL COMMENT '时长',
  `addUser` varchar(32) DEFAULT NULL COMMENT '添加人',
  `originalFile` int(11) NOT NULL DEFAULT '0' COMMENT '文件类型 1:原始文件 0:转码后的文件 2:预览时的文件',
  `fileTypeId` varchar(32) DEFAULT NULL,
  `mediaType` varchar(64) DEFAULT NULL,
  `created` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='转码文件表';

-- ----------------------------
-- Records of cmpc_videos
-- ----------------------------
INSERT INTO `cmpc_videos` VALUES ('1', '53E33C1A-8668-454E-B7A6-0C486C7875F8', '20141210095844.mp4', 'F:\\20140807\\20141210095844.mp4', 'E:/Clips/20140807/20141210095844.mp4', 'mp4', '47474712', null, null, '0', '', 'admin', '1', null, null, '2014-08-07 16:43:07');

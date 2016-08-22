<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo __('Main page title');?></title>
<link rel="icon" type="image/x-icon" href="themes/default/images/favicon.ico" />
<link href="<?php echo $this->webroot;?>themes/default/style.css" rel="stylesheet" type="text/css" media="screen"/>
<link href="<?php echo $this->webroot;?>themes/css/core.css" rel="stylesheet" type="text/css" media="screen"/>
<link href="<?php echo $this->webroot;?>themes/css/print.css" rel="stylesheet" type="text/css" media="print"/>
<link href="<?php echo $this->webroot;?>uploadify/css/uploadify.css" rel="stylesheet" type="text/css" media="screen"/>
<!--[if IE]>
<meta http-equiv="X-UA-Compatible" content="IE=8" />
<link href="<?php echo $this->webroot;?>themes/css/ieHack.css" rel="stylesheet" type="text/css" media="screen"/>
<![endif]-->

<script src="<?php echo $this->webroot;?>js/speedup.js" type="text/javascript"></script>
<script src="<?php echo $this->webroot;?>js/jquery-1.7.1.js" type="text/javascript"></script>
<script src="<?php echo $this->webroot;?>js/jquery.cookie.js" type="text/javascript"></script>
<script src="<?php echo $this->webroot;?>js/jquery.validate.js" type="text/javascript"></script>
<script src="<?php echo $this->webroot;?>js/jquery.bgiframe.js" type="text/javascript"></script>
<script src="<?php echo $this->webroot;?>xheditor/xheditor-1.1.12-zh-cn.min.js" type="text/javascript"></script>

<script src="<?php echo $this->webroot;?>js/dwz.core.js" type="text/javascript"></script>
<script src="<?php echo $this->webroot;?>js/dwz.util.date.js" type="text/javascript"></script>
<script src="<?php echo $this->webroot;?>js/dwz.validate.method.js" type="text/javascript"></script>
<script src="<?php echo $this->webroot;?>js/dwz.regional.zh.js" type="text/javascript"></script>
<!-- <script src="<?php //echo $this->webroot;?>js/dwz.barDrag.js" type="text/javascript"></script> -->
<script src="<?php echo $this->webroot;?>js/dwz.drag.js" type="text/javascript"></script>
<script src="<?php echo $this->webroot;?>js/dwz.tree.js" type="text/javascript"></script>
<script src="<?php echo $this->webroot;?>js/dwz.accordion.js" type="text/javascript"></script>
<script src="<?php echo $this->webroot;?>js/dwz.ui.js" type="text/javascript"></script>
<script src="<?php echo $this->webroot;?>js/dwz.theme.js" type="text/javascript"></script>
<script src="<?php echo $this->webroot;?>js/dwz.switchEnv.js" type="text/javascript"></script>
<script src="<?php echo $this->webroot;?>js/dwz.alertMsg.js" type="text/javascript"></script>
<script src="<?php echo $this->webroot;?>js/dwz.contextmenu.js" type="text/javascript"></script>
<script src="<?php echo $this->webroot;?>js/dwz.navTab.js" type="text/javascript"></script>
<script src="<?php echo $this->webroot;?>js/dwz.tab.js" type="text/javascript"></script>
<script src="<?php echo $this->webroot;?>js/dwz.resize.js" type="text/javascript"></script>
<script src="<?php echo $this->webroot;?>js/dwz.dialog.js" type="text/javascript"></script>
<script src="<?php echo $this->webroot;?>js/dwz.dialogDrag.js" type="text/javascript"></script>
<script src="<?php echo $this->webroot;?>js/dwz.sortDrag.js" type="text/javascript"></script>
<script src="<?php echo $this->webroot;?>js/dwz.cssTable.js" type="text/javascript"></script>
<script src="<?php echo $this->webroot;?>js/dwz.stable.js" type="text/javascript"></script>
<script src="<?php echo $this->webroot;?>js/dwz.taskBar.js" type="text/javascript"></script>
<script src="<?php echo $this->webroot;?>js/dwz.ajax.js" type="text/javascript"></script>
<script src="<?php echo $this->webroot;?>js/dwz.pagination.js" type="text/javascript"></script>
<script src="<?php echo $this->webroot;?>js/dwz.database.js" type="text/javascript"></script>
<script src="<?php echo $this->webroot;?>js/dwz.datepicker.js" type="text/javascript"></script>
<script src="<?php echo $this->webroot;?>js/dwz.effects.js" type="text/javascript"></script>
<script src="<?php echo $this->webroot;?>js/dwz.panel.js" type="text/javascript"></script>
<script src="<?php echo $this->webroot;?>js/dwz.checkbox.js" type="text/javascript"></script>
<script src="<?php echo $this->webroot;?>js/dwz.history.js" type="text/javascript"></script>
<script src="<?php echo $this->webroot;?>js/dwz.combox.js" type="text/javascript"></script>
<script src="<?php echo $this->webroot;?>js/dwz.print.js" type="text/javascript"></script>
<script src="<?php echo $this->webroot;?>swf/swfobject_modified.js" type="text/javascript"></script>
<!--
<script src="<?php //echo $this->webroot;?>bin/dwz.min.js" type="text/javascript"></script>
-->
<script src="<?php echo $this->webroot;?>js/dwz.regional.zh.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo $this->webroot;?>uploadify/scripts/jquery.uploadify-3.1.min.js?ver=<?php echo time();?>"></script>
<script type="text/javascript">
  
$(function(){
	DWZ.init("<?php echo $this->webroot;?>dwz.frag.xml", {
//		loginUrl:"login_dialog.html", loginTitle:"登录",	// 弹出登录对话框
//		loginUrl:"<?php echo $this->Html->url(array('controller'=>'users','action'=>'login'));?>",	// 跳到登录页面
		statusCode:{ok:200, error:300, timeout:301}, //【可选】
		pageInfo:{pageNum:"pageNum", numPerPage:"numPerPage", orderField:"orderField", orderDirection:"orderDirection"}, //【可选】
		debug:false,	// 调试模式 【true|false】
		callback:function(){
			initEnv();
			$("#themeList").theme({themeBase:"themes"}); // themeBase 相对于index页面的主题base路径
		}
	});
	$('#showDownlist').mouseover(function(){
		$('#downlist').show();
	});
	$('#showDownlist').mouseout(function(){
		$('#downlist').hide();
	});
	$('#downlist').mouseover(function(){
		$('#downlist').show();
	});
	$('#downlist').mouseout(function(){
		$('#downlist').hide();
	});
	$("div.manageFirst").click(function(){
		$("#videoTree").find('div[class*=selected]').removeClass();
		$("ul.tree").find('div[class*=selected]').removeClass();
	});

	$('a.helpdown').mouseover(function(){
		$("#help_down").show();
	});
	$('#downsuccess').mouseover(function(){
		$("#help_down").show();
	});
	$('#help_down').mouseout(function(){
		$("#help_down").hide();
	});

	$('#file_upload').uploadify({
			'swf'      			: '<?php echo $this->webroot;?>uploadify/scripts/uploadify.swf',
			'uploader' 			: '<?php echo $this->webroot;?>uploads/video',
			'buttonImage' 		: '<?php echo $this->webroot;?>themes/default/images/add_video.png',
			'buttonText'		: false,
			'auto'				: false,
			'width'				: 83,
			'successTimeout'	: 100000,
			'queueID' 			: 'downlist',
			'fileSizeLimit'		: 0,
			'onSelect' 			: function(file) {
				var selectValue = $("#templateid option:selected").text();
				var arr = selectValue.split('|');
				var cid = $('#categoryid').val();
				var tid = $('#templateid').val();
				var fname = file.name;
				var name = fname.split('.')[fname.split('.').length-2];
				var fid = file.id;
				var metaDataBtn = '<input onclick=checkoutmetaDataForm(\"' + fid + '\") title=编辑元数据 style=float:left; type=radio name=metadataRadio value='+ fid +'>';
				$("#upload_file").append('<div id="'+file.id+'_user"><input class="cmpcInput" type="text" name="filename" value="'+name+'" fid="'+file.id+'" />' + metaDataBtn + '<a class="cmpcCancel" onclick="$(\'#file_upload\').uploadify(\'cancel\',\''+file.id+'\');$(\'form#' + file.id + '\').remove();$(\'#upload_file input[type=radio]\').first().click();" href="javascript:;">X</a></div>');
				$('#'+fid).attr('fname',name);
				$('#'+fid).attr('cid',cid);
				$('#'+fid).attr('tid',tid);
				$('#'+fid).attr('metaData',tid);
				$("#defaultUploadInput").hide();
				if($.trim(arr[0]) == '视频')
				{
					var videoArr = '<?php echo strtolower(UPLOAD_VIDEO_FILE_FORMAT);?>'.split('|');
					var num = 0;
					if($.inArray((file.type).toLowerCase(),videoArr)<0)
					{
						$("#upload_file").find("input[name='filename']").each(function(){
							num++;
						});
						$("#file_upload").uploadify('cancel',file.id);
						$("#"+file.id+"_user").remove();
						if(num == 1)
						{
							$("#defaultUploadInput").show();
						}
						alertMsg.error("文件格式与模板类型不匹配！");
					}
				}else
				{
					var audioArr = '<?php echo strtolower(UPLOAD_AUDIO_FILE_FORMAT);?>'.split('|');
					var num = 0;
					if($.inArray((file.type).toLowerCase(),audioArr)<0)
					{
						$("#upload_file").find("input[name='filename']").each(function(){
							num++;
						});
						$("#file_upload").uploadify('cancel',file.id);
						$("#"+file.id+"_user").remove();
						if(num == 1)
						{
							$("#defaultUploadInput").show();
						}
						alertMsg.error("文件格式与模板类型不匹配！");
					}
				}
				$('.dialogContent .small-label').append($('.pageFormContent .metaDataInit').html());
				$('.dialogContent .small-label form').css({'display':'none'});
				$('.dialogContent .small-label form').last().attr('id',fid);
				$('.dialogContent .small-label form').last().addClass('metaDataForm');
				$('.dialogContent .small-label').children('form').first().css({'display':'block'});
				var firstID = $('.dialogContent .small-label').children('form').first().attr('id');
				$('#'+firstID+'_user input[type=radio]').click();

				//添加默认值
				if( isAutoFill ) {
					var currentValue = $('div#' + fid + '_user').children('input[type=text]').val();
					for(var i=0;i<isAutoFill.length;i++) {
						$('form#' + fid + ' [name="data[Metadata]['+isAutoFill[i]+']"]').val(currentValue);
					}
				}
				
			},
			'onUploadStart'		: function(file){
				var categoryid = $('#'+file.id).attr('cid');
				var templateid = $('#'+file.id).attr('tid');
				var is_split = $('#'+file.id).attr('is_split');
				var filename = $('#'+file.id).attr('fname');
				var platformId = $('#'+file.id).attr('platformId');

				var metaDataForm = $('#'+file.id).attr('metaDataForm');
				$("#file_upload").uploadify("settings", "formData" , {'filename' : filename, 'categoryid' : categoryid, 'templateid' : templateid, 'is_split':is_split, 'user_id':<?php echo $userInfo['User']['id']?>, 'user_name': '<?php echo $userInfo['User']['account']?>','platformId':platformId, 'metaData':metaDataForm});
			},
			'onUploadSuccess'	: function(file, data, response){
				setTimeout(function(){
					if($('#downlist').find('span[class*=fileName]').html())
					{
					}else
					{
						$('#downlist').hide();
					}
				},4500);
			}
	    });
	$('.cmpcCancel').live('click',function(){
		 var formID = $(this).parents('#upload_file>div').attr('data-id');
		 $('#upload_file input[type=radio]').first().click();
		 $(this).parents('#upload_file>div').remove();
		 $('form#' + formID).remove();
		 $(this).each(function(){
		 	if($('.cmpcInput').val()){

		 	}else{
		 		$("#defaultUploadInput").show();
		 	}
		 });
	});

	$("#manageFirst").click(function(){
	 	$("#usermanage").children('div:first').addClass('selected');
	});
	 
	$("a").click(function(){
	 	$("#op_combox_videoSearch").css('display','none');
		$("#op_combox_templateSearch").css('display','none');
		$("#op_combox_userSearch").css('display','none');
		$("#calendar").css('display','none');
	});
	
	setTimeout(function(){
		$("#sidebar").find(".accordion>div:first a").trigger("click");
	},1000);
});
function checkoutmetaDataForm(fid) {
	$('.small-label form').css({'display':'none'}).removeClass('current');
	$('.small-label form#' + fid).css({'display':'block'}).addClass('current');
}
function titleStrs( strs ){
$('#titleViews').html(' > '+strs);
}
function titleStr( str ){
	$('#titleView').html(str);
	$('#titleViews').html('');
	if(str == '<?php echo __('Video manage');?>'){
		$('#searchVideos').css('display','');
		$('#searchTemplates').css('display','none');
		$('#searchUsers').css('display','none');
		$("input[name='keyword']").attr('value','');
		
	}else if(str == '<?php echo __('recycle');?>'){
		$('#searchVideos').css('display','');
		$('#searchTemplates').css('display','none');
		$('#searchUsers').css('display','none');
		$("input[name='keyword']").attr('value','');

	}else if(str == '<?php echo __('Templates manage');?>'){
		$('#searchVideos').css('display','none');
		$('#searchTemplates').css('display','');
		$('#searchUsers').css('display','none');
		$("input[name='keyword']").attr('value','');

	}else if(str == '<?php echo __('Users manage');?>'){
		$('#searchVideos').css('display','none');
		$('#searchTemplates').css('display','none');
		$('#searchUsers').css('display','');
		$("input[name='keyword']").attr('value','');

	}else{
		$('#searchVideos').css('display','none');
		$('#searchTemplates').css('display','none');
		$('#searchUsers').css('display','none');

	}
}
	//add 20141119 收缩、展开左边的导航
function leftsideSpread () {
	var sidebarWidth = $('#sidebar').width();
	if(sidebarWidth > 44) {
		$('#sidebar .accordion .accordionContent').css( {'display':'none'} );
		$('#sidebar').css({'width':'44px'});
		$('#splitBar').css({'left':'44px'});
		$('#container').css({'left':'49px'});
	}else {
		$('#sidebar .accordion .accordionHeader .collapsable').parent().parent().next().css( {'display':'block'} );
		$('#sidebar').css({'width':'205px'});
		$('#splitBar').css({'left':'205px'});
		$('#container').css({'left':'210px'});
	}
	// fix container and table css
	initLayout();
	$(window).trigger("resizeGrid");
	$('.j-resizeGrid .pagination li.selected a').click();
}
</script>

</head>

<body scroll="no">
	<div id="layout">
		<div id="header">
			<div class="headerNav">
				<a class="logo" href="#"><?php echo __('sign');?></a>
				<ul class="nav">
					<li class="first"><span style="color:#FFFFFF;"><?php echo $userInfo['Role']['name'].' ：'.$userInfo['User']['account'];?></span></li>
					<!-- <li><a class="helpdown" target="_blank"><?php //echo __('help');?></a><div id="help_down"><a id="downsuccess" href="SobeyTransfer.exe" target="_blank">客户端下载</a></div></li> -->
					<li><a href="<?php echo $this->Html->url(array('controller'=>'users','action'=>'logout'));?>"><?php echo __('logout');?></a></li>
                    <!-- <li style="color:#FFFFFF;">客户存储空间：<span id="disksize">已用：<?php //echo $cookie['cookieCustomerLastSpace']?$cookie['cookieCustomerLastSpace'].'G':'0';?> / 总共：<?php //echo $cookie['cookieCustomerSpace']?$cookie['cookieCustomerSpace'].'G':'0';?></span></li> -->
				</ul>
			</div>
			<!-- navMenu -->
			<div class="button" id="showDownlist" style="bottom:0;right:3px;position:absolute"><div class="buttonContent"><button><?php echo __('Show download list');?></button></div></div>
		</div>
		<div id="downlist">
			<div id="info"></div>
			<div id="boxone" style="position:absolute;top:5px;right:10px;">
			<div id="boxtwo" class="formBarCustomer" style="background:none;border:0;">
	            <ul id="boxthree">
	            	<li style="float:left;"><div class="button"><div class="buttonContent"><button onclick="javascript:$('#file_upload').uploadify('cancel', '*');return false;"><?php echo __('Clean list');?></button></div></div></li>
	                <!--<li><div class="buttonActive"><div class="buttonContent"><button id="downlistClose"><?php //echo __('Close list');?></button></div></div></li>
	                <li style="float:left;"><div class="buttonActive"><div class="buttonContent"><button onclick="javascript:$('#file_upload').uploadify('upload','*');return false;"><?php //echo __('Again upload');?></button></div></div></li>-->
	            </ul>
	        </div>
			</div>
		</div>
		<div id="leftside">
			<div id="sidebar">
				<div class="accordion" fillSpace="sidebar">
                	<?php
                    if(in_array(1,explode(',',$rolesSystemPermissions['Role']['operation_accesses'])))
					{
					?>
					<div class="accordionHeader manageFirst">
						<a href="<?php echo $this->Html->url(array('controller'=>'tasks','action'=>'index'));?>" target="navTab" rel="main"><h2 class="leftside-video-manage" onclick="return titleStr('<?php echo __('Video manage');?>');"><?php echo __('Video manage');?></h2></a>
					</div>
                    
					<div class="accordionContent">
                    
						<div class="special">
							<a mask="true" target="dialog" class="adds" title="添加分类" href="<?php echo $this->Html->url(array('controller'=>'categories','action'=>'add'))?>" style="float:right;width:14px;height:14px;margin:9px;"></a>
							<?php echo __('Video category');?>
						</div>
                        
						<ul id="videoTree" class="tree expand sideBarTree_1">
							<?php foreach($tree as $blist):?>
							<li><a href="<?php echo $this->Html->url('/tasks/index/'.$blist['Category']['id']);?>"  target="navTab" rel="main" categoryid="<?php echo $blist['Category']['id']?>" addurl="<?php echo $this->Html->url('/categories/add')?>" editurl="<?php echo $this->Html->url('/categories/edit')?>" deleteurl="<?php echo $this->Html->url('/categories/del')?>" onclick="return titleStrs('<?php echo $blist['Category']['name'];?>');"><?php echo $blist['Category']['name']?></a>
								<?php if(count($blist['children']) > 0):?>
								<ul class="sideBarTree_2">
									<?php foreach($blist['children'] as $clist):?>
									<li><a href="<?php echo $this->Html->url('/tasks/index/'.$clist['Category']['id']);?>" target="navTab" rel="main" categoryid="<?php echo $clist['Category']['id']?>" addurl="<?php echo $this->Html->url('/categories/add')?>" deleteurl="<?php echo $this->Html->url('/categories/del')?>" editurl="<?php echo $this->Html->url('/categories/edit')?>"><?php echo $clist['Category']['name']?></a>
										<?php if(count($clist['children']) > 0):?>
											<ul class="sideBarTree_3">
												<?php foreach($clist['children'] as $dlist):?>
												<li><a href="<?php echo $this->Html->url('/tasks/index/'. $dlist['Category']['id']);?>" target="navTab" rel="main" categoryid="<?php echo $dlist['Category']['id']?>" addurl="<?php echo $this->Html->url('/categories/add')?>" deleteurl="<?php echo $this->Html->url('/categories/del')?>" editurl="<?php echo $this->Html->url('/categories/edit')?>"><?php echo $dlist['Category']['name']?></a>
													<?php if(count($dlist['children']) > 0):?>
														<ul class="sideBarTree_4">
															<?php foreach($dlist['children'] as $elist):?>
															<li><a href="<?php echo $this->Html->url('/tasks/index/'.$elist['Category']['id']);?>" target="navTab" rel="main" categoryid="<?php echo $elist['Category']['id']?>" deleteurl="<?php echo $this->Html->url('/categories/del')?>" editurl="<?php echo $this->Html->url('/categories/edit')?>"><?php echo $elist['Category']['name']?></a></li>
															<?php endforeach;?>
														</ul>
													<?php endif;?>
													<!-- 4 -->
												</li>
												<?php endforeach;?>
											</ul>
										<?php endif;?>
										<!-- 3 -->
									</li>
								    <?php endforeach;?>
								</ul>
								<?php endif;?>
                                <!-- 2 -->
							</li>
							<?php endforeach;?>
                            <!-- 1 -->
						</ul>
                        
					</div>
                    <?php
					}if(in_array(2,explode(',',$rolesSystemPermissions['Role']['operation_accesses'])))
					{
					?>
					<div class="accordionHeader">
						<a href="<?php echo $this->Html->url(array('controller'=>'recycle'));?>" target="navTab" rel="main"><h2 class="leftside-recyle-manage" onclick="return titleStr('<?php echo __('recycle');?>');"><?php echo __('recycle');?></h2></a>
					</div>
					<div class="accordionContent">
						<div class="special">
							<?php echo __('recycle');?>
						</div>
					</div>
                    <?php
					}if(in_array(3,explode(',',$rolesSystemPermissions['Role']['operation_accesses'])))
					{
					?>
					<div class="accordionHeader">
						<a href="<?php echo $this->Html->url(array('controller'=>'TranscodeGroups'));?>" target="navTab" rel="main"><h2 class="leftside-templates-manage" onclick="return titleStr('<?php echo __('Templates manage');?>');"><?php echo __('Templates manage');?></h2></a>
					</div>
					<div class="accordionContent">
						<!-- <div class="special">
							<?php //echo __('Templates manage');?>
						</div> -->
						<div class="special">
							<a height="170" mask="true" target="dialog" class="adds" title="添加分类" href="<?php echo $this->Html->url(array('controller'=>'TranscodeGroups','action'=>'add_category'))?>" style="float:right;width:14px;height:14px;margin:9px;"></a>
							<?php echo __('Templates category');?>
						</div>
						<ul id="videoTree" class="tree expand sideBarTree_1">
                        	<!-- 1 -->
                        	<?php //var_dump($tree);exit;?>
							<?php foreach($template_tree as $blist):?>
							<li><a href="<?php echo $this->Html->url(array('controller'=>'TranscodeGroups','action'=>'index',$blist['TranscodeCategory']['id']));?>"  target="navTab" height="160" rel="main" categoryid="<?php echo $blist['TranscodeCategory']['id']?>" editurl="<?php echo $this->Html->url(array('controller'=>'TranscodeGroups','action'=>'edit_category'))?>" deleteurl="<?php echo $this->Html->url(array('controller'=>'TranscodeGroups','action'=>'del_category'))?>"  onclick="return titleStrs('<?php echo $blist['TranscodeCategory']['name'];?>');"><?php echo $blist['TranscodeCategory']['name']?></a>
								<!-- 2 -->
								<?php if(count($blist['children']) > 0):?>
								<ul class="sideBarTree_2">
									<?php foreach($blist['children'] as $clist):?>
									<li><a href="<?php echo $this->Html->url(array('controller'=>'TranscodeGroups','action'=>'index',$clist['TranscodeCategory']['id']));?>" target="navTab" rel="main" categoryid="<?php echo $clist['TranscodeCategory']['id']?>" addurl="<?php echo $this->Html->url(array('controller'=>'TranscodeGroups','action'=>'add_category'))?>" deleteurl="<?php echo $this->Html->url(array('controller'=>'TranscodeGroups','action'=>'del_category'))?>" editurl="<?php echo $this->Html->url(array('controller'=>'TranscodeGroups','action'=>'edit_category'))?>"><?php echo $clist['TranscodeCategory']['name']?></a>
										<!-- 3 -->
										<?php if(count($clist['children']) > 0):?>
											<ul class="sideBarTree_3">
												<?php foreach($clist['children'] as $dlist):?>
												<li><a href="<?php echo $this->Html->url(array('controller'=>'TranscodeGroups','action'=>'index',$dlist['TranscodeCategory']['id']));?>" target="navTab" rel="main" categoryid="<?php echo $dlist['TranscodeCategory']['id']?>" addurl="<?php echo $this->Html->url(array('controller'=>'TranscodeGroups','action'=>'add_category'))?>" deleteurl="<?php echo $this->Html->url(array('controller'=>'TranscodeGroups','action'=>'del_category'))?>" editurl="<?php echo $this->Html->url(array('controller'=>'TranscodeGroups','action'=>'edit_category'))?>"><?php echo $dlist['TranscodeCategory']['name']?></a>
													<!-- 4 -->
													<?php if(count($dlist['children']) > 0):?>
														<ul class="sideBarTree_4">
															<?php foreach($dlist['children'] as $elist):?>
															<li><a href="<?php echo $this->Html->url(array('controller'=>'TranscodeGroups','action'=>'index',$elist['TranscodeCategory']['id']));?>" target="navTab" rel="main" categoryid="<?php echo $elist['TranscodeCategory']['id']?>" deleteurl="<?php echo $this->Html->url(array('controller'=>'TranscodeGroups','action'=>'del_category'))?>" editurl="<?php echo $this->Html->url(array('controller'=>'TranscodeGroups','action'=>'edit_category'))?>"><?php echo $elist['TranscodeCategory']['name']?></a></li>
															<?php endforeach;?>
														</ul>
													<?php endif;?>
													<!-- 4 -->
												</li>
												<?php endforeach;?>
											</ul>
										<?php endif;?>
										<!-- 3 -->
									</li>
								    <?php endforeach;?>
								</ul>

								<?php endif;?>
                                <!-- 2 -->
							</li>
							<?php endforeach;?>
                            <!-- 1 -->
						</ul>
					</div>
                    <?php
					}if(in_array(4,explode(',',$rolesSystemPermissions['Role']['operation_accesses'])))
					{
					?>
					<div id="manageFirst" class="accordionHeader manageFirst">
						<a href="<?php echo $this->Html->url(array('controller'=>'users'));?>" target="navTab" rel="main"><h2 class="leftside-users-manage" onclick="return titleStr('<?php echo __('Users manage');?>');"><?php echo __('Users manage');?></h2></a>
					</div>
					<div class="accordionContent">
						<ul class="tree usermanage">
							<li id="usermanage"><a href="<?php echo $this->Html->url(array('controller'=>'users'));?>" target="navTab" rel="main" onclick="return titleStrs('<?php echo __('Users manage');?>');"><?php echo __('Users manage');?></a></li>
							<li><a href="<?php echo $this->Html->url(array('controller'=>'roles'));?>" target="navTab" rel="main"  onclick="return titleStrs('<?php echo __('Roles manage');?>');"><?php echo __('Roles manage');?></a></li>
							<li><a href="<?php echo $this->Html->url(array('controller'=>'roles', 'action'=>'permissions'));?>" target="navTab" rel="main"   onclick="return titleStrs('<?php echo __('Roles permissions');?>');"><?php echo __('Roles permissions');?></a></li>
						</ul>
					</div>
                    <?php
					}if(in_array(5,explode(',',$rolesSystemPermissions['Role']['operation_accesses'])))
					{
					?>
					<div class="accordionHeader">
						<a href="<?php echo $this->Html->url(array('controller'=>'logs'));?>" target="navTab" rel="main"><h2 class="leftside-system-logs" onclick="return titleStr('<?php echo __('System logs');?>');">系统管理</h2></a>
					</div>
					<div class="accordionContent">
						<ul class="tree usermanage">
							<li><a href="<?php echo $this->Html->url('/logs');?>" target="navTab" rel="main" onclick="return titleStrs('<?php echo __('System logs');?>');"><?php echo __('System logs');?></a></li>
							<li><a href="<?php echo $this->Html->url('/configs');?>" target="navTab" rel="main"  onclick="return titleStrs('<?php echo '系统配置';?>');">系统配置</a></li>
							<li><a href="<?php echo $this->Html->url('/AutoScans');?>" target="navTab" rel="main"  onclick="return titleStrs('<?php echo '扫描配置';?>');">扫描配置</a></li>
							<li><a href="<?php echo $this->Html->url('/Metadatas');?>" target="navTab" rel="main"  onclick="return titleStrs('<?php echo '元数据配置';?>');">元数据配置</a></li>
							<li><a href="<?php echo $this->Html->url(array('controller'=>'XmlAnalysis', 'action'=>'index'));?>" target="navTab" rel="main"  onclick="return titleStrs('<?php echo 'xml上传';?>');">xml上传</a></li>
						</ul>
					</div>
                    <?php
					}
					?>
				</div>
			</div>
		</div>
		<div id="container">
			<div id="navTab" class="tabsPage">
				<div class="tabsPageHeader">
					<div class="tabsPageHeaderContent"><!-- 显示左右控制时添加 class="tabsPageHeaderMargin" -->
						<ul class="navTab-tab">
							<li tabid="main" class="main"><a href="javascript:;"><span><span class="home_icon"><?php echo __('My homepage');?></span></span></a></li>
						</ul>
					</div>
					<div class="tabsLeft">left</div><!-- 禁用只需要添加一个样式 class="tabsLeft tabsLeftDisabled" -->
					<div class="tabsRight">right</div><!-- 禁用只需要添加一个样式 class="tabsRight tabsRightDisabled" -->
					<div class="tabsMore">more</div>
				</div>
				<ul class="tabsMoreList">
					<li><a href="javascript:;"><?php echo __('My homepage');?></a></li>
				</ul>
                
                
				<div class="accountInfo">
					<div onclick='leftsideSpread()' class='new-menu' title="收缩/展开"></div>
					<div class="navigationInfo">
						<?php echo __('Current location');?>： <?php echo __('Project name');?> &gt; <span id="titleView"><?php echo __('Video manage');?></span><span id="titleViews"></span>
					</div>
                    <span id="searchVideos">
					<form method="post" onsubmit="return navTabSearch(this);" action="<?php echo $this->Html->url(array('controller'=>'tasks','action'=>'index'));?>">
						<div class="right">
							<span class="filterType">
								<select class="combox" name="searchType" id="videoSearch">
									<option value="title"><?php echo __('Video name');?></option>
									<option value="user_name"><?php echo __('Upload user');?></option>
								</select>
							</span>
							<span class="filterTxt"><input type="text" name="keyword" id="keyword"></span>
							<input type="submit" class="filterSubmit" name="submit" value="" />
						</div>
					</form>
                    </span>
                    
                    <span id="searchTemplates" style="display:none;">
					<form method="post" onsubmit="return navTabSearch(this);" action="<?php echo $this->Html->url(array('controller'=>'transcode_groups','action'=>'index'));?>">
						<div class="right">
							<span class="filterType">
								<select class="combox" name="searchType" id="templateSearch">
									<option value="title"><?php echo __('Templates name');?></option>
								</select>
							</span>
							<span class="filterTxt"><input type="text" name="keyword" id="keyword"></span>
							<input type="submit" class="filterSubmit" name="submit" value="<?php echo @$keyword?:'';?>" />
						</div>
					</form>
                    </span>
                    
                    <span id="searchUsers" style="display:none;">
					<form method="post" onsubmit="return navTabSearch(this);" action="<?php echo $this->Html->url(array('controller'=>'users','action'=>'index'));?>">
						<div class="right">
							<span class="filterType">
                            <?php echo $this->Form->input('searchType', array('type'=>'select','class'=>'combox', 'id'=>'userSearch', 'name'=>'searchType' ,'options'=>$roles, 'label'=>false));?>
							</span>
							<span class="filterTxt"><input type="text" name="keyword" id="keyword"></span>
							<input type="submit" class="filterSubmit" name="submit" value="" />
						</div>
					</form>
                    </span>
                    
				</div>
                
				<div class="navTab-panel tabsPageContent layoutBox">
					<div class="page unitBox">
						<div class="pageFormContent" layoutH="20">
						<?php echo $this->fetch('content'); ?>
						</div>
					</div>
				</div>
                
			</div>
		</div>
	</div>
	<input type="file" name="file_upload" id="file_upload" />
	<input type="file" name="ftp_upload" id="ftp_upload" />
</body>
</html>
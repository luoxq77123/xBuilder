<script type="text/javascript">
var CloudiaTransfer = new Object;
CloudiaTransfer.FileInfo=new Array();
$(function(){
	$('.categorySelect').click(function(){
		$('#categoryid').val($(this).attr('categoryid'));
	});
	//点击上传
	$('#ftp_upload_submit').die().live('click',function(){
		if($('#upload_file').find('input[name="filename"]').val())
		{
			var cid = $('#categoryid').val();
			var tid = $('#templateid').val();
			var uid = <?php echo $cookie['cookieUserId'];?>;
			var uname = '<?php echo $cookie['cookieUserName'];?>';
			var cname=$('#videoTree div.selected').find('a').html();
			var arr=new Array();
			$('#upload_file div').each(function()
			{
				var fguid=$(this).attr('fguid');
				var str=getFileStr(fguid);
				var fname=$(this).find('input[name=filename]').val().trim();
				var fsize=$(this).find('input[name=filename]').attr('fsize');
				var ftype=$(this).find('input[name=filename]').attr('ftype');
				var backSendData='{filename:"'+fname+'",ftype:"'+ftype+'",fsize:"'+fsize+'",templateid:"'+tid+'",categoryname:"'+cname+'",categoryid:"'+cid+'",uid:"'+uid+'",uname:"'+uname+'"}';
				var da='{backSendData:'+backSendData+',backUploadUrl:"'+$.parseJSON(str).ContentName+'",backId:"'+fguid+'"}';
				arr.push(da);
				setTimeout(function(){ftpUpload(str);},200);
			});
			var tmpjson='{arr:['+arr+']}';
			var redata=eval("("+tmpjson+")");
			$.post("<?php $this->webroot;?>Uploads/ftp_upload",redata);
			$.pdialog.closeCurrent();
			return false;
		}else
		{
			alertMsg.error('没有文件不能上传！');
			return false;
		}
	});

	/*获得弹窗 选择文件上传*/
	$('#ftp_uploads').die().live('click',function(){
		$.ajax({
			type:'GET',
			url:"http://127.0.0.1:6789/?Request=SelectFile<selectfile><subsys>vms</subsys><type></type><filter>*.wmv;*.wm;*.asf;*.asx;*.rm;*.rmvb;*.ra;*.ram;*.mpg;*.mpeg;*.mpe;*.vob;*.dat;*.mov;*.3gp;*.mp4;*.mp4v;*.m4v;*.mkv;*.avi;*.flv;*.f4v;*.mts;*.m2t;*.ts|*.wmv;*.wm;*.asf;*.asx;*.rm;*.rmvb;*.ra;*.ram;*.mpg;*.mpeg;*.mpe;*.vob;*.dat;*.mov;*.3gp;*.mp4;*.mp4v;*.m4v;*.mkv;*.avi;*.flv;*.f4v;*.mts;*.m2t;*.ts||</filter></selectfile>",
			dataType:'jsonp',
			jsonp: "callbackparam",
			jsonpCallback:"success_jsonpCallback",
			cache: false,
			complete:function(XMLHttpRequest, textStatus){
				getSelectList();
			}
		});
	});

	/**
	* 上传时获取分类信息并写到弹出层相应的地方
	*/
	setTimeout(function(){
		var cNameValue = null;
		$("a.uploadSelect").parent().addClass("selected");
		/**
		* 点击是二级分类时
		*/
		if($('.uploadSelect').attr('level') == 'two')
		{
			//判断是不是最后一个导航
			if($('.uploadSelect').prev("div").attr('class') == 'last_collapsable')
			{
				//判断第一级是第几个
				if($('.uploadSelect').parent().parent().parent().prev("div").find("div.first_collapsable").attr("class") == "first_collapsable")
				{
					$('.uploadSelect').parent().parent().parent().prev("div").find("div.first_collapsable").removeClass("first_collapsable").addClass("first_expandable");
				}else if($('.uploadSelect').parent().parent().parent().prev("div").find("div.last_collapsable").attr("class") == "last_collapsable")
				{
					$('.uploadSelect').parent().parent().parent().prev("div").find("div.last_collapsable").removeClass("last_collapsable").addClass("last_expandable");
				}else
				{
					$('.uploadSelect').parent().parent().parent().prev("div").find("div.collapsable").removeClass("collapsable").addClass("expandable");
				}
				//被点击的本身转换样式
				$('.uploadSelect').prev("div.last_collapsable").removeClass("last_collapsable").addClass("last_expandable");
				//展开一级类
				$('.uploadSelect').parent().parent().parent().css('display','block');
				//展开三级类
				$('.uploadSelect').parent().next().css('display','block');
			}else
			{
				//中间的和第一个导航
				
				//判断第一级是第几个，父级转换样式
				if($('.uploadSelect').parent().parent().parent().prev("div").find("div.first_collapsable").attr("class") == "first_collapsable")
				{
					$('.uploadSelect').parent().parent().parent().prev("div").find("div.first_collapsable").removeClass("first_collapsable").addClass("first_expandable");
				}else if($('.uploadSelect').parent().parent().parent().prev("div").find("div.last_collapsable").attr("class") == "last_collapsable")
				{
					$('.uploadSelect').parent().parent().parent().prev("div").find("div.last_collapsable").removeClass("last_collapsable").addClass("last_expandable");
				}else
				{
					$('.uploadSelect').parent().parent().parent().prev("div").find("div.collapsable").removeClass("collapsable").addClass("expandable");
				}
				//被点击的本身转换样式
				$('.uploadSelect').prev("div.collapsable").removeClass("collapsable").addClass("expandable");
				//展开一级类
				$('.uploadSelect').parent().parent().parent().css('display','block');
				//展开三级类
				$('.uploadSelect').parent().next().css('display','block');
			}

			//右侧导航条title赋值
			cNameValue = $('.uploadSelect').parent().parent().parent().parent().find('a').html()+' > '+$('.uploadSelect').html();
		}



		
		/**
		* 点击三级分类时
		*/
		else if($('.uploadSelect').attr('level') == 'three')
		{
			//有子级的第三级的最后一级last_collapsable
			
			if($('.uploadSelect').prev("div.last_collapsable").attr('class') == 'last_collapsable')
			{
				//判断第一级是第几个，父级转换样式
				if($('.uploadSelect').parent().parent().parent().parent().parent().prev("div").find("div.first_collapsable").attr("class") == "first_collapsable")
				{
					$('.uploadSelect').parent().parent().parent().parent().parent().prev("div").find("div.first_collapsable").removeClass("first_collapsable").addClass("first_expandable");
				}else if($('.uploadSelect').parent().parent().parent().parent().parent().prev("div").find("div.last_collapsable").attr("class") == "last_collapsable")
				{
					$('.uploadSelect').parent().parent().parent().parent().parent().prev("div").find("div.last_collapsable").removeClass("last_collapsable").addClass("last_expandable");
				}else
				{
					$('.uploadSelect').parent().parent().parent().parent().parent().prev("div").find("div.collapsable").removeClass("collapsable").addClass("expandable");
				}
				
				//判断第二级是第几个，父级转换样式
				
				if($('.uploadSelect').parent().parent().parent().prev("div").find("div.first_collapsable").attr("class") == "first_collapsable")
				{
					$('.uploadSelect').parent().parent().parent().prev("div").find("div.first_collapsable").removeClass("first_collapsable").addClass("first_expandable");
					
				}else if($('.uploadSelect').parent().parent().parent().prev("div").find("div.last_collapsable").attr("class") == "last_collapsable")
				{
					$('.uploadSelect').parent().parent().parent().prev("div").find("div.last_collapsable").removeClass("last_collapsable").addClass("last_expandable");
				}else
				{
					$('.uploadSelect').parent().parent().parent().prev("div").find("div.collapsable").removeClass("collapsable").addClass("expandable");
				}
				//被点击的本身转换样式
				$('.uploadSelect').prev("div.last_collapsable").removeClass("last_collapsable").addClass("last_expandable");
				//展开一级类
				$('.uploadSelect').parent().parent().parent().parent().parent().css('display','block');
				//展开二级类
				$('.uploadSelect').parent().parent().parent().css('display','block');
				//展开四级类
				$('.uploadSelect').parent().next().css('display','block');
			}
			//没有子级的第三级 node，第一个到最后一个都是这样
			else if($('.uploadSelect').prev("div").attr('class') == 'node')
			{
				//判断第一级是第几个，父级转换样式
				if($('.uploadSelect').parent().parent().parent().parent().parent().prev("div").find("div.first_collapsable").attr("class") == "first_collapsable")
				{
					$('.uploadSelect').parent().parent().parent().parent().parent().prev("div").find("div.first_collapsable").removeClass("first_collapsable").addClass("first_expandable");
				}else if($('.uploadSelect').parent().parent().parent().parent().parent().prev("div").find("div.last_collapsable").attr("class") == "last_collapsable")
				{
					$('.uploadSelect').parent().parent().parent().parent().parent().prev("div").find("div.last_collapsable").removeClass("last_collapsable").addClass("last_expandable");
				}else
				{
					$('.uploadSelect').parent().parent().parent().parent().parent().prev("div").find("div.collapsable").removeClass("collapsable").addClass("expandable");
				}
				
				//判断第二级是第几个，父级转换样式
				if($('.uploadSelect').parent().parent().parent().prev("div").find("div.last_collapsable").attr("class") == "last_collapsable")
				{
					$('.uploadSelect').parent().parent().parent().prev("div").find("div.last_collapsable").removeClass("last_collapsable").addClass("last_expandable");
				}else
				{
					$('.uploadSelect').parent().parent().parent().prev("div").find("div.collapsable").removeClass("collapsable").addClass("expandable");
				}
				//展开一级类
				$('.uploadSelect').parent().parent().parent().parent().parent().css('display','block');
				//展开二级类
				$('.uploadSelect').parent().parent().parent().css('display','block');
				
			}
			//有子级的第三级collapsable，
			else
			{
				//判断第一级是第几个，父级转换样式
				
				if($('.uploadSelect').parent().parent().parent().parent().parent().prev("div").find("div.first_collapsable").attr("class") == "first_collapsable")
				{
					$('.uploadSelect').parent().parent().parent().parent().parent().prev("div").find("div.first_collapsable").removeClass("first_collapsable").addClass("first_expandable");
				}else if($('.uploadSelect').parent().parent().parent().parent().parent().prev("div").find("div.last_collapsable").attr("class") == "last_collapsable")
				{
					$('.uploadSelect').parent().parent().parent().parent().parent().prev("div").find("div.last_collapsable").removeClass("last_collapsable").addClass("last_expandable");
				}else
				{
					$('.uploadSelect').parent().parent().parent().parent().parent().prev("div").find("div.collapsable").removeClass("collapsable").addClass("expandable");
				}
				//判断第二级是第几个，父级转换样式
				if($('.uploadSelect').parent().parent().parent().prev("div").find("div.first_collapsable").attr("class") == "first_collapsable")
				{
					$('.uploadSelect').parent().parent().parent().prev("div").find("div.first_collapsable").removeClass("first_collapsable").addClass("first_expandable");
				}else if($('.uploadSelect').parent().parent().parent().prev("div").find("div.last_collapsable").attr("class") == "last_collapsable")
				{
					$('.uploadSelect').parent().parent().parent().prev("div").find("div.last_collapsable").removeClass("last_collapsable").addClass("last_expandable");
				}else
				{
					$('.uploadSelect').parent().parent().parent().prev("div").find("div.collapsable").removeClass("collapsable").addClass("expandable");
				}
				
				//被点击的本身转换样式
				$('.uploadSelect').prev("div.collapsable").removeClass("collapsable").addClass("expandable");
				//展开一级类
				$('.uploadSelect').parent().parent().parent().parent().parent().css('display','block');
				//展开二级类
				$('.uploadSelect').parent().parent().parent().css('display','block');
				//展开四级类
				$('.uploadSelect').parent().next().css('display','block');
			}

			//右侧导航条title赋值
			cNameValue = $('.uploadSelect').parent().parent().parent().parent().parent().parent().find('a').html()+' > '+$('.uploadSelect').parent().parent().parent().parent().find('a').html()+' > '+$('.uploadSelect').html();
		}



		
		/**
		* 点击四级分类时
		*/

		else if($('.uploadSelect').attr('level') == 'four')
		{
			//判断第一级是第几个，父级转换样式
			if($('.uploadSelect').parent().parent().parent().parent().parent().parent().parent().prev("div").find("div.first_collapsable").attr("class") == "first_collapsable")
			{
				$('.uploadSelect').parent().parent().parent().parent().parent().parent().parent().prev("div").find("div.first_collapsable").removeClass("first_collapsable").addClass("first_expandable");
				
			}else if($('.uploadSelect').parent().parent().parent().parent().parent().parent().parent().prev("div").find("div.last_collapsable").attr("class") == "last_collapsable")
			{
				$('.uploadSelect').parent().parent().parent().parent().parent().parent().parent().prev("div").find("div.last_collapsable").removeClass("last_collapsable").addClass("last_expandable");
			}else
			{
				$('.uploadSelect').parent().parent().parent().parent().parent().parent().parent().prev("div").find("div.collapsable").removeClass("collapsable").addClass("expandable");
			}
			
			//判断第二级是第几个，父级转换样式
			
			if($('.uploadSelect').parent().parent().parent().parent().parent().prev("div").find("div.first_collapsable").attr("class") == "first_collapsable")
			{
				$('.uploadSelect').parent().parent().parent().parent().parent().prev("div").find("div.first_collapsable").removeClass("first_collapsable").addClass("first_expandable");
				
			}else if($('.uploadSelect').parent().parent().parent().parent().parent().prev("div").find("div.last_collapsable").attr("class") == "last_collapsable")
			{
				$('.uploadSelect').parent().parent().parent().parent().parent().prev("div").find("div.last_collapsable").removeClass("last_collapsable").addClass("last_expandable");
			}else
			{
				$('.uploadSelect').parent().parent().parent().parent().parent().prev("div").find("div.collapsable").removeClass("collapsable").addClass("expandable");
			}

			//判断第三级是第几个，父级转换样式
			
			if($('.uploadSelect').parent().parent().parent().prev("div").find("div.first_collapsable").attr("class") == "first_collapsable")
			{
				$('.uploadSelect').parent().parent().parent().prev("div").find("div.first_collapsable").removeClass("first_collapsable").addClass("first_expandable");
				
			}else if($('.uploadSelect').parent().parent().parent().prev("div").find("div.last_collapsable").attr("class") == "last_collapsable")
			{
				$('.uploadSelect').parent().parent().parent().prev("div").find("div.last_collapsable").removeClass("last_collapsable").addClass("last_expandable");
			}else
			{
				$('.uploadSelect').parent().parent().parent().prev("div").find("div.collapsable").removeClass("collapsable").addClass("expandable");
			}
			//展开一级类
			$('.uploadSelect').parent().parent().parent().parent().parent().parent().parent().css('display','block');
			//展开二级类
			$('.uploadSelect').parent().parent().parent().parent().parent().css('display','block');
			//展开三级类
			$('.uploadSelect').parent().parent().parent().css('display','block');

			//右侧导航条title赋值
			cNameValue = $('.uploadSelect').parent().parent().parent().parent().parent().parent().parent().parent().find('a').html()+' > '+$('.uploadSelect').parent().parent().parent().parent().parent().parent().find('a').html()+' > '+$('.uploadSelect').parent().parent().parent().parent().find('a').html()+' > '+$('.uploadSelect').html();
		}


		
		
		/**
		* 点击一级分类时
		*/
		else
		{
			//回写样式
			var backNum = <?php echo @$this->params['pass'][0]?$this->params['pass'][0]:'null';?>;
			if(backNum == null)
			{
			}else
			{
				if($('.uploadSelect').prev("div").attr('class') == 'first_collapsable')
				{
					$('.uploadSelect').prev("div").removeClass("first_collapsable").addClass("first_expandable");
					$('.uploadSelect').parent().next().css('display','block');
				}else if($('.uploadSelect').prev("div").attr('class') == 'last_collapsable')
				{
					$('.uploadSelect').prev("div").removeClass("last_collapsable").addClass("last_expandable");
					$('.uploadSelect').parent().next().css('display','block');
				}else
				{
					$('.uploadSelect').prev("div").removeClass("collapsable").addClass("expandable");
					$('.uploadSelect').parent().next().css('display','block');
				}
			}
			cNameValue = $('.uploadSelect').html();
		}
		$("#cName").html(cNameValue);
	},1);
	

	//上传弹出层，选择分类时改写面包屑标签
	$('.categorySelect').click(function(){
		var cNameValue = null;
		var oneMenuName = $(this).parent().parent().parent().parent().find('a').html();
		var twoMenuName = $(this).parent().parent().parent().parent().parent().prev("div").find('a').html();
		var threeMenuName = $(this).parent().parent().parent().parent().parent().parent().parent().prev("div").find('a').html();
		if($(this).attr('level') == 'two')
		{
			cNameValue = oneMenuName+' > '+$(this).html();
		}else if($(this).attr('level') == 'three')
		{
			cNameValue = twoMenuName+' > '+oneMenuName+' > '+$(this).html();
		}else if($(this).attr('level') == 'four')
		{
			cNameValue = threeMenuName+' > '+twoMenuName+' > '+oneMenuName+' > '+$(this).html();
		}else{
			cNameValue = $(this).html();
		}
		$("#cName").html(cNameValue);
	});
});

/*分析xml*/
CloudiaTransfer.handleingXML=function(xml){
	var XMLArr = new Array();
	XMLArr['name'] = new Array();
	XMLArr['path'] = new Array();
	XMLArr['size'] = new Array();
	var reName = /[n][a][m][e][=][\"][^\"]*[\"]/g;
	var rePath = /[p][a][t][h][=][\"][^\"]*[\"]/g;
	var reSize = /[s][i][z][e][=][\"][^\"]*[\"]/g;
	var arr;
	while((arr = reName.exec(xml)) !=null){
		for(key in arr){
			if(!isNaN(key)){
				//XMLArr['name'][XMLArr['name'].length] = arr[key];
				var tempName=arr[key];
                var relaName=tempName.replace(/[n][a][m][e][=][\"]/g,'');
				relaName=relaName.replace('"','');
				XMLArr['name'][XMLArr['name'].length] =relaName;
			}
		} 
	}
	while((arr = rePath.exec(xml)) !=null){
		for(key in arr){
			if(!isNaN(key)){
				//XMLArr['path'][XMLArr['path'].length] = arr[key];
				 var tempPath=arr[key];
	                var relaPath=tempPath.replace(/[p][a][t][h][=][\"]/g,'');
					relaPath=relaPath.replace('"','');
					XMLArr['path'][XMLArr['path'].length] = relaPath;
			}
		} 
	}
	while((arr = reSize.exec(xml)) !=null){
		for(key in arr){
			if(!isNaN(key)){
				//XMLArr['size'][XMLArr['size'].length] = arr[key];
				var tempSize=arr[key];
                var relaSize=tempSize.replace(/[s][i][z][e][=][\"]/g,'');
				relaSize=relaSize.replace('"','');
				XMLArr['size'][XMLArr['size'].length] = relaSize;
			}
		} 
	}
	return XMLArr;
}

/*获得弹窗返回的选择文件信息 */
function getSelectList()
{
	var guid=newGuid();
	var num = 0;
	var maxnum = 100;
	var timer = setInterval(function(){
		num++;
		if(num == maxnum)
		{
			clearInterval(timer);
		}
		$("#upload_file").find("div").each(function() {
            if($(this).attr('fguid') == guid)
			{
				clearInterval(timer);
			}
        });
		$.ajax({
			type:'GET',
			url: "http://127.0.0.1:6789/?Request=FileList",
			dataType:'jsonp',
			jsonp: "callbackparam",
			jsonpCallback:"success_jsonpCallback",
			cache: false,
			timeout: 360000,
			success:function(data)
			{
				var xmlDom = CloudiaTransfer.handleingXML(data[0].Result);
				for(var i=0;i < xmlDom['name'].length;i++)
				{
					var filename=xmlDom['name'][i];
					var filepath=xmlDom['path'][i];
					var filesize=xmlDom['size'][i];
					var filetype=filepath.substring(filepath.lastIndexOf('.')+1);
					if(filename!=''&&filepath!='')
					{
						CloudiaTransfer.FileInfo['FileName']=filename;
						CloudiaTransfer.FileInfo['FilePath']=encodeURI(filepath);
						CloudiaTransfer.FileInfo['ContentID']=newGuid();
						
						var date=new Date();
						var y=date.getFullYear();
						var m=date.getMonth()+1;
						var d=date.getDate();
						
						var SaveFilePath=CloudiaTransfer.BasePath+'/'+y+"/"+m+"/"+d+"/"+CloudiaTransfer.FileInfo['ContentID']+"/";
						
						var ftype=CloudiaTransfer.FileInfo['FilePath'].substring(CloudiaTransfer.FileInfo['FilePath'].lastIndexOf('.')+1);
						var selectValue = $("#templateid option:selected").text();
						var arr = selectValue.split('|');
						if($.trim(arr[0]) == '视频')
						{
							var videoArr = '<?php echo strtolower(UPLOAD_VIDEO_FILE_FORMAT);?>'.split('|');
							if($.inArray(('.'+ftype).toLowerCase(),videoArr)<0)
							{
								alertMsg.error("文件格式与模板类型不匹配！");
								return false;
							}
						}else
						{
							var audioArr = '<?php echo strtolower(UPLOAD_AUDIO_FILE_FORMAT);?>'.split('|');
							if($.inArray(('.'+ftype).toLowerCase(),audioArr)<0)
							{
								alertMsg.error("文件格式与模板类型不匹配！");
								return false;
							}
						}
						$("#defaultUploadInput").hide();
						$('#upload_file').append('<div fguid="'+guid+'"><input class="cmpcInput" type="text" name="filename" ftype="'+ftype+'" fsize="'+filesize+'" value="'+CloudiaTransfer.FileInfo['FileName']+'" fpath="'+CloudiaTransfer.FileInfo['FilePath']+'"/><a class="cmpcCancel">X</a></div>');
					}
				}
			},
		});
	},2000);
}

/*js生成guid */
function newGuid()
{
    var guid = "";
    for (var i = 1; i <= 32; i++){
      var n = Math.floor(Math.random()*16.0).toString(16);
      guid +=   n;
    }
    return guid;
}


/*添加任务返回请求xml字符串*/
function addTask(str){
	var json=eval("("+str+")");
	var xmlStr="<CloudiaTransfer>";
	xmlStr+="<AddTask>";
	xmlStr+="<Request>";
	xmlStr+="<ContentID>"+json.ContentID+"</ContentID>";
	xmlStr+="<ContentName>"+json.ContentName+"</ContentName>";
	xmlStr+="<TransferType>0</TransferType>";
	xmlStr+="<TransferMode>0</TransferMode>";
	xmlStr+="<SystemName>cmpc</SystemName>";
	xmlStr+="<UserName></UserName>";
	xmlStr+="<FtpAddress><?php echo $ftpaddress;?></FtpAddress>";
	xmlStr+="<FtpUserName><?php echo $ftpuser;?></FtpUserName>";
	xmlStr+="<Password><?php echo $ftppass;?></Password>";
	xmlStr+="<FtpPort>21</FtpPort>";
	xmlStr+="<FileList>";
	xmlStr+="<PathName>"+json.FileList.PathName+"</PathName>";
	xmlStr+="<AffixInfo></AffixInfo>";
	xmlStr+="<SubDirectory>"+json.FileList.SubDirectory+"</SubDirectory>";
	xmlStr+="<TargetFileName>"+json.FileList.TargetFileName+"</TargetFileName>";
	xmlStr+="</FileList>";
	xmlStr+="<Priority>0</Priority>";
	xmlStr+="<FeedBackInfo>";
	xmlStr+="<FeedbackCallMode>2</FeedbackCallMode>";
	xmlStr+="<ProgroessFrequence>-1</ProgroessFrequence>";
	xmlStr+="<WebServiceNode>";
	xmlStr+="<WebServiceUrl><?php echo FULL_BASE_URL;?>CmpcService.php</WebServiceUrl>";
	xmlStr+="<ServiceName>CmpcService</ServiceName>";
	xmlStr+="<FunctionName>getTmpResult</FunctionName>";
	xmlStr+="</WebServiceNode>";
	xmlStr+="</FeedBackInfo>";
	xmlStr+="</Request>";
	xmlStr+="</AddTask>";
	xmlStr+="</CloudiaTransfer>";
	return xmlStr;
}

/*开始上传*/
function ftpUpload(str)
{
	$.ajax({
		type:'GET',
		url: "http://127.0.0.1:6789/?Request=CloudiaTransfer"+addTask(str),
		dataType: 'jsonp',
		jsonp: "callbackparam",
		jsonpCallback:"success_jsonpCallback",
		cache: false,
		success:function(data)
		{
		}
	});
}

/*获取上传后的返回信息，如进度*/
function getResponse(conId){
	$.ajax({
		type:'GET',
		url: "http://127.0.0.1:6789/?Request=CloudiaTransfer<CloudiaTransfer><AddTask><TransferFeedback><Request><ContentID>"+conId+"</ContentID></Request></TransferFeedback></AddTask></CloudiaTransfer>",
		dataType: 'jsonp',
		jsonp: "callbackparam",
		jsonpCallback:"success_jsonpCallback",
		cache: false,
		success:function(data)
		{
			var pvalue=$("Progress",data[0].Result).text();
			alert(data[0].Result); 
			alert(pvalue);

			if($(data).find('Progress').text()!=100){
				setTimeout("getResponse('"+conId+"')",2000);
			}else{
				alert($(data).find('Progress').text());
				//入库；
				$('#'+$(data).find('ContentID').text()).progressbar('value',100);
				var categoryid=$('div[fguid="'+$(data).find('ContentID').text()+'"]').attr('cid');
				var templateid=$('div[fguid="'+$(data).find('ContentID').text()+'"]').attr('tid');
				var filename=$('div[fguid="'+$(data).find('ContentID').text()+'"]').attr('fname');
				var description=$('div[fguid="'+$(data).find('ContentID').text()+'"]').attr('fdes');
				var fvideoname=$('div[fguid="'+$(data).find('ContentID').text()+'"]').attr('fvideoname');
				var backSendData='{filename:"'+filename+'",templateid:"'+templateid+'",categoryid:"'+categoryid+'",description:"'+description+'",fvideoname:"'+fvideoname+'"}';
				var backUploadUrl=$('div[fguid="'+$(data).find('ContentID').text()+'"]').attr('fdir');;
				var backUploadType=$('div[fguid="'+$(data).find('ContentID').text()+'"]').attr('ftype');
				var backUploadSize=$('div[fguid="'+$(data).find('ContentID').text()+'"]').attr('fsize');
				var backId=$(data).find('ContentID').text().trim();
				var da='{backSendData:'+backSendData+',backUploadUrl:"'+backUploadUrl+'",backUploadType:"'+backUploadType+'",backUploadSize:"'+backUploadSize+'",backId:"'+backId+'"}';
				var redata=eval("("+da+")");
				$.post("<?php $this->webroot;?>Resources/saveFtpVideo",redata,function(json){
					delRowDiv($(data).find('ContentID').text());
				});
				delRowDiv($(data).find('ContentID').text());
			}

		}
	});
}

/*返回str结构如'{ContentID:"db697478f50a4910b114b0ac410530f5",ContentName:"测试",FileList:[{PathName:"D:/电影/LinkinPark.mp4",SubDirectory:"/upload/2012-07-18/",TargetFileName:"测试文件"},{PathName:"D:/电影/LinkinPark1.mp4",SubDirectory:"/upload/2012-07-18/",TargetFileName:"测试文件1"}]}*/
function getFileStr(conId)
{
	var ContentID=conId;
	var PathName=$('div[fguid="'+conId+'"]').find('input[name=filename]').attr('fpath').replace(/\\/g,"/").trim();
	var SubDirectory = '/source/'+getTimeFile()+'/';
	$('div[fguid="'+conId+'"]').attr('fdir',SubDirectory);
	var TargetFileName=new Date().getTime().toString()+Math.floor(Math.random()*10+10);
	$('div[fguid="'+conId+'"]').attr('fvideoname',TargetFileName);
	var arr='{"PathName":"'+PathName+'","SubDirectory":"'+SubDirectory+'","TargetFileName":"'+TargetFileName+'"}';
	return '{"ContentID":"'+ContentID+'","ContentName":"'+TargetFileName+'","FileList":'+arr+'}';
}

/*根据日期返回当天的文件夹*/
function getTimeFile(){
	var dt,time;
	dt=new Date();
	time=dt.getFullYear()+'';
	time +=((dt.getMonth()+1)>9?(dt.getMonth()+1)+'':"0"+(dt.getMonth()+1)+'');
	time +=(dt.getDate()>9?dt.getDate():"0"+dt.getDate());
	return time;
}

//子模板滑动
function G(id){  
  return document.getElementById(id);
}  
function switchTab(n){  
	for(var i=1;i<=<?php echo @$allTranscode?count($allTranscode):0;?>;i++){  
		G("uploadtab" + i).className = "";  
		G("tabCon" + i).style.display = "none";  
	}  
	G("uploadtab" + n).className = "active";  
	G("tabCon" + n).style.display = "block";  
} 	
</script>

<div class="pageContent" style="border:0;">
	<div style="float:left;width:180px;">
		<div class="panel" defH="350">
		 <h1>选择栏目</h1>
		 <div>
		<ul id="videoTree" class="tree expand uploadCategory">
			<?php foreach($tree as $blist):?>
			<li><a class="categorySelect<?php if(@$this->params['pass'][0] == $blist['Category']['id']){echo ' uploadSelect';}elseif(empty($this->params['pass'][0]) && $tree[0]['Category']['id'] == $blist['Category']['id']){echo ' uploadSelect';}?>" categoryid="<?php echo $blist['Category']['id']?>"><?php echo $blist['Category']['name']?></a>
				<?php if(count($blist['children']) > 0):?>
				<ul>
					<?php foreach($blist['children'] as $clist):?>
					<li><a level="two" class="categorySelect<?php if(@$this->params['pass'][0] == $clist['Category']['id']){echo ' uploadSelect';}?>" categoryid="<?php echo $clist['Category']['id']?>"><?php echo $clist['Category']['name']?></a>
                    	<?php if(count($clist['children']) > 0):?>
                        <ul>
                            <?php foreach($clist['children'] as $dlist):?>
                            <li><a level="three" class="categorySelect<?php if(@$this->params['pass'][0] == $dlist['Category']['id']){echo ' uploadSelect';}?>" categoryid="<?php echo $dlist['Category']['id']?>"><?php echo $dlist['Category']['name']?></a>
                            	<?php if(count($dlist['children']) > 0):?>
                                <ul>
                                    <?php foreach($dlist['children'] as $elist):?>
                                    <li><a level="four" class="categorySelect<?php if(@$this->params['pass'][0] == $elist['Category']['id']){echo ' uploadSelect';}?>" categoryid="<?php echo $elist['Category']['id']?>"><?php echo $elist['Category']['name']?></a></li>
                                    <?php endforeach;?>
                                </ul>
                                <?php endif;?>
                            </li>
                            <?php endforeach;?>
                        </ul>
                        <?php endif;?>
                    </li>
					<?php endforeach;?>
				</ul>
				<?php endif;?>
			</li>
			<?php endforeach;?>
		</ul> 
		</div>
		</div>
	</div>
	<div style="float:right;width:580px;" id="upload_dialog">
			<input type="hidden" name="cid" value="<?php echo @$this->params['pass'][0]?$this->params['pass'][0]:$tree[0]['Category']['id']?>" id="categoryid">
			<div class="cell" style="height:25px;">
				<div class="title">上传分类：</div>
				<span id="cName"></span>
				<div class="clear"></div>
			</div>

			<div class="cell">
				<div class="title">模板选择：</div>
				<div><?php if(is_array($options)){$options = $options;}else{$options=array(''=>'没有模板');}echo $this->Form->input('templateid', array('type'=>'select','target'=>'subTemplate','selectUrl'=>'materials/transcodeGroup/{value}','options'=>$options,'selected'=>$defaultTemplatesId,'name'=>'templateid','class'=>'combox','label'=>false));?></div>
				<div class="clear"></div>
			</div>

			<div class="cell">
				<div class="title short">文件名称：</div>
				<div id="upload_file"><input type="text" name="data[upload_file]" id="defaultUploadInput" style="display:block;" value="请点击添加素材添加文件！" maxlength="50" class="readonly uploadIw" readonly="true" /></div>
				<div id="ftp_uploads"></div>
				<div class="clear"></div>
			</div>
			
			<div class="cell">
				<div class="title">&nbsp;</div>
				<div class="buttonActive">
					<div class="buttonContent">
						<button id="ftp_upload_submit">开始上传</button>
					</div>
				</div>
				<div class="button">
					<div class="buttonContent">
						<button type="button" class="close" id="cancelUpload">关闭</button>
					</div>
				</div>
				<div class="clear"></div>
			</div>
			
			<!-- 详细模板 -->
			<div class="templateDetail" id="subTemplate">
				<div class="title">模板信息：</div>
				<h1><?php echo $transcodeGroupName;?></h1>
	            <div class="uploadtabs">  
	            <ul>
	            	<?php
	            	if(isset($allTranscode)):
	            	$allTranscodeNum = 0;
	            	foreach($allTranscode as $value):
	            	$allTranscodeNum++;
	            	?>
                	<?php if($value['params']['Transcode']['type'] == 1):?>
	                <li id="uploadtab<?php echo $allTranscodeNum;?>" onClick="switchTab(<?php echo $allTranscodeNum;?>)"<?php if($allTranscodeNum == 1){?> class="active"<?php }?>><?php echo $value['title'];?></li>
	                <?php else:?>
	                <li id="uploadtab<?php echo $allTranscodeNum;?>" onClick="switchTab(<?php echo $allTranscodeNum;?>)"<?php if($allTranscodeNum == 1){?> class="active"<?php }?>><?php echo $value['title'];?></li>
	                <?php endif;?>
					<?php endforeach;else:echo '<li id="uploadtab1" onClick="switchTab(1)" class="active">'.__('No subtemplates').'</li>'; endif;?> 
	            </ul>  
	            <div class="tabCon">
	            	<?php
	            	if(isset($allTranscode)):
	            	$allTranscodeNum = 0;
	            	foreach($allTranscode as $value):
	            	$allTranscodeNum++;
	            	?>
	            	<?php if($value['params']['Transcode']['type'] == 1):?>
	                <div id="tabCon<?php echo $allTranscodeNum;?>"<?php if($allTranscodeNum != 1){?> style="display:none;"<?php }?>>
						<dl>
							<dt>子模板名称：<?php echo $value['title'];?></dt>
							<dd>视频部分</dd>
							<dd><span>幅面：<?php echo @$value['params']['Transcode']['ImageWidth'];?>*<?php echo @$value['params']['Transcode']['ImageHeight'];?></span><span>编码格式：<?php echo $value['params']['Transcode']['VideoFormat'];?></span></dd>
							<dd><span>码率：<?php echo $value['params']['Transcode']['BitRate'];?>bps</span><span>视频帧率：<?php echo $value['params']['Transcode']['FrameRate'];?></span></dd>
							<dd>音频部分</dd>
							<dd><span>音频编码：<?php echo $value['params']['Transcode']['AudioFormat'];?></span></dd>
							<dd><span>采样率：<?php echo $value['params']['Transcode']['SamplesPerSec']=="null"?"null":($value['params']['Transcode']['SamplesPerSec']/1000)."K"?></span><span>采样位率：<?php echo $value['params']['Transcode']['BitsPerSample'];?></span></dd>
							<dd>
                                <div class="f">分片</div>
                                <div style="clear: both;"></div>
                            </dd>
							<dd>
                                <div class="f font"><?php echo @$value['params']['Transcode']['SliceTime']?$value['params']['Transcode']['SliceTime']:0;?></div>
                                <div style="clear: both;"></div>
                            </dd>
						</dl>
					</div>

	                <?php endif;?>
					<?php endforeach;else:echo '<li id="uploadtab1" style="border: 1px solid #e8e8e8;" onClick="switchTab(1)" class="active">'.__('No subtemplates').'</li>'; endif;?>
	            </div>  
	            </div>
            </div>
	</div>
</div>
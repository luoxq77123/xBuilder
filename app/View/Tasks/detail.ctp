<script>
function ftp_Download()
{
	var clientStatus = false;
	$.ajax({
		type:'GET',
		url:"http://127.0.0.1:6789/?Request=CheckState",
		dataType:'jsonp',
		cache: false,
		async:false,
		jsonpCallback:"success_jsonpCallback",
		jsonp: "callbackparam",
		success : function(json){
			try
			{
			   var State=json[0].State.toLowerCase();
			   if(State=="ok")
			   {
				   clientStatus=true;
			   }
			}catch(e)
			{
			  clientStatus=false;
			}
        }
	});
	setTimeout(function(){
		if(clientStatus == false)
		{
			alertMsg.error('请启动客户端服务再使用客户端下载！');
			return false;
		}
		downstart();
	},1000);
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
function downstart()
{
	setTimeout(function(){
		if($("#ftpDownload").attr('downtype') == 'nosplit')
		{
			//var ContentID = $("#ftpDownload").attr('cid');
			var ContentID = newGuid();
			var xmlStr="<CloudiaTransfer><AddTask><Request><ContentID>"+ContentID+"</ContentID><ContentName>"+$("#ftpDownload").attr('fname')+"</ContentName><TransferType>1</TransferType><TransferMode>0</TransferMode><SystemName>cmpc</SystemName><UserName/><FtpAddress><?php echo $ftpaddress;?></FtpAddress><FtpUserName><?php echo $ftpuser;?></FtpUserName><Password><?php echo $ftppass;?></Password><FtpPort>21</FtpPort><Priority>0</Priority><FeedBackInfo><FeedbackCallMode>2</FeedbackCallMode><ProgroessFrequence>-1</ProgroessFrequence><WebServiceNode><WebServiceUrl></WebServiceUrl><ServiceName>CmpcService</ServiceName><FunctionName>getTmpResult</FunctionName></WebServiceNode></FeedBackInfo><filelist><pathname>/desc"+$("#ftpDownload").attr('dpath')+"</pathname><affixinfo></affixinfo><subdirectory></subdirectory><targetfilename></targetfilename></filelist></Request></AddTask></CloudiaTransfer>";
			var getUrl = "http://127.0.0.1:6789/?Request=CloudiaTransfer"+xmlStr;
		}else
		{
			var xmlStart = "<CloudiaTransfer><AddTask><Request><ContentID>"+$("#ftpDownload").attr('cid')+"</ContentID><ContentName>"+$("#ftpDownload").attr('fname')+"</ContentName><TransferType>1</TransferType><TransferMode>0</TransferMode><SystemName>cmpc</SystemName><UserName/><FtpAddress><?php echo $ftpaddress;?></FtpAddress><FtpUserName><?php echo $ftpuser;?></FtpUserName><Password><?php echo $ftppass;?></Password><FtpPort>21</FtpPort><Priority>0</Priority><FeedBackInfo><FeedbackCallMode>2</FeedbackCallMode><ProgroessFrequence>-1</ProgroessFrequence><WebServiceNode><WebServiceUrl></WebServiceUrl><ServiceName>CmpcService</ServiceName><FunctionName>getTmpResult</FunctionName></WebServiceNode></FeedBackInfo>";
			var xmlFileList = $("#ftpDownload").attr('dpath').split("|");
			var FileList = '';
			for(var i=0;i<xmlFileList.length-1;i++)
			{
				FileList+="<FileList><PathName>/desc"+xmlFileList[i]+"</PathName><AffixInfo></AffixInfo><SubDirectory></SubDirectory><TargetFileName></TargetFileName></FileList>";
			}
			var xmlEnd  = "</Request></AddTask></CloudiaTransfer>";
			var getUrl = "http://127.0.0.1:6789/?Request=CloudiaTransfer"+xmlStart+FileList+xmlEnd;
		}
		$.ajax({
			type:'GET',
			url: getUrl,
			dataType: 'jsonp',
			jsonp: "callbackparam",
			jsonpCallback:"success_jsonpCallback",
			cache: false,
			success:function(data){
			}
		});
	},2000);
}

$(function(){
	$('.detail_list').loadUrl('<?php echo $this->Html->url("/tasks/view/".$content['Content']['task_id'])?>');
});
</script>
<div class="cmpc_ri_top_tit"><?php echo __('Task detail');?></div>
<div class="xxinfo" layoutH="52">
	<div class="detail_list"></div>
	<div class="xxinfo_tit"><?php echo StringExpand::cutStr($content['Content']['title'],24);?></div>
	<div class="xxinfo_body">
		<?php
			$num=0;
			foreach($content['Video'] as $v):
		?>
		<?php if($content['Content']['type'] == 1):?>
		
			<?php if($v['originalFile'] == 0 || $v['originalFile'] == 3):
	        $num++;
	        ?>
			<div class="zmo zmos">转码文件<?php echo $num;?></div>
			<div class="xaizai">
			  <ul>
				  <li>文件格式：<?php echo $v['fileFormat']?></li>
				  <li>视频格式：<?php echo $v['videoFormat']?></li>
				  <li><span class="infoso">视频时长：<?php echo $v['duration']?gmstrftime('%H:%M:%S',$v['duration']/10000000):'NULL';?></span><span class="infos">幅面：<?php echo $v['pictureWidth']?>*<?php echo $v['pictureHeight']?></span></li>
				  <li><span class="infoso">码率：<?php echo $v['fileRate']?>kbps</span></li>
				  <li>
				  	<?php if($v['originalFile'] == 0){?><div class="infosos downfile"><a href='<?php echo $this->Html->url('/download/web?filePath='.$v['filePath']);?>'>Web下载</a></div><?php }?>
				  	<!-- <div class="infosos downfile"><a style="cursor:pointer;" id="ftpDownload" downtype="<?php //if($v['originalFile'] == 3){?>split<?php //}else{?>nosplit<?php //}?>" dpath="<?php //if($v['originalFile'] == 3){foreach($splits as $vv){echo $vv['Split']['filePath']."|";}}else{echo $v['filePath'];}?>" cid="<?php //echo $v['content_id'];?>" fname="<?php //echo $content['Content']['title']."_".$v['mediaType'];?>" onclick='ftp_Download();'>Ftp下载</a></div> -->
				  </li>
			  </ul>
			</div>
	        <?php
	        endif;
	        ?>
        <?php else:?>
			<?php
				if($v['originalFile'] == 0):
		        $num++;
	        ?>
			<div class="zmo zmos">转码文件<?php echo $num;?></div>
			<div class="xaizaiaudio">
			  <ul>
				  <li>文件格式：<?php echo $v['fileFormat'];?></li>
				  <li><span class="infoso">音频时长：<?php echo $v['duration']?gmstrftime('%H:%M:%S',$v['duration']/10000000):'NULL';?></span><span class="infos">音频码率：<?php echo $v['fileRate'];?></span></li>
				  <li>
				  	<?php if($v['originalFile'] == 0){?><div class="infosos downfile"><a href='<?php echo $this->Html->url('/download/web?filePath='.$v['filePath']);?>'>Web下载</a></div><?php }?>
				  	<!-- <div class="infosos downfile"><a style="cursor:pointer;" id="ftpDownload" dpath="<?php //echo $v['FileUrl'];?>" cid="<?php //echo $v['ContentId'];?>" fname="<?php //echo $detail['title']."_".$v['MediaType'];?>" onclick='ftp_Download();'>Ftp下载</a></div> -->
				  </li>
			  </ul>
			</div>
			<?php endif;?>
			
		<?php endif;?>
		
		<?php endforeach;?>
	</div>
</div>

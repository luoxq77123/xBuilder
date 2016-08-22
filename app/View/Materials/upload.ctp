<script type="text/javascript">
$(function(){
	var iTop = ($(window).height()- 550)/2 + 113;
	$('#file_upload').css({
		left: ($(window).width()- 800)/2 + 647,
		top: iTop > 116 ? iTop : 116,
		"z-index"	: 1001
	});

	$('.categorySelect').click(function(){
		$('#categoryid').val($(this).attr('categoryid'));
	});
	$('#cancelUpload').click(function(){
		$('#upload_file').find("input[name='filename']").each(function(){
			$('#file_upload').uploadify('cancel',$(this).attr('fid'));
		});
	});
	$('#upload_submit').click(function(){
		if($('#upload_file').find('input[name="filename"]').val()){
			var cid = $('#categoryid').val();
			var tid = $('#templateid').val();
			$('#upload_file').find('input[name="filename"]').each(function(){
				var fname = $(this).val();
				var fid = $(this).attr('fid');
				$('#'+fid).attr('fname',fname);
				$('#'+fid).attr('cid',cid);
				$('#'+fid).attr('tid',tid);
			});
			$('#file_upload').uploadify('upload','*');
			$('#downlist').show();
			$.pdialog.uploadcloseCurrent();
			return false;
		}else
		{
			alertMsg.error('没有文件不能上传！');
			return false;
		}
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
			<input type="hidden" name="cid" value="<?php echo @$cid?:$tree[0]['Category']['id']?>" id="categoryid">
			<div class="cell" style="height:25px;">
				<div class="title">上传分类：</div>
				<span id="cName"></span>
				<div class="clear"></div>
			</div>

			<div class="cell">
				<div class="title">模板选择：</div>
				<div><?php if(!is_array($options)){$options=array(''=>'没有模板');}echo $this->Form->input('templateid', array('type'=>'select','target'=>'subTemplate','selectUrl'=>'transcodeGroups/view_tab/{value}','options'=>$options,'selected'=>$defaultTemplatesId,'name'=>'templateid','class'=>'combox','label'=>false));?></div>
				<div class="clear"></div>
			</div>

			<div class="cell">
				<div class="title short">文件名称：</div>
				<div id="upload_file"><input type="text" name="data[upload_file]" id="defaultUploadInput" style="display:block;" value="请点击添加素材添加文件！" maxlength="50" class="readonly uploadIw" readonly="true" /></div>
				<div class="clear"></div>
			</div>
			
			<div class="cell">
				<div class="title">&nbsp;</div>
				<div class="buttonActive">
					<div class="buttonContent">
						<button id="upload_submit">开始上传</button>
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
			<?php echo $this->element('transcodeGroup/view',array('transcodeGroups'=>$transcodeGroups,'formatCode'=>$formatCode));?>
	</div>
</div>
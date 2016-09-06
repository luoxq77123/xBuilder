<style>
    .ieBox{margin: 6px 0px; float: left; padding: 0; border: 0;+margin: 1px 0px;}
</style> 
<div class="pageContent" style="border:0;">
	<div style="float:left;width:180px;">
		<div class="panel" defH="432">
			<h1>选择分类</h1>
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
	<div style="float:left;width:580px;" id="upload_dialog" layoutH="12">
		<input type="hidden" name="cid" value="<?php echo @$this->params['pass'][0]?$this->params['pass'][0]:$tree[0]['Category']['id']?>" id="categoryid">
		<div class="cell" style="height:25px;">
			<div class="title">上传分类：</div>
			<span id="cName"></span>
			<div class="clear"></div>
		</div>

		<div class="cell">
			<div class="title">模板选择：</div>
			<div><?php if(is_array($options)){$options = $options;}else{$options=array(''=>'没有模板');}echo $this->Form->input('templateid', array('type'=>'select','target'=>'subTemplate','selectUrl'=>'transcodeGroups/view_tab/{value}','options'=>$options,'selected'=>$defaultTemplatesId,'name'=>'templateid','class'=>'combox','label'=>false));?></div>
			<div class="clear"></div>
		</div>

		<div class="cell">
			<div class="title">快速分片：</div>
			<div><?php 
				$value = SPLIT_DEFAULT_VALUE == 0?1:0;
				echo $this->Form->input('is_split',array('type'=>'checkbox','name'=>'is_split','hiddenField' => false,'value'=>$value,'label'=>false,'class'=>'ieBox'));?></div>
			<div class="clear"></div>
		</div>

		<?php if(defined('PUBLISH_PLATFORMS')):?>
		<div class="cell">
			<div class="title">平台选择：</div>
				<ul style="width: 330px;">
					<?php 
					$plats = explode('|',PUBLISH_PLATFORMS);
					foreach($plats as $plat):
						$tmp = explode(',', $plat);
					?>
					<li><input type="checkbox" value="<?php echo $tmp[0]?>" name="platFormId" class="ieBox" ><label><?php echo $tmp[1]?></label></li>
				<?php endforeach;?>
			</ul>
			<div class="clear"></div>
		</div>
		<?php endif;?>

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
			<?php echo $this->element('transcodeGroup/view',array('transcodeGroups'=>$transcodeGroups,'formatCode'=>$formatCode));?>
		</div>
	</div>
	<div style="float:left;width:320px;">
		<div style="border-width: 1px;" class="pageFormContent small-label" layoutH="42">
				<div style="overflow:hidden; font-weight: bold; padding: 10px 5px;font-size: 14px;">
			<div style="float: left; height: 25px; line-height: 25px;">
				元数据:
			</div>
			<div class="buttonActive" style="float: left; margin-left: 32px">
				<div class="buttonContent">
					<button onclick="copyAllGet()" id="copyAll">一键复制</button>
				</div>
			</div>
		</div>

			<div style="display: none;" class="metaDataInit">
				<?php 
        //展示判断开始
				echo $this->element('metadata/metadataList',array('metaData'=>$metaData));
				?>
				<div class="diy-attr"></div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	var CloudiaTransfer = new Object;
	CloudiaTransfer.FileInfo=new Array();
	$(function(){

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
        },5);

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
        $('#categoryid').val($(this).attr('categoryid'));
        $("#cName").html(cNameValue);
    });


    //点击上传
    $('#ftp_upload_submit').die().live('click',function(){
        if($('#upload_file').find('input[name="filename"]').val())
        {
            var cid = $('#categoryid').val();
            var tid = $('#templateid').val();
            var is_split = $('#is_split:checked').val() || <?php echo SPLIT_DEFAULT_VALUE;?>;

            var platformId = new Array();
            $('input[name="platFormId"]:checked').each(function(i,e){
                platformId.push($(this).val());
            });
            var arr = new Array();
            $('#upload_file div').each(function()
            {
                var fguid=$(this).attr('fguid');
                var gid=$(this).attr('data-id');
                var str=getFileStr(fguid);
                var fname=$(this).find('input[name=filename]').val().trim();
                var fsize=$(this).find('input[name=filename]').attr('fsize');
                var ftype=$(this).find('input[name=filename]').attr('ftype');
                var cvalue  = JSON.stringify($('.small-label form#' + gid).serializeArray());
                var delimiter = 'codeDelimiter';
                //转义双引号
                cvalue = cvalue.replace(/"/g, delimiter);
                var backSendData='{filename:"'+fname+'",ftype:"'+ftype+'",fsize:"'+fsize+'",templateid:"'+tid+'",categoryid:"'+cid+'",is_split:"'+is_split+'",platFormId:"'+platformId.join(',')+'",uid:"<?php echo $userInfo['User']['id']?>",uname:"<?php echo $userInfo['User']['account']?>",medaData:"' + cvalue + '"}';
                var da='{backSendData:'+backSendData+',backUploadUrl:"'+$.parseJSON(str).ContentName+'",backUploadPath:"'+$.parseJSON(str).ContentPath+'", backId:"'+fguid+'"}';
                arr.push(da);
                var logscontent = '?fileName='+fname+'&ftype='+ftype+'&fsize='+fsize+'&fguid='+fguid;
                serverlogs(logscontent);
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
                type:'POST',
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

    /* 获得弹窗返回的选择文件信息 */
    function getSelectList(){
        var isSelected = false;
        var num = 0;
        var maxnum = 100;
        var timer = setInterval(function(){
            num++;
            if(num == maxnum){
                clearInterval(timer);
            }
            $("#upload_file").find("div").each(function() {
                if(isSelected){
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
                success:function(data){
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
                            var gid = CloudiaTransfer.FileInfo['ContentID']+ "Form";
                            $('#upload_file').append('<div data-id="' + gid + '" fguid="'+CloudiaTransfer.FileInfo['ContentID']+'"><input class="cmpcInput" type="text" name="filename" ftype="'+ftype+'" fsize="'+filesize+'" value="'+UrlDecode(CloudiaTransfer.FileInfo['FileName'])+'" fpath="'+CloudiaTransfer.FileInfo['FilePath']+'"/><input onclick=checkoutmetaDataForm(\"' + gid + '\") title=编辑元数据 style=float:left; type=radio name=metadataRadio value='+ gid +'><a class="cmpcCancel">X</a></div>');
                            $('.dialogContent .small-label').append($('.pageFormContent .metaDataInit').html());
                            $('.dialogContent .small-label form').css({'display':'none'});
                            $('.dialogContent .small-label form').last().addClass('metaDataForm');
                            $('.dialogContent .small-label form').last().attr('id',gid);
                            // var thisID = $('form#'+gid+' .select').attr('data-id');
                            // $('form#'+gid+' .select').attr('id',gid+'_'+thisID);
                            // $('form#'+gid+' .select').combox();
                            $('.dialogContent .small-label').children('form').first().css({'display':'block'});
                            var firstID = $('.dialogContent .small-label').children('form').first().attr('id');

                            $('div[data-id='+firstID+']').children('input[type=radio]').click();
                            if( isAutoFill ) {
                                var currentValue = $('div[data-id=' + gid + ']').children('input[type=text]').last().val();
                                for(var j=0;j<isAutoFill.length;j++) {
                                $('form#' + gid + ' [name="data[Metadata]['+isAutoFill[j]+']"]').val(currentValue);
                                }
                            }
                        }
                    }
                    isSelected = true;
                }
            });
    },2000);
    }

    /*js生成guid */
    function newGuid(){
        var guid = "";
        $.ajax({
            type:"GET",
            url:"<?php echo $this->Html->url('/tasks/new_guid');?>",
            dataType:"json",
            cache: false,
            async: false,
            success:function(data){
                guid = data.guid;
            }
        })
        return guid;
    }

    function UrlDecode(zipStr){
        var uzipStr="";
        for(var i=0;i<zipStr.length;i++){
            var chr = zipStr.charAt(i);
            if(chr == "+"){
                uzipStr+=" ";
            }else if(chr=="%"){
                var asc = zipStr.substring(i+1,i+3);
                if(parseInt("0x"+asc)>0x7f){
                    uzipStr+=decodeURI("%"+asc.toString()+zipStr.substring(i+3,i+9).toString());
                    i+=8;
                }else{
                    uzipStr+=AsciiToString(parseInt("0x"+asc));
                    i+=2;
                }
            }else{
                uzipStr+= chr;
            }
        }
        return uzipStr;
    }

    function StringToAscii(str){
        return str.charCodeAt(0).toString(16);
    }

    function AsciiToString(asccode){
        return String.fromCharCode(asccode);
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
        xmlStr+="<WebServiceUrl><?php echo FULL_BASE_URL;?>/webservices/callback</WebServiceUrl>";
        xmlStr+="<ServiceName>callback</ServiceName>";
        xmlStr+="<FunctionName>getXML</FunctionName>";
        xmlStr+="</WebServiceNode>";
        xmlStr+="</FeedBackInfo>";
        xmlStr+="</Request>";
        xmlStr+="</AddTask>";
        xmlStr+="</CloudiaTransfer>";
        return xmlStr;
    }
    /*开始上传*/
    function ftpUpload(str){
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
    /*上传文件时先到服务器记录上传日志*/
    function serverlogs(str) {
        $.ajax({
            type: 'GET',
            url: "<?php $this->webroot;?>Tasks/uploadLog/"+str,
            dataType: 'jsonp',
            jsonp: "callbackparam",
            jsonpCallback: "success_jsonpCallback",
            cache: false,
            success: function (data) {
            }
        });
    }

    /*返回str结构如'{ContentID:"db697478f50a4910b114b0ac410530f5",ContentName:"测试",FileList:[{PathName:"D:/电影/LinkinPark.mp4",SubDirectory:"/upload/2012-07-18/",TargetFileName:"测试文件"},{PathName:"D:/电影/LinkinPark1.mp4",SubDirectory:"/upload/2012-07-18/",TargetFileName:"测试文件1"}]}*/
    function getFileStr(conId){
        var ContentID=conId;
        var PathName=$('div[fguid="'+conId+'"]').find('input[name=filename]').attr('fpath').replace(/\\/g,"/").trim();
        var SubDirectory = getTimeFile();
        $('div[fguid="'+conId+'"]').attr('fdir',SubDirectory);
        var TargetFileName=new Date().getTime().toString()+Math.floor(Math.random()*10+10);
        $('div[fguid="'+conId+'"]').attr('fvideoname',TargetFileName);
        var arr='{"PathName":"'+PathName+'","SubDirectory":"'+SubDirectory+'","TargetFileName":"'+TargetFileName+'"}';
        return '{"ContentID":"'+ContentID+'","ContentName":"'+TargetFileName+'","ContentPath":"'+SubDirectory+'","FileList":'+arr+'}';
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
</script>
<style type="text/css">
    .left{
        float: left;
        width: 50%;
    }
    .formBarBtn{
        margin-top: 20px;
    }
    .centerBtn label { width: 60px; }
    .storage_dialog_choose ul.tree  li{
        padding-left: 10px;
        padding-right: 10px;
        cursor: default;
    }
    .storage_dialog_choose ul li div {
        display: block;
        float: right;
        width: 15px;
        background: none;
    }
    .storage_dialog_choose ul li { border-bottom: 1px dotted #ccc; }
    .storage_dialog_choose .panel .panelContent ul.tree li div { border: 0 none; }
    .storage_dialog_choose ul li div div { color: red; cursor: pointer; }
    .storage_dialog_choose ul li div .node { display: none; }
    .choose .unit { overflow: hidden; }
    .choose .more .unit { margin-top: 10px; }
    .choose .h1header{
        background: none repeat scroll 0 0 #f4f4f4;
        border-bottom: 2px solid #74b501;
        font-size: 14px;
        height: 37px;
        line-height: 37px;
        overflow: hidden;
        padding: 0 5px;
        text-indent: 1em;
    }
    .choose .middle {
        height: 303px;
        width:5%;
        text-indent: -1111px;
        background: #f8f8f8;
        border-left:1px solid #eeeded;
        border-right:1px solid #eeeded;
    }
    .choose .formBarBtn { position:absolute;left:199px;top:123px; }
    .choose .formBarBtn .buttonContent{ background: url("./themes/default/images/choose.png") no-repeat scroll 8px 10px rgba(0, 0, 0, 0)}
    .choose .formBarBtn .buttonContent button { width: 30px; }
    .choose .formBarBtn .buttonActive{ background: 0 none; }
</style> 
<div class="" style="border-bottom: 1px solid #b8d0d6;">
	<form action="<?php echo $this->Html->url('/materials/add_transcode_unit');?>" onsubmit="return addTranscodeUnit(this)">
        <input type="hidden" name="filename" value="<?php echo $filename;?>">
        <input type="hidden" name="isdvd" value="<?php echo $isdvd;?>">
        <input type="hidden" name="channel" value="<?php echo @$list[0]['a'][0]['CH']?:0;?>">
        <div class="pageFormContent centerBtn choose" style=" padding: 0px; border: 0 none; ">
            <div class="left" style="width:37%; margin-top: 25px;"> 
                <div class="unit" style="padding-left: 10px;">
                    <label>节目：</label>
                    <select name="pgm" id="pgm_s" class="combox">
                        <?php foreach($list as $pgm_key => $pgm):?>
                            <option value="<?php echo $pgm_key?>"><?php echo $pgm['name'] . '-' . $pgm_key;?></option>
                        <?php endforeach;?>
                    </select>
                </div>

                <div class="more" style="padding-left: 10px;">
                    <div class="unit">
                        <label>视频：</label>
                        <select name="video" class="combox" id="v">
                            <?php 
                            if(count(@$list[0]['v']) > 0):
                                foreach ($list[0]['v'] as $v_key => $video):
                                    ?>
                                <option value="<?php echo $v_key?>"><?php echo $video['name'].'('.$video['Format']. '-' . $v_key . ')'?></option>
                                <?php 
                                endforeach;
                                else:
                                    ?>
                                <option value="">无</option>
                            <?php endif;?>

                        </select>
                        <span class="inputInfo"></span>
                    </div>
                    <div class="unit">
                        <label>音频：</label>
                        <select name="audio" class="combox" id="a">
                            <?php 
                            if(count(@$list[0]['a']) > 0):
                                foreach ($list[0]['a'] as $a_key => $audio):
                                    ?>
                                <option value="<?php echo $a_key?>" data-channel="<?php echo $audio['CH'];?>"><?php echo $audio['name'].'-'.$audio['CH'].'CH('.$audio['Format'] . '-' . $a_key . ')'?></option>
                                <?php 
                                endforeach;
                                else:
                                    ?>
                                <option value="">无</option>
                            <?php endif;?>
                        </select>
                        <span class="inputInfo"></span>
                    </div>
                    <div class="unit">
                        <label>字幕：</label>
                        <select name="cg" class="combox" id="c">
                            <?php 
                            if(count(@$list[0]['c']) > 0):
                                foreach ($list[0]['c'] as $c_key => $cg):
                                    ?>
                                <option value="<?php echo $c_key?>"><?php echo $cg['name'].'('.$cg['Format']. '-' . $c_key . ')'?></option>
                                <?php 
                                endforeach;
                                else:
                                    ?>
                                <option value="">无</option>
                            <?php endif;?>
                        </select>
                        <span class="inputInfo"></span>
                    </div>
                </div>
                <div class="formBarBtn">
                    <ul>
                        <li><div style="margin-left: 50px;" class="buttonActive"><div title="添加" class="buttonContent"><button type="submit"></button></div></div></li>
                    </ul>
                </div>
            </div>
            <div class="left middle">middle</div>
            <div style="width:57.7%;" class="left storage_dialog_choose">
                <div class="">
                    <div class="">
                        <h1 class="h1header">已添加列表
                        </h1>
                        <div id="transcodeTree" layoutH="70">
                            <ul class="tree expand">
                            </ul> 
                        </div>
                    </div>
                </div>
            </div> 

        </div>
    </form>
</div>
<div class="formBar" style="border-width:0px 0 1px 0;">
    <ul>
        <li><div class="button"><div class="buttonContent"><button type="button" class="close">提交</button></div></div></li>
    </ul>
</div>
<script type="text/javascript">
    var list = jQuery.parseJSON('<?php echo json_encode($list);?>');
    var key = ['v','a','c'];
    $(function(){
        // $('.choose').parent().parent('.pageContent').css({'border':'0 none','width':'678px'});
        //自定义header高度
        setTimeout(function(){
          $('.choose').parent().parent().parent('.dialogContent').css({'padding':0});
          $('.choose').parent().parent().parent().siblings('.dialogHeader').find('.dialogHeader_r').css({'height':'43px'});
          $('.choose').parent().parent().parent().siblings('.dialogHeader').find('.dialogHeader_c').css({'height':'43px'});
          $('.choose').parent().parent().parent().siblings('.dialogHeader').css({'height':'43px'});
          var dialogHeight = $('.choose').parent().parent().parent().height();
          $('.choose').parent().parent().parent().css({'height': (dialogHeight+9) +'px'});
              //$('.choose .formBarBtn').css();
          },1);

        $('#a').change(function(){
            $('input[name="channel"]').val($(this).find(':selected').data('channel'));
        });

        $('#pgm_s').change(function(){
            var $this = $(this);
            var pgm_key = $this.val();
            $.each(key,function(i){
            //var now_key = key[i];
            $ref = $('#'+key[i]);

            var html = '';
            if(list[pgm_key][key[i]] && list[pgm_key][key[i]].length > 1){
                $.each(list[pgm_key][key[i]],function(m){
                    html += '<option value="' + m + '">' + list[pgm_key][key[i]][m]['name'] + '</option>';
                })
            }
            var $refCombox = $ref.parents("div.combox:first");
            $ref.html(html).insertAfter($refCombox);
            $refCombox.remove();
            $ref.trigger("refChange").trigger("change").combox();
        });

        });
    });

function addTranscodeUnit(form){
    var $form = $(form);
    if (!$form.valid()) {
        return false;
    }
    $.ajax({
        type: form.method || 'POST',
        url:$form.attr("action"),
        data:$form.serializeArray(),
        cache: false,
        success: function(html){
            var progoram_text = $('.centerBtn').find('#pgm_s').find("option:selected").text();
            var video_text = $('.centerBtn').find('select[name=video]').find("option:selected").text();
            var audio_text = $('.centerBtn').find('select[name=audio]').find("option:selected").text();
            var cg_text = $('.centerBtn').find('select[name=cg]').find("option:selected").text();
            var append_content = '节目(' + progoram_text + ')视频(' + video_text + ')音频(' + audio_text + ')字幕(' + cg_text + ')';
                //同时更新弹出框的文件列表
                if( !isAlreadyExits(append_content) || ($('#upload_file .unit').length < 1) ){
                    $('#defaultUploadInput').hide();
                    $('#upload_file .unitNode').removeClass('expand');
                    $('#upload_file .unitBody').find('.media_list').hide();
                    $('#upload_file').append(html).initUI();
                    appendNewList(append_content);

                    var fid = $('#upload_file .unit').last().attr('data-id');
                    $('.dialogContent .small-label').append($('.pageFormContent .metaDataInit').html());
                    $('.dialogContent .small-label form').css({'display':'none'});
                    $('.dialogContent .small-label form').last().attr('id', + fid);
                    $('.dialogContent .small-label form').last().addClass('metaDataForm');
                    $('.dialogContent .small-label').children('form').first().css({'display':'block'});
                    var firstID = $('.dialogContent .small-label').children('form').first().attr('id');
                    $('div[data-id='+firstID+']').children('input[type=radio]').click();
                 if( isAutoFill ) {
                    var currentValue = $('#clip_' + fid + ' .unitBody .media_title').val();
                    for(var i=0;i<isAutoFill.length;i++) {
                        $('form#' + fid + ' [name="data[Metadata]['+isAutoFill[i]+']"]').val(currentValue);
                        }
                    }
                }else {
                    //todo 去重复
                    alertMsg.error('所选组合已经存在');
                    return false;
                }
            },
            error: DWZ.ajaxError
        });
return false;
}
function appendNewList (append_content) {
        //绑定删除按钮
        var currentBtnID = $('#upload_file .unit').last().attr('data-id');
        var append_content_model = '<li>' + append_content + '<div><div class="node"></div><div onclick="appendNewListDelete(this,'+currentBtnID+')" class="none" title="删除">x</div></div></li>';
        $('.storage_dialog_choose ul').append(append_content_model);
        return true;
    }
    function appendNewListDelete (obj,id) {
        $(obj).parent().parent().remove();
        $('#upload_file .unit').each(function(){
            if($(this).attr('data-id')==id) {
                $(this).find('.close_unit').click();
                return false;
            }
        });
    }
    function isAlreadyExits(str) {
        var status = false;
        $('.storage_dialog_choose ul li').each(function(){
            var compareStr = '<div>' + $(this).children('div').html() + '</div>';
            var allStr = $(this).html();
            allStr = allStr.replace(compareStr,'');
            if(allStr==str){
                status = true;
            }
        });
        return status;
    }
</script>
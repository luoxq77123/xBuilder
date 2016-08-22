<!--水印-->
<div class="fileWater">
  <div class="waterTit">
      <span class="tit">水印设置</span><input type="checkbox" id="waterCheck"<?php if(@$params['Transcode']['waterCheck'] == 'on'){echo "checked";}?> name="data[Transcode][waterCheck]"/>
  </div>
  <div id="waterTime" class="waterTime" style="<?php if(@$params['Transcode']['waterCheck'] == 'on'){echo 'display:block';}else{echo 'display:none';}?>">

  <div class="upload_slider">
  
      <div class="waterUpload">
          <span><input id="water_file_<?php echo $time.$vs['id'];?>" type="file" name="data[file]" /></span>
          <span class="uploadPic"><a id="uploadPic" href="javascript:$('#water_file_<?php echo $time.$vs['id'];?>').uploadify('upload','*')"></a></span>
          <div style="clear:both;"></div>
      </div>
      
      <div class="waterSlider">
          <dl>
              <dt>X：</dt>
              <dd><div id="slider_1_<?php echo $time.$vs['id'];?>" class="slider"></div></dd>
              <dd class="spanInput"><?php echo $this->Form->input('StartX',array('class'=>'spin', 'id'=>'spin_1_'.$time.$vs['id'] , 'label'=>false, 'div'=>false, 'value'=>$params['Transcode']['StartX']))?></dd>
          </dl>
          <dl>
              <dt>Y：</dt>
              <dd><div id="slider_2_<?php echo $time.$vs['id'];?>" class="slider"></div></dd>
              <dd class="spanInput"><?php echo $this->Form->input('StartY',array('class'=>'spin', 'id'=>'spin_2_'.$time.$vs['id'] , 'label'=>false, 'div'=>false, 'value'=>$params['Transcode']['StartY']))?></dd>
          </dl>
      </div>
      
      <div class="waterMessage" style="<?php if(@!empty($params['Transcode']['water_file'])){echo "display:block";}?>">
          <span class="t">水印信息：</span>
          <span class="waterPicbox" id="picBox" style="<?php if(@!empty($params['Transcode']['water_file'])){echo "display:block";}?>">
              <span id="pic_s_font" class="s_font_width spannone">缩略图：</span>
              <span id="picUrl" class="spannone"></span>
              <span id="pic_c_font" class="c_font_width">尺寸：</span>
              <span id="picWH" class="c_num_width"><?php echo @$params['Transcode']['ObjWidth']."X".@$params['Transcode']['ObjHeight'];?></span>
              <span class="waterPicdel" id='delWater'>删除水印</span>
              <span id="water_notice" class="notice"></span>
          </span>
          <div style="clear:both;"></div>
      </div>
  </div>
  <div class="perviewWater">
      <div>
          <div id="waterFile_<?php echo $time . $vs['id'];?>" class="waterFile" style="top:<?php echo $top;?>px;left:<?php echo $left;?>px"></div>
      </div>
  </div>
  
  </div>
</div>
<!--水印结束-->

<script type="text/javascript">
  $(function(){
    /**
    * 上传控件
    *
    */
    $('#water_file_<?php echo $time;?>').uploadify({
            'swf'      : '<?php echo $this->webroot;?>files/uploadify.swf',
            'uploader' : '<?php echo $this->webroot;?>uploads/water',
            'buttonText' : '选择图片',
            'auto' : false,
      'onSelect' : function() {
        if(selectedLi("input[name='data[Transcode][FormatWidth]']").val() == '' || selectedLi("input[name='data[Transcode][FormatHeight]']").val() == '')
        {
          var swfID = $("#water_file-queue").find("div").attr('id');
          $('#water_file_<?php echo $time;?>').uploadify('cancel', ''+swfID+'');
          alertMsg.error("请先填幅面再传水印且图尺寸小于幅面！");
          return false;
        }
      },
      'onUploadStart'   : function(file){
      },
      'onUploadSuccess' : function(file, data, response){
        selectedLi('#water').val($.parseJSON(data).imageUrl);
        selectedLi('#waterWidth').html($.parseJSON(data).imageWidth);
        selectedLi('#waterHeight').html($.parseJSON(data).imageHeight);
        selectedLi(".waterMessage").css("display","block");
        selectedLi('#picBox').css({width:'200px',display:'block'});
        selectedLi('#pic_c_font').css('display','block');
        selectedLi('#overDel').removeClass().addClass('waterPicdel').attr('id','delWater').html('删除水印');
        selectedLi('#picWH').html($.parseJSON(data).imageWidth+"X"+$.parseJSON(data).imageHeight);
        selectedLi('#picUrl').html($.parseJSON(data).imageViewUrl);
        //上传水印时重置滑动条
        reset_spin_slider();
        changeLiColor();
        selectedLi('#water_notice').html("<?php echo __('Upload successful');?>");
      }
    });

/**
  * 滑动条
  *
  */
  var slider_1 = $( "#slider_1_<?php echo $time;?>" ).slider({
    range: "min",
    value: 0,
    min: 0,
    slide: function( event, ui ) {
      //判断是否填写了幅面宽度以及格式
      var reg = new RegExp("^[1-9][0-9]*$");
      if(selectedLi("input[name='data[Transcode][FormatWidth]']").val() == '')
      {
        alertMsg.error('请先填写幅面宽度!');
        return false;
      }
      if(!reg.test(selectedLi("input[name='data[Transcode][FormatWidth]']").val()))
      {
        alertMsg.error('幅面宽度格式错误，请填写大于0的数字!');
        return false;
      }
      changeLiColor();
      var arrValue_1=selectedLi("input[name='data[Transcode][FormatWidth]']").val();          
      $( "#spin_1_<?php echo $time;?>" ).val( ((ui.value/100)*(arrValue_1-$('#waterWidth').html())).toFixed(0) );
      var left = parseInt(((ui.value/100)*267));
      selectedLi("div[class='waterFile']").css('left',left);
    }
  });
  $( "#spin_1_<?php echo $time;?>" ).val( $( "#slider_1_<?php echo $time;?>" ).slider( "value" ) );
  
  var slider_2 = $( "#slider_2_<?php echo $time;?>" ).slider({
      range: "min",
      value: 0,
      min: 0,
      slide: function( event, ui ) {
        //判断是否填写了幅面高度以及格式
        var reg = new RegExp("^[1-9][0-9]*$");
        if(selectedLi("input[name='data[Transcode][FormatHeight]']").val() == '')
        {
          alertMsg.error('请先填写幅面高度!');
          return false;
        }
        if(!reg.test(selectedLi("input[name='data[Transcode][FormatHeight]']").val()))
        {
          alertMsg.error('幅面高度格式错误，请填写大于0的数字!');
          return false;
        }
        changeLiColor();
        var arrValue_2=selectedLi("input[name='data[Transcode][FormatHeight]']").val(); 
        $( "#spin_2_<?php echo $time;?>" ).val( ((ui.value/100)*(arrValue_2-$('#waterHeight').html())).toFixed(0) );
        var top = parseInt(((ui.value/100)*152));
        selectedLi("div[class='waterFile']").css('top',top);
      }
    });
        $( "#spin_2_<?php echo $time;?>" ).val( $( "#slider_2_<?php echo $time;?>" ).slider( "value" ) );
    
/**
 * 滑动条下拉框
 *
 */
  $.spin.imageBasePath = '<?php echo $this->webroot;?>img/spin/';
        $('#spin_1_<?php echo $time;?>').spin({
      min: 0,
      changed:function(n,o){
        changeLiColor();
        slider_1.slider("value",n);
        var left = parseInt(((n/100)*267));
        selectedLi("div[class='waterFile']").css('left',left);
          }
    });
        $('#spin_2_<?php echo $time;?>').spin({
      min: 0,
      changed:function(n,o){
        changeLiColor();
          slider_2.slider("value",n);
          var top = parseInt(((n/100)*152));
            selectedLi("div[class='waterFile']").css('top',top);
        }
    });
  })
</script>
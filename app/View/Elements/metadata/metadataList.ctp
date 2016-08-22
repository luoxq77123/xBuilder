  <script type="text/javascript">
  var isAutoFill = eval(<?php echo isset($isAutoFill)?$isAutoFill:'[]';?>);
  // 一键复制
  function copyAllGet () {
    //获取到当前的需要被复制的元数据并复制到其他元数据中
    var currentForm = $('.small-label form.current');
    var currentFormInput = $('.small-label form.current .unit');
    var id = currentForm.attr('id');
    var copyForm = '';
    if ( currentFormInput.length > 0 ) {
        copyAllSet(id);
    }
  }
  function copyAllSet (id) {
    $('.small-label form.metaDataForm').each(function(){
        if($(this).attr('id') != id){
            $(this).children('.unit').each(function(){
                //todo checkbox radio select
                //only text textarea
                var lastAttr = $(this).children().last();
                var nameLength = lastAttr.attr('name').length;
                var lastAttrName = lastAttr.attr('name').substring(15,nameLength-1);
                if(!in_array(lastAttrName,isAutoFill)) {
                    lastAttr.val($('form.current [name="data[Metadata]['+lastAttrName+']"]').val());
                }
            });
        }
    });
  }
  function in_array(search,array){
    for(var i in array){
        if(array[i]==search){
            return true;
        }
    }
    return false;
}
  </script>
  <?php 
    echo $this->Form->create('Metadata',array(
        'class'=>'pageForm required-validate',
        'onsubmit'=>'return false',
        'inputDefaults'=>array(
            'div'=>array(
                'class'=>'unit'
                ),
            'legend'=>false
            )
        )
    );
        //展示判断开始
        if(@$metaData) {
            foreach ($metaData as $value) {
                $dataCode = $value['Metadata']['code'];
                $dataValue = $value['Metadata']['data_value'];
                $dataTitle = $value['Metadata']['title'];
                switch ($value['Metadata']['type']) {
                    case '0':
                    echo $this->Form->input($dataCode,array('class'=>'','type'=>'text','label'=>$dataTitle));
                    break;
                    case '1':
                    echo '<div class="unit"><label for="MetadataButtons">' . $dataTitle . '</label><button type="submit" class="" value="' . $dataValue . '">' . $dataValue . '</button></div>';
                    break;
                    case '2':
                    echo $this->Form->input($dataCode, array('value'=>$dataValue, 'class'=>'format-time date textInput readonly valid', 'readonly'=>'true', 'label'=>$dataTitle));
                    echo '<script>$(".pageFormContent .format-time").attr("format","yyyy-MM-dd HH:mm:ss")</script>';
                    break;
                    case '3':
                    echo $this->Form->input($dataCode, array('value'=>$dataValue, 'class'=>'', 'type'=>'button', 'label'=>$dataTitle));
                    break;
                    case '4':
                    //select
                    if( $dataValue ) {
                        $newArray = array();
                        $dataValue = explode('|',$dataValue);
                        foreach ($dataValue as $key => $value) {
                             $temp = explode(':',$value);
                             $newArray[$temp[0]] =$temp[1];
                        }
                        echo $this->Form->input($dataCode, array('class'=>'select','data-id'=>$dataCode,'label'=>$dataTitle,'options'=>$newArray));
                    }
                    break;
                    case '5':
                    //radio
                    echo '<div class="unit"> <label for="' . $dataCode . '">' . $dataTitle . '</label>';
                    echo $this->Form->radio($dataCode, explode('|',$dataValue), array('legend'=>false,'label'=>'','hiddenField'=>false));
                    echo '</div>';
                    break;
                    case '6':
                    $dataValue = explode('|',$dataValue);
                    echo '<div class="unit"> <label for="' . $dataCode . '">' . $dataTitle . '</label>';
                    foreach( $dataValue as $k=>$v ) {
                        echo $v;
                        echo "<input type='checkbox' value=" . $k . " name='data[Metadata][" . $dataCode . "]'>";
                    }
                    echo '</div>';
                    break;
                    default:
                    //textarea
                    echo $this->Form->input($dataCode, array('value'=>$dataValue, 'style'=>'margin: 0px;height: 85px;width: 205px;', 'class'=>'xb_textarea','type'=>'textarea', 'label'=>$dataTitle));
                    break;
                }
                echo '<div class="divider"></div>';
            }
        }
        echo $this->Form->end();        
        ?>
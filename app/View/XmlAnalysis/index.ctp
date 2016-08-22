<a href="<?php echo $this->Html->url(array('controller'=>'XmlAnalysis','action'=>'analysisXml'));?>">解析xml文件</a>
<input type="button" value="解析xml文件" id="analysisXml"/>
<script>
$(function(){
	$("#analysisXml").live('click',function(){
		$.ajax({
    		type: 'POST',
    		url:'<?php echo $this->Html->url(array('controller'=>'XmlAnalysis','action'=>'analysisXml'));?>',
    		dataType:"json",
    		cache: false,
    		success: dialogAjaxDone,
    		error: DWZ.ajaxError
    	});
	});
	/**
    * 回调方法
	*
	*/
    function dialogAjaxDone(json)
	{
    	DWZ.ajaxDone(json);
    	if (json.statusCode == DWZ.statusCode.ok)
		{
    		if(json.id)
			{
    			selectedLi("#TranscodeId").val(json.id);
    			selectedLi("button[type='delete']").attr("target",json.id);
        		$('.tabsHeaderContent').find('li[class=selected]').html('<a href="javascript:;"><span>'+json.name+'</span></a>');
    		}    		
    	}
    }
})
</script>
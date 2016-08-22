	                    	<fieldset class="fieldVbox param">
	                        	<div class="legend">
	                            	<span class="basicPara">
	                                    <table border="0" cellpadding="0" cellspacing="0" width="180">
	                                        <tr>
	                                            <td width="63">文件特殊参数：</td>
	                                        </tr>
	                                    </table>
	                                </span>
	                            </div>
                                <div class="paraList">
	                                <ul>
	                                
<?php foreach($htmlparam['html'] as $k=>$v){?>
<li>
	<?php foreach($v as $key=>$val){?>
                                    	<span<?php if($key%2==0)echo ' class="p"';else echo ' class="boxInput"'; ?>>
                                    	<?php if(is_string($val)){
                                    	    echo $val;
                                    	}else{
                                    		foreach($val as $i=>$j){
                                    			if($j['type']=='select'){
                                    				echo $j['start_str'].$this->Form->input($j['name'],$j['options']).$j['end_str'];
                                    			}else{
                                    				if($j['type']=='checkbox'){
                                    					echo $j['start_str'].$this->Form->checkbox($j['name'], $j).$j['end_str'];
                                    				}else{
                                    					echo $j['start_str'].$this->Form->input($j['name'],$j).$j['end_str'];
                                    				}
                                    			}
                                    		}
                                    	}
                                    	?>
                                    	</span>
    <?php }?>
</li>
<?php }?>

</ul>
</fieldset>

<?php if($basic == 1):?>
    <?php 
        foreach ($video['Param'] as $i=>$j):
            if($j['basic'] == 0) continue;
            $inputOptions = array('label'=>false,'div'=>false);
            $inputOptions['type'] = $j['type']?:'text';
            $inputOptions['name'] = "data[video][".$j['name']."]";
            $inputOptions['id'] = "video".$j['name'];
            $inputOptions['value'] = $j['value'];

            if($j['type']=='select'){
                $inputOptions['options'] = json_decode($j['options'],true);
                $inputOptions['class'] = 'combox';
            }
    ?>
    <dd <?php if($j['name'] == 'FormatWidth'):?> class="doble"<?php endif;?>>
        <table border="0" cellpadding="0" cellspacing="0">
            <tr>
                <?php if(@$j['c_name']):?><th><?php echo $j['c_name'];?></th><?php endif;?>
                <td>
                    <?php if($j['start_str']):?><span><?php echo $j['start_str'];?></span><?php endif;?>
                    <?php echo $this->Form->input($j['name'],$inputOptions);?>
                    <?php if($j['end_str']):?><span class="e"><?php echo $j['end_str'];?></span><?php endif;?>
                </td>
            </tr>
        </table>
    </dd>
    <?php endforeach;?>
<?php else:?>
<table border="0" cellpadding="0" cellspacing="0">
    <tr>
    <?php 
        $k = 1;
        foreach ($video['Param'] as $i=>$j):
            $inputOptions = array('label'=>false,'div'=>false);
            $inputOptions['type'] = $j['type']?:'text';
            $inputOptions['name'] = "data[video][".$j['name']."]";
            $inputOptions['id'] = "video".$j['name'];
            $inputOptions['value'] = $j['value'];

            if($j['type']=='select'){
                $inputOptions['options'] = json_decode($j['options'],true);
                $inputOptions['class'] = 'combox';
            }
    ?>
        <?php if(@$j['c_name']):?><th class="video"><?php echo $j['c_name'];?></th><?php endif;?>
            <?php if(!in_array($j['name'], array('sar_height','dest_height'))):?><td><?php endif;?>
                <?php if($j['start_str']):?><span><?php echo $j['start_str'];?></span><?php endif;?>
                <?php echo $this->Form->input($j['name'],$inputOptions);?>
                <?php if($j['end_str']):?><span class="e"><?php echo $j['end_str'];?></span><?php endif;?>
            <?php if(!in_array($j['name'], array('sar_width','dest_width'))):?></td><?php endif;?>
    <?php 
        if($k%3 == 0) echo '</tr><tr>';
        if(!in_array($j['name'], array('sar_width','dest_width')))$k++;
        endforeach;
    ?>
    </tr>
</table>
<?php endif;?>
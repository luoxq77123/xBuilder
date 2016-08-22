<?php if($basic == 1):?>
    <?php 
        foreach ($audio['Param'] as $i=>$j):
            if($j['basic'] == 0) continue;
            $inputOptions = array('label'=>false,'div'=>false);
            $inputOptions['type'] = $j['type']?:'text';
            $inputOptions['name'] = "data[audio][".$j['name']."]";
            $inputOptions['id'] = "audio".$j['name'];
            $inputOptions['value'] = $j['value'];

            if($j['type']=='select'){
                $inputOptions['options'] = json_decode($j['options'],true);
                $inputOptions['class'] = 'combox';
            }
    ?>
    <dd>
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
            foreach ($audio['Param'] as $i=>$j):
                $inputOptions = array('label'=>false,'div'=>false);
                $inputOptions['type'] = $j['type']?:'text';
                $inputOptions['name'] = "data[audio][".$j['name']."]";
                $inputOptions['id'] = "audio".$j['name'];
                $inputOptions['value'] = $j['value'];

                if($j['type']=='select'){
                    $inputOptions['options'] = json_decode($j['options'],true);
                    $inputOptions['class'] = 'combox';
                }
        ?>
            <?php if(@$j['c_name']):?><th class="audio"><?php echo $j['c_name'];?></th><?php endif;?>
            <td>
                <?php if($j['start_str']):?><span><?php echo $j['start_str'];?></span><?php endif;?>
                <?php echo $this->Form->input($j['name'],$inputOptions);?>
                <?php if($j['end_str']):?><span class="e"><?php echo $j['end_str'];?></span><?php endif;?>
            </td>
        <?php 
            if($k%3 == 0) echo '</tr><tr>';
            $k++;
            endforeach;
        ?>
    </tr>
</table>
<?php endif;?>
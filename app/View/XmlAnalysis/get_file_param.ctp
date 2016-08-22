<table border="0" cellpadding="0" cellspacing="0">
    <tr>
    <?php 
        $k = 1;
        foreach ($file['Param'] as $i=>$j):
            $inputOptions = array('label'=>false,'div'=>false);
            $inputOptions['type'] = $j['type']?:'text';
            $inputOptions['name'] = "data[file][".$j['name']."]";
            $inputOptions['id'] = "file_".$j['name'];
            $inputOptions['value'] = $j['value'];

            if($j['type']=='select'){
                $inputOptions['options'] = json_decode($j['options'],true);
                $inputOptions['class'] = 'combox';
            }
    ?>
        <?php if(@$j['c_name']):?><th class="file"><?php echo $j['c_name'];?></th><?php endif;?>
        <td>
            <?php if($j['start_str']):?><span><?php echo $j['start_str'];?></span><?php endif;?>
            <?php echo $this->Form->input($j['name'],$inputOptions);?>
            <?php if($j['end_str']):?><span class="e"><?php echo $j['end_str'];?></span><?php endif;?>
        </td>
    <?php 
        if($k%6 == 0) echo '</tr><tr>';
        $k++;
        endforeach;
    ?>
    </tr>
</table>
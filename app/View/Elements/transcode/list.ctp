<?php 
	if (isset($transcodes['Transcode'])):
		foreach($transcodes['Transcode'] as $key => $blist):
			if($id == $blist['id'])$t_key = $key;
	?>
	<li><a href="<?php echo $this->Html->url('/transcodes/edit/'.$blist['id']);?>" rel="transcodeContent" target="ajax" class="categorySelect <?php if($id == $blist['id']) echo 'transcodeSelected';?>" categoryid="<?php echo $blist['id']?>"><?php echo  $key+1 . ") " . $blist['title']?></a>
	</li>
<?php 
	endforeach;
	endif;
?>
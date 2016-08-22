<ul class="tree expand">
<?php 
	if (isset($transcodeGroup['Transcode'])):
		foreach($transcodeGroup['Transcode'] as $key => $blist):
	?>
	<li class="<?php if($id == $blist['id']) echo 'selected';?>"><a href="<?php echo $this->Html->url('/transcodes/edit/'.$blist['id']);?>" rel="transcodeContent" target="ajax"  categoryid="<?php echo $blist['id']?>"><?php echo  $key+1 . ") " . $blist['title']?></a>
	</li>
<?php 
	endforeach;
	endif;
?>
</ul>
<?php 
class Video extends AppModel{
	public $name = 'Video';
	public $useTable = 'videos';
	
	public function deleteVideos( $id )
	{
		$this->deleteAll(array('ContentId'=>$id));
	}
}
?>
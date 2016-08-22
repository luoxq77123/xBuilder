<?php
class XmlAvideorelation extends AppModel{
	public $name = 'XmlAvideorelation';
    
	public $belongsTo = array(
	        'Audiotype'=>array(
	                'className'=>'XmlAudiotype',
	                'foreignKey'=>'audioid',
	        )
	);
}
?>
<?php
class MpcInterfaceXml extends AppModel{
	public $useTable = false;

	public $ApolloAdapterProjectFile = array(
		'ApolloAdapterProjectFile'	=>	array(
			'Header'	=>	array(
				'Version'	=>	'1.0'
			),
			'MediaList'	=>	array(
				'Media'	=>	array()
			),
			'TaskInfoList'	=>	array(
				'TaskInfo'	=>	array(
				)
			)
		)
	);

	public $Media = array('MediaIndex'=>'','FileInfo'=>'');

	public $TaskInfo = array(
		'VideoMedia'	=>	array(
			'MediaIndex'	=>	'',
			'PgmID'			=>	'',
			'VideoIndex'	=>	'',
			'InPoint'		=>	0,
			'OutPoint'		=>	600000
		),
		'AudioMedia'	=>	array(
			'MediaIndex'	=>	'',
			'PgmID'			=>	'',
			'AudioIndex'	=>	'',
			'InPoint'		=>	0,
			'OutPoint'		=>	600000
		),
		'CGMedia'		=>	array(
			'MediaIndex'	=>	'',
			'PgmID'			=>	'',
			'CGIndex'	=>	'',
			'InPoint'		=>	0,
			'OutPoint'		=>	600000
		),
		'TimeLineInpoint'	=>	0,
		'TimeLineOutpoint'	=>	600000
	);
}
?>
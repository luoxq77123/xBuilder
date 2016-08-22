<td class="task-type-icon task-type-<?php
switch ($taskType) {
	case '1':
	echo 'upload';
	break;
	case '3':
	echo 'diskfind';
	break;
	case '4':
	echo 'autoscan';
	break;
	case '2':
	echo 'ftp';
	break;
	default:
	echo 'none';
	break;
}
?>-icon" style="text-indent:-2000px">
<?php
switch ($taskType) {
	case '1':
	echo '网页上传';
	break;
	case '3':
	echo '磁盘选择';
	break;
	case '4':
	echo '自动扫描';
	break;
	case '2':
	echo 'FTP';
	break;
	default:
	echo '未知';
	break;
}
?>
</td>
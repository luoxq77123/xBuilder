<table class="table" width="100%">
	<thead>
	<tr>
		<th width="20%">步骤</th>
		<th width="20%">进度</th>
		<th width="20%">长度/用时</th>
		<th width="20%">执行机器</th>
		<th width="20%">状态</th>
	</tr>
	</thead>
	<?php
	if (@$stepInfo) {
		foreach ($stepInfo as $key => $value) {
			if (in_array($value['JobType'], array('nmfastrender', 'mediatrans', 'NM_CPUpLoadNewTask', 'nmfastsplit', 'NM_ClipToML'))) {
				echo "<tr>";
				echo "<td>" . $value['JobName'] . "</td>";
				echo "<td>" . $value['ExecuteGuage'] . "%</td>";
				echo "<td>" . $value['ExecuteTime'] . "</td>";
				echo "<td>" . $value['ExecuteServer'] . "</td>";
				echo "<td>" . __('ExecuteStatus_' . $value['ExecuteStatus']) . "</td>";
				echo "</tr>";
			}
		}
	}
	?>
</table>
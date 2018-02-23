
<?php
$SQL = 'SELECT *
FROM `Machines`
WHERE ';
if (
	!empty($SelectedDepartment) &&
	$SelectedDepartment != '*'
) {
	$SQL .= '`Machines`.`Department`=\''.$SelectedDepartment.'\' AND ';
}
if (
	!empty($SelectedLine) &&
	$SelectedLine != '*'
) {
	$SQL .= '`Machines`.`Line`=\''.$SelectedLine.'\' AND ';
}
if (
	!empty($SelectedMachine) &&
	$SelectedMachine != '*'
) {
	$SQL .= '`Machines`.`AssetTag`=\''.$SelectedMachine.'\' AND ';
}
if ( !empty($SoftSearch) ) {
	$SQL .= '(
		`Machines`.`Department`  LIKE \'%'.$SoftSearch.'%\' OR
		`Machines`.`Line`        LIKE \'%'.$SoftSearch.'%\' OR
		`Machines`.`Machine`     LIKE \'%'.$SoftSearch.'%\' OR
		`Machines`.`AssetTag`    LIKE \'%'.$SoftSearch.'%\' OR
		`Machines`.`Description` LIKE \'%'.$SoftSearch.'%\' OR
		`Machines`.`Make`        LIKE \'%'.$SoftSearch.'%\' OR
		`Machines`.`Model`       LIKE \'%'.$SoftSearch.'%\' OR
		`Machines`.`SerialNo`    LIKE \'%'.$SoftSearch.'%\'
	) ';
}
$SQL .= ';';
$Machine = mysqli_query($Sitewide['Database']['Connection'], $SQL);
if ( $Machine && mysqli_num_rows($Machine) === 1 ) {
	$Machine = mysqli_fetch_assoc($Machine);
	echo '
	<hr>
	<div class="whole grid">
		<div class="whole">
			<h2>Machine '.$Machine['AssetTag'].'</h2>
		</div>
		<div class="medium-half">
			<p>
				Department: '.$Machine['Department'].',
				Line:       '.$Machine['Line'].',
				Machine:    '.$Machine['Machine'].'.
				Make:       '.$Machine['Make'].',
				Model:      '.$Machine['Model'].',
				SerialNo:   '.$Machine['SerialNo'].'.
			</p>
		</div>
		<div class="medium-half">
			<p>'.$Machine['Description'].'</p>
		</div>
	</div>
	<hr>
	<br>';
}
<?php
	require_once __DIR__.'/_puff/sitewide.php';
	$Page['Type']  = 'Page';
	$Page['Title'] = 'Tickets';
	require_once $Sitewide['Templates']['Header'];

	if (
		!empty($_GET['status-new']) ||
		!empty($_GET['status-waiting']) ||
		!empty($_GET['status-in-progress']) ||
		!empty($_GET['status-complete']) ||
		!empty($_GET['status-signed-off']) ||
		!empty($_GET['status-cancelled'])
	) {
		$Status['New']         = !empty($_GET['status-new']);
		$Status['Waiting']     = !empty($_GET['status-waiting']);
		$Status['In Progress'] = !empty($_GET['status-in-progress']);
		$Status['Complete']    = !empty($_GET['status-complete']);
		$Status['Signed Off']  = !empty($_GET['status-signed-off']);
		$Status['Cancelled']   = !empty($_GET['status-cancelled']);
	} else if (
		$Sitewide['Authenticated']['Role'] == 'Manager' ||
		$Sitewide['Authenticated']['Role'] == 'Admin'
	) {
		$Status['New']         = true;
		$Status['Waiting']     = true;
		$Status['In Progress'] = true;
		$Status['Complete']    = true;
		$Status['Signed Off']  = false;
		$Status['Cancelled']   = false;
	} else if ( $Sitewide['Authenticated']['Department'] == 'Engineering' ) {
		$Status['New']         = true;
		$Status['Waiting']     = true;
		$Status['In Progress'] = true;
		$Status['Complete']    = false;
		$Status['Signed Off']  = false;
		$Status['Cancelled']   = false;
	} else {
		$Status['New']         = true;
		$Status['Waiting']     = true;
		$Status['In Progress'] = true;
		$Status['Complete']    = true;
		$Status['Signed Off']  = true;
		$Status['Cancelled']   = true;
	}

	if ( !empty($_GET['department']) ) {
		$SelectedDepartment = htmlentities($_GET['department'], ENT_QUOTES, 'UTF-8');
	} else if (
		$Sitewide['Authenticated']['Department'] == 'Engineering'
	) {
		$SelectedDepartment = '*';
	} else if ( $Sitewide['Authenticated']['Department'] ) {
		$SelectedDepartment = $Sitewide['Authenticated']['Department'];
	} else {
		$SelectedDepartment = false;
	}
	if ( !empty($_GET['line']) ) {
		$SelectedLine = htmlentities($_GET['line'], ENT_QUOTES, 'UTF-8');
	} else {
		$SelectedLine = false;
	}
	if ( !empty($_GET['machine']) ) {
		$SelectedMachine = htmlentities($_GET['machine'], ENT_QUOTES, 'UTF-8');
	} else {
		$SelectedMachine = false;
	}

	if ( isset($_GET['assignee']) ) {
		$SelectedAssignee = htmlentities($_GET['assignee'], ENT_QUOTES, 'UTF-8');
	} else {
		$SelectedAssignee = false;
	}

	if ( isset($_GET['soft-search']) ) {
		$SoftSearch = htmlentities($_GET['soft-search'], ENT_QUOTES, 'UTF-8');
	} else {
		$SoftSearch = false;
	}

?>

<h2>Tickets</h2>

<?php require $Sitewide['Templates']['Root'].'tickets_search-form.php'; ?>

<br>

<?php
$SQL = 'SELECT *
FROM `Tickets`
JOIN `Statuses`
	ON `Tickets`.`Status` = `Statuses`.`Status`
JOIN `Machines`
	ON `Tickets`.`AssetTag` = `Machines`.`AssetTag`
WHERE ';
if (
	!empty($SelectedDepartment) &&
	$SelectedDepartment != '*'
) {
	$SQL .= '`Tickets`.`Department`=\''.$SelectedDepartment.'\' AND ';
}
if (
	!empty($SelectedLine) &&
	$SelectedLine != '*'
) {
	$SQL .= '`Tickets`.`Line`=\''.$SelectedLine.'\' AND ';
}
if (
	!empty($SelectedMachine) &&
	$SelectedMachine != '*'
) {
	$SQL .= '`Tickets`.`AssetTag`=\''.$SelectedMachine.'\' AND ';
}
if ( 
	!empty($SelectedAssignee) &&
	$SelectedAssignee != '*'
) {
	$SQL .= '`Tickets`.`Assigned` LIKE \'%'.$SelectedAssignee.'%\' AND ';
}
$SQL .= '(';
if ( $Status['New'] ) {
	$SQL .= '`Tickets`.`Status`=\'New\' OR ';
}
if ( $Status['Waiting'] ) {
	$SQL .= '`Tickets`.`Status`=\'Waiting\' OR ';
}
if ( $Status['In Progress'] ) {
	$SQL .= '`Tickets`.`Status`=\'In Progress\' OR ';
}
if ( $Status['Complete'] ) {
	$SQL .= '`Tickets`.`Status`=\'Complete\' OR ';
}
if ( $Status['Signed Off'] ) {
	$SQL .= '`Tickets`.`Status`=\'Signed Off\' OR ';
}
if ( $Status['Cancelled'] ) {
	$SQL .= '`Tickets`.`Status`=\'Cancelled\' OR ';
}
if ( substr($SQL, -4, 4) == ' OR ' ) {
	$SQL = substr($SQL, 0, -4);
}
$SQL .= ') ';
if ( $SoftSearch ) {
	$SQL .= 'AND (
		`Tickets`.`Department`   LIKE \'%'.$SoftSearch.'%\' OR
		`Tickets`.`Line`         LIKE \'%'.$SoftSearch.'%\' OR
		`Tickets`.`Machine`      LIKE \'%'.$SoftSearch.'%\' OR
		`Tickets`.`AssetTag`     LIKE \'%'.$SoftSearch.'%\' OR
		`Tickets`.`Title`        LIKE \'%'.$SoftSearch.'%\' OR
		`Tickets`.`Description`  LIKE \'%'.$SoftSearch.'%\' OR
		`Machines`.`Description` LIKE \'%'.$SoftSearch.'%\' OR
		`Machines`.`Make`        LIKE \'%'.$SoftSearch.'%\' OR
		`Machines`.`Model`       LIKE \'%'.$SoftSearch.'%\' OR
		`Machines`.`SerialNo`    LIKE \'%'.$SoftSearch.'%\'
	) ';
}
$SQL .= 'ORDER BY `Ticket` DESC;';
var_dump($SQL);
$Result = mysqli_query($Sitewide['Database']['Connection'], $SQL);
if ( !mysqli_num_rows($Result) ) {
	echo '<h2>Sorry, there are no tickets for your selected filters.</h2>';
} else {
	echo '
<table class="tablesorter">
	<thead>
		<tr class="clickable">
			<th>#</td>
			<th>Title</td>
			<th>Department</td>
			<th>Line</td>
			<th>Machine</td>
			<th>Asset Tag</td>
			<th>Created Time</td>
			<th>Status</td>
		</tr>
	</thead>
	<tbody>';
	while ( $Ticket = mysqli_fetch_assoc($Result) ) {
		echo '
		<tr class="clickable';
		if ( $Sitewide['Authenticated']['Username'] ) {
			echo ' assigned-to-me';
		}
		echo '" onclick="window.location.href=\''.$Sitewide['Settings']['Site Root'].'ticket.php?id='.$Ticket['Ticket'].'\'">
			<td>#'.$Ticket['Ticket'].'</td>
			<td>'.$Ticket['Title'].'</td>
			<td>'.$Ticket['Department'].'</td>
			<td>'.$Ticket['Line'].'</td>
			<td>'.$Ticket['Machine'].'</td>
			<td>'.$Ticket['AssetTag'].'</td>
			<td>'.$Ticket['CreatedTime'].'</td>
			<td class="status-tag background-'.$Ticket['Color'].' color-white text-center">'.$Ticket['Status'].'</td>
		</tr>';
	}
	echo '
	</tbody>
</table>';
}
?>

<?php
	require_once $Sitewide['Templates']['Footer'];

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

<form method="GET">
	<input type="checkbox"<?php if ( $Status['New'] ) echo ' checked'; ?> class="appearance" id="checkbox-status-new" name="status-new"><label for="checkbox-status-new">New</label>
	<input type="checkbox"<?php if ( $Status['Waiting'] ) echo ' checked'; ?>  class="appearance" id="checkbox-status-waiting" name="status-waiting"><label for="checkbox-status-waiting">Waiting</label>
	<input type="checkbox"<?php if ( $Status['In Progress'] ) echo ' checked'; ?>  class="appearance" id="checkbox-status-in-progress" name="status-in-progress"><label for="checkbox-status-in-progress">In Progress</label>
	<input type="checkbox"<?php if ( $Status['Complete'] ) echo ' checked'; ?>  class="appearance" id="checkbox-status-complete" name="status-complete"><label for="checkbox-status-complete">Complete</label>
	<input type="checkbox"<?php if ( $Status['Signed Off'] ) echo ' checked'; ?>  class="appearance" id="checkbox-status-signed-off" name="status-signed-off"><label for="checkbox-status-signed-off">Signed Off</label>
	<input type="checkbox"<?php if ( $Status['Cancelled'] ) echo ' checked'; ?>  class="appearance" id="checkbox-status-cancelled" name="status-cancelled"><label for="checkbox-status-cancelled">Cancelled</label>
	&emsp;
	<select name="department" class="whole" required>
		<option value="*"<?php if ( $SelectedDepartment == '*' || $SelectedDepartment == 'Engineering' ) echo ' selected="selected"'; ?>>All Departments</option>
		<?php
			$SQL = 'SELECT * FROM `Departments` WHERE `Active`=\'1\' ORDER BY `Order` DESC, `Department` ASC;';
			$Result = mysqli_query($Sitewide['Database']['Connection'], $SQL);
			$DepartmentSelected = false;
			while ( $Department = mysqli_fetch_assoc($Result) ) {
				$Selected = false;
				if (
					$SelectedDepartment == $Department['Department'] &&
					$Department['Department'] != 'Engineering'
				) {
					$Selected = ' selected="selected"';
					$DepartmentSelected = true;
				}
				echo '<option value="'.$Department['Department'].'"'.$Selected .'>'.$Department['Department'].'</option>';
			}
			echo '<option value="Other"';
			if ( $SelectedDepartment == 'Other' ) {
				echo ' selected="selected"';
				$DepartmentSelected = true;
			}
			echo '>Other</option>';
		?>
	</select>
	&emsp;
	<select name="line" class="whole">
		<?php
			$LineSelected = false;
			if ( !$DepartmentSelected ) {
				echo '<option value="*" disabled selected>All Lines</option>';
			} else {
				echo '<option value="*" selected>All Lines</option>';
				$SQL = 'SELECT * FROM `Lines` WHERE `Active`=\'1\' AND `Department`=\''.$SelectedDepartment.'\' ORDER BY `Order` DESC, `Department` ASC, `Line` ASC;';
				$Result = mysqli_query($Sitewide['Database']['Connection'], $SQL);
				while ( $Line = mysqli_fetch_assoc($Result) ) {
					$Selected = false;
					if ( $SelectedLine == $Line['Line'] ) {
						$Selected = ' selected="selected"';
						$LineSelected = true;
					}
					echo '<option value="'.$Line['Line'].'"'.$Selected.'>'.$Line['Line'].'</option>';
				}
				echo '<option value="Other"';
				if ( $SelectedLine == 'Other' ) {
					echo ' selected="selected"';
					$LineSelected = true;
				}
				echo '>Other</option>';
			}
		?>
	</select>
	&emsp;
	<select name="machine" class="whole">
		<?php
			if ( !$LineSelected ) {
				echo '<option value="*" disabled selected>All Machines</option>';
			} else {
				echo '<option value="*" selected>All Machines</option>';
				$SQL = 'SELECT * FROM `Machines` WHERE `Active`=\'1\' AND `Department`=\''.$SelectedDepartment.'\' AND `Line`=\''.$SelectedLine.'\' ORDER BY `Order` DESC, `Department` ASC, `Line` ASC, `Machine` ASC, `AssetTag` ASC;';
				$Result = mysqli_query($Sitewide['Database']['Connection'], $SQL);
				while ( $Machine = mysqli_fetch_assoc($Result) ) {
					if ( $SelectedMachine == $Machine['AssetTag'] ) {
						$Selected = ' selected="selected"';
					}
					echo '<option value="'.$Machine['AssetTag'].'"'.$Selected.'>'.$Machine['Machine'].'</option>';
				}
				echo '<option value="Other"';
				if ( $SelectedMachine == 'Other' ) {
					echo ' selected="selected"';
				}
				echo '>Other</option>';
			}
		?>
	</select>
	&emsp;
	<select name="assignee" class="whole">
		<?php
			echo '<option value="*" selected>Any Assignee</option>';
			$SQL = "SELECT
					`Members`.`Username`,
					`Names`.`Value` AS `Name`
				FROM `Members`
				JOIN `KeyValues` AS `Departments`
					ON `Members`.`Username` = `Departments`.`Username`
				JOIN `KeyValues` AS `Names`
					ON `Members`.`Username` = `Names`.`Username`
				WHERE
					`Members`.`Active`='1' AND
					`Departments`.`Key`='Department' AND
					`Departments`.`Value` LIKE '%Engineering%' AND
					`Names`.`Key`='Name'
				ORDER BY `Names`.`Value` ASC";
			$Result = mysqli_query($Sitewide['Database']['Connection'], $SQL);
			while ( $Engineer = mysqli_fetch_assoc($Result) ) {
				if ( $SelectedAssignee == $Engineer['Username'] ) {
					$Selected = ' selected="selected"';
				} else {
					$Selected = false;
				}
				echo '<option value="'.$Engineer['Username'].'"'.$Selected.'>'.$Engineer['Name'].'</option>';
			}
		?>
	</select>
	&emsp;
	<input name="soft-search" type="text" placeholder="Machine details" value="<?php echo $SoftSearch; ?>">
	&emsp;
	<button type="submit"><i class="fa fa-search"></i> Search</button>
</form>
<br>

<?php
$SQL = 'SELECT *
FROM `Tickets`
JOIN `Statuses`
ON `Tickets`.`Status` = `Statuses`.`Status`
WHERE ';
if (
	!empty($SelectedDepartment) &&
	$SelectedDepartment != '*'
) {
	$SQL .= '`Department`=\''.$SelectedDepartment.'\' AND ';
}
if (
	!empty($SelectedLine) &&
	$SelectedLine != '*'
) {
	$SQL .= '`Line`=\''.$SelectedLine.'\' AND ';
}
if (
	!empty($SelectedMachine) &&
	$SelectedMachine != '*'
) {
	$SQL .= '`AssetTag`=\''.$SelectedMachine.'\' AND ';
}
if ( 
	!empty($SelectedAssignee) &&
	$SelectedAssignee != '*'
) {
	$SQL .= '`Assigned` LIKE \'%'.$SelectedAssignee.'%\' AND ';
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
		`Machine` LIKE \'%'.$SoftSearch.'%\' OR
		`AssetTag` LIKE \'%'.$SoftSearch.'%\'
	) ';
}
$SQL .= 'ORDER BY `Ticket` DESC;';
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
		//var_dump($Ticket);
	}
	echo '
	</tbody>
</table>';
}
?>

<?php
	require_once $Sitewide['Templates']['Footer'];

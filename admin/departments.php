<?php
	require_once __DIR__.'/../_puff/sitewide.php';
	$Page['Type']  = 'Admin';
	$Page['Title'] = 'Manage Departments';

	if ( !isEngManager($Sitewide['Authenticated']) ) {
		header('Location: '.$Sitewide['Settings']['Site Root'].'log_in.php?redirect='.urlencode($Sitewide['Request']['Path']), true, 302);
		exit;
	}

	require_once $Sitewide['Templates']['Header'];
	echo '<h2>Manage Departments</h2>';

	////	Handle Transactions
	if (
		isset($_POST['department_order'])
	) {
		$Order      = $_POST['department_order'];
		$Department = $_POST['department_toggle'];
		$Result = Department_Reorder($Sitewide['Database']['Connection'], $Department, $Order);
		echo '<h3 class="color-nephritis">The Department "'.$Department.'" has been updated.</h3>';
	} else if (
		!empty($_POST['department_toggle'])
	) {
		$Department = $_POST['department_toggle'];
		$DepartmentEnabled = Department_Exists($Sitewide['Database']['Connection'], $Department, true);
		$DepartmentDisabled = Department_Exists($Sitewide['Database']['Connection'], $Department, false);
		if ( $DepartmentEnabled ) {
			$Result = Department_Disable($Sitewide['Database']['Connection'], $Department);
			echo '<h3 class="color-nephritis">The Department "'.$Department.'" has been disabled.</h3>';
		} else if ( $DepartmentDisabled ) {
			$Result = Department_Enable($Sitewide['Database']['Connection'], $Department);
			echo '<h3 class="color-nephritis">The Department "'.$Department.'" has been re-enabled.</h3>';
		} else {
			echo '<h3 class="color-pomegranate">Sorry, that Department doesn\'t seem to exist.</h3>';
		}
	}

	////	Departments
	$SQL = 'SELECT * FROM `Departments` ORDER BY `Order` ASC, `Department` ASC;';
	$Result = mysqli_query($Sitewide['Database']['Connection'], $SQL);
	echo '<table class="tablesorter">
	<thead>
		<tr>
			<th>Department</th>
			<th>Order</th>
			<th>Set</th>
			<th>Active</th>
			<th>Manage Lines</th>
		</tr>
	</thead>
	<tbody>';
	while ( $Department = mysqli_fetch_assoc($Result) ) {
		echo '<tr>
			<td>'.$Department['Department'].'</td>
			<td data-text="'.$Department['Order'].'">
				<form method="POST">
					<input type="hidden" name="department_toggle" value="'.$Department['Department'].'">
					<input type="number" min="0" max="999" step="1" name="department_order" value="'.$Department['Order'].'">
			</td>
			<td>
					<button type="submit"><i class="fa fa-sort"></i> Re-order</button>
				</form>
			</td>
			<td>
				<form method="POST">
					<input type="hidden" name="department_toggle" value="'.$Department['Department'].'">';
		if ( $Department['Active'] ) {
			echo '
					<button type="submit"><i class="fa fa-ban"></i> Disable</button>';
		} else {
			echo '
					<button type="submit"><i class="fa fa-check"></i> Enable</button>';
		}
		echo '
				</form>
			</td>
			<td><button onclick="window.location.href=\'lines.php?department='.$Department['Department'].'\'"><i class="fa fa-cogs"></i> Manage Lines</button>
		</tr>';
	}
	echo '</tbody>
</table>';

	require_once $Sitewide['Templates']['Footer'];

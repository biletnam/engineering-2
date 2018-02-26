<?php
	require_once __DIR__.'/../_puff/sitewide.php';
	$Page['Type']  = 'Admin';
	$Page['Title'] = 'Manage Departments';

	if (
		!( $Sitewide['Authenticated']['Role'] == 'Admin' ) &&
		!(
			$Sitewide['Authenticated']['Department'] == 'Engineering' &&
			$Sitewide['Authenticated']['Role'] == 'Manager'
		)
	) {
		header('Location: '.$Sitewide['Settings']['Site Root'].'log_in.php?redirect='.urlencode($Sitewide['Request']['Path']), true, 302);
		exit;
	}

	require_once $Sitewide['Templates']['Header'];
	echo '<h2>Manage Departments</h2>';

	////	Handle Transactions
	if (
		!empty($_POST['department_toggle'])
	) {
		$DepartmentEnabled = Department_Exists($Sitewide['Database']['Connection'], $Username, true);
		$DepartmentDisabled = Department_Exists($Sitewide['Database']['Connection'], $Username, false);
		if ( $DepartmentEnabled ) {
			$Result = Department_Disable($Sitewide['Database']['Connection'], $Username);
			echo '<h3 class="color-nephritis">The user '.$Username.' has been disabled.</h3>';
		} else if ( $DepartmentDisabled ) {
			$Result = Department_Enable($Sitewide['Database']['Connection'], $Username);
			echo '<h3 class="color-nephritis">The user '.$Username.' has been re-enabled.</h3>';
		} else {
			echo '<h3 class="color-pomegranate">Sorry, that Department doesn\'t seem to exist.</h3>';
		}
	}

	////	Departments
	$SQL = 'SELECT * FROM `Departments` ORDER BY `Department` DESC;';
	$Result = mysqli_query($Sitewide['Database']['Connection'], $SQL);
	echo '<table class="tablesorter">
	<thead>
		<tr>
			<th>Department</th>
			<th>Order</th>
			<th>Active</th>
		</tr>
	</thead>
	<tbody>';
	while ( $Department = mysqli_fetch_assoc($Result) ) {
		echo '<tr>
			<td>'.$Department['Department'].'</td>
			<td>
				<form method="POST">
					<input type="number" min="0" name="department_order_'.$Department['Department'].'" value="'.$Department['Order'].'">
					<button type="submit"><i class="fa fa-ban"></i> Disable</button>
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
		</tr>';
	}
	echo '</tbody>
</table>';

	require_once $Sitewide['Templates']['Footer'];

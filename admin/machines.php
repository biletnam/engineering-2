<?php
	require_once __DIR__.'/../_puff/sitewide.php';
	$Page['Type']  = 'Admin';
	$Page['Title'] = 'Manage Machines';

	if ( !isEngManager($Sitewide['Authenticated']) ) {
		header('Location: '.$Sitewide['Settings']['Site Root'].'log_in.php?redirect='.urlencode($Sitewide['Request']['Path']), true, 302);
		exit;
	}

	require_once $Sitewide['Templates']['Header'];
	echo '<h2>Manage Machines</h2>';

	////	Handle Transactions
	if (
		isset($_POST['line_order'])
	) {
		$Order      = $_POST['line_order'];
		$Machine = $_POST['line_toggle'];
		$Result = Machine_Reorder($Sitewide['Database']['Connection'], $Machine, $Order);
		echo '<h3 class="color-nephritis">The Machine "'.$Machine.'" has been updated.</h3>';
	} else if (
		!empty($_POST['line_toggle'])
	) {
		$Machine = $_POST['line_toggle'];
		$MachineEnabled = Machine_Exists($Sitewide['Database']['Connection'], $Machine, true);
		$MachineDisabled = Machine_Exists($Sitewide['Database']['Connection'], $Machine, false);
		if ( $MachineEnabled ) {
			$Result = Machine_Disable($Sitewide['Database']['Connection'], $Machine);
			echo '<h3 class="color-nephritis">The Machine "'.$Machine.'" has been disabled.</h3>';
		} else if ( $MachineDisabled ) {
			$Result = Machine_Enable($Sitewide['Database']['Connection'], $Machine);
			echo '<h3 class="color-nephritis">The Machine "'.$Machine.'" has been re-enabled.</h3>';
		} else {
			echo '<h3 class="color-pomegranate">Sorry, that Machine doesn\'t seem to exist.</h3>';
		}
	}

	////	Machines
	$Department = htmlentities($_GET['department'], ENT_QUOTES, 'UTF-8');
	$Line       = htmlentities($_GET['line'], ENT_QUOTES, 'UTF-8');
	$SQL = 'SELECT * FROM `Machines` WHERE `Department`=\''.$Department.'\' AND `Line`=\''.$Line.'\' ORDER BY `Order` ASC, `Machine` ASC, `AssetTag` ASC;';
	$Result = mysqli_query($Sitewide['Database']['Connection'], $SQL);
	echo '<table class="tablesorter">
	<thead>
		<tr>
			<th>Machine</th>
			<th>AssetTag</th>
			<th>Order</th>
			<th>Set</th>
			<th>Active</th>
			<th>Change Details</th>
		</tr>
	</thead>
	<tbody>';
	while ( $Machine = mysqli_fetch_assoc($Result) ) {
		echo '<tr>
			<td>'.$Machine['Machine'].'</td>
			<td>'.$Machine['AssetTag'].'</td>
			<td>
				<form method="POST">
					<input type="hidden" name="line_toggle" value="'.$Machine['AssetTag'].'">
					<input type="number" min="0" max="999" step="1" name="line_order" value="'.$Machine['Order'].'">
			</td>
			<td>
					<button type="submit"><i class="fa fa-sort"></i> Re-order</button>
				</form>
			</td>
			<td>
				<form method="POST">
					<input type="hidden" name="line_toggle" value="'.$Machine['AssetTag'].'">';
		if ( $Machine['Active'] ) {
			echo '
					<button type="submit"><i class="fa fa-ban"></i> Disable</button>';
		} else {
			echo '
					<button type="submit"><i class="fa fa-check"></i> Enable</button>';
		}
		echo '
				</form>
			</td>
			<td><button onclick="window.location.href=\'machine_details.php?assettag='.$Member['AssetTag'].'\'"><i class="fa fa-info-circle"></i> Change Details</button>
		</tr>';
	}
	echo '</tbody>
</table>';

	require_once $Sitewide['Templates']['Footer'];

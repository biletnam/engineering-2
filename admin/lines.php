<?php
	require_once __DIR__.'/../_puff/sitewide.php';
	$Page['Type']  = 'Admin';
	$Page['Title'] = 'Manage Lines';
	$Department = htmlentities($_GET['department'], ENT_QUOTES, 'UTF-8');

	if ( !isEngManager($Sitewide['Authenticated']) ) {
		header('Location: '.$Sitewide['Settings']['Site Root'].'log_in.php?redirect='.urlencode($Sitewide['Request']['Path']), true, 302);
		exit;
	}

	require_once $Sitewide['Templates']['Header'];
	echo '<h2>Manage Lines</h2>';
	echo '<p>You are currently managing the department &quot;'.$Department.'&quot;</p>';

	////	Handle Transactions
	if (
		isset($_POST['line_order'])
	) {
		$Order  = $_POST['line_order'];
		$Line   = $_POST['line_toggle'];
		$Result = Line_Reorder($Sitewide['Database']['Connection'], $Line, $Order);
		echo '<h3 class="color-nephritis">The Line "'.$Line.'" has been updated.</h3>';
	} else if (
		!empty($_POST['line_toggle'])
	) {
		$Line = $_POST['line_toggle'];
		$LineEnabled = Line_Exists($Sitewide['Database']['Connection'], $Line, true);
		$LineDisabled = Line_Exists($Sitewide['Database']['Connection'], $Line, false);
		if ( $LineEnabled ) {
			$Result = Line_Disable($Sitewide['Database']['Connection'], $Line);
			echo '<h3 class="color-nephritis">The Line "'.$Line.'" has been disabled.</h3>';
		} else if ( $LineDisabled ) {
			$Result = Line_Enable($Sitewide['Database']['Connection'], $Line);
			echo '<h3 class="color-nephritis">The Line "'.$Line.'" has been re-enabled.</h3>';
		} else {
			echo '<h3 class="color-pomegranate">Sorry, that Line doesn\'t seem to exist.</h3>';
		}
	}

	////	Lines
	$SQL = 'SELECT * FROM `Lines` WHERE `Department`=\''.$Department.'\' ORDER BY `Order` ASC, `Line` ASC;';
	$Result = mysqli_query($Sitewide['Database']['Connection'], $SQL);
	echo '<table class="tablesorter">
	<thead>
		<tr>
			<th>Line</th>
			<th>Order</th>
			<th>Set</th>
			<th>Active</th>
			<th>Manage Machines</th>
		</tr>
	</thead>
	<tbody>';
	while ( $Line = mysqli_fetch_assoc($Result) ) {
		echo '<tr>
			<td>'.$Line['Line'].'</td>
			<td data-text="'.$Line['Order'].'">
				<form method="POST">
					<input type="hidden" name="line_toggle" value="'.$Line['Line'].'">
					<input type="number" min="0" max="999" step="1" name="line_order" value="'.$Line['Order'].'">
			</td>
			<td>
					<button type="submit"><i class="fa fa-sort"></i> Re-order</button>
				</form>
			</td>
			<td>
				<form method="POST">
					<input type="hidden" name="line_toggle" value="'.$Line['Line'].'">';
		if ( $Line['Active'] ) {
			echo '
					<button type="submit"><i class="fa fa-ban"></i> Disable</button>';
		} else {
			echo '
					<button type="submit"><i class="fa fa-check"></i> Enable</button>';
		}
		echo '
				</form>
			</td>
			<td><button onclick="window.location.href=\'machines.php?department='.$Department.'&line='.$Line['Line'].'\'"><i class="fa fa-cogs"></i> Manage Machines</button>
		</tr>';
	}
	echo '</tbody>
</table>';

	require_once $Sitewide['Templates']['Footer'];

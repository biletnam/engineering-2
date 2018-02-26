<?php
	require_once __DIR__.'/../_puff/sitewide.php';
	$Page['Type']  = 'Admin';
	$Page['Title'] = 'Manage Users';

	if (
		( $Sitewide['Authenticated']['Role'] == 'Admin' ) ||
		(
			$Sitewide['Authenticated']['Department'] == 'Engineering' &&
			$Sitewide['Authenticated']['Role'] == 'Manager'
		)
	) {
		header('Location: '.$Sitewide['Settings']['Site Root'].'log_in.php?redirect='.urlencode($Sitewide['Request']['Path']), true, 302);
		exit;
	}

	require_once $Sitewide['Templates']['Header'];
	echo '<h2>Manage Users <a class="float-right" href="'.$Sitewide['Settings']['Site Root'].'admin/member_create.php"><i class="fa fa-user-plus"></i> Create a User</a></h2>';

	////	Handle Transactions
	if (
		!empty($_POST['username_toggle'])
	) {
		$Username = Puff_Member_Sanitize_Username($_POST['username_toggle']);
		$MemberEnabled = Puff_Member_Exists($Sitewide['Database']['Connection'], $Username, true);
		$MemberDisabled = Puff_Member_Exists($Sitewide['Database']['Connection'], $Username, false);
		if ( $MemberEnabled ) {
			$Result = Puff_Member_Disable($Sitewide['Database']['Connection'], $Username);
			echo '<h3 class="color-nephritis">The user '.$Username.' has been disabled.</h3>';
		} else if ( $MemberDisabled ) {
			$Result = Puff_Member_Enable($Sitewide['Database']['Connection'], $Username);
			echo '<h3 class="color-nephritis">The user '.$Username.' has been re-enabled.</h3>';
		} else {
			echo '<h3 class="color-pomegranate">Sorry, that member doesn\'t seem to exist.</h3>';
		}
	}

	////	Index
	$SQL = 'SELECT `Username`, `Active` FROM `Members` ORDER BY `Username`';
	$Result = mysqli_query($Sitewide['Database']['Connection'], $SQL);
	echo '<table class="tablesorter">
	<thead>
		<tr>
			<th>Username</th>
			<th>Department</th>
			<th>Role</th>
			<th>Name</th>
			<th>EMail</th>
			<th>Phone</th>
		</tr>
	</thead>
	<tbody>';
	while ( $Member = mysqli_fetch_assoc($Result) ) {
		echo '<tr>
			<td>'.$Member['Username'].'</td>';
		$Departments = json_decode(html_entity_decode(Puff_Member_Key_Value($Sitewide['Database']['Connection'], $Member['Username'], 'Department'), ENT_QUOTES, 'UTF-8'));
		$DepartmentsCount = count($Departments);
		if ( $DepartmentsCount != 1 ) {
			echo '
				<td>'.$DepartmentsCount.' Departments</td>';
		} else {
			echo '
				<td>'.$Departments[0].'</td>';
		}
		echo '
			<td>'.Puff_Member_Key_Value($Sitewide['Database']['Connection'], $Member['Username'], 'Role').'</td>
			<td>'.Puff_Member_Key_Value($Sitewide['Database']['Connection'], $Member['Username'], 'Name').'</td>
			<td>'.Puff_Member_Key_Value($Sitewide['Database']['Connection'], $Member['Username'], 'EMail').'</td>
			<td>'.Puff_Member_Key_Value($Sitewide['Database']['Connection'], $Member['Username'], 'Phone').'</td>
			<td><button onclick="window.location.href=\'member_details.php?username='.$Member['Username'].'\'"><i class="fa fa-info-circle"></i> Change Details</button>
			<td><button onclick="window.location.href=\'member_password.php?username='.$Member['Username'].'\'"><i class="fa fa-lock"></i> Change Password</button>
			<td><form method="POST"><input type="hidden" name="username_toggle" value="'.$Member['Username'].'">';
		if ( $Member['Active'] ) {
			echo '<button type="submit"><i class="fa fa-ban"></i> Disable</button>';
		} else {
			echo '<button type="submit"><i class="fa fa-check"></i> Enable</button>';
		}
		echo '</form></a></td>
		</tr>';
	}
	echo '</tbody>
</table>';

	require_once $Sitewide['Templates']['Footer'];

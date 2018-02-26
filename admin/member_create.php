<?php
	require_once __DIR__.'/../_puff/sitewide.php';
	$Page['Type']  = 'Admin';
	$Page['Title'] = 'Create a User';

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
	$ShowForm = true;

	if (
		!empty($_POST['username']) &&
		!empty($_POST['password'])
	) {
		$Result = Puff_Member_Create($Sitewide['Database']['Connection'], $_POST['username'], $_POST['password']);
		if ( !empty($Result['error']) ) {
			echo '<h2 class="color-pomegranate">'.$Result['error'].'</h2>';
		} else if ( $Result ) {
			$Departments = json_encode($_POST['departments']);
			Puff_Member_Key_Update($Sitewide['Database']['Connection'], $_POST['username'], 'Department', $Departments);
			Puff_Member_Key_Update($Sitewide['Database']['Connection'], $_POST['username'], 'Role', htmlentities($_POST['role'], ENT_QUOTES, 'UTF-8'));
			Puff_Member_Key_Update($Sitewide['Database']['Connection'], $_POST['username'], 'Name', htmlentities($_POST['name'], ENT_QUOTES, 'UTF-8'));
			Puff_Member_Key_Create($Sitewide['Database']['Connection'], $_POST['username'], 'EMail', htmlentities($_POST['email'], ENT_QUOTES, 'UTF-8'));
			Puff_Member_Key_Create($Sitewide['Database']['Connection'], $_POST['username'], 'Phone', htmlentities($_POST['phone'], ENT_QUOTES, 'UTF-8'));
			$Username = Puff_Member_Sanitize_Username($_POST['username']);
			echo '<h2 class="color-nephritis">The user '.$Username.' has been created.</h2>';
			echo '<button class="navlink admin" onclick="window.location.href=\''.$Sitewide['Settings']['Site Root'].'admin/members.php\'"><i class="fa fa-arrow-right"></i> Continue</button>';
			$ShowForm = false;
		} else {
			echo '<h2 class="color-pomegranate">There was an unspecified error.</h2>';
		}
	} else if (
		!empty($_POST['username']) &&
		empty($_POST['password'])
	) {
		echo '<h2 class="color-pomegranate">You must enter a password.</h2>';
	} else if (
		empty($_POST['username']) &&
		!empty($_POST['password'])
	) {
		echo '<h2 class="color-pomegranate">You must enter a username.</h2>';
	}
	if ( $ShowForm ) {
?>

<h2>Create a User</h2>
<form method="post">
<h3><i class="fa fa-user"></i> Username</h3>
<input type="text"     name="username" placeholder="JSmith">
<h3><i class="fa fa-lock-alt"></i> Password</h3>
<input type="password" name="password" placeholder="Password1">
<h3><i class="fa fa-users"></i> Departments</h3>
<?php
		$SQL = 'SELECT * FROM `Departments` WHERE `Active`=\'1\' ORDER BY `Order` DESC, `Department` ASC;';
		$Result = mysqli_query($Sitewide['Database']['Connection'], $SQL);
		while ( $Department = mysqli_fetch_assoc($Result) ) {
			echo '<input type="checkbox" name="departments[]" value="'.$Department['Department'].'" id="departments-'.$Department['Department'].'"> <label for="departments-'.$Department['Department'].'">'.$Department['Department'].'</option><br>';
		}
?>
<h3><i class="fa fa-users"></i> Role</h3>
<select name="role">
	<option value="User">User</option>
	<option value="Manager">Manager</option>
	<option value="Admin">Admin</option>
</select>
<h3><i class="fa fa-user"></i> Name</h3>
<input type="text"     name="name"    placeholder="John Smith">
<h3><i class="fa fa-envelope"></i> EMail</h3>
<input type="email"    name="email"    placeholder="john.smith@banhampoultryuk.com" value="@banhampoultryuk.com">
<h3><i class="fa fa-phone"></i> Phone</h3>
<input type="number"   name="phone"    placeholder="123" min="200" max="99999999999">
<input type="submit"   value="Create">
</form>

<?php
	}
	require_once $Sitewide['Templates']['Footer'];

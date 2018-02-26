<?php
	require_once __DIR__.'/../_puff/sitewide.php';
	$Page['Type']  = 'Admin';
	$Page['Title'] = 'Change a Users Password';

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
		$Result = Puff_Member_Password($Sitewide['Database']['Connection'], $_POST['username'], $_POST['password'], $Sitewide['Authenticated']['Session']);
		if ( !empty($Result['error']) ) {
			echo '<h1 class="color-pomegranate">'.$Result['error'].'</h1>';
		} else if ( $Result ) {
			$Username = Puff_Member_Sanitize_Username($_POST['username']);
			echo '<h1 class="color-nephritis">The password for user '.$Username.' has been changed.</h1>';
			echo '<h2 class="color-nephritis">All their sessions have been destroyed.</h2>';
			echo '<button class="navlink admin" onclick="window.location.href=\''.$Sitewide['Settings']['Site Root'].'admin/members.php\'"><i class="fa fa-arrow-right"></i> Continue</button>';
			$ShowForm = false;
		} else {
			echo '<h1 class="color-pomegranate">There was an unspecified error.</h1>';
		}
	} else if (
		!empty($_POST['username']) &&
		empty($_POST['password'])
	) {
		echo '<h1>You must enter a password.</h1>';
	} else if (
		empty($_POST['username']) &&
		!empty($_POST['password'])
	) {
		var_dump($_POST);
		echo '<h1>You must enter a username.</h1>';
	}
	if ( $ShowForm ) {
?>

<h1>Change a Users Password</h1>
<form method="post">
<h3><i class="fa fa-user"></i> Username</h3>
<input type="text"     name="username" placeholder="JSmith"    value="<?php echo $_GET['username']; ?>">
<h3><i class="fa fa-lock-alt"></i> Password</h3>
<input type="password" name="password" placeholder="Password1">
<input type="submit"   value="Change">
</form>

<?php
	}
	require_once $Sitewide['Templates']['Footer'];

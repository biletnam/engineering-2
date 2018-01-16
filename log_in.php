<?php
	require_once __DIR__.'/_puff/sitewide.php';
	$Page['Type']  = 'Log In';

	$ShowForm = true;

	if (
		!empty($_POST['username']) &&
		!empty($_POST['password'])
	) {
		$Username = Puff_Member_Sanitize_Username($_POST['username']);
		$MemberExists = Puff_Member_Exists($Sitewide['Database']['Connection'], $Username, true);
		if ( !$MemberExists ) {
			echo '<h1 class="color-pomegranate">The username you entered is incorrect.</h1>';
		} else {
			$SQL = 'SELECT * FROM `Members` WHERE `Username` = \''.$Username.'\';';
			$Member = mysqli_fetch_once($Sitewide['Database']['Connection'], $SQL);
			$Password = Puff_Member_PassHash($_POST['password'], $Member['Salt'], $Member['PassHash']);
			if ( $Password['Password'] === $Member['Password'] ) {
				$Session = Puff_Member_Session_Create($Sitewide['Database']['Connection'], $Username);
				setcookie('session', $Session['Session'], 2147483647, '/', $Sitewide['Request']['Host'], $Sitewide['Request']['Secure'], $Sitewide['Cookies']['HTTPOnly']);
				if ( $_GET['redirect'] ) {
					header('Location: '.urldecode($_GET['redirect']), true, 302);
				} else {
					header('Location: '.$Sitewide['Settings']['Site Root'], true, 302);
				}
				exit();
			} else {
				echo '<h1 class="color-pomegranate">The password you entered is incorrect.</h1>';
			}
		}
	} else if (
		!empty($_POST['username']) &&
		empty($_POST['password'])
	) {
		echo '<h1 class="color-pomegranate">You must enter a password.</h1>';
	} else if (
		empty($_POST['username']) &&
		!empty($_POST['password'])
	) {
		echo '<h1 class="color-pomegranate">You must enter a username.</h1>';
	}

	require_once $Sitewide['Templates']['Header'];

	if ( $ShowForm ) {
?>

<h1>Log In</h1>
<form method="post">
<input type="text"     name="username" placeholder="JSmith">
<input type="password" name="password" placeholder="Password1">
<input type="submit"   value="Log In">
</form>

<?php
	}
	require_once $Sitewide['Templates']['Footer'];



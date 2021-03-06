<?php

function Puff_Member_2FA_Enable($Connection, $Username, $Code, $CurrentSession) {
	global $Sitewide;

	require_once $Sitewide['Puff']['Libs'].'authenticatron.php';

	////	Check Member Existence
	// For the sake of the space-time continuum,
	// new users should not already exist.
	$Username = Puff_Member_Sanitize_Username($Username);
	$MemberExists = Puff_Member_Exists($Connection, $Username, true);
	if ( !$MemberExists ) {
		return array('error' => 'Sorry, that user doesn\'t exist, so we can\'t make a session for it.');
	}

	////	Get Secret
	$Secret = mysqli_fetch_once($Connection, 'SELECT `2FA Secret` FROM `Members` WHERE `Username`=\''.$Username.'\';');
	if ( empty($Secret['2FA Secret']) ) {
		return array('error' => 'Sorry, 2FA isn\'t set up for that user.');
	}
	$Secret = $Secret['2FA Secret'];

	////	Generate all the 2FA Stuff
	$Check = Authenticatron_Check($Code, $Secret);
	if ( $Check ) {
		////	Disable existing Sessions
		Puff_Member_Session_Disable_All($Connection, $Username, $CurrentSession);
		////	Update Database
		$Result = mysqli_query($Connection, 'UPDATE `Members` SET `2FA Active`=\'1\' WHERE `Username`=\''.$Username.'\';');
		return $Result;
	} else {
		return array('error' => 'Sorry, your code was not valid. They are only valid for 30 seconds.');
	}

}

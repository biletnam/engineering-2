<?php

// Make sure this is ready.
require_once __DIR__.'/connect-to-database.php';
$Connection = $Sitewide['Database']['Connection'];

function NotAuthenticated() {
	global $Sitewide;
	setcookie('session', null, time()-3600, '/', $Sitewide['Request']['Host'], $Sitewide['Request']['Secure'], $Sitewide['Cookies']['HTTPOnly']);
	unset($_COOKIE['session']);
	$Sitewide['Authenticated'] = false;
}

if ( isset($_COOKIE['session']) ) {
	$Session = htmlentities($_COOKIE['session'], ENT_QUOTES, 'UTF-8');
	$Result = Puff_Member_Session_Exists($Connection, $Session);
	if ( !$Result ) {
		NotAuthenticated();
	} else {
		$SQL = 'SELECT *
			FROM `Sessions`
			WHERE `Session`=\''.$Session.'\';';
		$Member = mysqli_fetch_once($Connection, $SQL);
		$Result = Puff_Member_Exists($Connection, $Member['Username'], true);
		if ( !$Result || !$Member ) {
			NotAuthenticated();
		} else {
			$Sitewide['Authenticated']['Member'] = $Member['Username'];
			$Sitewide['Authenticated']['Username'] = $Member['Username'];
			$Sitewide['Authenticated']['Session'] = $Session;
			$Sitewide['Authenticated']['Role'] = Puff_Member_Key_Value($Sitewide['Database']['Connection'], $Sitewide['Authenticated']['Member'], 'Role');
			$Sitewide['Authenticated']['Departments'] = json_decode(html_entity_decode(Puff_Member_Key_Value($Sitewide['Database']['Connection'], $Sitewide['Authenticated']['Member'], 'Department'), ENT_QUOTES, 'UTF-8'));
			if ( in_array('Engineering', $Sitewide['Authenticated']['Departments']) ) {
				$Sitewide['Authenticated']['Department'] = 'Engineering';
			} else if ( count( $Sitewide['Authenticated']['Departments']) > 0 ) {
				$Sitewide['Authenticated']['Department'] = $Sitewide['Authenticated']['Departments'][0];
			} else {
				$Sitewide['Authenticated']['Department'] = false;
			}
			$Sitewide['Authenticated']['Name'] = Puff_Member_Key_Value($Sitewide['Database']['Connection'], $Sitewide['Authenticated']['Member'], 'Name');
			$Sitewide['Authenticated']['EMail'] = Puff_Member_Key_Value($Sitewide['Database']['Connection'], $Sitewide['Authenticated']['Member'], 'EMail');
			$Sitewide['Authenticated']['Phone'] = Puff_Member_Key_Value($Sitewide['Database']['Connection'], $Sitewide['Authenticated']['Member'], 'Phone');
		}
	}
} else {
	$Sitewide['Authenticated'] = false;
}

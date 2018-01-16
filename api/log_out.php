<?php
	require_once __DIR__.'/../_puff/sitewide.php';
	$Page['Type']  = 'Log Out';

	$Result = Puff_Member_Session_Disable($Sitewide['Database']['Connection'], $_COOKIE['session']);
	setcookie('session', null, time()-3600, '/', $Sitewide['Request']['Host'], $Sitewide['Request']['Secure'], $Sitewide['Cookies']['HTTPOnly']);
	unset($_COOKIE['session']);
	if ( $_GET['redirect'] ) {
		header('Location: '.urldecode($_GET['redirect']), true, 302);
	} else {
		header('Location: '.$Sitewide['Settings']['Site Root'], true, 302);
	}

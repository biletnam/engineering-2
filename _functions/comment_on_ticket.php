<?php

function comment_on_ticket($Ticket, $Username, $Comment) {
	global $Sitewide;
	$SQL = 'INSERT INTO `Comments`
	(
		`Ticket`,
		`Username`,
		`Timestamp`,
		`Comment`
	) VALUES (
		\''.$Ticket.'\',
		\''.$Username.'\',
		CURRENT_TIMESTAMP,
		\''.$Comment.'\'
	);';
	$Result = mysqli_query($Sitewide['Database']['Connection'], $SQL);
	if ( $Result ) {
		$Comment_ID = mysqli_insert_id($Sitewide['Database']['Connection']);
		return $Comment_ID;
	} else {
		echo 'Error #'.mysqli_errno($Sitewide['Database']['Connection']).' "'.mysqli_error($Sitewide['Database']['Connection']).'"';
		return false;
	}
}

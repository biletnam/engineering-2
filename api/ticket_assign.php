<?php

require_once __DIR__.'/../_puff/sitewide.php';

if (
	$Sitewide['Authenticated']['Role'] == 'Manager' ||
	$Sitewide['Authenticated']['Role'] == 'Admin'
) {
	if ( !empty($_POST['engineers']) ) {
	    $Assign = json_encode($_POST['engineers']);
	} else {
	    $Assign = '[]';
	}
	$SQL = 'UPDATE `Tickets` SET `Assigned`=\''.$Assign.'\' WHERE `Ticket`=\''.$_POST['ticket'].'\';';
	$Result = mysqli_query($Sitewide['Database']['Connection'], $SQL);
	if ( $Result ) {
		$Comment = $Sitewide['Authenticated']['Name'].' has assigned ';
		foreach ( $_POST['engineers'] as $Assigned ) {
			$Comment .= Puff_Member_Key_Value($Sitewide['Database']['Connection'], $Assigned, 'Name');
			$Comment .= ', ';
		}
		$Comment = trim($Comment, ', ');
		$Comment .= '.';
		$Comment_ID = comment_on_ticket($_POST['ticket'], 'SYSTEM', $Comment);
		header('Location: '.$Sitewide['Settings']['Site Root'].'ticket.php?id='.$_POST['ticket'].'#assignment', true, 302);
	} else {
		echo 'Error #'.mysqli_errno($Sitewide['Database']['Connection']).' "'.mysqli_error($Sitewide['Database']['Connection']).'"';
	}
} else {
		echo 'Error #403 "The current user is forbidden from performing this command."';
}
